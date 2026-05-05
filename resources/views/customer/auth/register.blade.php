<x-customer-layout>
    <div class="max-w-md mx-auto px-4 py-12 mb-20">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2 transition-colors">Create Account</h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">Join Bossku House to start earning rewards!</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl p-8 border border-gray-100 dark:border-gray-800 relative overflow-hidden transition-colors duration-300">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            
            <form method="POST" action="{{ route('customer.register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Full Name</label>
                    <input id="name" class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-900 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition transition-colors" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Email</label>
                    <input id="email" class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-900 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition transition-colors" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Password</label>
                    <input id="password" class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-900 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition transition-colors" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Confirm Password</label>
                    <input id="password_confirmation" class="w-full bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-900 dark:text-white font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition transition-colors" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
                </div>

                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 px-8 rounded-2xl text-lg font-black shadow-xl shadow-emerald-500/20 transition transform hover:scale-[1.02] active:scale-95">
                    Create Account
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">Already have an account?</p>
                <a href="{{ route('customer.login') }}" class="inline-block mt-2 text-emerald-600 dark:text-emerald-400 font-black hover:text-emerald-700 dark:hover:text-emerald-300 transition transition-colors">
                    Sign in here &rarr;
                </a>
            </div>
        </div>
    </div>
</x-customer-layout>
