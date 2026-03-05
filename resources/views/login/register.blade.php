@extends('layouts.logpage')

@section('content')
<body class="min-h-[100dvh] flex items-center justify-center overflow-hidden relative">

    <!-- Decorative Blobs -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/4 w-48 h-48 md:w-72 md:h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute top-1/3 right-1/4 w-48 h-48 md:w-72 md:h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-1/3 w-48 h-48 md:w-72 md:h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-md p-4 md:p-6">

        <!-- Glass Card -->
        <div class="glass rounded-3xl p-6 md:p-8 shadow-2xl w-full transform opacity-0 animate-fade-in-up" style="animation-delay: 0.1s;">

            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2 tracking-tight">Create Account</h1>
                <p class="text-gray-500 text-sm">Join us and start your journey</p>
            </div>

            <!-- Form -->
            <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name Input -->
                <div class="relative group input-group">
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder=" "
                        class="w-full px-4 py-3 rounded-xl bg-gray-50/50 border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 outline-none peer text-gray-800 placeholder-transparent">
                    <label for="name"
                        class="absolute left-4 top-3 text-gray-400 text-sm transition-all duration-300 pointer-events-none peer-focus:opacity-0 peer-[:not(:placeholder-shown)]:opacity-0 bg-none px-1">
                        Username
                    </label>
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="relative group input-group">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder=" "
                        class="w-full px-4 py-3 rounded-xl bg-gray-50/50 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 outline-none peer text-gray-800 placeholder-transparent">
                    <label for="email"
                        class="absolute left-4 top-3 text-gray-400 text-sm transition-all duration-300 pointer-events-none peer-focus:opacity-0 peer-[:not(:placeholder-shown)]:opacity-0 bg-none px-1">
                        Email Address
                    </label>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="relative group input-group">
                    <input type="password" id="password" name="password" required placeholder=" "
                        class="w-full px-4 py-3 rounded-xl bg-gray-50/50 border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 outline-none peer text-gray-800 placeholder-transparent">
                    <label for="password"
                        class="absolute left-4 top-3 text-gray-400 text-sm transition-all duration-300 pointer-events-none peer-focus:opacity-0 peer-[:not(:placeholder-shown)]:opacity-0 bg-none px-1">
                        Password
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
                        Confirm Password
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-3.5 px-4 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                    Register
                </button>

            </form>

            <!-- Sign In Link -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition-colors duration-200">Sign In</a>
                </p>
            </div>

        </div>
    </div>

</body>
@endsection