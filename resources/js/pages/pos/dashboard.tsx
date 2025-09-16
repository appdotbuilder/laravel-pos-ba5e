import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface Sale {
    id: number;
    invoice_number: string;
    total_amount: number;
    payment_method: string;
    created_at: string;
    customer?: {
        name: string;
    };
    user: {
        name: string;
    };
}

interface Product {
    id: number;
    name: string;
    total_sold: number;
    stok: number;
    harga_jual: number;
}

interface Props {
    stats: {
        today: {
            sales_count: number;
            revenue: number;
            customers: number;
            avg_sale: number;
        };
        this_week: {
            sales_count: number;
            revenue: number;
        };
        this_month: {
            sales_count: number;
            revenue: number;
        };
    };
    recentSales: Sale[];
    topProducts: Product[];
    lowStockProducts: Product[];
}

export default function PosDashboard({ stats, recentSales, topProducts, lowStockProducts }: Props) {
    const paymentMethodLabels = {
        cash: '💰 Tunai',
        card: '💳 Kartu',
        digital: '📱 Digital',
    };

    return (
        <AppShell>
            <Head title="Dashboard POS" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Dashboard POS 📊</h1>
                        <p className="text-gray-600">Ringkasan penjualan dan performa toko</p>
                    </div>
                    <Link
                        href="/pos"
                        className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        🛒 Ke POS
                    </Link>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                        <div className="flex items-center">
                            <div className="flex-shrink-0">
                                <div className="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span className="text-blue-600">🏪</span>
                                </div>
                            </div>
                            <div className="ml-4">
                                <h2 className="text-sm font-medium text-gray-500">Penjualan Hari Ini</h2>
                                <p className="text-2xl font-bold text-gray-900">{stats.today.sales_count}</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                        <div className="flex items-center">
                            <div className="flex-shrink-0">
                                <div className="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span className="text-green-600">💰</span>
                                </div>
                            </div>
                            <div className="ml-4">
                                <h2 className="text-sm font-medium text-gray-500">Pendapatan Hari Ini</h2>
                                <p className="text-2xl font-bold text-gray-900">
                                    Rp {stats.today.revenue.toLocaleString('id-ID')}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                        <div className="flex items-center">
                            <div className="flex-shrink-0">
                                <div className="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <span className="text-purple-600">👥</span>
                                </div>
                            </div>
                            <div className="ml-4">
                                <h2 className="text-sm font-medium text-gray-500">Pelanggan Hari Ini</h2>
                                <p className="text-2xl font-bold text-gray-900">{stats.today.customers}</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                        <div className="flex items-center">
                            <div className="flex-shrink-0">
                                <div className="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <span className="text-orange-600">📈</span>
                                </div>
                            </div>
                            <div className="ml-4">
                                <h2 className="text-sm font-medium text-gray-500">Rata-rata Penjualan</h2>
                                <p className="text-2xl font-bold text-gray-900">
                                    Rp {Math.round(stats.today.avg_sale).toLocaleString('id-ID')}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Period Comparison */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Perbandingan Periode</h3>
                        <div className="space-y-4">
                            <div className="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <div>
                                    <p className="font-medium text-blue-900">Minggu Ini</p>
                                    <p className="text-sm text-blue-600">{stats.this_week.sales_count} transaksi</p>
                                </div>
                                <p className="text-lg font-bold text-blue-900">
                                    Rp {stats.this_week.revenue.toLocaleString('id-ID')}
                                </p>
                            </div>
                            <div className="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <div>
                                    <p className="font-medium text-green-900">Bulan Ini</p>
                                    <p className="text-sm text-green-600">{stats.this_month.sales_count} transaksi</p>
                                </div>
                                <p className="text-lg font-bold text-green-900">
                                    Rp {stats.this_month.revenue.toLocaleString('id-ID')}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Low Stock Alert */}
                    <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">
                            ⚠️ Stok Menipis
                        </h3>
                        {lowStockProducts.length > 0 ? (
                            <div className="space-y-3">
                                {lowStockProducts.slice(0, 5).map(product => (
                                    <div key={product.id} className="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                                        <div>
                                            <p className="font-medium text-red-900">{product.name}</p>
                                            <p className="text-sm text-red-600">
                                                Rp {product.harga_jual.toLocaleString('id-ID')}
                                            </p>
                                        </div>
                                        <div className="text-right">
                                            <p className="text-sm font-bold text-red-900">
                                                Stok: {product.stok}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-gray-500 text-center py-4">
                                ✅ Semua produk memiliki stok yang cukup
                            </p>
                        )}
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Recent Sales */}
                    <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">
                            🛒 Penjualan Terbaru
                        </h3>
                        {recentSales.length > 0 ? (
                            <div className="space-y-3">
                                {recentSales.map(sale => (
                                    <div key={sale.id} className="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p className="font-medium text-gray-900">#{sale.invoice_number}</p>
                                            <p className="text-sm text-gray-600">
                                                {sale.customer?.name || 'Walk-in Customer'} • {sale.user.name}
                                            </p>
                                            <p className="text-xs text-gray-500">
                                                {new Date(sale.created_at).toLocaleDateString('id-ID')} •{' '}
                                                {paymentMethodLabels[sale.payment_method as keyof typeof paymentMethodLabels]}
                                            </p>
                                        </div>
                                        <p className="text-lg font-bold text-gray-900">
                                            Rp {sale.total_amount.toLocaleString('id-ID')}
                                        </p>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-gray-500 text-center py-8">
                                Belum ada penjualan
                            </p>
                        )}
                    </div>

                    {/* Top Products */}
                    <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">
                            🏆 Produk Terlaris
                        </h3>
                        {topProducts.length > 0 ? (
                            <div className="space-y-3">
                                {topProducts.map((product, index) => (
                                    <div key={product.id} className="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                        <div className="flex items-center">
                                            <div className="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                                <span className="text-sm font-bold text-yellow-600">
                                                    {index + 1}
                                                </span>
                                            </div>
                                            <div>
                                                <p className="font-medium text-yellow-900">{product.name}</p>
                                                <p className="text-sm text-yellow-600">
                                                    Rp {product.harga_jual.toLocaleString('id-ID')} • Stok: {product.stok}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <p className="text-lg font-bold text-yellow-900">
                                                {product.total_sold}
                                            </p>
                                            <p className="text-xs text-yellow-600">terjual</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-gray-500 text-center py-8">
                                Belum ada data penjualan produk
                            </p>
                        )}
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h3 className="text-lg font-semibold text-gray-900 mb-4">
                        🚀 Aksi Cepat
                    </h3>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <Link
                            href="/pos"
                            className="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                        >
                            <span className="text-2xl mb-2">🛒</span>
                            <span className="text-sm font-medium text-blue-900">Mulai Transaksi</span>
                        </Link>
                        <Link
                            href="/products"
                            className="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors"
                        >
                            <span className="text-2xl mb-2">📦</span>
                            <span className="text-sm font-medium text-green-900">Kelola Produk</span>
                        </Link>
                        <Link
                            href="/customers"
                            className="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors"
                        >
                            <span className="text-2xl mb-2">👥</span>
                            <span className="text-sm font-medium text-purple-900">Data Pelanggan</span>
                        </Link>
                        <Link
                            href="/reports"
                            className="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors"
                        >
                            <span className="text-2xl mb-2">📊</span>
                            <span className="text-sm font-medium text-orange-900">Laporan</span>
                        </Link>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}