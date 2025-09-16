<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ImportLog
 *
 * @property int $id
 * @property int $user_id
 * @property string $file_name
 * @property int $total_rows
 * @property int $successful_rows
 * @property int $updated_rows
 * @property int $failed_rows
 * @property array|null $errors
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $user
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereErrors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereFailedRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereSuccessfulRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereTotalRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereUpdatedRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportLog whereUserId($value)
 * @method static \Database\Factories\ImportLogFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class ImportLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'file_name',
        'total_rows',
        'successful_rows',
        'updated_rows',
        'failed_rows',
        'errors',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_rows' => 'integer',
        'successful_rows' => 'integer',
        'updated_rows' => 'integer',
        'failed_rows' => 'integer',
        'errors' => 'array',
    ];

    /**
     * Get the user that performed the import.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}