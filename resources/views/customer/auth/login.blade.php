<x-customer-layout>
    <div class="max-w-md mx-auto px-4 py-12 mb-20">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2 transition-colors">Welcome Back</h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">Login to earn and redeem loyalty points!</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl p-8 border border-gray-100 dark:border-gray-800 relative overflow-hidden transition-colors duration-300">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <form method="POST" action="{{ route('customer.login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Email</label>
                    <input id="email" class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-900 dark:text-white font-bold focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition transition-colors" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Password</label>
                    <input id="password" class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-900 dark:text-white font-bold focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition transition-colors" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Forgot Password -->
                <div class="flex items-center justify-end mb-8">
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
                <p class="text-gray-500 dark:text-gray-400 font-medium">Don't have an account?</p>
                <a href="{{ route('customer.register') }}" class="inline-block mt-2 text-blue-600 dark:text-blue-400 font-black hover:text-blue-700 dark:hover:text-blue-300 transition">
                    Create one now &rarr;
                </a>
            </div>
        </div>
    </div>
</x-customer-layout>
