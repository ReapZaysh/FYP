<x-customer-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 mb-20">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('customer.profile') }}" class="p-2 bg-white dark:bg-gray-800 text-gray-500 hover:text-gray-900 dark:hover:text-white rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">Account Settings</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">Update your profile information and password</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Update Profile Form -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 transition-colors relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-premium-brown/5 rounded-full blur-2xl group-hover:bg-premium-brown/10 transition-colors"></div>
                <div class="relative z-10">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-1 transition-colors">Profile Information</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 transition-colors">Update your account's profile information, email address, and phone number.</p>
                    
                    <form method="post" action="{{ route('customer.profile.update') }}" class="space-y-4">
                        @csrf
                        @method('patch')
                        
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl focus:ring-premium-brown focus:border-premium-brown transition-colors">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl focus:ring-premium-brown focus:border-premium-brown transition-colors">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">Phone Number <span class="text-xs font-normal text-gray-400">(Optional)</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="012-3456789" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl focus:ring-premium-brown focus:border-premium-brown transition-colors">
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" class="px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-xl shadow-sm hover:bg-gray-800 dark:hover:bg-gray-100 transition">Save Changes</button>
                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-emerald-500">Saved successfully.</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password Form -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 transition-colors relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-premium-brown/5 rounded-full blur-2xl group-hover:bg-premium-brown/10 transition-colors"></div>
                <div class="relative z-10">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-1 transition-colors">Update Password</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 transition-colors">Ensure your account is using a long, random password to stay secure.</p>
                    
                    <form method="post" action="{{ route('customer.password.update') }}" class="space-y-4">
                        @csrf
                        @method('put')
                        
                        <div>
                            <label for="current_password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl focus:ring-premium-brown focus:border-premium-brown transition-colors">
                            @error('current_password', 'updatePassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">New Password</label>
                            <input type="password" id="password" name="password" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl focus:ring-premium-brown focus:border-premium-brown transition-colors">
                            @error('password', 'updatePassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl focus:ring-premium-brown focus:border-premium-brown transition-colors">
                            @error('password_confirmation', 'updatePassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" class="px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-xl shadow-sm hover:bg-gray-800 dark:hover:bg-gray-100 transition">Update Password</button>
                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-emerald-500">Password updated.</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-customer-layout>
