@extends('layouts.logpage')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8 font-sans">
    
    <!-- Animated Background Blobs -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    
    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-md p-4 md:p-6">

        <!-- Glass Card -->
        <div class="glass rounded-3xl p-6 md:p-8 shadow-2xl w-full transform opacity-0 animate-fade-in-up" style="animation-delay: 0.1s;">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">
                    Reset Password
                </h1>
                <p class="mt-2 text-sm text-gray-500">
                    Masukkan password baru Anda.
                </p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Email Display -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-200 text-gray-600">
                        {{ $email }}
                    </div>
                </div>

                <!-- New Password Input -->
                <div class="relative group input-group">
                    <input type="password" id="password" name="password" required placeholder=" "
                        class="w-full px-4 py-3 rounded-xl bg-gray-50/50 border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 outline-none peer text-gray-800 placeholder-transparent">
                    <label for="password"
                        class="absolute left-4 top-3 text-gray-400 text-sm transition-all duration-300 pointer-events-none peer-focus:opacity-0 peer-[:not(:placeholder-shown)]:opacity-0 bg-none px-1">
                        Password Baru
                    </label>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div class="relative group input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder=" "
                        class="w-full px-4 py-3 rounded-xl bg-gray-50/50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 outline-none peer text-gray-800 placeholder-transparent">
                    <label for="password_confirmation"
                        class="absolute left-4 top-3 text-gray-400 text-sm transition-all duration-300 pointer-events-none peer-focus:opacity-0 peer-[:not(:placeholder-shown)]:opacity-0 bg-none px-1">
                        Konfirmasi Password
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Reset Password
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 group">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
