@extends('layouts.app')

@section('title', 'Track Order #' . $order->order_number . ' | mybiz')

@section('content')
<div class="p-6 max-w-4xl mx-auto my-8">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <!-- Header & Status Badge -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-800 mb-8 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Order Tracking & Receipt</span>
                <h1 class="text-3xl font-black text-white flex items-center gap-3">
                    {{ $order->order_number }}
                    <span class="text-xs font-extrabold px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 rounded-full">
                        {{ strtoupper($order->order_status) }}
                    </span>
                </h1>
            </div>
            <span class="text-xs text-slate-400 font-mono bg-slate-950 px-3.5 py-2 rounded-xl border border-slate-800">
                Escrow Status: <strong class="text-amber-400">{{ strtoupper($order->escrow_status) }}</strong>
            </span>
        </div>

        @if (session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-2xl mb-6 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Order Lifecycle Stepper Bar (§3.1 State Machine) -->
        <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 mb-8">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6">Realtime Order Progress</h3>
            <div class="grid grid-cols-4 gap-2 text-center text-xs">
                <!-- Placed -->
                <div class="space-y-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold mx-auto ring-4 ring-indigo-600/20">✓</div>
                    <span class="font-bold text-white block">Placed</span>
                    <span class="text-[10px] text-slate-500">{{ $order->created_at->format('M d, H:i') }}</span>
                </div>

                <!-- Packed -->
                <div class="space-y-2">
                    <div class="w-8 h-8 rounded-full {{ in_array($order->order_status, ['packed', 'shipped', 'delivered']) ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-500' }} flex items-center justify-center font-bold mx-auto">2</div>
                    <span class="font-semibold text-slate-300 block">Packed</span>
                    <span class="text-[10px] text-slate-500">Supplier Packing</span>
                </div>

                <!-- Shipped -->
                <div class="space-y-2">
                    <div class="w-8 h-8 rounded-full {{ in_array($order->order_status, ['shipped', 'delivered']) ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-500' }} flex items-center justify-center font-bold mx-auto">3</div>
                    <span class="font-semibold text-slate-300 block">Shipped</span>
                    <span class="text-[10px] text-slate-500">Blind AWB</span>
                </div>

                <!-- Delivered -->
                <div class="space-y-2">
                    <div class="w-8 h-8 rounded-full {{ $order->order_status === 'delivered' ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-slate-500' }} flex items-center justify-center font-bold mx-auto">4</div>
                    <span class="font-semibold text-slate-300 block">Delivered</span>
                    <span class="text-[10px] text-slate-500">15-Day Escrow Release</span>
                </div>
            </div>
        </div>

        <!-- Order Items & Customer Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Line Items -->
            <div>
                <h3 class="font-bold text-white text-base mb-4">Purchased Items</h3>
                <div class="space-y-3">
                    @foreach ($order->lineItems as $item)
                        <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-white block text-sm">{{ $item->product_name }}</span>
                                <span class="text-slate-400">SKU: {{ $item->sku }} | Qty: {{ $item->quantity }}</span>
                            </div>
                            <span class="font-extrabold text-white text-base">₹{{ number_format($item->total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Customer & Shipping Address -->
            <div>
                <h3 class="font-bold text-white text-base mb-4">Delivery & Merchant Brand Details</h3>
                <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 space-y-2 text-xs text-slate-300">
                    <p>Customer: <strong class="text-white">{{ $order->customer?->name }}</strong></p>
                    <p>Phone: <span class="text-white">{{ $order->customer?->phone_number }}</span></p>
                    <p>Address: <span class="text-white">{{ $order->shipping_address }} ({{ $order->pincode }})</span></p>
                    <div class="pt-2 border-t border-slate-800 text-indigo-400 font-semibold">
                        Sold by: {{ $order->merchant?->store_name }} (White-Label Merchant)
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center pt-4 border-t border-slate-800">
            <a href="{{ route('storefront.index') }}" class="text-xs font-bold text-indigo-400 hover:underline">
                ← Return to Storefront
            </a>
        </div>
    </div>
</div>
@endsection
