<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeFeedbackNotifier
{
    /**
     * Handle access to customer feedback and cancelled orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Check permissions instead of roles
        if (!Auth::user()->hasPermissionTo('view_customer_feedback')) {
            abort(403, 'Access denied. Required permission: view_customer_feedback');
        }

        // Load unread feedback and cancellation notifications
        $unreadNotifications = Auth::user()->unreadNotifications()
            ->whereIn('type', [
                'App\Notifications\OrderCancelled',
                'App\Notifications\NewFeedback'
            ])
            ->get();
            
        // Make notifications available to the view
        view()->share('feedbackNotifications', $unreadNotifications);
        view()->share('unreadFeedbackCount', $unreadNotifications->count());
        
        return $next($request);
    }
}
