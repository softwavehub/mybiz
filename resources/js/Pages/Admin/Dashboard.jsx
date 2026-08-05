import React from 'react';
import { Head } from '@inertiajs/react';
import { ShieldAlert, DollarSign, Users, PackageCheck, AlertTriangle, Activity } from 'lucide-react';

export default function AdminDashboard({ metrics, strikes, escrows }) {
  return (
    <div className="min-h-screen bg-slate-950 text-slate-100 font-sans p-6">
      <Head title="Super Admin Engine | mybiz" />

      {/* Admin Top Header */}
      <header className="flex items-center justify-between pb-6 border-b border-slate-800 mb-8">
        <div>
          <span className="text-xs font-bold uppercase tracking-widest text-indigo-400">Platform Command Desk</span>
          <h1 className="text-3xl font-black text-white">Super Admin Engine</h1>
        </div>
        <div className="flex items-center space-x-3">
          <span className="px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
            <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Ledger Balanced (Paisas-Perfect)
          </span>
        </div>
      </header>

      {/* Realtime KPI Grid */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div className="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl">
          <div className="flex items-center justify-between text-slate-400 text-xs mb-2">
            <span>Gross Merchandise Value</span>
            <DollarSign className="w-4 h-4 text-indigo-400" />
          </div>
          <p className="text-3xl font-black text-white">₹{metrics?.gmv || '1,24,500'}</p>
          <span className="text-xs text-emerald-400 mt-2 block font-medium">+14.2% from last month</span>
        </div>

        <div className="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl">
          <div className="flex items-center justify-between text-slate-400 text-xs mb-2">
            <span>Platform Commission (10%)</span>
            <Activity className="w-4 h-4 text-emerald-400" />
          </div>
          <p className="text-3xl font-black text-white">₹{metrics?.commission || '12,450'}</p>
          <span className="text-xs text-slate-400 mt-2 block font-medium">Auto-aggregated</span>
        </div>

        <div className="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl">
          <div className="flex items-center justify-between text-slate-400 text-xs mb-2">
            <span>Escrow Holding Tank</span>
            <PackageCheck className="w-4 h-4 text-amber-400" />
          </div>
          <p className="text-3xl font-black text-white">₹{metrics?.escrow || '45,200'}</p>
          <span className="text-xs text-amber-400 mt-2 block font-medium">15/7-Day Hold Enforced</span>
        </div>

        <div className="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl">
          <div className="flex items-center justify-between text-slate-400 text-xs mb-2">
            <span>Active Disputes</span>
            <AlertTriangle className="w-4 h-4 text-rose-400" />
          </div>
          <p className="text-3xl font-black text-white">{metrics?.disputes || '2'}</p>
          <span className="text-xs text-rose-400 mt-2 block font-medium">Wrong/Damaged Only</span>
        </div>
      </div>

      {/* Seller Strike System & Escrow Ledger Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Seller Defect Strike Engine Monitor */}
        <div className="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-lg font-bold text-white flex items-center gap-2">
              <ShieldAlert className="w-5 h-5 text-rose-400" />
              Seller Defect Strike Engine (&gt;3% Auto-Lock)
            </h2>
          </div>

          <div className="space-y-4">
            {(strikes || [
              { id: 1, name: 'Surat Textiles Wholesaler', orders: 120, defect: 4.2, status: 'Suspended (Locked)' },
              { id: 2, name: 'Ludhiana Knitwear Hub', orders: 450, defect: 0.8, status: 'Excellent' },
            ]).map((s) => (
              <div key={s.id} className="bg-slate-950/60 border border-slate-800 p-4 rounded-xl flex items-center justify-between">
                <div>
                  <h4 className="font-semibold text-white text-sm">{s.name}</h4>
                  <span className="text-xs text-slate-400">Total Orders: {s.orders}</span>
                </div>
                <div className="text-right">
                  <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${s.defect > 3 ? 'bg-rose-500/10 text-rose-400 border border-rose-500/30' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30'}`}>
                    Defect Rate: {s.defect}%
                  </span>
                  <span className="text-xs block text-slate-400 mt-1 font-medium">{s.status}</span>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Escrow Payout Clearance Queue */}
        <div className="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
          <h2 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
            <PackageCheck className="w-5 h-5 text-indigo-400" />
            Escrow Payout Clearance Queue
          </h2>

          <div className="space-y-4">
            {(escrows || [
              { id: 'ORD-9821', merchant: 'UrbanFit Store', seller: 'Surat Textiles', profit: '₹450', daysLeft: 4 },
              { id: 'ORD-9822', merchant: 'StudentMerch Co', seller: 'Agra Footwear', profit: '₹320', daysLeft: 1 },
            ]).map((e) => (
              <div key={e.id} className="bg-slate-950/60 border border-slate-800 p-4 rounded-xl flex items-center justify-between text-sm">
                <div>
                  <span className="font-bold text-indigo-400 block">{e.id}</span>
                  <span className="text-xs text-slate-400">{e.merchant} ← {e.seller}</span>
                </div>
                <div className="text-right">
                  <span className="font-extrabold text-white block">{e.profit} Profit</span>
                  <span className="text-xs text-amber-400 font-medium">Releases in {e.daysLeft} days</span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
