<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property float $total_spent
 * @property int $total_orders
 * @property \Illuminate\Support\Carbon|null $last_order_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLastOrderAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereTotalOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereTotalSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer recentCustomers($days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer topSpenders($limit = 10)
 * @method static \Database\Factories\CustomerFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'total_spent',
        'total_orders',
        'last_order_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_spent' => 'decimal:2',
        'total_orders' => 'integer',
        'last_order_at' => 'datetime',
    ];

    /**
     * Scope a query to get recent customers.
     */
    public function scopeRecentCustomers($query, $days = 30)
    {
        return $query->where('last_order_at', '>=', now()->subDays($days))
                    ->orderBy('last_order_at', 'desc');
    }

    /**
     * Scope a query to get top spending customers.
     */
    public function scopeTopSpenders($query, $limit = 10)
    {
        return $query->orderBy('total_spent', 'desc')
                    ->limit($limit);
    }

    /**
     * Get all sales for this customer.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Update customer statistics after a sale.
     */
    public function updateStats(float $amount): void
    {
        $this->increment('total_spent', $amount);
        $this->increment('total_orders');
        $this->update(['last_order_at' => now()]);
    }
}