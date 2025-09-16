<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PosController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        $products = Product::with('category')
                          ->where('is_active', true)
                          ->where('stok', '>', 0)
                          ->latest()
                          ->paginate(20);

        $categories = Category::withCount('products')->get();

        $customers = Customer::select('id', 'name', 'email', 'phone')
                            ->latest()
                            ->limit(50)
                            ->get();

        $todayStats = [
            'sales_count' => Sale::whereDate('created_at', today())->count(),
            'sales_total' => Sale::whereDate('created_at', today())->sum('total_amount'),
            'customers_served' => Sale::whereDate('created_at', today())->whereNotNull('customer_id')->distinct('customer_id')->count(),
            'products_sold' => SaleItem::whereHas('sale', function ($query) {
                $query->whereDate('created_at', today());
            })->sum('quantity'),
        ];

        return Inertia::render('pos/index', [
            'products' => $products,
            'categories' => $categories,
            'customers' => $customers,
            'todayStats' => $todayStats,
        ]);
    }



    /**
     * Process a sale.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,digital',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = 0;
            $totalItemDiscount = 0;

            foreach ($validated['items'] as $item) {
                $itemTotal = $item['unit_price'] * $item['quantity'];
                $subtotal += $itemTotal;
                $totalItemDiscount += $item['discount_amount'] ?? 0;
            }

            $saleDiscount = $validated['discount_amount'] ?? 0;
            $totalDiscount = $totalItemDiscount + $saleDiscount;
            $taxAmount = ($subtotal - $totalDiscount) * 0.1; // 10% tax
            $totalAmount = $subtotal - $totalDiscount + $taxAmount;

            $paidAmount = $validated['paid_amount'];
            $changeAmount = max(0, $paidAmount - $totalAmount);

            // Create sale
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'notes' => $validated['notes'],
            ]);

            // Create sale items and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check stock availability
                if ($product->stok < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'total_price' => ($item['unit_price'] * $item['quantity']) - ($item['discount_amount'] ?? 0),
                ]);

                // Update product stock
                $product->decreaseStock($item['quantity']);
            }

            // Update customer stats
            if ($validated['customer_id']) {
                $customer = Customer::findOrFail($validated['customer_id']);
                $customer->updateStats($totalAmount);
            }

            DB::commit();

            return Inertia::render('pos/receipt', [
                'sale' => $sale->load(['items.product', 'customer', 'user']),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the POS interface.
     */
    public function show()
    {
        $stats = [
            'today' => [
                'sales_count' => Sale::whereDate('created_at', today())->count(),
                'revenue' => Sale::whereDate('created_at', today())->sum('total_amount'),
                'customers' => Sale::whereDate('created_at', today())->whereNotNull('customer_id')->distinct('customer_id')->count(),
                'avg_sale' => Sale::whereDate('created_at', today())->avg('total_amount') ?? 0,
            ],
            'this_week' => [
                'sales_count' => Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'revenue' => Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount'),
            ],
            'this_month' => [
                'sales_count' => Sale::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'revenue' => Sale::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_amount'),
            ],
        ];

        // Recent sales
        $recentSales = Sale::with(['customer', 'user', 'items'])
                          ->latest()
                          ->limit(10)
                          ->get();

        // Top products
        $topProducts = Product::withSum('saleItems', 'quantity')
                            ->whereHas('saleItems')
                            ->orderBy('sale_items_sum_quantity', 'desc')
                            ->limit(10)
                            ->get();

        // Add total_sold property for compatibility
        foreach ($topProducts as $product) {
            $product->total_sold = $product->sale_items_sum_quantity ?? 0;
        }

        // Low stock products
        $lowStockProducts = Product::where('is_active', true)
                                  ->where('stok', '<=', 10)
                                  ->where('stok', '>', 0)
                                  ->with('category')
                                  ->orderBy('stok')
                                  ->limit(10)
                                  ->get();

        return Inertia::render('pos/dashboard', [
            'stats' => $stats,
            'recentSales' => $recentSales,
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}