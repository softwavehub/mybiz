import React from 'react';
import { Head } from '@inertiajs/react';
import { ShoppingBag, Truck, ShieldCheck, Star } from 'lucide-react';

export default function StorefrontIndex({ merchant, products }) {
  return (
    <div className="min-h-screen bg-slate-900 text-slate-100 font-sans">
      <Head title={`${merchant.store_name} | Official Store`} />

      {/* Header Bar */}
      <header className="border-b border-slate-800 bg-slate-950/80 backdrop-blur sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
          <div className="flex items-center space-x-3">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center font-bold text-xl text-white shadow-lg shadow-indigo-500/20">
              {merchant.store_name.charAt(0)}
            </div>
            <span className="font-extrabold text-xl tracking-tight text-white">
              {merchant.store_name}
            </span>
          </div>

          <div className="flex items-center space-x-6">
            <span className="text-xs text-slate-400 flex items-center gap-1.5">
              <ShieldCheck className="w-4 h-4 text-emerald-400" />
              Verified Brand
            </span>
            <div className="relative">
              <ShoppingBag className="w-6 h-6 text-slate-300 hover:text-white cursor-pointer transition" />
            </div>
          </div>
        </div>
      </header>

      {/* Hero Announcement Banner */}
      <section className="bg-gradient-to-r from-indigo-900/40 via-violet-900/40 to-slate-900 border-b border-slate-800 py-12 px-4 text-center">
        <div className="max-w-3xl mx-auto space-y-4">
          <span className="px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 rounded-full text-xs font-semibold tracking-wide uppercase">
            Direct-to-Consumer Specials
          </span>
          <h1 className="text-4xl md:text-5xl font-black text-white tracking-tight">
            Curated Quality. Delivered Fast.
          </h1>
          <p className="text-slate-400 text-sm md:text-base">
            Shop premium products backed by guaranteed delivery and 100% white-label fulfillment.
          </p>
        </div>
      </section>

      {/* Product Catalog Grid */}
      <main className="max-w-7xl mx-auto px-4 py-12">
        <h2 className="text-2xl font-bold text-white mb-8 flex items-center gap-2">
          Featured Catalog
        </h2>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {products.map((p) => (
            <div key={p.id} className="group bg-slate-950/60 border border-slate-800 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all duration-300 flex flex-col justify-between hover:shadow-xl hover:shadow-indigo-500/10">
              <div>
                <div className="aspect-square bg-slate-900 relative overflow-hidden">
                  <img
                    src={p.thumbnail_image || 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500'}
                    alt={p.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                  />
                  <span className="absolute top-3 right-3 bg-slate-950/80 backdrop-blur text-emerald-400 text-xs font-bold px-2.5 py-1 rounded-full border border-emerald-500/30">
                    Free Delivery
                  </span>
                </div>
                <div className="p-5">
                  <h3 className="font-semibold text-white group-hover:text-indigo-400 transition text-base line-clamp-1">
                    {p.name}
                  </h3>
                  <div className="flex items-center gap-1 text-amber-400 text-xs mt-1">
                    <Star className="w-3.5 h-3.5 fill-amber-400" />
                    <span className="font-medium text-slate-300">4.8 (120 reviews)</span>
                  </div>
                  <p className="text-slate-400 text-xs line-clamp-2 mt-2 leading-relaxed">
                    {p.description}
                  </p>
                </div>
              </div>

              <div className="p-5 pt-0 flex items-center justify-between border-t border-slate-900/50 mt-4">
                <div>
                  <span className="text-xs text-slate-500 block font-medium">Selling Price</span>
                  <span className="text-xl font-extrabold text-white">₹{p.retail_price}</span>
                </div>
                <button className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-lg shadow-indigo-600/20 active:scale-95">
                  Buy Now
                </button>
              </div>
            </div>
          ))}
        </div>
      </main>

      {/* Trust Footer */}
      <footer className="border-t border-slate-800 bg-slate-950 py-8 px-4 text-center text-xs text-slate-500">
        <div className="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
          <p>© {new Date().getFullYear()} {merchant.store_name}. All rights reserved.</p>
          <div className="flex items-center gap-6 text-slate-400">
            <span className="flex items-center gap-1"><Truck className="w-4 h-4 text-indigo-400"/> Express Shipping</span>
            <span className="flex items-center gap-1"><ShieldCheck className="w-4 h-4 text-emerald-400"/> Encrypted Checkout</span>
          </div>
        </div>
      </footer>
    </div>
  );
}
