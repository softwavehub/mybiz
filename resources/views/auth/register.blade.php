@extends('layouts.app')

@section('title', 'Register | mybiz B2B2C Platform')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-slate-950 my-8" x-data="{ selectedRole: 'merchant' }">
    <div class="w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 items-center justify-center font-black text-2xl text-white shadow-lg shadow-indigo-500/30 mb-3">
                m
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Create Business Account</h1>
            <p class="text-slate-400 text-xs mt-1">Launch your zero-inventory store or supply products to 10,000+ merchants</p>
        </div>

        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs p-3.5 rounded-xl mb-6 font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Role Selector Tabs -->
        <div class="grid grid-cols-2 gap-3 p-1.5 bg-slate-950 border border-slate-800 rounded-2xl mb-6">
            <button
                type="button"
                @click="selectedRole = 'merchant'"
                :class="selectedRole === 'merchant' ? 'bg-indigo-600 text-white font-bold shadow-md' : 'text-slate-400 font-medium hover:text-white'"
                class="py-2.5 rounded-xl text-xs transition"
            >
                🛍️ Merchant / Student (Dropshipper)
            </button>
            <button
                type="button"
                @click="selectedRole = 'seller'"
                :class="selectedRole === 'seller' ? 'bg-emerald-600 text-white font-bold shadow-md' : 'text-slate-400 font-medium hover:text-white'"
                class="py-2.5 rounded-xl text-xs transition"
            >
                🏭 Seller / Supplier (Wholesaler)
            </button>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="role" :value="selectedRole">

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Full Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="John Doe"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none transition"
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                    <span x-text="selectedRole === 'merchant' ? 'Store / Brand Name' : 'Company / Factory Name'"></span>
                </label>
                <input
                    type="text"
                    name="business_name"
                    value="{{ old('business_name') }}"
                    required
                    placeholder="UrbanFit Apparel"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none transition"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="john@mybiz.com"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none transition"
                    />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Mobile Phone Number</label>
                    <input
                        type="text"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        required
                        placeholder="+91 98765 43210"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none transition"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none transition"
                    />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Confirm Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        placeholder="••••••••"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-sm text-white outline-none transition"
                    />
                </div>
            </div>

            <button
                type="submit"
                :class="selectedRole === 'merchant' ? 'from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 shadow-indigo-600/30' : 'from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-emerald-600/30'"
                class="w-full bg-gradient-to-r text-white font-extrabold text-sm py-3.5 rounded-xl shadow-lg transition active:scale-98 mt-2"
            >
                <span x-text="selectedRole === 'merchant' ? 'Create Merchant Account & Launch Store' : 'Create Seller Account & Register Factory'"></span>
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-800/80 text-center">
            <p class="text-xs text-slate-400">
                Already registered?
                <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:underline">Sign in to existing account</a>
            </p>
        </div>
    </div>
</div>
@endsection
