@extends('layouts.app')

@section('title', 'Product SKU & KYC Approvals | Super Admin Engine')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between pb-6 border-b border-slate-800 mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Quality Control & Verification</span>
            <h1 class="text-3xl font-black text-white">Pending Approvals Desk</h1>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl">
            ← Back to Admin Engine
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-2xl mb-6 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Pending Product SKUs Queue (§3.2) -->
    <div class="mb-12">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
            📦 Pending Product SKUs for Approval ({{ $pendingProducts->count() }})
        </h2>

        @if ($pendingProducts->isEmpty())
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center text-slate-400 text-xs">
                No pending product SKU submissions awaiting approval right now.
            </div>
        @else
            <div class="space-y-4">
                @foreach ($pendingProducts as $p)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex items-start space-x-4">
                            <img src="{{ $p->thumbnail_image }}" alt="{{ $p->name }}" class="w-20 h-20 rounded-2xl object-cover bg-slate-950 border border-slate-800">
                            <div>
                                <span class="text-[10px] font-bold uppercase text-indigo-400 bg-indigo-500/10 border border-indigo-500/30 px-2.5 py-0.5 rounded-full">
                                    {{ $p->category?->name ?? 'General' }}
                                </span>
                                <h3 class="font-bold text-white text-lg mt-1">{{ $p->name }}</h3>
                                <p class="text-xs text-slate-400">Supplier: <strong class="text-white">{{ $p->seller?->company_name }}</strong> | HSN: <span class="font-mono text-slate-300">{{ $p->hsn_code }}</span> | GST: {{ $p->gst_rate }}%</p>
                                <div class="mt-2 text-xs font-semibold text-emerald-400">
                                    Base Price: ₹{{ number_format($p->base_price, 2) }} | Min Price Floor: ₹{{ number_format(($p->base_price + $p->shipping_zone_a) / 0.88, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3" x-data="{ showReject: false }">
                            <!-- Approve Button -->
                            <form method="POST" action="{{ route('admin.products.approve', $p->id) }}">
                                @csrf
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs px-5 py-2.5 rounded-xl shadow-lg shadow-emerald-600/30 transition">
                                    ✓ Approve & Release Live
                                </button>
                            </form>

                            <!-- Reject Button Toggle -->
                            <button type="button" @click="showReject = !showReject" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold text-xs px-4 py-2.5 rounded-xl transition">
                                ✗ Reject SKU
                            </button>

                            <!-- Standardized Rejection Reason Form (§4.5) -->
                            <div x-show="showReject" class="fixed inset-0 bg-slate-950/80 backdrop-blur z-50 flex items-center justify-center p-4">
                                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 max-w-md w-full">
                                    <h3 class="font-bold text-white text-base mb-2">Select Standardized Rejection Reason</h3>
                                    <form method="POST" action="{{ route('admin.products.reject', $p->id) }}">
                                        @csrf
                                        <select name="rejection_reason" required class="w-full bg-slate-950 border border-slate-800 text-white text-xs p-3 rounded-xl mb-4 outline-none">
                                            <option value="">Choose Reason...</option>
                                            <option value="Incorrect HSN Tax Code for Category">Incorrect HSN Tax Code for Category</option>
                                            <option value="Unclear/Low Quality Product Photos">Unclear/Low Quality Product Photos</option>
                                            <option value="Base Price Exceeds Market Threshold">Base Price Exceeds Market Threshold</option>
                                            <option value="Prohibited or Copyrighted Material">Prohibited or Copyrighted Material</option>
                                        </select>

                                        <div class="flex justify-end space-x-3">
                                            <button type="button" @click="showReject = false" class="text-xs text-slate-400 px-4 py-2">Cancel</button>
                                            <button type="submit" class="bg-rose-600 text-white font-bold text-xs px-4 py-2 rounded-xl">Confirm Rejection</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Pending KYC Verification Queue (§3.3) -->
    <div>
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
            🛡️ Pending KYC Verification Queue
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Seller KYCs -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <h3 class="font-bold text-white text-sm mb-4">Pending Supplier KYCs</h3>
                @if ($pendingKycSellers->isEmpty())
                    <p class="text-xs text-slate-500">No pending seller KYC reviews.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($pendingKycSellers as $s)
                            <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-white block">{{ $s->company_name }}</span>
                                    <span class="text-slate-400">{{ $s->user?->email }}</span>
                                </div>
                                <form method="POST" action="{{ route('admin.kyc.approve', ['type' => 'seller', 'id' => $s->id]) }}">
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg">
                                        Approve KYC
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Merchant KYCs -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <h3 class="font-bold text-white text-sm mb-4">Pending Merchant KYCs</h3>
                @if ($pendingKycMerchants->isEmpty())
                    <p class="text-xs text-slate-500">No pending merchant KYC reviews.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($pendingKycMerchants as $m)
                            <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-white block">{{ $m->store_name }}</span>
                                    <span class="text-slate-400">{{ $m->user?->email }}</span>
                                </div>
                                <form method="POST" action="{{ route('admin.kyc.approve', ['type' => 'merchant', 'id' => $m->id]) }}">
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg">
                                        Approve KYC
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
