<x-customer-layout>
    <div class="max-w-md mx-auto px-4 py-12 mb-20">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900 mb-2">Welcome Back</h2>
            <p class="text-gray-500 font-medium">Login to earn and redeem loyalty points!</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="bg-white rounded-[2rem] shadow-xl p-8 border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <form method="POST" action="{{ route('customer.login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Email</label>
                    <input id="email" class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl py-3 px-4 text-gray-900 font-bold focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Password</label>
                    <input id="password" class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl py-3 px-4 text-gray-900 font-bold focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-8">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm font-bold text-gray-600">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-bold text-blue-600 hover:text-blue-500 transition" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-8 rounded-2xl text-lg font-black shadow-xl shadow-blue-600/20 transition transform hover:scale-[1.02] active:scale-95">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-500 font-medium">Don't have an account?</p>
                <a href="{{ route('customer.register') }}" class="inline-block mt-2 text-blue-600 font-black hover:text-blue-700 transition">
                    Create one now &rarr;
                </a>
            </div>
        </div>
    </div>
</x-customer-layout>
