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
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">
                    Lupa Password?
                </h1>
                <p class="mt-2 text-sm text-gray-500">
                    Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
                </p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div class="relative group input-group">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder=" "
                        class="w-full px-4 py-3 rounded-xl bg-gray-50/50 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 outline-none peer text-gray-800 placeholder-transparent">
                    <label for="email"
                        class="absolute left-4 top-3 text-gray-400 text-sm bg-none px-1 transition-all duration-300 peer-focus:opacity-0 peer-[:not(:placeholder-shown)]:opacity-0 pointer-events-none">
                        Email Address
                    </label>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Kirim Link Reset
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

@if(session('success') || session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Email Terkirim! 📧',
        html: `
            <p class="mb-2">{{ session('success') }}</p>
            <p class="text-sm text-gray-500">Anda akan diarahkan ke halaman login...</p>
        `,
        confirmButtonColor: '#4F46E5',
        confirmButtonText: 'OK, Mengerti!',
        allowOutsideClick: false,
        timer: 5000,
        timerProgressBar: true,
    }).then((result) => {
        window.location.href = "{{ route('login') }}";
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops! 😞',
        text: '{{ session('error') }}',
        confirmButtonColor: '#EF4444',
        confirmButtonText: 'Tutup',
    });
    @endif
});
</script>
@endif
@endsection
