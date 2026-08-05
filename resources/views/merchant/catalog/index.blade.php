@extends('layouts.app')

@section('title', 'Supplier Catalog & 1-Click Import | Merchant Operations')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between pb-6 border-b border-slate-800 mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Zero-Inventory Catalog Engine</span>
            <h1 class="text-3xl font-black text-white">Verified Supplier Catalog</h1>
        </div>
        <a href="{{ route('merchant.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl">
            ← Back to Merchant Desk
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-2xl mb-6 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs p-4 rounded-2xl mb-6 font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Catalog Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($approvedProducts as $p)
            @php
                $floor = round(($p->base_price + $p->shipping_zone_a) / 0.88, 2);
                $isImported = in_array($p->id, $importedProductIds);
            @endphp

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between" x-data="{ markup: 30, floor: {{ $floor }} }">
                <div>
                    <div class="aspect-square bg-slate-950 rounded-xl overflow-hidden mb-4 relative border border-slate-800">
                        <img src="{{ $p->thumbnail_image }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                        <span class="absolute top-2.5 right-2.5 bg-slate-950/90 text-emerald-400 text-[10px] font-bold px-2.5 py-1 rounded-full border border-emerald-500/30">
                            Verified Supplier
                        </span>
                    </div>

                    <h3 class="font-bold text-white text-base leading-tight mb-1">{{ $p->name }}</h3>
                    <p class="text-xs text-slate-400 line-clamp-2 mb-3">{{ $p->description }}</p>

                    <!-- Price Floor Calculation Box (§4.1) -->
                    <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800 text-xs space-y-1.5 mb-4">
                        <div class="flex justify-between text-slate-400">
                            <span>Supplier Base Price:</span>
                            <span class="text-slate-200">₹{{ number_format($p->base_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Flat Shipping (Zone A):</span>
                            <span class="text-slate-200">₹{{ number_format($p->shipping_zone_a, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-indigo-400 border-t border-slate-800 pt-1.5">
                            <span>Absolute Price Floor:</span>
                            <span>₹{{ number_format($floor, 2) }}</span>
                        </div>
                    </div>

                    <!-- Dynamic Profit & Retail Calculator -->
                    <div class="bg-indigo-950/40 border border-indigo-500/30 p-3.5 rounded-xl text-xs space-y-2 mb-4">
                        <div class="flex items-center justify-between">
                            <label class="font-bold text-slate-300">Set Markup (%):</label>
                            <input type="number" x-model="markup" min="0" class="w-16 bg-slate-900 border border-slate-700 text-white font-bold text-xs p-1 rounded text-center outline-none">
                        </div>
                        <div class="flex justify-between pt-1 border-t border-indigo-500/20">
                            <span class="text-slate-300">Your Selling Price:</span>
                            <span class="font-extrabold text-white text-sm" x-text="'₹' + Math.round(floor * (1 + markup / 100))"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-300">Your Net Profit/Sale:</span>
                            <span class="font-extrabold text-emerald-400 text-sm" x-text="'+₹' + Math.round(floor * (1 + markup / 100) - floor)"></span>
                        </div>
                    </div>
                </div>

                <!-- 1-Click Import Form -->
                <form method="POST" action="{{ route('merchant.catalog.import', $p->id) }}">
                    @csrf
                    <input type="hidden" name="markup_percent" :value="markup">

                    @if ($isImported)
                        <button type="button" disabled class="w-full bg-slate-800 text-slate-400 text-xs font-bold py-3 rounded-xl border border-slate-700 cursor-not-allowed">
                            ✓ Imported to Your Store
                        </button>
                    @else
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-extrabold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30">
                            ➕ 1-Click Import to My Store
                        </button>
                    @endif
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
