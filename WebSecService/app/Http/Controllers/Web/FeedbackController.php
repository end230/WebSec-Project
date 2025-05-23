<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    /**
     * List all feedback
     */
    public function index(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermissionTo('view_customer_feedback') && 
            !auth()->user()->hasPermissionTo('respond_to_feedback')) {
            abort(403, 'You do not have permission to view feedback.');
        }
        
        // Get all feedback ordered by creation date WITH PAGINATION
        $feedbacks = Feedback::orderBy('created_at', 'desc')->paginate(10);
        
        // Format feedback count with plural
        $feedbackCount = $feedbacks->total();
        $feedbackLabel = $feedbackCount . ' ' . Str::plural('feedback', $feedbackCount);
        
        return view('feedback.index', compact('feedbacks', 'feedbackLabel'));
    }
    
    /**
     * Show the form for submitting feedback
     */
    public function create(Request $request)
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'open',
        ]);

        return redirect()->route('feedback.list.own')
            ->with('success', 'Feedback submitted successfully');
    }

    /**
     * Show a specific feedback with its responses
     */
    public function show(Request $request, Feedback $feedback)
    {
        // Check if user can view this feedback (either it's their own or they have permission)
        if (auth()->id() !== $feedback->user_id && !auth()->user()->hasPermissionTo('view_feedback')) {
            abort(403, 'You do not have permission to view this feedback');
        }
        
        return view('feedback.show', compact('feedback'));
    }

    /**
     * Store a response to a feedback
     */
    public function respond(Request $request, Feedback $feedback)
    {
        // Check if user can respond to feedback
        if (!auth()->user()->hasPermissionTo('respond_to_feedback')) {
            abort(403, 'You do not have permission to respond to feedback');
        }
        
        $request->validate([
            'admin_response' => 'required|string|max:1000',
        ]);
        
        // Update the feedback with the response
        $feedback->update([
            'admin_response' => $request->admin_response,
            'resolved' => true,
            'resolved_by' => auth()->id(),
            'resolved_at' => now()
        ]);
        
        return redirect()->route('feedback.show', $feedback->id)
            ->with('success', 'Your response has been submitted successfully.');
    }
}
