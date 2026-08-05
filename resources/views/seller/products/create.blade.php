@extends('layouts.app')

@section('title', 'Add Product SKU | Seller Portal')

@section('content')
<div class="p-6 max-w-4xl mx-auto my-6">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <div class="mb-8 border-b border-slate-800 pb-6">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Supplier SKU Submission</span>
            <h1 class="text-3xl font-black text-white">Add New Product SKU</h1>
            <p class="text-slate-400 text-xs mt-1">Submitted products undergo automated verification and Admin review (§3.2 state machine).</p>
        </div>

        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs p-4 rounded-2xl mb-6 font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.products.store') }}" class="space-y-6">
            @csrf

            <!-- Product Basic Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">Product Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Premium Fleece Unisex Hoodie" class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-sm text-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">Taxonomy Category</label>
                    <select name="category_id" required class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-sm text-white outline-none">
                        <option value="">Select Master Category</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tax & Pricing Info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 bg-slate-950 p-5 rounded-2xl border border-slate-800">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">HSN Code</label>
                    <input type="text" name="hsn_code" value="{{ old('hsn_code', '61091000') }}" required placeholder="61091000" class="w-full bg-slate-900 border border-slate-800 text-white font-mono text-sm px-3.5 py-2.5 rounded-xl outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">GST Tax Rate (%)</label>
                    <input type="number" step="0.01" name="gst_rate" value="{{ old('gst_rate', '5.00') }}" required placeholder="5.00" class="w-full bg-slate-900 border border-slate-800 text-white text-sm px-3.5 py-2.5 rounded-xl outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Seller Base Price (₹)</label>
                    <input type="number" step="0.01" name="base_price" value="{{ old('base_price', '450.00') }}" required placeholder="450.00" class="w-full bg-slate-900 border border-slate-800 text-white font-extrabold text-sm px-3.5 py-2.5 rounded-xl outline-none focus:border-emerald-500">
                </div>
            </div>

            <!-- 3-Zone Shipping Rates Matrix -->
            <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider block mb-3">Pincode Distance Shipping Rates (Added to Base Price for Price Floor)</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Zone A (Intra-District)</label>
                        <input type="number" step="0.01" name="shipping_zone_a" value="{{ old('shipping_zone_a', '30.00') }}" required class="w-full bg-slate-900 border border-slate-800 text-white font-bold text-sm px-3 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Zone B (Intra-State)</label>
                        <input type="number" step="0.01" name="shipping_zone_b" value="{{ old('shipping_zone_b', '50.00') }}" required class="w-full bg-slate-900 border border-slate-800 text-white font-bold text-sm px-3 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Zone C (Rest of India)</label>
                        <input type="number" step="0.01" name="shipping_zone_c" value="{{ old('shipping_zone_c', '80.00') }}" required class="w-full bg-slate-900 border border-slate-800 text-white font-bold text-sm px-3 py-2 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- Image URL -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-2">Thumbnail Photo URL</label>
                <input type="url" name="thumbnail_image" value="{{ old('thumbnail_image', 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=500') }}" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl px-4 py-3 text-sm text-white outline-none">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-2">Product Description</label>
                <textarea name="description" rows="3" placeholder="380 GSM fleece lined hoodie built for maximum comfort and longevity." class="w-full bg-slate-950 border border-slate-800 focus:border-emerald-500 rounded-xl p-4 text-sm text-white outline-none">{{ old('description') }}</textarea>
            </div>

            <!-- Initial SKU Variant -->
            <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
                <span class="text-xs font-bold text-white uppercase tracking-wider block mb-3">Initial Stock SKU Variant</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">SKU Code</label>
                        <input type="text" name="variants[0][sku]" value="HD-BLK-M" required placeholder="HD-BLK-M" class="w-full bg-slate-900 border border-slate-800 text-white font-mono text-sm px-3 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Variant Attributes (JSON/Text)</label>
                        <input type="text" name="variants[0][attributes][Size]" value="M" required placeholder="M" class="w-full bg-slate-900 border border-slate-800 text-white text-sm px-3 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Initial Stock Units</label>
                        <input type="number" name="variants[0][quantity]" value="50" min="0" required class="w-full bg-slate-900 border border-slate-800 text-white font-bold text-sm px-3 py-2 rounded-xl">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-800">
                <a href="{{ route('seller.products.index') }}" class="text-xs font-bold text-slate-400 hover:text-white px-4 py-3">Cancel</a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs px-6 py-3.5 rounded-xl shadow-lg shadow-emerald-600/30 transition">
                    Submit SKU for Admin Approval
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
