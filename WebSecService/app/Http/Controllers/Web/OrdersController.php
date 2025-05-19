<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Feedback;
use App\Notifications\OrderCancelled;
use Carbon\Carbon;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['index']);
    }

    /**
     * Display a listing of the customer's orders.
     */
    public function index()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If user has permissions to manage orders, show all orders and feedback analytics
        if ($user->hasPermissionTo('manage_orders')) {
            $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
            
            // Feedback analytics data
            $recentFeedbackCount = Feedback::where('created_at', '>=', Carbon::now()->subDays(7))->count();
            $unresolvedFeedbackCount = Feedback::where('resolved', false)->count();
            $recentCancellationsCount = Order::where('status', 'cancelled')
                ->where('updated_at', '>=', Carbon::now()->subDays(7))
                ->count();
            
            // Calculate response rate
            $totalFeedback = Feedback::count();
            $resolvedFeedback = Feedback::where('resolved', true)->count();
            $responseRate = $totalFeedback > 0 ? round(($resolvedFeedback / $totalFeedback) * 100) . '%' : '0%';
            
            return view('orders.index', compact(
                'orders', 
                'recentFeedbackCount', 
                'unresolvedFeedbackCount', 
                'recentCancellationsCount',
                'responseRate'
            ));
        }

        // For customers, only show their orders
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        // Check if user has permission to view this order
        $user = Auth::user();
        if ($order->user_id !== $user->id && !$user->hasPermissionTo('view_orders')) {
            abort(403, 'Unauthorized action.');
        }

        // Load order with related data including feedback
        $order->load('items.product', 'user', 'feedback');
        return view('orders.show', compact('order'));
    }

    /**
     * Add a product to the cart
     */
    public function addToCart(Request $request, Product $product)
    {
        // Check if user is authenticated and allowed to make purchases
        $user = Auth::user();
        if (!$user) {
            abort(403, 'You must be logged in to add products to cart.');
        }

        // Check if product is in stock (even if stock_quantity is 1, it's still available)
        if ($product->stock_quantity <= 0) {
            return redirect()->back()->with('error', 'Sorry, this product is out of stock.');
        }

        // Get requested quantity and ensure it's an integer
        $requestedQuantity = (int) $request->input('quantity', 1);

        // Prevent negative quantities
        if ($requestedQuantity <= 0) {
            return redirect()->back()->with('error', 'Quantity must be positive.');
        }

        // Initialize cart if it doesn't exist
        $cart = Session::get('cart', []);

        // Get original quantity in cart
        $originalQuantity = isset($cart[$product->id]) ? (int) $cart[$product->id]['quantity'] : 0;

        // Make sure we don't exceed available stock
        if ($requestedQuantity > $product->stock_quantity) {
            // We can still add the last item
            $requestedQuantity = $product->stock_quantity;
            session()->flash('warning', "Only {$product->stock_quantity} item(s) available. We've adjusted your quantity.");
        }

        // Add or update product in cart
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $originalQuantity + $requestedQuantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $requestedQuantity
            ];
        }

        // Update product stock by reducing the requested quantity
        if (!$product->updateStock($requestedQuantity)) {
            // This should no longer happen since we've validated quantities above,
            // but keep as a safeguard
            Log::error("Failed to update stock for product {$product->id} with quantity {$requestedQuantity}");
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] = $originalQuantity;
            }
            Session::put('cart', $cart);
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // Log the successful stock update
        Log::info("Successfully updated stock for product {$product->id}. New stock level: {$product->stock_quantity}");

        Session::put('cart', $cart);

        return redirect()->back()->with('success', "Added {$requestedQuantity} {$product->name}(s) to cart successfully!");
    }

    /**
     * Display the cart
     */
    public function cart()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $user = Auth::user();

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('orders.cart', compact('cart', 'total', 'user'));
    }

    /**
     * Remove an item from the cart
     */
    public function removeFromCart(Request $request, $productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            // Get the quantity being removed
            $quantityToRestore = (int) $cart[$productId]['quantity'];

            // Get the product and restore stock quantity
            $product = Product::find($productId);
            if ($product && $quantityToRestore > 0) {
                // Use negative quantity to increase stock
                $product->updateStock(-$quantityToRestore);

                // Log what we're doing
                Log::info("Restoring {$quantityToRestore} items to stock for product {$productId}");
            }

            // Remove from cart
            unset($cart[$productId]);
            Session::put('cart', $cart);

            return redirect()->back()->with('success', 'Product removed from cart and stock restored.');
        }

        return redirect()->back();
    }

    /**
     * Proceed to checkout
     */
    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products_list')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        $user = Auth::user();

        // Validate stock and recalculate total
        $outOfStockItems = [];
        $stockWarnings = [];

        // First calculate total based on current cart
        foreach ($cart as $id => $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Then check stock availability but don't remove items that were already in cart
        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            // If product doesn't exist anymore, remove it
            if (!$product) {
                unset($cart[$id]);
                Session::put('cart', $cart);
                session()->flash('error', "A product in your cart is no longer available and has been removed.");
                continue;
            }

            // If the quantity in cart is more than available in stock (but not 0),
            // just display a warning but don't remove from cart
            if ($product->stock_quantity < $item['quantity'] && $product->stock_quantity > 0) {
                $stockWarnings[] = "Only {$product->stock_quantity} of {$item['name']} available (you have {$item['quantity']} in cart).";
            }

            // Only if product is completely out of stock AND not already in cart,
            // mark it as out of stock
            if ($product->stock_quantity == 0) {
                $outOfStockItems[] = $product->name;
            }
        }

        // Display warnings about stock quantities
        if (!empty($stockWarnings)) {
            foreach ($stockWarnings as $warning) {
                session()->flash('warning', $warning);
            }
        }

        // Display out of stock messages but don't remove items
        if (!empty($outOfStockItems)) {
            $message = count($outOfStockItems) > 1
                ? "These items have limited availability: " . implode(', ', $outOfStockItems)
                : "This item has limited availability: " . $outOfStockItems[0];
            session()->flash('warning', $message);
        }

        if (empty($cart)) {
            return redirect()->route('products_list')->with('error', 'There are no items in your cart.');
        }

        // Check if user has enough credits
        if ($user->credits < $total) {
            return view('orders.insufficient_credits', compact('total', 'user'));
        }

        return DB::transaction(function() use ($request, $user, $cart, $total) {
            $freshUser = User::find($user->id);
            if (!$freshUser->hasEnoughCredits($total)) {
                return redirect()->route('cart')->with('error', 'Insufficient credits.');
            }

            $orderItems = [];
            foreach ($cart as $id => $item) {
                $product = Product::lockForUpdate()->find($id);

                if (!$product) {
                    DB::rollBack();
                    return redirect()->route('cart')->with('error', 'A product has been removed from our system.');
                }

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }

            // Create order
            $order = new Order();
            $order->user_id = $user->id;
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->shipping_address = $request->shipping_address;
            $order->billing_address = $request->billing_address;
            $order->save();

            // Create order items
            foreach ($orderItems as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product']->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['price'];
                $orderItem->save();
            }

            // Deduct credits from user account after creating the order items
            if (!$freshUser->deductCredits($total)) {
                throw new \Exception('Failed to deduct credits');
            }

            // Clear cart
            Session::forget('cart');

            // Return to confirmation page
            return redirect()->route('orders.confirmation', ['order' => $order->id])
                ->with('success', 'Your order has been placed successfully!');
        });
    }

    /**
     * Place an order
     */
    public function placeOrder(Request $request)
    {
        // Validate request
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products_list')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();

        // Use a database transaction to ensure atomicity
        return DB::transaction(function() use ($cart, $user, $request) {
            $total = 0;
            $orderItems = [];
            $stockIssues = false;

            // Calculate total and verify stock is still available
            foreach ($cart as $id => $item) {
                $product = Product::lockForUpdate()->find($id);

                if (!$product) {
                    DB::rollBack();
                    return redirect()->route('cart')->with('error', 'A product has been removed from our system.');
                }

                // Items that were already in cart can still be purchased even if stock is now at 0
                // because we already adjusted the stock when adding to cart
                $total += $item['price'] * $item['quantity'];
                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }

            // Check if user has enough credits
            if ($user->credits < $total) {
                DB::rollBack();
                return view('orders.insufficient_credits', ['total' => $total, 'user' => $user]);
            }

            // Create order
            $order = new Order();
            $order->user_id = $user->id;
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->shipping_address = $request->shipping_address;
            $order->billing_address = $request->billing_address;
            $order->save();

            // Create order items (stock was already updated when adding to cart)
            foreach ($orderItems as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product']->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['price'];
                $orderItem->save();
            }

            // Deduct credits from user account
            $user->deductCredits($total);

            // Clear cart
            Session::forget('cart');

            // Return to confirmation page
            return redirect()->route('orders.confirmation', ['order' => $order->id])
                ->with('success', 'Your order has been placed successfully!');
        });
    }

    /**
     * Show order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($orderId);

        // Security check: only the order owner can see the confirmation
        if (Auth::id() != $order->user_id && !Auth::user()->hasPermissionTo('view_orders')) {
            abort(403, 'Unauthorized action.');
        }

        return view('orders.confirmation', compact('order'));
    }

    /**
     * Update order status (permission-based)
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Check permissions
        if (!Auth::user()->hasPermissionTo('manage_orders')) {
            abort(403, 'Unauthorized action. You need manage_orders permission.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated.');
    }

    /**
     * List all customers (permission-based)
     */
    public function listCustomers()
    {
        // Check if user has permission
        if (!Auth::user()->hasPermissionTo('list_customers')) {
            abort(403, 'Unauthorized action. You need list_customers permission.');
        }

        $customers = User::role('Customer')->get();
        return view('users.customers', compact('customers'));
    }

    /**
     * Add credits to customer (permission-based)
     */
    public function addCreditsForm(User $user)
    {
        // Check permissions
        if (!Auth::user()->hasPermissionTo('manage_orders')) {
            abort(403, 'Unauthorized action. You need manage_orders permission.');
        }

        return view('users.add_credits', compact('user'));
    }

    /**
     * Process adding credits to a customer
     */
    public function addCredits(Request $request, User $user)
    {
        // Check permissions
        if (!Auth::user()->hasPermissionTo('manage_orders')) {
            abort(403, 'Unauthorized action. You need manage_orders permission.');
        }

        // Enhanced validation
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:10000|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        // Log the operation for audit purposes
        Log::info("User {$request->user()->id} ({$request->user()->email}) added {$validated['amount']} credits to user {$user->id} ({$user->email})");

        // Add credits using Database transaction to prevent race conditions
        DB::transaction(function () use ($user, $validated) {
            // Refetch the user in the transaction to prevent race conditions
            $freshUser = User::lockForUpdate()->find($user->id);
            
            // Check if user still exists
            if (!$freshUser) {
                throw new \Exception('User no longer exists');
            }
            
            $freshUser->addCredits($validated['amount']);
        });

        return redirect()->route('list_customers')
            ->with('success', "Successfully added {$validated['amount']} credits to {$user->name}'s account.");
    }

    /**
     * Show the order cancellation form (permission-based)
     */
    public function showCancelForm(Order $order)
    {
        // Check if user is authorized to cancel the order (needs cancel_order permission)
        if (!Auth::user()->hasPermissionTo('cancel_order')) {
            abort(403, 'Unauthorized action. You need cancel_order permission.');
        }
        
        // Only allow cancellation for pending or processing orders
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Only pending or processing orders can be cancelled.');
        }

        // Determine if this is a customer or employee cancellation
        $isEmployeeCancellation = false;
        $isOwner = Auth::id() == $order->user_id;
        
        if (!$isOwner && Auth::user()->hasPermissionTo('manage_orders')) {
            $isEmployeeCancellation = true;
            $reasons = Feedback::getEmployeeReasons();
        } else {
            $reasons = Feedback::getReasons();
        }
        
        return view('orders.cancel', compact('order', 'reasons', 'isEmployeeCancellation'));
    }

    /**
     * Process order cancellation (permission-based)
     */
    public function cancelOrder(Request $request, Order $order)
    {
        // Check if user is authorized to cancel the order (needs cancel_order permission)
        if (!Auth::user()->hasPermissionTo('cancel_order')) {
            abort(403, 'Unauthorized action. You need cancel_order permission.');
        }
        
        // Only allow cancellation for pending or processing orders
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Only pending or processing orders can be cancelled.');
        }

        // Determine if this is a customer or employee cancellation
        $isEmployeeCancellation = false;
        $isOwner = Auth::id() == $order->user_id;
        
        if (!$isOwner && Auth::user()->hasPermissionTo('manage_orders')) {
            $isEmployeeCancellation = true;
            
            // Validate employee cancellation
            $request->validate([
                'reason' => 'required|string|in:' . implode(',', array_keys(Feedback::getEmployeeReasons())),
                'staff_notes' => 'required|string|min:10|max:1000',
            ]);
        } else {
            // Validate customer cancellation
            $request->validate([
                'reason' => 'required|string|in:' . implode(',', array_keys(Feedback::getReasons())),
                'comments' => 'required|string|min:10|max:1000',
            ]);
        }

        return DB::transaction(function() use ($order, $request, $isEmployeeCancellation) {
            // Get all order items to restore stock
            $orderItems = $order->items;
            
            // Restore product stock for each item
            foreach ($orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    // Use negative quantity to increase stock
                    $product->updateStock(-$item->quantity);
                    
                    // Log stock restore
                    Log::info("User {" . Auth::user()->name . "} restored {$item->quantity} items to stock for product {$item->product_id} from cancelled order {$order->id}");
                }
            }
            
            // Refund user credits
            $user = User::find($order->user_id);
            if ($user) {
                $user->addCredits($order->total_amount);
                
                // Log credit refund
                Log::info("User {" . Auth::user()->name . "} refunded {$order->total_amount} credits to user {$user->id} for cancelled order {$order->id}");
            }
            
            // Update order status to cancelled
            $order->status = 'cancelled';
            $order->cancelled_by = Auth::id();
            $order->cancelled_at = now();
            $order->save();
            
            // Create feedback record with proper cancellation type
            $feedbackData = [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'reason' => $request->reason,
                'cancellation_type' => $isEmployeeCancellation ? 
                    Feedback::CANCELLATION_TYPE_EMPLOYEE : 
                    Feedback::CANCELLATION_TYPE_CUSTOMER,
            ];
            
            if ($isEmployeeCancellation) {
                $feedbackData['staff_notes'] = $request->staff_notes;
            } else {
                $feedbackData['comments'] = $request->comments;
            }
            
            $feedback = Feedback::create($feedbackData);

            // Send notification to the customer
            if ($user) {
                $user->notify(new OrderCancelled($order, $request->reason, $isEmployeeCancellation));
            }

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'The order has been cancelled successfully. Credits have been refunded to the customer\'s account.');
        });
    }
}
