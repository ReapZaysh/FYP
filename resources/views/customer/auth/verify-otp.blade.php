<x-customer-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-950 transition-colors duration-300">
        <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-900 p-10 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 relative overflow-hidden transition-colors duration-300">
            
            {{-- Decorative elements --}}
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-premium-brown/5 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-premium-brown/5 blur-3xl"></div>

            <div>
                <div class="flex justify-center">
                    <div class="w-16 h-16 bg-premium-brown/10 rounded-2xl flex items-center justify-center rotate-3">
                        <svg class="w-8 h-8 text-premium-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white font-serif tracking-tight">
                    Verify your email
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    We've sent a 6-digit verification code to<br>
                    <span class="font-bold text-gray-900 dark:text-white">{{ session('verify_email') }}</span>
                </p>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-2xl text-sm flex items-center gap-2" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-2xl text-sm flex items-center gap-2" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ url('/c/verify-otp') }}" method="POST">
                @csrf
                <div>
                    <label for="otp" class="sr-only">6-Digit Code</label>
                    <input id="otp" name="otp" type="text" required 
                           class="appearance-none relative block w-full px-4 py-4 border border-gray-300 dark:border-gray-700 placeholder-gray-500 text-gray-900 dark:text-white dark:bg-gray-800 rounded-2xl focus:outline-none focus:ring-2 focus:ring-premium-brown focus:border-premium-brown sm:text-lg text-center tracking-[0.5em] font-bold shadow-sm transition-colors duration-300" 
                           placeholder="••••••" maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code">
                    @error('otp')
                        <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-2xl text-white bg-premium-brown hover:bg-[#8A5A3B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-premium-brown shadow-lg shadow-premium-brown/30 transition-all transform hover:scale-[1.02] active:scale-95">
                        Verify Account
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Didn't receive the code? 
                    <form action="{{ url('/c/resend-otp') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="font-bold text-premium-brown hover:text-[#8A5A3B] dark:text-premium-brown-light dark:hover:text-white transition-colors underline bg-transparent border-none p-0 cursor-pointer">
                            Click to resend
                        </button>
                    </form>
                </p>
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('customer.register') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-300">
                    &larr; Back to Registration
                </a>
            </div>
        </div>
    </div>
</x-customer-layout>
