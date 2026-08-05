@extends('layouts.app')

@section('title', 'Super Admin Engine | mybiz')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Admin Top Header -->
    <header class="flex items-center justify-between pb-6 border-b border-slate-800 mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Platform Command Desk</span>
            <h1 class="text-3xl font-black text-white">Super Admin Engine</h1>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Ledger Balanced (Paisas-Perfect)
            </span>
        </div>
    </header>

    <!-- Realtime KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-2">
                <span>Gross Merchandise Value</span>
                <span>💰</span>
            </div>
            <p class="text-3xl font-black text-white">₹1,24,500</p>
            <span class="text-xs text-emerald-400 mt-2 block font-medium">+14.2% from last month</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-2">
                <span>Platform Commission (10%)</span>
                <span>📈</span>
            </div>
            <p class="text-3xl font-black text-white">₹12,450</p>
            <span class="text-xs text-slate-400 mt-2 block font-medium">Auto-aggregated</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-2">
                <span>Escrow Holding Tank</span>
                <span>📦</span>
            </div>
            <p class="text-3xl font-black text-white">₹45,200</p>
            <span class="text-xs text-amber-400 mt-2 block font-medium">15/7-Day Hold Enforced</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
            <div class="flex items-center justify-between text-slate-400 text-xs mb-2">
                <span>Active Disputes</span>
                <span>⚠️</span>
            </div>
            <p class="text-3xl font-black text-white">2</p>
            <span class="text-xs text-rose-400 mt-2 block font-medium">Wrong/Damaged Only</span>
        </div>
    </div>

    <!-- Seller Strike System & Escrow Ledger Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Seller Defect Strike Engine Monitor -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                🛡️ Seller Defect Strike Engine (&gt;3% Auto-Lock)
            </h2>

            <div class="space-y-4">
                <div class="bg-slate-950 border border-slate-800 p-4 rounded-xl flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-white text-sm">Surat Textiles Wholesaler</h4>
                        <span class="text-xs text-slate-400">Total Orders: 120</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/30">
                            Defect Rate: 4.2%
                        </span>
                        <span class="text-xs block text-slate-400 mt-1 font-medium">Suspended (Locked)</span>
                    </div>
                </div>

                <div class="bg-slate-950 border border-slate-800 p-4 rounded-xl flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-white text-sm">Ludhiana Knitwear Hub</h4>
                        <span class="text-xs text-slate-400">Total Orders: 450</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            Defect Rate: 0.8%
                        </span>
                        <span class="text-xs block text-slate-400 mt-1 font-medium">Excellent</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Escrow Payout Clearance Queue -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                📦 Escrow Payout Clearance Queue
            </h2>

            <div class="space-y-4">
                <div class="bg-slate-950 border border-slate-800 p-4 rounded-xl flex items-center justify-between text-sm">
                    <div>
                        <span class="font-bold text-indigo-400 block">ORD-9821</span>
                        <span class="text-xs text-slate-400">UrbanFit Store ← Surat Textiles</span>
                    </div>
                    <div class="text-right">
                        <span class="font-extrabold text-white block">₹450 Profit</span>
                        <span class="text-xs text-amber-400 font-medium">Releases in 4 days</span>
                    </div>
                </div>

                <div class="bg-slate-950 border border-slate-800 p-4 rounded-xl flex items-center justify-between text-sm">
                    <div>
                        <span class="font-bold text-indigo-400 block">ORD-9822</span>
                        <span class="text-xs text-slate-400">StudentMerch Co ← Agra Footwear</span>
                    </div>
                    <div class="text-right">
                        <span class="font-extrabold text-white block">₹320 Profit</span>
                        <span class="text-xs text-amber-400 font-medium">Releases in 1 day</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
