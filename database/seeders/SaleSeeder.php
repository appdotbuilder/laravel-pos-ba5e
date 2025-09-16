<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', ['admin', 'kasir'])->get();
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();

        // Create sales for the last 3 months
        for ($i = 0; $i < 150; $i++) {
            $user = $users->random();
            $customer = fake()->boolean(60) ? $customers->random() : null; // 60% chance of having a customer
            
            $sale = Sale::factory()->create([
                'user_id' => $user->id,
                'customer_id' => $customer?->id,
                'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            ]);

            // Add random products to each sale
            $saleProducts = $products->random(random_int(1, 5));
            $subtotal = 0;
            $totalDiscount = 0;

            foreach ($saleProducts as $product) {
                $quantity = random_int(1, 3);
                $unitPrice = $product->harga_jual;
                $itemDiscount = fake()->boolean(30) ? fake()->randomFloat(2, 0, $unitPrice * $quantity * 0.1) : 0;
                $totalPrice = ($unitPrice * $quantity) - $itemDiscount;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_amount' => $itemDiscount,
                    'total_price' => $totalPrice,
                ]);

                $subtotal += $unitPrice * $quantity;
                $totalDiscount += $itemDiscount;

                // Update product stock
                $product->decrement('stok', $quantity);
            }

            // Apply additional sale-wide discount (20% chance)
            if (fake()->boolean(20)) {
                $additionalDiscount = fake()->randomFloat(2, 0, $subtotal * 0.05);
                $totalDiscount += $additionalDiscount;
            }

            $taxAmount = ($subtotal - $totalDiscount) * 0.1; // 10% tax
            $totalAmount = $subtotal - $totalDiscount + $taxAmount;
            $paidAmount = $totalAmount;
            $changeAmount = 0;

            // For cash payments, add some change
            if ($sale->payment_method === 'cash') {
                $paidAmount = $totalAmount + fake()->randomFloat(2, 0, 50000);
                $changeAmount = $paidAmount - $totalAmount;
            }

            // Update sale totals
            $sale->update([
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
            ]);

            // Update customer stats if customer exists
            if ($customer) {
                $customer->updateStats($totalAmount);
            }
        }

        // Create some high-value sales
        Sale::factory(10)->highValue()->create()->each(function ($sale) use ($products) {
            $saleProducts = $products->random(random_int(3, 8));
            $subtotal = 0;

            foreach ($saleProducts as $product) {
                $quantity = random_int(2, 5);
                $unitPrice = $product->harga_jual;
                $totalPrice = $unitPrice * $quantity;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_amount' => 0,
                    'total_price' => $totalPrice,
                ]);

                $subtotal += $totalPrice;
                $product->decrement('stok', $quantity);
            }

            $taxAmount = $subtotal * 0.1;
            $totalAmount = $subtotal + $taxAmount;

            $sale->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount,
            ]);
        });
    }
}