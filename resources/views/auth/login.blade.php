@extends('layouts.app')

@section('title', 'Login | mybiz Platform')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-slate-950" x-data="{ email: '{{ old('email', '') }}', password: '', copiedMessage: '' }">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 items-center justify-center font-black text-2xl text-white shadow-lg shadow-indigo-500/30 mb-3">
                m
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Welcome Back</h1>
            <p class="text-slate-400 text-xs mt-1">Sign in to your mybiz Admin, Merchant, or Seller desk</p>
        </div>

        <!-- 1-Click Demo Credentials Quick Fill Buttons -->
        <div class="bg-slate-950 border border-slate-800/80 rounded-2xl p-4 mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-400">⚡ Instant 1-Click Demo Logins</span>
                <span x-show="copiedMessage" x-text="copiedMessage" class="text-[10px] font-bold text-emerald-400 animate-pulse"></span>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <!-- Admin Demo -->
                <button
                    type="button"
                    @click="
                        email = 'admin@mybiz.com';
                        password = 'Password123';
                        navigator.clipboard.writeText('admin@mybiz.com / Password123');
                        copiedMessage = '✓ Admin Filled!';
                        setTimeout(() => copiedMessage = '', 2500);
                    "
                    class="bg-indigo-950/60 hover:bg-indigo-900/80 border border-indigo-500/30 text-indigo-300 rounded-xl p-2.5 text-center transition group"
                >
                    <span class="block text-xs font-bold text-white group-hover:scale-105 transition">🛡️ Admin</span>
                    <span class="text-[9px] text-slate-400 block font-mono mt-0.5">Click to Fill</span>
                </button>

                <!-- Merchant Demo -->
                <button
                    type="button"
                    @click="
                        email = 'merchant@mybiz.com';
                        password = 'Password123';
                        navigator.clipboard.writeText('merchant@mybiz.com / Password123');
                        copiedMessage = '✓ Merchant Filled!';
                        setTimeout(() => copiedMessage = '', 2500);
                    "
                    class="bg-purple-950/60 hover:bg-purple-900/80 border border-purple-500/30 text-purple-300 rounded-xl p-2.5 text-center transition group"
                >
                    <span class="block text-xs font-bold text-white group-hover:scale-105 transition">🏪 Merchant</span>
                    <span class="text-[9px] text-slate-400 block font-mono mt-0.5">Click to Fill</span>
                </button>

                <!-- Seller Demo -->
                <button
                    type="button"
                    @click="
                        email = 'seller@mybiz.com';
                        password = 'Password123';
                        navigator.clipboard.writeText('seller@mybiz.com / Password123');
                        copiedMessage = '✓ Seller Filled!';
                        setTimeout(() => copiedMessage = '', 2500);
                    "
                    class="bg-emerald-950/60 hover:bg-emerald-900/80 border border-emerald-500/30 text-emerald-300 rounded-xl p-2.5 text-center transition group"
                >
                    <span class="block text-xs font-bold text-white group-hover:scale-105 transition">🏭 Seller</span>
                    <span class="text-[9px] text-slate-400 block font-mono mt-0.5">Click to Fill</span>
                </button>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs p-3.5 rounded-xl mb-6 font-medium">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-3.5 rounded-xl mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs p-3.5 rounded-xl mb-6 font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-2">Email Address</label>
                <input
                    type="email"
                    name="email"
                    x-model="email"
                    required
                    placeholder="name@business.com"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-3 text-sm text-white outline-none transition"
                />
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-xs font-bold text-slate-300">Password</label>
                    <a href="#" class="text-xs font-semibold text-indigo-400 hover:underline">Forgot password?</a>
                </div>
                <input
                    type="password"
                    name="password"
                    x-model="password"
                    required
                    placeholder="••••••••"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-3 text-sm text-white outline-none transition"
                />
            </div>

            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-800 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="ml-2 text-slate-400 font-medium">Remember me</label>
                </div>
                <span class="text-slate-500">Demo Password: <strong class="text-slate-300 font-mono">Password123</strong></span>
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-extrabold text-sm py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transition active:scale-98"
            >
                Sign In to Portal
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-800/80 text-center">
            <p class="text-xs text-slate-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-bold text-indigo-400 hover:underline">Create a Business Account</a>
            </p>
        </div>
    </div>
</div>
@endsection
