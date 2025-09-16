import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="POS System - Toko Modern">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-900">
                {/* Header Navigation */}
                <header className="w-full border-b border-slate-200 bg-white/80 backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="flex h-16 items-center justify-between">
                            <div className="flex items-center">
                                <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white">
                                    <span className="text-sm font-bold">📊</span>
                                </div>
                                <h1 className="ml-3 text-xl font-bold text-slate-900 dark:text-white">
                                    POS System
                                </h1>
                            </div>
                            <nav className="flex items-center gap-4">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <div className="flex items-center gap-3">
                                        <Link
                                            href={route('login')}
                                            className="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                                        >
                                            Masuk
                                        </Link>
                                        <Link
                                            href={route('register')}
                                            className="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700"
                                        >
                                            Daftar
                                        </Link>
                                    </div>
                                )}
                            </nav>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <main className="flex-1">
                    <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
                        <div className="text-center">
                            <h1 className="text-4xl font-bold tracking-tight text-slate-900 sm:text-6xl dark:text-white">
                                🏪 <span className="text-blue-600">POS System</span><br />
                                Toko Modern
                            </h1>
                            <p className="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-400">
                                Sistem Point of Sales lengkap untuk mengelola toko Anda dengan mudah. 
                                Kelola produk, proses transaksi, dan pantau penjualan dalam satu platform terintegrasi.
                            </p>
                        </div>

                        {/* Feature Grid */}
                        <div className="mx-auto mt-16 max-w-6xl">
                            <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                                {/* Manajemen Produk */}
                                <div className="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                        <span className="text-2xl">📦</span>
                                    </div>
                                    <h3 className="mt-6 text-lg font-semibold text-slate-900 dark:text-white">
                                        Manajemen Produk
                                    </h3>
                                    <p className="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                        CRUD produk lengkap dengan SKU, kategori, stok, dan harga. 
                                        Import/export CSV dengan validasi otomatis.
                                    </p>
                                    <ul className="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Kelola produk dengan mudah
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Import/Export CSV
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Tracking stok real-time
                                        </li>
                                    </ul>
                                </div>

                                {/* Transaksi Penjualan */}
                                <div className="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                                        <span className="text-2xl">💳</span>
                                    </div>
                                    <h3 className="mt-6 text-lg font-semibold text-slate-900 dark:text-white">
                                        Transaksi Penjualan
                                    </h3>
                                    <p className="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                        Interface kasir yang intuitif dengan multiple payment methods 
                                        dan sistem diskon otomatis.
                                    </p>
                                    <ul className="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Kasir cepat & mudah
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Multiple payment methods
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Struk PDF otomatis
                                        </li>
                                    </ul>
                                </div>

                                {/* Laporan Analytics */}
                                <div className="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                                        <span className="text-2xl">📊</span>
                                    </div>
                                    <h3 className="mt-6 text-lg font-semibold text-slate-900 dark:text-white">
                                        Laporan & Analytics
                                    </h3>
                                    <p className="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                        Dashboard lengkap dengan grafik penjualan, produk terlaris, 
                                        dan analisis profit margin.
                                    </p>
                                    <ul className="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Dashboard real-time
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Grafik tren penjualan
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Analisis ROI & profit
                                        </li>
                                    </ul>
                                </div>

                                {/* Manajemen User */}
                                <div className="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900">
                                        <span className="text-2xl">👥</span>
                                    </div>
                                    <h3 className="mt-6 text-lg font-semibold text-slate-900 dark:text-white">
                                        Multi-User System
                                    </h3>
                                    <p className="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                        Sistem role-based access dengan 3 level: Owner, Admin, dan Kasir. 
                                        Kontrol akses yang ketat dan aman.
                                    </p>
                                    <ul className="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                        <li className="flex items-center">
                                            <span className="mr-2">🔑</span>
                                            Owner (Full Control)
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">🛠</span>
                                            Admin (Management)
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">💰</span>
                                            Kasir (Sales Only)
                                        </li>
                                    </ul>
                                </div>

                                {/* Customer Management */}
                                <div className="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-pink-100 dark:bg-pink-900">
                                        <span className="text-2xl">🤝</span>
                                    </div>
                                    <h3 className="mt-6 text-lg font-semibold text-slate-900 dark:text-white">
                                        Customer Management
                                    </h3>
                                    <p className="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                        Kelola database pelanggan dengan riwayat pembelian dan 
                                        statistik spending untuk program loyalty.
                                    </p>
                                    <ul className="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Database pelanggan
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Riwayat pembelian
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">✅</span>
                                            Customer insights
                                        </li>
                                    </ul>
                                </div>

                                {/* Modern Tech Stack */}
                                <div className="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900">
                                        <span className="text-2xl">⚡</span>
                                    </div>
                                    <h3 className="mt-6 text-lg font-semibold text-slate-900 dark:text-white">
                                        Modern Tech Stack
                                    </h3>
                                    <p className="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                        Dibangun dengan Laravel, React, dan PostgreSQL. 
                                        Interface modern dengan Tailwind CSS dan real-time updates.
                                    </p>
                                    <ul className="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                        <li className="flex items-center">
                                            <span className="mr-2">🚀</span>
                                            Laravel + React
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">💾</span>
                                            PostgreSQL Database
                                        </li>
                                        <li className="flex items-center">
                                            <span className="mr-2">🎨</span>
                                            Tailwind CSS
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {/* CTA Section */}
                        <div className="mt-20 text-center">
                            <h2 className="text-2xl font-bold text-slate-900 dark:text-white">
                                Siap memulai bisnis modern Anda? 🚀
                            </h2>
                            <p className="mt-4 text-lg text-slate-600 dark:text-slate-400">
                                Bergabunglah dengan ribuan toko yang sudah menggunakan sistem POS kami.
                            </p>
                            <div className="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="inline-flex items-center rounded-lg bg-blue-600 px-8 py-3 text-base font-medium text-white shadow-sm transition hover:bg-blue-700"
                                    >
                                        Ke Dashboard →
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href={route('register')}
                                            className="inline-flex items-center rounded-lg bg-blue-600 px-8 py-3 text-base font-medium text-white shadow-sm transition hover:bg-blue-700"
                                        >
                                            Mulai Gratis
                                        </Link>
                                        <Link
                                            href={route('login')}
                                            className="inline-flex items-center rounded-lg border border-slate-300 bg-white px-8 py-3 text-base font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                                        >
                                            Sudah Punya Akun?
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </main>

                {/* Footer */}
                <footer className="border-t border-slate-200 bg-white/50 backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/50">
                    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                        <div className="text-center text-sm text-slate-600 dark:text-slate-400">
                            <p>
                                © 2024 POS System. Dibangun dengan ❤️ menggunakan{" "}
                                <span className="font-medium text-blue-600">Laravel</span> &{" "}
                                <span className="font-medium text-blue-600">React</span>
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}