<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {
		$query = Product::with(['comments.user']); // Load comments with users
		
		// Apply search and filter logic
		if ($request->filled('search')) {
			$search = $request->search;
			$query->where(function($q) use ($search) {
				$q->where('name', 'like', "%{$search}%")
				  ->orWhere('description', 'like', "%{$search}%");
			});
		}
		
		if ($request->filled('min_price')) {
			$query->where('price', '>=', $request->min_price);
		}
		
		if ($request->filled('max_price')) {
			$query->where('price', '<=', $request->max_price);
		}
		
		// Sort products
		$sort = $request->sort ?? 'name_asc';
		
		switch ($sort) {
			case 'price_asc':
				$query->orderBy('price', 'asc');
				break;
			case 'price_desc':
				$query->orderBy('price', 'desc');
				break;
			case 'name_desc':
				$query->orderBy('name', 'desc');
				break;
			case 'newest':
				$query->orderBy('created_at', 'desc');
				break;
			default:
				$query->orderBy('name', 'asc');
		}
		
		$products = $query->paginate(12)->withQueryString();
		
		// For each product, check if current user has purchased it
		$userPurchases = [];
		$userComments = [];
		
		if (auth()->check()) {
			// Get all products the user has purchased and received
			$purchasedProductIds = \App\Models\Order::where('user_id', auth()->id())
				->where('status', 'delivered')
				->with('items')
				->get()
				->pluck('items')
				->flatten()
				->pluck('product_id')
				->unique()
				->toArray();
			
			$userPurchases = array_flip($purchasedProductIds);
			
			// Get user's existing comments for these products
			$userComments = \App\Models\ProductComment::where('user_id', auth()->id())
				->whereIn('product_id', $products->pluck('id'))
				->pluck('product_id')
				->flip()
				->toArray();
		}
		
		return view('products.list', compact('products', 'userPurchases', 'userComments'));
	}

	public function edit(Request $request, Product $product = null) {
		// Check if user has permission to edit products
		if(!auth()->user()->hasPermissionTo('edit_products')) {
			abort(403, 'You do not have permission to edit products.');
		}

		$product = $product??new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null) {
        // Check if user has permission to add or edit products
        if(!auth()->user()->hasAnyPermission(['add_products', 'edit_products'])) {
            abort(403, 'You do not have permission to add or edit products.');
        }

        $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'model' => ['required', 'string', 'max:256'],
            'description' => ['required', 'string', 'max:1024', 'no_script_tags', 'safe_html'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:100000'],
            'photo' => ['nullable', 'string', 'max:255'],
            'main_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max size
            'additional_photos.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Each additional photo 2MB max
            'remove_main_photo' => ['nullable', 'boolean'],
            'remove_additional_photos' => ['nullable', 'array'],
            'remove_additional_photos.*' => ['nullable', 'string']
        ]);

        try {
            DB::beginTransaction();

            $product = $product ?? new Product();
            
            // Fix mass assignment by explicitly setting fields
            $product->code = $request->input('code');
            $product->name = $request->input('name');
            $product->model = $request->input('model');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->stock_quantity = $request->input('stock_quantity');
            
            // For backward compatibility
            if ($request->has('photo')) {
                $product->photo = $request->input('photo');
            }
            
            // Handle main photo removal if requested
            if ($request->input('remove_main_photo')) {
                if ($product->main_photo) {
                    Storage::delete('public/products/' . $product->main_photo);
                    $product->main_photo = null;
                }
            }
            
            // Handle main photo upload
            if ($request->hasFile('main_photo')) {
                $mainPhoto = $request->file('main_photo');
                if ($mainPhoto->isValid()) {
                    // Delete old photo if exists
                    if ($product->main_photo) {
                        Storage::delete('public/products/' . $product->main_photo);
                    }
                    
                    // Generate unique filename
                    $mainPhotoName = time() . '_' . uniqid() . '.' . $mainPhoto->getClientOriginalExtension();
                    
                    // Store the file
                    $mainPhoto->storeAs('public/products', $mainPhotoName);
                    $product->main_photo = $mainPhotoName;
                } else {
                    throw new \Exception('Invalid main photo file uploaded.');
                }
            }
            
            // Handle additional photos removal
            if ($request->has('remove_additional_photos') && is_array($request->input('remove_additional_photos'))) {
                $photosToKeep = [];
                $currentPhotos = $product->additional_photos ?? [];
                
                foreach ($currentPhotos as $photo) {
                    if (!in_array($photo, $request->input('remove_additional_photos'))) {
                        $photosToKeep[] = $photo;
                    } else {
                        Storage::delete('public/products/' . $photo);
                    }
                }
                
                $product->additional_photos = $photosToKeep;
            }
            
            // Handle additional photos upload
            if ($request->hasFile('additional_photos')) {
                $additionalPhotos = $product->additional_photos ?? [];
                
                foreach ($request->file('additional_photos') as $photo) {
                    if ($photo->isValid()) {
                        // Generate unique filename
                        $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                        
                        // Store the file
                        $photo->storeAs('public/products', $photoName);
                        $additionalPhotos[] = $photoName;
                    } else {
                        throw new \Exception('Invalid additional photo file uploaded.');
                    }
                }
                
                $product->additional_photos = $additionalPhotos;
            }
            
            $product->save();
            DB::commit();

            return redirect()->route('products_list')
                ->with('success', 'Product saved successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            Log::error('Product save failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save product. Please try again. ' . $e->getMessage());
        }
    }

	public function delete(Request $request, Product $product) {
		// Check if user has permission to delete products
		if(!auth()->user()->hasPermissionTo('delete_products')) abort(403, 'You do not have permission to delete products.');

		$product->delete();

		return redirect()->route('products_list');
	}

	/**
	 * Show a single product's details
	 */
	public function show(Product $product)
	{
		// Load approved comments with user information
		$comments = $product->comments()->with('user')->paginate(5);
		
		// Check if current user has purchased this product
		$hasPurchased = false;
		$existingComment = null;
		
		if (auth()->check()) {
			// Check if user has completed order with this product
			$hasPurchased = \App\Models\Order::where('user_id', auth()->id())
				->whereHas('items', function($query) use ($product) {
					$query->where('product_id', $product->id);
				})
				->where('status', 'delivered')
				->exists();
			
			// Check if user has already reviewed this product
			$existingComment = \App\Models\ProductComment::where('product_id', $product->id)
				->where('user_id', auth()->id())
				->first();
		}
		
		$relatedProducts = Product::where('id', '!=', $product->id)
			->inRandomOrder()
			->limit(4)
			->get();
			
		// If user is logged in, generate a personalized view token
		$viewToken = null;
		if (auth()->check()) {
			$viewToken = md5(auth()->id() . '_' . $product->id . '_' . time());
		}
		
		return view('products.show', compact('product', 'relatedProducts', 'viewToken', 'comments', 'hasPurchased', 'existingComment'));
	}
}
