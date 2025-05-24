<?php

namespace App\Http\Controllers;

use App\Models\CustomerServiceCase;
use App\Models\CaseActivity;
use App\Models\ProductComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['permission:view_customer_feedback|manage_users|admin_users']);
    }

    /**
     * Customer Service Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $isAdmin = $user->hasAnyPermission(['manage_users', 'admin_users']);
        
        // Get dashboard statistics
        $stats = [
            'total_cases' => CustomerServiceCase::count(),
            'open_cases' => CustomerServiceCase::where('status', 'open')->count(),
            'my_cases' => $isAdmin ? CustomerServiceCase::count() : CustomerServiceCase::where('assigned_to', $user->id)->count(),
            'overdue_cases' => CustomerServiceCase::overdue()->count(),
            'urgent_cases' => CustomerServiceCase::where('priority', 'urgent')
                ->whereNotIn('status', ['resolved', 'closed'])->count(),
            'resolved_cases' => CustomerServiceCase::whereIn('status', ['resolved', 'closed'])->count(),
            'avg_response_time' => CustomerServiceCase::whereNotNull('response_time_hours')
                ->avg('response_time_hours'),
            'avg_resolution_time' => CustomerServiceCase::whereNotNull('resolution_time_hours')
                ->avg('resolution_time_hours'),
            'cases_handled' => $isAdmin ? CustomerServiceCase::count() : CustomerServiceCase::where('assigned_to', $user->id)->count(),
            'resolution_rate' => $this->calculateResolutionRate($user->id, $isAdmin),
        ];

        // Recent cases - show all for admin, only assigned for CS
        $casesQuery = CustomerServiceCase::with(['customer', 'product', 'productComment']);
        if (!$isAdmin) {
            $casesQuery->where('assigned_to', $user->id);
        }
        $myCases = $casesQuery->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Unassigned cases
        $unassignedCases = CustomerServiceCase::with(['customer', 'product', 'productComment'])
            ->unassigned()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent low-rated comments that might need attention
        $recentLowRatedComments = ProductComment::with(['product', 'user'])
            ->lowRated()
            ->approved()
            ->whereDoesntHave('customerServiceCase')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('customer-service.dashboard', compact(
            'stats', 'myCases', 'unassignedCases', 'recentLowRatedComments'
        ));
    }

    /**
     * Calculate resolution rate for a user
     */
    private function calculateResolutionRate($userId, $isAdmin)
    {
        $totalCases = $isAdmin ? CustomerServiceCase::count() : CustomerServiceCase::where('assigned_to', $userId)->count();
        if ($totalCases === 0) {
            return '0%';
        }
        
        $resolvedCases = CustomerServiceCase::where('assigned_to', $userId)
            ->whereIn('status', ['resolved', 'closed'])
            ->count();
            
        return round(($resolvedCases / $totalCases) * 100) . '%';
    }

    /**
     * List all customer service cases
     */
    public function index(Request $request)
    {
        $query = CustomerServiceCase::with(['customer', 'product', 'assignedTo', 'productComment']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } elseif ($request->assigned_to === 'me') {
                $query->where('assigned_to', Auth::id());
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        if ($request->filled('overdue') && $request->overdue === 'yes') {
            $query->overdue();
        }

        $cases = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get customer service representatives for filter
        $csReps = User::role('Customer Service')->get();

        return view('customer-service.index', compact('cases', 'csReps'));
    }

    /**
     * Show a specific case
     */
    public function show(CustomerServiceCase $case)
    {
        $case->load([
            'customer', 
            'product', 
            'assignedTo', 
            'productComment.user',
            'activities.user'
        ]);

        // Get customer service representatives for assignment
        $csReps = User::role('Customer Service')->get();

        return view('customer-service.show', compact('case', 'csReps'));
    }

    /**
     * Assign a case to a customer service representative
     */
    public function assign(Request $request, CustomerServiceCase $case)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $assignee = User::findOrFail($request->assigned_to);
        
        // Check if assignee has customer service permissions
        if (!$assignee->hasPermissionTo('view_customer_feedback')) {
            return redirect()->back()
                ->with('error', 'Selected user does not have customer service permissions.');
        }

        $case->assignTo($assignee, Auth::user());

        return redirect()->back()
            ->with('success', "Case assigned to {$assignee->name} successfully!");
    }

    /**
     * Update case status
     */
    public function updateStatus(Request $request, CustomerServiceCase $case)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,waiting_customer,resolved,closed',
            'reason' => 'nullable|string|max:500',
        ]);

        $case->updateStatus($request->status, Auth::user(), $request->reason);

        return redirect()->back()
            ->with('success', 'Case status updated successfully!');
    }

    /**
     * Update case priority
     */
    public function updatePriority(Request $request, CustomerServiceCase $case)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
            'reason' => 'nullable|string|max:500',
        ]);

        $oldPriority = $case->priority;
        $case->priority = $request->priority;
        $case->save();

        // Create activity
        CaseActivity::create([
            'case_id' => $case->id,
            'user_id' => Auth::id(),
            'activity_type' => 'priority_changed',
            'title' => "Priority changed from {$oldPriority} to {$request->priority}",
            'description' => $request->reason ?: "Priority updated to {$request->priority}",
            'metadata' => [
                'old_priority' => $oldPriority,
                'new_priority' => $request->priority,
                'reason' => $request->reason,
            ],
            'is_customer_visible' => false,
            'is_system_generated' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Case priority updated successfully!');
    }

    /**
     * Add a comment/response to a case
     */
    public function addComment(Request $request, CustomerServiceCase $case)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
            'is_customer_visible' => 'boolean',
        ]);

        // Update first response time if this is the first response
        if (!$case->first_response_at) {
            $case->updateResponseTime();
        }

        CaseActivity::create([
            'case_id' => $case->id,
            'user_id' => Auth::id(),
            'activity_type' => 'comment_added',
            'title' => 'Comment added',
            'description' => $request->comment,
            'is_customer_visible' => $request->boolean('is_customer_visible', true),
            'is_system_generated' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Add internal notes to a case
     */
    public function addInternalNote(Request $request, CustomerServiceCase $case)
    {
        $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        // Update internal notes
        $currentNotes = $case->internal_notes ?: '';
        $newNote = "[" . now()->format('Y-m-d H:i:s') . " - " . Auth::user()->name . "]\n" . $request->note . "\n\n";
        $case->internal_notes = $newNote . $currentNotes;
        $case->save();

        // Create activity
        CaseActivity::create([
            'case_id' => $case->id,
            'user_id' => Auth::id(),
            'activity_type' => 'internal_note_added',
            'title' => 'Internal note added',
            'description' => $request->note,
            'is_customer_visible' => false,
            'is_system_generated' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Internal note added successfully!');
    }

    /**
     * Resolve a case
     */
    public function resolve(Request $request, CustomerServiceCase $case)
    {
        $request->validate([
            'resolution' => 'required|string|max:2000',
        ]);

        $case->resolution = $request->resolution;
        $case->updateStatus('resolved', Auth::user(), 'Case resolved with solution');

        return redirect()->back()
            ->with('success', 'Case resolved successfully!');
    }

    /**
     * Analytics and reports
     */
    public function analytics()
    {
        // Case statistics by status
        $casesByStatus = CustomerServiceCase::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Case statistics by priority
        $casesByPriority = CustomerServiceCase::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        // Average response and resolution times
        $avgTimes = CustomerServiceCase::select(
            DB::raw('AVG(response_time_hours) as avg_response'),
            DB::raw('AVG(resolution_time_hours) as avg_resolution')
        )->first();

        // Cases created per day (last 30 days)
        $casesPerDay = CustomerServiceCase::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top customer service representatives by cases resolved
        $topReps = User::role('Customer Service')
            ->withCount(['assignedCases as resolved_cases_count' => function($query) {
                $query->where('status', 'resolved');
            }])
            ->orderBy('resolved_cases_count', 'desc')
            ->limit(10)
            ->get();

        return view('customer-service.analytics', compact(
            'casesByStatus', 'casesByPriority', 'avgTimes', 'casesPerDay', 'topReps'
        ));
    }
}
