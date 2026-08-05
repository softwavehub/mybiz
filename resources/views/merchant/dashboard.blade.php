@extends('layouts.app')

@section('title', 'Merchant Operations Desk | mybiz')

@section('content')
<div class="p-6 max-w-7xl mx-auto" x-data="{ markupPercent: 30 }">
    <!-- Merchant Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-slate-800 mb-8 gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Merchant Operations Desk</span>
            <h1 class="text-3xl font-black text-white flex items-center gap-3">
                UrbanStyle Apparel
                <span class="text-xs font-bold px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 rounded-full">
                    urbanstyle.mybiz.dhaivam.in
                </span>
            </h1>
        </div>

        <button class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2">
            🌐 Connect Custom CNAME Domain
        </button>
    </header>

    <!-- Smart Price Floor & Markup Engine Controls -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8">
        <h2 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
            📈 Automated Smart Markup Engine
        </h2>
        <p class="text-xs text-slate-400 mb-6">
            Set your global profit markup rule. Prices are automatically validated against the immutable <strong>Absolute Price Floor</strong>.
        </p>

        <div class="flex items-center space-x-4 max-w-md">
            <label class="text-xs font-bold text-slate-300">Global Markup (%):</label>
            <input
                type="number"
                x-model="markupPercent"
                class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-white font-bold text-sm w-24 text-center focus:border-indigo-500 outline-none"
            />
            <button class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                Apply Markup Rule
            </button>
        </div>
    </div>

    <!-- Supplier Catalog (1-Click Import) -->
    <h2 class="text-xl font-bold text-white mb-6">Available Supplier Products (1-Click Store Import)</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Item 1 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between" x-data="{ base: 510, floor: 573.47 }">
            <div>
                <h3 class="font-bold text-white text-base mb-1">Heavyweight Unisex Hoodie</h3>
                <div class="text-xs space-y-1 text-slate-400 mt-3 bg-slate-950 p-3 rounded-xl border border-slate-800/80">
                    <div class="flex justify-between">
                        <span>Seller Base + Ship:</span>
                        <span class="font-medium text-slate-200">₹510.00</span>
                    </div>
                    <div class="flex justify-between font-bold text-indigo-400 border-t border-slate-800 pt-1">
                        <span>Absolute Price Floor:</span>
                        <span>₹573.47</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 border-t border-slate-800/80 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <span class="text-xs text-slate-500 block">Retail Price</span>
                        <span class="text-lg font-extrabold text-white" x-text="'₹' + Math.round(floor * (1 + markupPercent / 100))">₹746</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-500 block">Your Profit</span>
                        <span class="text-base font-extrabold text-emerald-400" x-text="'+₹' + Math.round(floor * (1 + markupPercent / 100) - floor)">+₹173</span>
                    </div>
                </div>

                <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/20">
                    ➕ 1-Click Import to My Store
                </button>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between" x-data="{ base: 260, floor: 292.86 }">
            <div>
                <h3 class="font-bold text-white text-base mb-1">Cotton Oversized Graphic Tee</h3>
                <div class="text-xs space-y-1 text-slate-400 mt-3 bg-slate-950 p-3 rounded-xl border border-slate-800/80">
                    <div class="flex justify-between">
                        <span>Seller Base + Ship:</span>
                        <span class="font-medium text-slate-200">₹260.00</span>
                    </div>
                    <div class="flex justify-between font-bold text-indigo-400 border-t border-slate-800 pt-1">
                        <span>Absolute Price Floor:</span>
                        <span>₹292.86</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 border-t border-slate-800/80 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <span class="text-xs text-slate-500 block">Retail Price</span>
                        <span class="text-lg font-extrabold text-white" x-text="'₹' + Math.round(floor * (1 + markupPercent / 100))">₹381</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-500 block">Your Profit</span>
                        <span class="text-base font-extrabold text-emerald-400" x-text="'+₹' + Math.round(floor * (1 + markupPercent / 100) - floor)">+₹88</span>
                    </div>
                </div>

                <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/20">
                    ➕ 1-Click Import to My Store
                </button>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between" x-data="{ base: 730, floor: 820.41 }">
            <div>
                <h3 class="font-bold text-white text-base mb-1">Waterproof Canvas Backpack</h3>
                <div class="text-xs space-y-1 text-slate-400 mt-3 bg-slate-950 p-3 rounded-xl border border-slate-800/80">
                    <div class="flex justify-between">
                        <span>Seller Base + Ship:</span>
                        <span class="font-medium text-slate-200">₹730.00</span>
                    </div>
                    <div class="flex justify-between font-bold text-indigo-400 border-t border-slate-800 pt-1">
                        <span>Absolute Price Floor:</span>
                        <span>₹820.41</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 border-t border-slate-800/80 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <span class="text-xs text-slate-500 block">Retail Price</span>
                        <span class="text-lg font-extrabold text-white" x-text="'₹' + Math.round(floor * (1 + markupPercent / 100))">₹1067</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-500 block">Your Profit</span>
                        <span class="text-base font-extrabold text-emerald-400" x-text="'+₹' + Math.round(floor * (1 + markupPercent / 100) - floor)">+₹247</span>
                    </div>
                </div>

                <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/20">
                    ➕ 1-Click Import to My Store
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
