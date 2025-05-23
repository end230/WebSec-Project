<?php

namespace App\Http\Controllers;

use App\Models\ProductComment;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCommentController extends Controller
{
    /**
     * Store a new comment for a product
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Check if user has purchased and received this product
        $hasPurchased = Order::where('user_id', Auth::id())
            ->whereHas('items', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->where('status', 'delivered')
            ->exists();

        // Only allow comments from users who have received the product
        if (!$hasPurchased) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You can only review products you have purchased and received.');
        }

        // Check if user has already reviewed this product
        $existingComment = ProductComment::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingComment) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You have already reviewed this product.');
        }

        // Create the comment (all comments from verified purchasers are auto-approved)
        $comment = ProductComment::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified_purchase' => true, // Always true since we verified above
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        $message = 'Your review has been submitted successfully!';
        
        // Add note about customer service case for low ratings
        if ($comment->rating <= 3) {
            $message .= ' A customer service case has been created to address your concerns.';
        }

        return redirect()->route('products.show', $product)
            ->with('success', $message);
    }

    /**
     * Show comments for a product (for moderation)
     */
    public function index(Request $request)
    {
        $query = ProductComment::with(['product', 'user', 'customerServiceCase'])
            ->orderBy('created_at', 'desc');

        // Filter by rating if specified
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by approval status
        if ($request->filled('approved')) {
            $query->where('is_approved', $request->approved === 'yes');
        }

        // Filter by verified purchase
        if ($request->filled('verified')) {
            $query->where('is_verified_purchase', $request->verified === 'yes');
        }

        $comments = $query->paginate(20);

        return view('comments.index', compact('comments'));
    }

    /**
     * Show a specific comment
     */
    public function show(ProductComment $comment)
    {
        $comment->load(['product', 'user', 'customerServiceCase.activities']);
        
        return view('comments.show', compact('comment'));
    }

    /**
     * Approve or reject a comment
     */
    public function updateApproval(Request $request, ProductComment $comment)
    {
        $request->validate([
            'approved' => 'required|boolean',
        ]);

        $comment->update([
            'is_approved' => $request->approved,
            'approved_at' => $request->approved ? now() : null,
            'approved_by' => $request->approved ? Auth::id() : null,
        ]);

        $status = $request->approved ? 'approved' : 'rejected';
        
        return redirect()->back()
            ->with('success', "Comment has been {$status} successfully!");
    }

    /**
     * Delete a comment
     */
    public function destroy(ProductComment $comment)
    {
        // Check if there's an associated customer service case
        if ($comment->customerServiceCase) {
            return redirect()->back()
                ->with('error', 'Cannot delete comment with an active customer service case.');
        }

        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comment deleted successfully!');
    }
}
