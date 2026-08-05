@extends('layouts.app')

@section('title', 'Super Admin Control Engine | mybiz')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">
    <!-- Admin Top Navigation & Command Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-slate-800 gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Platform Command & Risk Control Engine</span>
            <h1 class="text-3xl font-black text-white flex items-center gap-3">
                Super Admin Engine
                <span class="text-xs font-bold px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 rounded-full">
                    v2.0 Master Desk
                </span>
            </h1>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.approvals.index') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-lg shadow-indigo-600/30">
                ⚡ SKU & KYC Approvals Desk
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 text-xs font-bold px-4 py-2.5 rounded-xl transition">
                    Sign Out
                </button>
            </form>
        </div>
    </header>

    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-2xl font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs p-4 rounded-2xl font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <!-- 1. Realtime Aggregated Financial KPI Grid (§5.1.1) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-slate-400 text-xs block mb-1">Gross Merchandise Value</span>
            <p class="text-2xl font-black text-white">₹{{ number_format($gmv, 2) }}</p>
            <span class="text-[10px] text-emerald-400 font-semibold block mt-1">+14.2% GMV Volume</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-slate-400 text-xs block mb-1">Platform Commission (10%)</span>
            <p class="text-2xl font-black text-indigo-400">₹{{ number_format($platformCommission, 2) }}</p>
            <span class="text-[10px] text-slate-400 font-medium block mt-1">Platform Revenue</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-slate-400 text-xs block mb-1">Payment Gateway Fees (2%)</span>
            <p class="text-2xl font-black text-slate-300">₹{{ number_format($pgFees, 2) }}</p>
            <span class="text-[10px] text-slate-400 font-medium block mt-1">Pass-Through PG Fee</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-slate-400 text-xs block mb-1">Escrow Tank Reserve</span>
            <p class="text-2xl font-black text-amber-400">₹{{ number_format($escrowBalance, 2) }}</p>
            <span class="text-[10px] text-amber-400 font-semibold block mt-1">15/7-Day Hold Active</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-slate-400 text-xs block mb-1">Active Open Disputes</span>
            <p class="text-2xl font-black text-rose-400">{{ $activeDisputesCount }}</p>
            <span class="text-[10px] text-rose-400 font-semibold block mt-1">Wrong/Damaged Only</span>
        </div>
    </div>

    <!-- 2. Seller Strike Engine Monitor & Account Lock Control (§3.4 & §5.1.2) -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    🛡️ Seller Defect Strike Engine Monitor (&gt;3% Auto-Lock Enforced)
                </h2>
                <p class="text-xs text-slate-400">Sellers exceeding 3.00% defect rate are automatically locked to protect platform integrity (§3.4).</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 pb-3 uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Supplier Factory</th>
                        <th class="py-3 px-4">Total Fulfillment</th>
                        <th class="py-3 px-4">Defect Rate %</th>
                        <th class="py-3 px-4">KYC / Account Status</th>
                        <th class="py-3 px-4">Escrow Hold Tier</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach ($sellers as $s)
                        <tr class="hover:bg-slate-950/40 transition">
                            <td class="py-3.5 px-4 font-bold text-white">
                                {{ $s->company_name }}
                                <span class="text-[10px] text-slate-500 block font-normal">{{ $s->user?->email }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-300">{{ $s->total_orders_count }} Orders</td>
                            <td class="py-3.5 px-4">
                                <span class="font-extrabold px-2.5 py-1 rounded-full border {{ $s->defect_rate > 3.00 ? 'bg-rose-500/10 text-rose-400 border-rose-500/30' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' }}">
                                    {{ $s->defect_rate }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold uppercase text-[10px] px-2.5 py-1 rounded-full border {{ $s->kyc_status === 'approved' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border-rose-500/30' }}">
                                    {{ strtoupper($s->kyc_status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <form method="POST" action="{{ route('admin.sellers.escrow-tier', $s->id) }}" class="flex items-center space-x-2">
                                    @csrf
                                    <select name="escrow_tier" onchange="this.form.submit()" class="bg-slate-950 border border-slate-800 text-white font-bold text-[11px] px-2.5 py-1 rounded-lg outline-none">
                                        <option value="15_days" {{ $s->escrow_tier === '15_days' ? 'selected' : '' }}>15-Day Hold (Default)</option>
                                        <option value="7_days" {{ $s->escrow_tier === '7_days' ? 'selected' : '' }}>7-Day Fast Release (Accelerator)</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form method="POST" action="{{ route('admin.sellers.toggle-status', $s->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="font-bold text-[11px] px-3 py-1.5 rounded-lg border transition {{ $s->kyc_status === 'suspended' ? 'bg-emerald-600 hover:bg-emerald-500 text-white border-emerald-500' : 'bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border-rose-500/30' }}">
                                        {{ $s->kyc_status === 'suspended' ? 'Unlock Account' : 'Lock Account' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Escrow Clearance Ledger Queue & Manual Fund Release (§3.5) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                📦 Escrow Holding Tank Clearance Queue
            </h2>

            @if ($escrowOrders->isEmpty())
                <p class="text-xs text-slate-500 py-6 text-center">No orders currently holding in escrow reserve.</p>
            @else
                <div class="space-y-3">
                    @foreach ($escrowOrders as $o)
                        <div class="bg-slate-950 border border-slate-800 p-4 rounded-2xl flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-indigo-400 block text-sm">{{ $o->order_number }}</span>
                                <span class="text-slate-400">{{ $o->merchant?->store_name }} ← {{ $o->seller?->company_name }}</span>
                            </div>
                            <div class="text-right flex items-center space-x-3">
                                <div>
                                    <span class="font-extrabold text-white block">₹{{ number_format($o->lineItems->sum('base_price'), 2) }}</span>
                                    <span class="text-[10px] text-amber-400 font-semibold">Holding in Reserve</span>
                                </div>
                                <form method="POST" action="{{ route('admin.orders.release-escrow', $o->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] px-3 py-2 rounded-xl transition">
                                        Release Funds
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 4. Taxonomy Category Management (§4.3 Check #2) -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                🏷️ Master Category Taxonomy Manager
            </h2>
            <p class="text-xs text-slate-400 mb-4">Disabling a category immediately blocks new merchant catalog imports (§4.3 Check #2).</p>

            <div class="space-y-3">
                @foreach ($categories as $c)
                    <div class="bg-slate-950 border border-slate-800 p-3.5 rounded-2xl flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-white text-sm block">{{ $c->name }}</span>
                            <span class="text-slate-500 font-mono">slug: {{ $c->slug }}</span>
                        </div>
                        <form method="POST" action="{{ route('admin.categories.toggle', $c->id) }}">
                            @csrf
                            <button type="submit" class="font-bold text-[11px] px-3 py-1.5 rounded-xl border transition {{ $c->is_disabled ? 'bg-rose-500/10 text-rose-400 border-rose-500/30' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' }}">
                                {{ $c->is_disabled ? 'Disabled (Blocked)' : 'Active (Enabled)' }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 5. Platform Feature Flags & Kill Switches (§0.7) -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <h2 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
            🚨 Platform Feature Flags & System Kill Switches (§0.7)
        </h2>
        <p class="text-xs text-slate-400 mb-6">Instantly toggle core platform capabilities off or on in real-time emergency situations.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach ($featureFlags as $flag)
                <div class="bg-slate-950 border border-slate-800 p-4 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="font-mono text-xs font-bold text-indigo-400 block">{{ $flag->feature_key }}</span>
                        <span class="text-[10px] text-slate-400">Scope: {{ strtoupper($flag->scope) }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.feature-flags.toggle', $flag->id) }}">
                        @csrf
                        <button type="submit" class="font-extrabold text-xs px-3.5 py-2 rounded-xl transition border {{ $flag->enabled ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-rose-600 text-white border-rose-500' }}">
                            {{ $flag->enabled ? 'ON' : 'KILL SWITCH ACTIVE' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 6. Immutable Double-Entry Ledger Transaction Logs (§0.3) -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            📑 Immutable Double-Entry Audit Trail Logs (§0.3)
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase tracking-wider text-[10px]">
                        <th class="py-2.5 px-4">Timestamp</th>
                        <th class="py-2.5 px-4">Account Type</th>
                        <th class="py-2.5 px-4">Entry Type</th>
                        <th class="py-2.5 px-4 text-right">Amount (₹)</th>
                        <th class="py-2.5 px-4">Reference</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse ($auditLogs as $log)
                        <tr>
                            <td class="py-2.5 px-4 text-slate-400">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="py-2.5 px-4 font-bold text-indigo-400">{{ strtoupper($log->account_type) }}</td>
                            <td class="py-2.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $log->entry_type === 'debit' ? 'bg-rose-500/10 text-rose-400' : 'bg-emerald-500/10 text-emerald-400' }}">
                                    {{ strtoupper($log->entry_type) }}
                                </span>
                            </td>
                            <td class="py-2.5 px-4 text-right font-bold text-white">₹{{ number_format($log->amount, 2) }}</td>
                            <td class="py-2.5 px-4 text-slate-400">{{ $log->reference_type }} #{{ $log->reference_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-500">No ledger entries recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
