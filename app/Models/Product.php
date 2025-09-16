<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $sku
 * @property string $name
 * @property int $category_id
 * @property float $harga_beli
 * @property float $harga_jual
 * @property int $stok
 * @property string|null $barcode
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $saleItems
 * @property-read int|null $sale_items_count
 * @property-read float $profit_margin
 * @property-read float $profit_percentage
 * @property int $total_sold
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHargaBeli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHargaJual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product active()
 * @method static \Illuminate\Database\Eloquent\Builder|Product inStock()
 * @method static \Illuminate\Database\Eloquent\Builder|Product lowStock($threshold = 10)
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'harga_beli',
        'harga_jual',
        'stok',
        'barcode',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope a query to only include products with low stock.
     */
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stok', '<=', $threshold)->where('stok', '>', 0);
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all sale items for this product.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the profit margin for this product.
     */
    public function getProfitMarginAttribute(): float
    {
        return $this->harga_jual - $this->harga_beli;
    }

    /**
     * Get the profit percentage for this product.
     */
    public function getProfitPercentageAttribute(): float
    {
        if ($this->harga_beli === 0.0) {
            return 0;
        }

        return (($this->harga_jual - $this->harga_beli) / $this->harga_beli) * 100;
    }

    /**
     * Decrease product stock.
     */
    public function decreaseStock(int $quantity): bool
    {
        if ($this->stok < $quantity) {
            return false;
        }

        $this->decrement('stok', $quantity);
        return true;
    }

    /**
     * Increase product stock.
     */
    public function increaseStock(int $quantity): void
    {
        $this->increment('stok', $quantity);
    }
}