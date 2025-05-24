<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['comments' => function($query) {
            $query->where('is_approved', true);
        }])->get();

        return response()->json([
            'products' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['comments' => function($query) {
            $query->where('is_approved', true);
        }])->findOrFail($id);

        return response()->json([
            'product' => $product
        ]);
    }

    public function addComment(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($productId);

        $comment = new ProductComment([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'user_id' => Auth::id(),
            'is_approved' => true,
            'is_verified_purchase' => $this->hasUserPurchasedProduct($productId)
        ]);

        $product->comments()->save($comment);

        // If rating is low (1-3 stars), create a customer service case
        if ($request->rating <= 3) {
            $this->createCustomerServiceCase($comment);
        }

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment
        ], 201);
    }

    private function hasUserPurchasedProduct($productId)
    {
        return Auth::user()->orders()
            ->whereHas('orderItems', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'delivered')
            ->exists();
    }

    private function createCustomerServiceCase($comment)
    {
        $case = $comment->customerServiceCases()->create([
            'case_number' => 'CS-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'customer_id' => Auth::id(),
            'product_id' => $comment->product_id,
            'status' => 'open',
            'priority' => $comment->rating == 1 ? 'high' : 'medium',
            'category' => 'product_quality',
            'subject' => 'Low rating (' . $comment->rating . ' stars) for ' . $comment->product->name,
            'description' => 'Customer left a ' . $comment->rating . '-star review: "' . $comment->comment . '"'
        ]);

        // Create initial case activity
        $case->activities()->create([
            'user_id' => Auth::id(),
            'activity_type' => 'created',
            'title' => 'Case automatically created from low rating',
            'description' => 'Case created from ' . $comment->rating . '-star product review',
            'is_system_generated' => true
        ]);
    }
} 