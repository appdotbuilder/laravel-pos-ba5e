import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface Product {
    id: number;
    sku: string;
    name: string;
    harga_jual: number;
    stok: number;
    category: {
        id: number;
        name: string;
    };
}

interface Category {
    id: number;
    name: string;
    products_count: number;
}

interface Customer {
    id: number;
    name: string;
    email?: string;
    phone?: string;
}

interface CartItem {
    product: Product;
    quantity: number;
    discount: number;
}

interface Props {
    products: {
        data: Product[];
        links: unknown[];
        meta: unknown;
    };
    categories: Category[];
    customers: Customer[];
    todayStats: {
        sales_count: number;
        sales_total: number;
        customers_served: number;
        products_sold: number;
    };
}

export default function PosIndex({ products, categories, customers, todayStats }: Props) {
    const [cart, setCart] = useState<CartItem[]>([]);
    const [selectedCustomer, setSelectedCustomer] = useState<Customer | null>(null);
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState<number | null>(null);
    const [paymentMethod, setPaymentMethod] = useState<'cash' | 'card' | 'digital'>('cash');
    const [paidAmount, setPaidAmount] = useState<string>('');
    const [saleDiscount, setSaleDiscount] = useState<number>(0);

    const addToCart = (product: Product) => {
        const existingItem = cart.find(item => item.product.id === product.id);
        
        if (existingItem) {
            if (existingItem.quantity < product.stok) {
                setCart(cart.map(item => 
                    item.product.id === product.id 
                        ? { ...item, quantity: item.quantity + 1 }
                        : item
                ));
            }
        } else {
            setCart([...cart, { product, quantity: 1, discount: 0 }]);
        }
    };

    const updateCartItemQuantity = (productId: number, quantity: number) => {
        if (quantity <= 0) {
            setCart(cart.filter(item => item.product.id !== productId));
        } else {
            setCart(cart.map(item => 
                item.product.id === productId 
                    ? { ...item, quantity: Math.min(quantity, item.product.stok) }
                    : item
            ));
        }
    };

    const updateCartItemDiscount = (productId: number, discount: number) => {
        setCart(cart.map(item => 
            item.product.id === productId 
                ? { ...item, discount: Math.max(0, discount) }
                : item
        ));
    };

    const removeFromCart = (productId: number) => {
        setCart(cart.filter(item => item.product.id !== productId));
    };

    const clearCart = () => {
        setCart([]);
        setSelectedCustomer(null);
        setSaleDiscount(0);
        setPaidAmount('');
    };

    // Calculate totals
    const subtotal = cart.reduce((sum, item) => sum + (item.product.harga_jual * item.quantity), 0);
    const itemDiscounts = cart.reduce((sum, item) => sum + item.discount, 0);
    const totalDiscount = itemDiscounts + saleDiscount;
    const taxAmount = (subtotal - totalDiscount) * 0.1; // 10% tax
    const total = subtotal - totalDiscount + taxAmount;
    const change = parseFloat(paidAmount) - total;

    const handleCheckout = () => {
        if (cart.length === 0) return;
        if (paymentMethod === 'cash' && parseFloat(paidAmount) < total) return;

        const saleData = {
            customer_id: selectedCustomer?.id || null,
            items: cart.map(item => ({
                product_id: item.product.id,
                quantity: item.quantity,
                unit_price: item.product.harga_jual,
                discount_amount: item.discount,
            })),
            discount_amount: saleDiscount,
            payment_method: paymentMethod,
            paid_amount: paymentMethod === 'cash' ? parseFloat(paidAmount) : total,
        };

        router.post('/pos', saleData);
    };

    const filteredProducts = products.data.filter(product => {
        const matchesSearch = product.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                             product.sku.toLowerCase().includes(searchQuery.toLowerCase());
        const matchesCategory = !selectedCategory || product.category.id === selectedCategory;
        return matchesSearch && matchesCategory;
    });

    return (
        <AppShell>
            <Head title="POS - Point of Sales" />
            
            <div className="flex h-full">
                {/* Left Panel - Products */}
                <div className="flex-1 flex flex-col bg-white">
                    {/* Header */}
                    <div className="p-6 border-b border-gray-200">
                        <h1 className="text-2xl font-bold text-gray-900">Point of Sales 🛒</h1>
                        
                        {/* Stats */}
                        <div className="mt-4 grid grid-cols-4 gap-4">
                            <div className="bg-blue-50 p-3 rounded-lg">
                                <div className="text-sm font-medium text-blue-600">Penjualan Hari Ini</div>
                                <div className="text-lg font-bold text-blue-900">{todayStats.sales_count}</div>
                            </div>
                            <div className="bg-green-50 p-3 rounded-lg">
                                <div className="text-sm font-medium text-green-600">Total Pendapatan</div>
                                <div className="text-lg font-bold text-green-900">
                                    Rp {todayStats.sales_total.toLocaleString('id-ID')}
                                </div>
                            </div>
                            <div className="bg-purple-50 p-3 rounded-lg">
                                <div className="text-sm font-medium text-purple-600">Pelanggan</div>
                                <div className="text-lg font-bold text-purple-900">{todayStats.customers_served}</div>
                            </div>
                            <div className="bg-orange-50 p-3 rounded-lg">
                                <div className="text-sm font-medium text-orange-600">Produk Terjual</div>
                                <div className="text-lg font-bold text-orange-900">{todayStats.products_sold}</div>
                            </div>
                        </div>

                        {/* Search and Filters */}
                        <div className="mt-4 space-y-3">
                            <input
                                type="text"
                                placeholder="Cari produk (nama, SKU)..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                            
                            <div className="flex gap-2 overflow-x-auto">
                                <button
                                    onClick={() => setSelectedCategory(null)}
                                    className={`px-4 py-2 rounded-lg whitespace-nowrap ${
                                        !selectedCategory 
                                            ? 'bg-blue-600 text-white' 
                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                    }`}
                                >
                                    Semua ({products.data.length})
                                </button>
                                {categories.map(category => (
                                    <button
                                        key={category.id}
                                        onClick={() => setSelectedCategory(category.id)}
                                        className={`px-4 py-2 rounded-lg whitespace-nowrap ${
                                            selectedCategory === category.id
                                                ? 'bg-blue-600 text-white' 
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                        }`}
                                    >
                                        {category.name} ({category.products_count})
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Products Grid */}
                    <div className="flex-1 overflow-y-auto p-6">
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            {filteredProducts.map(product => (
                                <div
                                    key={product.id}
                                    onClick={() => addToCart(product)}
                                    className="border border-gray-200 rounded-lg p-4 hover:shadow-md cursor-pointer transition-shadow"
                                >
                                    <div className="text-xs text-gray-500 mb-1">{product.category.name}</div>
                                    <div className="font-medium text-gray-900 text-sm mb-2 line-clamp-2">
                                        {product.name}
                                    </div>
                                    <div className="text-lg font-bold text-blue-600 mb-1">
                                        Rp {product.harga_jual.toLocaleString('id-ID')}
                                    </div>
                                    <div className="text-xs text-gray-500">
                                        Stok: {product.stok} | SKU: {product.sku}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Right Panel - Cart */}
                <div className="w-96 bg-gray-50 border-l border-gray-200 flex flex-col">
                    {/* Cart Header */}
                    <div className="p-4 border-b border-gray-200 bg-white">
                        <div className="flex items-center justify-between">
                            <h2 className="text-lg font-bold text-gray-900">Keranjang ({cart.length})</h2>
                            <button
                                onClick={clearCart}
                                className="text-red-600 hover:text-red-800 text-sm font-medium"
                            >
                                Bersihkan
                            </button>
                        </div>
                        
                        {/* Customer Selection */}
                        <div className="mt-3">
                            <select
                                value={selectedCustomer?.id || ''}
                                onChange={(e) => {
                                    const customer = customers.find(c => c.id === parseInt(e.target.value));
                                    setSelectedCustomer(customer || null);
                                }}
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Pilih Pelanggan (Opsional)</option>
                                {customers.map(customer => (
                                    <option key={customer.id} value={customer.id}>
                                        {customer.name} {customer.phone && `(${customer.phone})`}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>

                    {/* Cart Items */}
                    <div className="flex-1 overflow-y-auto">
                        {cart.length === 0 ? (
                            <div className="p-4 text-center text-gray-500">
                                <div className="text-4xl mb-2">🛒</div>
                                <p>Keranjang masih kosong</p>
                                <p className="text-sm">Klik produk untuk menambahkan</p>
                            </div>
                        ) : (
                            <div className="p-4 space-y-3">
                                {cart.map(item => (
                                    <div key={item.product.id} className="bg-white rounded-lg p-3 border border-gray-200">
                                        <div className="flex items-start justify-between">
                                            <div className="flex-1">
                                                <div className="font-medium text-sm text-gray-900 line-clamp-2">
                                                    {item.product.name}
                                                </div>
                                                <div className="text-sm text-blue-600 font-medium">
                                                    Rp {item.product.harga_jual.toLocaleString('id-ID')}
                                                </div>
                                            </div>
                                            <button
                                                onClick={() => removeFromCart(item.product.id)}
                                                className="text-red-600 hover:text-red-800 ml-2"
                                            >
                                                ✕
                                            </button>
                                        </div>
                                        
                                        <div className="mt-2 flex items-center gap-2">
                                            <button
                                                onClick={() => updateCartItemQuantity(item.product.id, item.quantity - 1)}
                                                className="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center"
                                            >
                                                −
                                            </button>
                                            <span className="w-8 text-center font-medium">{item.quantity}</span>
                                            <button
                                                onClick={() => updateCartItemQuantity(item.product.id, item.quantity + 1)}
                                                className="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center"
                                            >
                                                +
                                            </button>
                                            <div className="ml-auto text-sm font-medium">
                                                Rp {(item.product.harga_jual * item.quantity).toLocaleString('id-ID')}
                                            </div>
                                        </div>
                                        
                                        {/* Item Discount */}
                                        <div className="mt-2">
                                            <input
                                                type="number"
                                                placeholder="Diskon item (Rp)"
                                                value={item.discount || ''}
                                                onChange={(e) => updateCartItemDiscount(item.product.id, parseFloat(e.target.value) || 0)}
                                                className="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                            />
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Checkout Panel */}
                    {cart.length > 0 && (
                        <div className="border-t border-gray-200 bg-white p-4 space-y-4">
                            {/* Sale Discount */}
                            <div>
                                <input
                                    type="number"
                                    placeholder="Diskon keseluruhan (Rp)"
                                    value={saleDiscount || ''}
                                    onChange={(e) => setSaleDiscount(parseFloat(e.target.value) || 0)}
                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            {/* Payment Method */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Metode Pembayaran
                                </label>
                                <div className="grid grid-cols-3 gap-2">
                                    {[
                                        { value: 'cash', label: '💰 Tunai' },
                                        { value: 'card', label: '💳 Kartu' },
                                        { value: 'digital', label: '📱 Digital' },
                                    ].map(method => (
                                        <button
                                            key={method.value}
                                            onClick={() => setPaymentMethod(method.value as 'cash' | 'card' | 'digital')}
                                            className={`px-3 py-2 text-sm rounded-lg ${
                                                paymentMethod === method.value
                                                    ? 'bg-blue-600 text-white'
                                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                            }`}
                                        >
                                            {method.label}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Payment Amount for Cash */}
                            {paymentMethod === 'cash' && (
                                <div>
                                    <input
                                        type="number"
                                        placeholder="Jumlah bayar"
                                        value={paidAmount}
                                        onChange={(e) => setPaidAmount(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    />
                                </div>
                            )}

                            {/* Summary */}
                            <div className="space-y-2 text-sm">
                                <div className="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span>Rp {subtotal.toLocaleString('id-ID')}</span>
                                </div>
                                {totalDiscount > 0 && (
                                    <div className="flex justify-between text-red-600">
                                        <span>Diskon:</span>
                                        <span>-Rp {totalDiscount.toLocaleString('id-ID')}</span>
                                    </div>
                                )}
                                <div className="flex justify-between text-gray-600">
                                    <span>Pajak (10%):</span>
                                    <span>Rp {taxAmount.toLocaleString('id-ID')}</span>
                                </div>
                                <div className="flex justify-between font-bold text-lg border-t pt-2">
                                    <span>Total:</span>
                                    <span>Rp {total.toLocaleString('id-ID')}</span>
                                </div>
                                {paymentMethod === 'cash' && parseFloat(paidAmount) > 0 && (
                                    <div className="flex justify-between text-green-600">
                                        <span>Kembalian:</span>
                                        <span>Rp {Math.max(0, change).toLocaleString('id-ID')}</span>
                                    </div>
                                )}
                            </div>

                            {/* Checkout Button */}
                            <button
                                onClick={handleCheckout}
                                disabled={cart.length === 0 || (paymentMethod === 'cash' && parseFloat(paidAmount) < total)}
                                className="w-full py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Proses Pembayaran 🛒
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </AppShell>
    );
}