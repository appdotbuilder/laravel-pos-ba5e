import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface SaleItem {
    id: number;
    quantity: number;
    unit_price: number;
    discount_amount: number;
    total_price: number;
    product: {
        id: number;
        name: string;
        sku: string;
    };
}

interface Sale {
    id: number;
    invoice_number: string;
    subtotal: number;
    discount_amount: number;
    tax_amount: number;
    total_amount: number;
    payment_method: string;
    paid_amount: number;
    change_amount: number;
    created_at: string;
    customer?: {
        id: number;
        name: string;
        email?: string;
        phone?: string;
    };
    user: {
        id: number;
        name: string;
    };
    items: SaleItem[];
}

interface Props {
    sale: Sale;
}

export default function Receipt({ sale }: Props) {
    const handlePrint = () => {
        window.print();
    };

    const paymentMethodLabels = {
        cash: '💰 Tunai',
        card: '💳 Kartu',
        digital: '📱 Digital',
    };

    return (
        <AppShell>
            <Head title={`Receipt - ${sale.invoice_number}`} />
            
            <div className="max-w-2xl mx-auto p-6">
                {/* Header Actions */}
                <div className="flex justify-between items-center mb-6">
                    <Link
                        href="/pos"
                        className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        ← Kembali ke POS
                    </Link>
                    <button
                        onClick={handlePrint}
                        className="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                    >
                        🖨️ Print Struk
                    </button>
                </div>

                {/* Receipt */}
                <div className="bg-white border border-gray-200 rounded-lg shadow-sm" id="receipt">
                    {/* Receipt Header */}
                    <div className="p-6 border-b border-gray-200 text-center">
                        <h1 className="text-2xl font-bold text-gray-900 mb-2">🏪 TOKO POS MODERN</h1>
                        <p className="text-gray-600">Jl. Contoh No. 123, Jakarta</p>
                        <p className="text-gray-600">Telp: (021) 123-4567</p>
                        <div className="mt-4 pt-4 border-t border-gray-200">
                            <h2 className="text-lg font-semibold">STRUK PENJUALAN</h2>
                            <p className="text-sm text-gray-600">#{sale.invoice_number}</p>
                        </div>
                    </div>

                    {/* Transaction Info */}
                    <div className="p-6 border-b border-gray-200">
                        <div className="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p className="text-gray-600">Tanggal:</p>
                                <p className="font-medium">
                                    {new Date(sale.created_at).toLocaleDateString('id-ID', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}
                                </p>
                            </div>
                            <div>
                                <p className="text-gray-600">Kasir:</p>
                                <p className="font-medium">{sale.user.name}</p>
                            </div>
                            {sale.customer && (
                                <>
                                    <div>
                                        <p className="text-gray-600">Pelanggan:</p>
                                        <p className="font-medium">{sale.customer.name}</p>
                                    </div>
                                    <div>
                                        <p className="text-gray-600">Kontak:</p>
                                        <p className="font-medium">
                                            {sale.customer.phone || sale.customer.email || '-'}
                                        </p>
                                    </div>
                                </>
                            )}
                            <div>
                                <p className="text-gray-600">Pembayaran:</p>
                                <p className="font-medium">
                                    {paymentMethodLabels[sale.payment_method as keyof typeof paymentMethodLabels]}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Items */}
                    <div className="p-6 border-b border-gray-200">
                        <h3 className="font-semibold text-gray-900 mb-4">Detail Pembelian:</h3>
                        <div className="space-y-3">
                            {sale.items.map((item) => (
                                <div key={item.id} className="flex justify-between items-start">
                                    <div className="flex-1">
                                        <p className="font-medium text-gray-900">{item.product.name}</p>
                                        <p className="text-sm text-gray-600">
                                            {item.quantity} × Rp {item.unit_price.toLocaleString('id-ID')}
                                            {item.discount_amount > 0 && (
                                                <span className="text-red-600 ml-2">
                                                    (-Rp {item.discount_amount.toLocaleString('id-ID')})
                                                </span>
                                            )}
                                        </p>
                                        <p className="text-xs text-gray-500">SKU: {item.product.sku}</p>
                                    </div>
                                    <div className="text-right">
                                        <p className="font-medium">
                                            Rp {item.total_price.toLocaleString('id-ID')}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Totals */}
                    <div className="p-6 space-y-2">
                        <div className="flex justify-between text-sm">
                            <span className="text-gray-600">Subtotal:</span>
                            <span>Rp {sale.subtotal.toLocaleString('id-ID')}</span>
                        </div>
                        {sale.discount_amount > 0 && (
                            <div className="flex justify-between text-sm text-red-600">
                                <span>Total Diskon:</span>
                                <span>-Rp {sale.discount_amount.toLocaleString('id-ID')}</span>
                            </div>
                        )}
                        <div className="flex justify-between text-sm">
                            <span className="text-gray-600">Pajak (10%):</span>
                            <span>Rp {sale.tax_amount.toLocaleString('id-ID')}</span>
                        </div>
                        <div className="border-t border-gray-200 pt-2">
                            <div className="flex justify-between text-lg font-bold">
                                <span>TOTAL:</span>
                                <span>Rp {sale.total_amount.toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                        <div className="flex justify-between text-sm pt-2">
                            <span className="text-gray-600">Bayar:</span>
                            <span>Rp {sale.paid_amount.toLocaleString('id-ID')}</span>
                        </div>
                        {sale.change_amount > 0 && (
                            <div className="flex justify-between text-sm font-medium text-green-600">
                                <span>Kembalian:</span>
                                <span>Rp {sale.change_amount.toLocaleString('id-ID')}</span>
                            </div>
                        )}
                    </div>

                    {/* Footer */}
                    <div className="p-6 border-t border-gray-200 text-center text-sm text-gray-600">
                        <p className="mb-2">Terima kasih atas kunjungan Anda! 🙏</p>
                        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
                        <p className="mt-2 text-xs">www.tokopos.com | @tokoposmodern</p>
                        
                        <div className="mt-4 pt-4 border-t border-gray-100">
                            <p className="text-xs text-gray-500">
                                Powered by POS System Modern
                            </p>
                        </div>
                    </div>
                </div>

                {/* Success Message */}
                <div className="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div className="flex items-center">
                        <div className="flex-shrink-0">
                            <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span className="text-green-600">✅</span>
                            </div>
                        </div>
                        <div className="ml-3">
                            <h3 className="text-sm font-medium text-green-800">
                                Transaksi Berhasil!
                            </h3>
                            <div className="mt-2 text-sm text-green-700">
                                <p>Penjualan telah berhasil diproses dan stok produk sudah diperbarui.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="mt-6 grid grid-cols-2 gap-4">
                    <Link
                        href="/pos"
                        className="inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        🛒 Transaksi Baru
                    </Link>
                    <Link
                        href="/pos/dashboard"
                        className="inline-flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
                    >
                        📊 Dashboard
                    </Link>
                </div>
            </div>

            {/* Print Styles */}
            <style>{`
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    #receipt, #receipt * {
                        visibility: visible;
                    }
                    #receipt {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                    }
                    .no-print {
                        display: none !important;
                    }
                }
            `}</style>
        </AppShell>
    );
}