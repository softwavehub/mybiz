@extends('layouts.app')

@section('title', 'Seller Product Catalog | mybiz')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800 mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Supplier Inventory Management</span>
            <h1 class="text-3xl font-black text-white">Product SKUs & Stock Control</h1>
        </div>
        <a href="{{ route('seller.products.create') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-5 py-3 rounded-xl transition shadow-lg shadow-emerald-600/30 flex items-center gap-2">
            ➕ Add New Product SKU
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-2xl mb-6 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Product Grid -->
    @if ($products->isEmpty())
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-12 text-center">
            <div class="w-16 h-16 bg-slate-950 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 border border-slate-800">
                📦
            </div>
            <h3 class="text-lg font-bold text-white mb-1">No Product SKUs Added Yet</h3>
            <p class="text-slate-400 text-xs max-w-md mx-auto mb-6">Start supplying products to over 10,000+ dropshipping merchants on mybiz.</p>
            <a href="{{ route('seller.products.create') }}" class="inline-flex bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-6 py-3 rounded-xl transition">
                Create First Product SKU
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $p)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full border
                                @if($p->status === 'approved') bg-emerald-500/10 text-emerald-400 border-emerald-500/30
                                @elseif($p->status === 'pending_approval') bg-amber-500/10 text-amber-400 border-amber-500/30
                                @else bg-rose-500/10 text-rose-400 border-rose-500/30 @endif">
                                {{ str_replace('_', ' ', strtoupper($p->status)) }}
                            </span>
                            <span class="text-xs text-slate-500 font-mono">HSN: {{ $p->hsn_code }}</span>
                        </div>

                        <div class="flex gap-4">
                            <img src="{{ $p->thumbnail_image }}" alt="{{ $p->name }}" class="w-16 h-16 rounded-xl object-cover border border-slate-800 bg-slate-950">
                            <div>
                                <h3 class="font-bold text-white text-base leading-tight">{{ $p->name }}</h3>
                                <p class="text-xs text-slate-400 mt-1">Base Price: <strong class="text-white">₹{{ number_format($p->base_price, 2) }}</strong></p>
                                <p class="text-[11px] text-indigo-400 mt-0.5 font-semibold">Min Price Floor: ₹{{ number_format(($p->base_price + $p->shipping_zone_a + 0) / 0.88, 2) }}</p>
                            </div>
                        </div>

                        <!-- Variant Stock Quick Controls (§4.4 Non-Approval Stock Edit) -->
                        <div class="mt-4 pt-4 border-t border-slate-800/80">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Variant Stock (Instant Bypass Edit)</span>
                            <div class="space-y-2">
                                @foreach ($p->variants as $v)
                                    <form method="POST" action="{{ route('seller.variants.quantity', $v->id) }}" class="flex items-center justify-between bg-slate-950 p-2.5 rounded-xl border border-slate-800">
                                        @csrf
                                        @method('PATCH')
                                        <div class="text-xs">
                                            <span class="font-mono font-bold text-white">{{ $v->sku }}</span>
                                            <span class="text-slate-500 ml-1">({{ is_array($v->attributes) ? implode(', ', $v->attributes) : $v->attributes }})</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" name="quantity" value="{{ $v->quantity }}" min="0" class="w-16 bg-slate-900 border border-slate-700 text-white font-bold text-xs px-2 py-1 rounded outline-none text-center focus:border-emerald-500">
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded transition">Save</button>
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
