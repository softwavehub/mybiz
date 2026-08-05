import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import { Package, Truck, ShieldCheck, Printer, PlusCircle, CheckCircle } from 'lucide-react';

export default function SellerDashboard({ seller, orders }) {
  const [shippingZones, setShippingZones] = useState({ zoneA: 30, zoneB: 50, zoneC: 80 });

  return (
    <div className="min-h-screen bg-slate-950 text-slate-100 font-sans p-6">
      <Head title="Seller Operations Desk | mybiz" />

      {/* Seller Header */}
      <header className="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-slate-800 mb-8 gap-4">
        <div>
          <span className="text-xs font-bold uppercase tracking-widest text-emerald-400">Supplier & Manufacturing Hub</span>
          <h1 className="text-3xl font-black text-white flex items-center gap-3">
            {seller?.company_name || 'Surat Textile Mills Ltd'}
            <span className="text-xs font-bold px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full">
              KYC Approved
            </span>
          </h1>
        </div>

        <button className="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2">
          <PlusCircle className="w-4 h-4" />
          Add New Product SKU
        </button>
      </header>

      {/* 3-Zone Flat Shipping Rates Configurator */}
      <div className="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 mb-8">
        <h2 className="text-lg font-bold text-white mb-2 flex items-center gap-2">
          <Truck className="w-5 h-5 text-emerald-400" />
          Pincode Distance Flat-Rate Shipping Matrix
        </h2>
        <p className="text-xs text-slate-400 mb-6">
          Configure default flat shipping fees automatically added to your base price for price floor calculations.
        </p>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
            <span className="text-xs text-slate-400 font-semibold block mb-2">Zone A: Intra-District</span>
            <div className="flex items-center gap-2">
              <span className="text-slate-500 font-bold text-sm">₹</span>
              <input
                type="number"
                value={shippingZones.zoneA}
                onChange={(e) => setShippingZones({ ...shippingZones, zoneA: Number(e.target.value) })}
                className="bg-slate-900 border border-slate-700 text-white font-bold text-base px-3 py-1.5 rounded-lg w-full outline-none focus:border-emerald-500"
              />
            </div>
          </div>

          <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
            <span className="text-xs text-slate-400 font-semibold block mb-2">Zone B: Intra-State</span>
            <div className="flex items-center gap-2">
              <span className="text-slate-500 font-bold text-sm">₹</span>
              <input
                type="number"
                value={shippingZones.zoneB}
                onChange={(e) => setShippingZones({ ...shippingZones, zoneB: Number(e.target.value) })}
                className="bg-slate-900 border border-slate-700 text-white font-bold text-base px-3 py-1.5 rounded-lg w-full outline-none focus:border-emerald-500"
              />
            </div>
          </div>

          <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
            <span className="text-xs text-slate-400 font-semibold block mb-2">Zone C: Rest of India</span>
            <div className="flex items-center gap-2">
              <span className="text-slate-500 font-bold text-sm">₹</span>
              <input
                type="number"
                value={shippingZones.zoneC}
                onChange={(e) => setShippingZones({ ...shippingZones, zoneC: Number(e.target.value) })}
                className="bg-slate-900 border border-slate-700 text-white font-bold text-base px-3 py-1.5 rounded-lg w-full outline-none focus:border-emerald-500"
              />
            </div>
          </div>
        </div>
      </div>

      {/* Blind Fulfillment Orders Queue */}
      <h2 className="text-xl font-bold text-white mb-6 flex items-center gap-2">
        <Package className="w-5 h-5 text-indigo-400" />
        Blind Fulfillment Order Queue (100% Masked Supplier Info)
      </h2>

      <div className="space-y-4">
        {(orders || [
          { id: 'ORD-1092', merchantBrand: 'UrbanStyle Store', item: 'Heavyweight Unisex Hoodie (Size M, Black)', qty: 2, destinationPincode: '400001', status: 'Packed' },
          { id: 'ORD-1093', merchantBrand: 'StudentMerch Brand', item: 'Cotton Oversized Graphic Tee (Size L)', qty: 1, destinationPincode: '560001', status: 'Placed' },
        ]).map((o) => (
          <div key={o.id} className="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <div className="flex items-center gap-3 mb-1">
                <span className="font-bold text-white text-base">{o.id}</span>
                <span className="text-xs font-semibold px-2.5 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/30 rounded-full">
                  Sender: {o.merchantBrand} (Merchant Brand)
                </span>
              </div>
              <p className="text-sm text-slate-300 font-medium">{o.item} x {o.qty}</p>
              <span className="text-xs text-slate-500">Destination Pincode: {o.destinationPincode}</span>
            </div>

            <div className="flex items-center space-x-3">
              <button className="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 border border-slate-700">
                <Printer className="w-4 h-4 text-emerald-400" />
                Print Blind AWB Shipping Label
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
