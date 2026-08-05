<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus, Globe, TrendingUp } from 'lucide-vue-next';

const props = defineProps({
  merchant: Object,
  catalog: Array,
});

const markupPercent = ref(30);

const catalogList = props.catalog || [
  { id: 101, name: 'Heavyweight Unisex Hoodie', base: 450, shipping: 60, floor: 573.47 },
  { id: 102, name: 'Cotton Oversized Graphic Tee', base: 220, shipping: 40, floor: 292.86 },
  { id: 103, name: 'Waterproof Canvas Backpack', base: 650, shipping: 80, floor: 820.41 },
];
</script>

<template>
  <div className="min-h-screen bg-slate-950 text-slate-100 font-sans p-6">
    <Head title="Merchant Store Desk | mybiz" />

    <!-- Merchant Header -->
    <header className="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-slate-800 mb-8 gap-4">
      <div>
        <span className="text-xs font-bold uppercase tracking-widest text-indigo-400">Merchant Operations Desk</span>
        <h1 className="text-3xl font-black text-white flex items-center gap-3">
          {{ merchant?.store_name || 'UrbanStyle Apparel' }}
          <span className="text-xs font-bold px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 rounded-full">
            {{ merchant?.subdomain || 'urbanstyle' }}.mybiz.dhaivam.in
          </span>
        </h1>
      </div>

      <div className="flex items-center space-x-3">
        <button className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2">
          <Globe className="w-4 h-4" />
          Connect Custom CNAME Domain
        </button>
      </div>
    </header>

    <!-- Smart Price Floor & Markup Engine Controls -->
    <div className="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 mb-8">
      <h2 className="text-lg font-bold text-white mb-2 flex items-center gap-2">
        <TrendingUp className="w-5 h-5 text-indigo-400" />
        Automated Smart Markup Engine
      </h2>
      <p className="text-xs text-slate-400 mb-6">
        Set your global profit markup rule. Prices are automatically validated against the immutable **Absolute Price Floor**.
      </p>

      <div className="flex items-center space-x-4 max-w-md">
        <label className="text-xs font-bold text-slate-300">Global Markup (%):</label>
        <input
          type="number"
          v-model="markupPercent"
          className="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-white font-bold text-sm w-24 text-center focus:border-indigo-500 outline-none"
        />
        <button className="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
          Apply Markup Rule
        </button>
      </div>
    </div>

    <!-- Supplier Catalog (1-Click Import) -->
    <h2 className="text-xl font-bold text-white mb-6">Available Supplier Products (1-Click Store Import)</h2>

    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="item in catalogList" :key="item.id" className="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
        <div>
          <h3 className="font-bold text-white text-base mb-1">{{ item.name }}</h3>
          <div className="text-xs space-y-1 text-slate-400 mt-3 bg-slate-950 p-3 rounded-xl border border-slate-800/80">
            <div className="flex justify-between">
              <span>Seller Base + Ship:</span>
              <span className="font-medium text-slate-200">₹{{ item.base + item.shipping }}</span>
            </div>
            <div className="flex justify-between font-bold text-indigo-400 border-t border-slate-800 pt-1">
              <span>Absolute Price Floor:</span>
              <span>₹{{ item.floor }}</span>
            </div>
          </div>
        </div>

        <div className="mt-5 border-t border-slate-800/80 pt-4">
          <div className="flex items-center justify-between mb-3">
            <div>
              <span className="text-xs text-slate-500 block">Retail Price</span>
              <span className="text-lg font-extrabold text-white">₹{{ Math.round(item.floor * (1 + markupPercent / 100)) }}</span>
            </div>
            <div className="text-right">
              <span className="text-xs text-slate-500 block">Your Profit</span>
              <span className="text-base font-extrabold text-emerald-400">+₹{{ Math.round(item.floor * (1 + markupPercent / 100) - item.floor) }}</span>
            </div>
          </div>

          <button className="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/20">
            <Plus className="w-4 h-4" />
            1-Click Import to My Store
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
