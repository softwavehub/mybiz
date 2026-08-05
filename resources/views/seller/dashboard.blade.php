@extends('layouts.app')

@section('title', 'Seller Operations Desk | mybiz')

@section('content')
<div class="p-6 max-w-7xl mx-auto" x-data="{ zoneA: 30, zoneB: 50, zoneC: 80 }">
    <!-- Seller Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-slate-800 mb-8 gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Supplier & Manufacturing Hub</span>
            <h1 class="text-3xl font-black text-white flex items-center gap-3">
                Surat Textile Mills Ltd
                <span class="text-xs font-bold px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full">
                    KYC Approved
                </span>
            </h1>
        </div>

        <button class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2">
            ➕ Add New Product SKU
        </button>
    </header>

    <!-- 3-Zone Flat Shipping Rates Configurator -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 mb-8">
        <h2 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
            🚚 Pincode Distance Flat-Rate Shipping Matrix
        </h2>
        <p class="text-xs text-slate-400 mb-6">
            Configure default flat shipping fees automatically added to your base price for price floor calculations.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                <span class="text-xs text-slate-400 font-semibold block mb-2">Zone A: Intra-District</span>
                <div class="flex items-center gap-2">
                    <span class="text-slate-500 font-bold text-sm">₹</span>
                    <input
                        type="number"
                        x-model="zoneA"
                        class="bg-slate-900 border border-slate-700 text-white font-bold text-base px-3 py-1.5 rounded-lg w-full outline-none focus:border-emerald-500"
                    />
                </div>
            </div>

            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                <span class="text-xs text-slate-400 font-semibold block mb-2">Zone B: Intra-State</span>
                <div class="flex items-center gap-2">
                    <span class="text-slate-500 font-bold text-sm">₹</span>
                    <input
                        type="number"
                        x-model="zoneB"
                        class="bg-slate-900 border border-slate-700 text-white font-bold text-base px-3 py-1.5 rounded-lg w-full outline-none focus:border-emerald-500"
                    />
                </div>
            </div>

            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                <span class="text-xs text-slate-400 font-semibold block mb-2">Zone C: Rest of India</span>
                <div class="flex items-center gap-2">
                    <span class="text-slate-500 font-bold text-sm">₹</span>
                    <input
                        type="number"
                        x-model="zoneC"
                        class="bg-slate-900 border border-slate-700 text-white font-bold text-base px-3 py-1.5 rounded-lg w-full outline-none focus:border-emerald-500"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Blind Fulfillment Orders Queue -->
    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
        📦 Blind Fulfillment Order Queue (100% Masked Supplier Info)
    </h2>

    <div class="space-y-4">
        <!-- Order 1 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="font-bold text-white text-base">ORD-1092</span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/30 rounded-full">
                        Sender: UrbanStyle Store (Merchant Brand)
                    </span>
                </div>
                <p class="text-sm text-slate-300 font-medium">Heavyweight Unisex Hoodie (Size M, Black) x 2</p>
                <span class="text-xs text-slate-500">Destination Pincode: 400001</span>
            </div>

            <div class="flex items-center space-x-3">
                <button class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 border border-slate-700">
                    🖨️ Print Blind AWB Shipping Label
                </button>
            </div>
        </div>

        <!-- Order 2 -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="font-bold text-white text-base">ORD-1093</span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/30 rounded-full">
                        Sender: StudentMerch Brand (Merchant Brand)
                    </span>
                </div>
                <p class="text-sm text-slate-300 font-medium">Cotton Oversized Graphic Tee (Size L) x 1</p>
                <span class="text-xs text-slate-500">Destination Pincode: 560001</span>
            </div>

            <div class="flex items-center space-x-3">
                <button class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 border border-slate-700">
                    🖨️ Print Blind AWB Shipping Label
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
