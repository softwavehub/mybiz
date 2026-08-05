@extends('layouts.app')

@section('title', 'UrbanStyle Apparel | Official White-Label Storefront')

@section('content')
<!-- Top Announcement Bar -->
<div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-xs font-bold py-2 px-4 text-center tracking-wide shadow-md flex items-center justify-center gap-2">
    <span>✨ LIMITED TIME OFFER: Free Nationwide Express Delivery on Orders Over ₹499!</span>
</div>

<!-- Header Navigation -->
<header class="border-b border-slate-800 bg-slate-900/90 backdrop-blur sticky top-0 z-50 shadow-xl">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center font-black text-xl text-white shadow-lg shadow-indigo-500/30 ring-2 ring-indigo-400/30">
                U
            </div>
            <span class="font-extrabold text-xl tracking-tight text-white drop-shadow-sm">
                UrbanStyle Apparel
            </span>
        </div>

        <div class="flex items-center space-x-6">
            <span class="text-xs text-slate-300 font-medium flex items-center gap-1.5 bg-slate-800 px-3 py-1.5 rounded-full border border-slate-700">
                🛡️ Verified Brand
            </span>
            <div class="relative bg-slate-800 p-2.5 rounded-xl border border-slate-700 hover:border-indigo-500 cursor-pointer transition">
                🛒
            </div>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-950/80 via-slate-900 to-slate-950 border-b border-slate-800 py-16 px-4 text-center">
    <div class="relative max-w-4xl mx-auto space-y-5">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-500/20 border border-indigo-500/40 text-indigo-300 rounded-full text-xs font-bold tracking-wider uppercase shadow-inner">
            ⚡ Premium Direct Storefront
        </span>
        <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-tight">
            Curated Quality. <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">Delivered Fast.</span>
        </h1>
        <p class="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto font-normal">
            Shop exclusive streetwear, fashion, and lifestyle gear backed by guaranteed delivery and 100% white-label fulfillment.
        </p>
    </div>
</section>

<!-- Main Products Catalog Grid -->
<main class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8 border-b border-slate-800 pb-4">
        <h2 class="text-2xl font-black text-white tracking-tight">
            Trending Products
        </h2>
        <span class="text-xs text-slate-400 font-medium">3 Items Available</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-8">
        <!-- Product 1 -->
        <div class="group bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden hover:border-indigo-500/60 transition-all duration-300 flex flex-col justify-between hover:shadow-2xl hover:shadow-indigo-500/20">
            <div>
                <div class="aspect-square bg-slate-950 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=500" alt="Heavyweight Unisex Hoodie" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3 right-3 bg-slate-950/90 backdrop-blur text-emerald-400 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/40 shadow-md">
                        In Stock
                    </span>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-white group-hover:text-indigo-400 transition text-lg">
                        Heavyweight Unisex Hoodie
                    </h3>
                    <div class="flex items-center gap-1 text-amber-400 text-xs mt-1.5">
                        ★★★★★ <span class="text-slate-300 font-medium ml-1">4.9 (148 reviews)</span>
                    </div>
                    <p class="text-slate-400 text-xs mt-2 leading-relaxed">
                        380 GSM fleece lined hoodie built for maximum comfort and longevity.
                    </p>
                </div>
            </div>
            <div class="p-5 pt-0 flex items-center justify-between border-t border-slate-800/80 mt-4">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Retail Price</span>
                    <span class="text-xl font-black text-white">₹749.00</span>
                </div>
                <button class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl transition shadow-lg shadow-indigo-600/30 active:scale-95">
                    Buy Now
                </button>
            </div>
        </div>

        <!-- Product 2 -->
        <div class="group bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden hover:border-indigo-500/60 transition-all duration-300 flex flex-col justify-between hover:shadow-2xl hover:shadow-indigo-500/20">
            <div>
                <div class="aspect-square bg-slate-950 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500" alt="Cotton Oversized Graphic Tee" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3 right-3 bg-slate-950/90 backdrop-blur text-emerald-400 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/40 shadow-md">
                        In Stock
                    </span>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-white group-hover:text-indigo-400 transition text-lg">
                        Cotton Oversized Graphic Tee
                    </h3>
                    <div class="flex items-center gap-1 text-amber-400 text-xs mt-1.5">
                        ★★★★★ <span class="text-slate-300 font-medium ml-1">4.8 (92 reviews)</span>
                    </div>
                    <p class="text-slate-400 text-xs mt-2 leading-relaxed">
                        100% combed cotton, 220 GSM streetwear drop-shoulder t-shirt.
                    </p>
                </div>
            </div>
            <div class="p-5 pt-0 flex items-center justify-between border-t border-slate-800/80 mt-4">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Retail Price</span>
                    <span class="text-xl font-black text-white">₹399.00</span>
                </div>
                <button class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl transition shadow-lg shadow-indigo-600/30 active:scale-95">
                    Buy Now
                </button>
            </div>
        </div>

        <!-- Product 3 -->
        <div class="group bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden hover:border-indigo-500/60 transition-all duration-300 flex flex-col justify-between hover:shadow-2xl hover:shadow-indigo-500/20">
            <div>
                <div class="aspect-square bg-slate-950 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500" alt="Waterproof Canvas Backpack" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3 right-3 bg-slate-950/90 backdrop-blur text-emerald-400 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/40 shadow-md">
                        In Stock
                    </span>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-white group-hover:text-indigo-400 transition text-lg">
                        Waterproof Canvas Backpack
                    </h3>
                    <div class="flex items-center gap-1 text-amber-400 text-xs mt-1.5">
                        ★★★★★ <span class="text-slate-300 font-medium ml-1">4.9 (210 reviews)</span>
                    </div>
                    <p class="text-slate-400 text-xs mt-2 leading-relaxed">
                        25L capacity with padded 16-inch laptop compartment and secret anti-theft pocket.
                    </p>
                </div>
            </div>
            <div class="p-5 pt-0 flex items-center justify-between border-t border-slate-800/80 mt-4">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Retail Price</span>
                    <span class="text-xl font-black text-white">₹1,099.00</span>
                </div>
                <button class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl transition shadow-lg shadow-indigo-600/30 active:scale-95">
                    Buy Now
                </button>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="border-t border-slate-800 bg-slate-900 py-10 px-4 text-center text-xs text-slate-400">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <p>© {{ date('Y') }} <strong class="text-white">UrbanStyle Apparel</strong>. All rights reserved.</p>
        <div class="flex items-center gap-6 text-slate-300">
            <span>🚚 Fast Express Shipping</span>
            <span>🔒 256-Bit Encrypted Payment</span>
        </div>
    </div>
</footer>
@endsection
