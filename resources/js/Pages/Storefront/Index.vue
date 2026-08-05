<script setup>
import { Head } from '@inertiajs/vue3';
import { ShoppingBag, Truck, ShieldCheck, Star, Sparkles } from '@lucide/vue';

const props = defineProps({
  merchant: Object,
  products: Array,
});

const storeName = props.merchant?.store_name || 'UrbanStyle Apparel';
const productList = props.products || [];
</script>

<template>
  <div className="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white">
    <Head :title="`${storeName} | Official Store`" />

    <!-- Top Banner -->
    <div className="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-xs font-bold py-2 px-4 text-center tracking-wide shadow-md flex items-center justify-center gap-2">
      <Sparkles className="w-4 h-4 text-amber-300" />
      <span>LIMITED TIME OFFER: Free Nationwide Express Delivery on Orders Over ₹499!</span>
    </div>

    <!-- Header Bar -->
    <header className="border-b border-slate-800 bg-slate-900/90 backdrop-blur sticky top-0 z-50 shadow-xl">
      <div className="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
        <div className="flex items-center space-x-3">
          <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center font-black text-xl text-white shadow-lg shadow-indigo-500/30 ring-2 ring-indigo-400/30">
            {{ storeName.charAt(0) }}
          </div>
          <span className="font-extrabold text-xl tracking-tight text-white drop-shadow-sm">
            {{ storeName }}
          </span>
        </div>

        <div className="flex items-center space-x-6">
          <span className="text-xs text-slate-300 font-medium flex items-center gap-1.5 bg-slate-800 px-3 py-1.5 rounded-full border border-slate-700">
            <ShieldCheck className="w-4 h-4 text-emerald-400" />
            Verified Brand
          </span>
          <div className="relative bg-slate-800 p-2 rounded-xl border border-slate-700 hover:border-indigo-500 cursor-pointer transition">
            <ShoppingBag className="w-5 h-5 text-indigo-400" />
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section className="relative overflow-hidden bg-gradient-to-b from-indigo-950/80 via-slate-900 to-slate-950 border-b border-slate-800 py-16 px-4 text-center">
      <div className="relative max-w-4xl mx-auto space-y-5">
        <span className="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-500/20 border border-indigo-500/40 text-indigo-300 rounded-full text-xs font-bold tracking-wider uppercase shadow-inner">
          <Sparkles className="w-3.5 h-3.5 text-amber-400" /> Premium Direct Storefront
        </span>
        <h1 className="text-4xl sm:text-6xl font-black text-white tracking-tight leading-tight">
          Curated Quality. <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">Delivered Fast.</span>
        </h1>
        <p className="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto font-normal">
          Shop exclusive streetwear, fashion, and lifestyle gear backed by guaranteed delivery and 100% white-label fulfillment.
        </p>
      </div>
    </section>

    <!-- Catalog Grid -->
    <main className="max-w-7xl mx-auto px-4 py-12">
      <div className="flex items-center justify-between mb-8 border-b border-slate-800 pb-4">
        <h2 className="text-2xl font-black text-white tracking-tight flex items-center gap-2">
          Trending Products
        </h2>
        <span className="text-xs text-slate-400 font-medium">{{ productList.length }} Items Available</span>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="p in productList" :key="p.id" className="group bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden hover:border-indigo-500/60 transition-all duration-300 flex flex-col justify-between hover:shadow-2xl hover:shadow-indigo-500/20">
          <div>
            <div className="aspect-square bg-slate-950 relative overflow-hidden">
              <img
                :src="p.thumbnail_image || 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500'"
                :alt="p.name"
                className="w-full h-full object-cover group-hover:scale-105 transition duration-500"
              />
              <span className="absolute top-3 right-3 bg-slate-950/90 backdrop-blur text-emerald-400 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/40 shadow-md">
                In Stock
              </span>
            </div>

            <div className="p-5">
              <h3 className="font-bold text-white group-hover:text-indigo-400 transition text-base line-clamp-1">
                {{ p.name }}
              </h3>
              <div className="flex items-center gap-1.5 text-amber-400 text-xs mt-1.5">
                <div className="flex text-amber-400">
                  <Star v-for="i in 5" :key="i" className="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                </div>
                <span className="font-semibold text-slate-300 ml-1">4.9 (148 reviews)</span>
              </div>
              <p className="text-slate-400 text-xs line-clamp-2 mt-2 leading-relaxed">
                {{ p.description }}
              </p>
            </div>
          </div>

          <div className="p-5 pt-0 flex items-center justify-between border-t border-slate-800/80 mt-4">
            <div>
              <span className="text-[10px] uppercase font-bold text-slate-500 block">Retail Price</span>
              <span className="text-xl font-black text-white">₹{{ p.retail_price }}</span>
            </div>
            <button className="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-extrabold px-4 py-2.5 rounded-xl transition shadow-lg shadow-indigo-600/30 active:scale-95">
              Buy Now
            </button>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer className="border-t border-slate-800 bg-slate-900 py-10 px-4 text-center text-xs text-slate-400">
      <div className="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <p>© {{ new Date().getFullYear() }} <strong className="text-white">{{ storeName }}</strong>. All rights reserved.</p>
        <div className="flex items-center gap-6 text-slate-300">
          <span className="flex items-center gap-1.5"><Truck className="w-4 h-4 text-indigo-400"/> Fast Express Shipping</span>
          <span className="flex items-center gap-1.5"><ShieldCheck className="w-4 h-4 text-emerald-400"/> 256-Bit Encrypted Payment</span>
        </div>
      </div>
    </footer>
  </div>
</template>
