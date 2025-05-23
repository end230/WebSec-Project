<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model  {

    use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'code',
        'name',
        'model',
        'description',
        'price',
        'stock_quantity',
        'photo',
        'main_photo',
        'additional_photos',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'additional_photos' => 'array',
        'price' => 'float',
        'stock_quantity' => 'integer',
    ];

    /**
     * Check if the product is in stock
     */
    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Safely update stock quantity (prevent negative values)
     *
     * @param int $quantity The quantity to reduce (negative to increase)
     * @return boolean Whether the update was successful
     */
    public function updateStock($quantity)
    {
        // If reducing stock (positive quantity)
        if ($quantity > 0) {
            // Prevent reducing below zero
            if ($this->stock_quantity < $quantity) {
                return false;
            }
        }

        $this->stock_quantity -= $quantity;
        $this->save();

        return true;
    }

    /**
     * Get the orders that include this product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the main photo URL
     */
    public function getMainPhotoUrl()
    {
        if ($this->main_photo) {
            return asset('storage/products/' . $this->main_photo);
        } elseif ($this->photo) {
            // Fallback to old photo field for backward compatibility
            return asset('storage/products/' . $this->photo);
        }
        
        // Default image if none set
        return asset('images/product-placeholder.jpg');
    }

    /**
     * Get all photo URLs (main and additional)
     */
    public function getAllPhotoUrls()
    {
        $photos = [];
        
        // Add main photo
        if ($this->main_photo) {
            $photos[] = asset('storage/products/' . $this->main_photo);
        } elseif ($this->photo) {
            $photos[] = asset('storage/products/' . $this->photo);
        }
        
        // Add additional photos
        if ($this->additional_photos && is_array($this->additional_photos)) {
            foreach ($this->additional_photos as $photo) {
                $photos[] = asset('storage/products/' . $photo);
            }
        }
        
        // If no photos, return default
        if (empty($photos)) {
            $photos[] = asset('images/product-placeholder.jpg');
        }
        
        return $photos;
    }
}
