<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImportLog>
 */
class ImportLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalRows = fake()->numberBetween(10, 1000);
        $failedRows = fake()->numberBetween(0, (int)($totalRows * 0.1));
        $updatedRows = fake()->numberBetween(0, (int)(($totalRows - $failedRows) * 0.3));
        $successfulRows = $totalRows - $failedRows - $updatedRows;

        return [
            'user_id' => User::factory(),
            'file_name' => 'products_import_' . fake()->date('Y_m_d_H_i_s') . '.csv',
            'total_rows' => $totalRows,
            'successful_rows' => $successfulRows,
            'updated_rows' => $updatedRows,
            'failed_rows' => $failedRows,
            'errors' => $failedRows > 0 ? [
                'Row 5: Invalid SKU format',
                'Row 12: Negative price not allowed',
                'Row 25: Missing required field: name',
            ] : null,
        ];
    }

    /**
     * Indicate that the import was successful.
     */
    public function successful(): static
    {
        return $this->state(function (array $attributes) {
            $totalRows = $attributes['total_rows'] ?? fake()->numberBetween(10, 100);
            
            return [
                'successful_rows' => $totalRows,
                'updated_rows' => 0,
                'failed_rows' => 0,
                'errors' => null,
            ];
        });
    }

    /**
     * Indicate that the import had many errors.
     */
    public function withErrors(): static
    {
        return $this->state(function (array $attributes) {
            $totalRows = $attributes['total_rows'] ?? fake()->numberBetween(50, 200);
            $failedRows = fake()->numberBetween((int)($totalRows * 0.2), (int)($totalRows * 0.5));
            $successfulRows = $totalRows - $failedRows;
            
            return [
                'successful_rows' => $successfulRows,
                'updated_rows' => 0,
                'failed_rows' => $failedRows,
                'errors' => [
                    'Row 3: Duplicate SKU found',
                    'Row 8: Invalid category',
                    'Row 15: Price cannot be negative',
                    'Row 22: SKU field is required',
                    'Row 34: Invalid stock quantity',
                ],
            ];
        });
    }
}