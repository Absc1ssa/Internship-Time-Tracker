<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Logo --}}
    <div class="flex flex-col items-center justify-center text-center w-full">
        <img src="{{ asset('images/psu-logo.png') }}" height="70" width="70" style="margin-bottom: 0.5rem;" loading="lazy" alt="psu-logo" class="w-15 h-15">
        <p style="color: #000736; font-weight: bolder;" class="mb-6 mt-2">Internship Time Tracker</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="off" placeholder="Enter your email..." style="font-size: 12px; color: #05020e;" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="absolute block mt-1 w-full" type="password" name="password" required autocomplete="off" placeholder="Enter your password..." style="font-size: 12px; color: #05020e;" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button style="background-color: #4050B8;" class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- JavaScript for password visibility toggle -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const icon = document.getElementById('togglePassword').querySelector('span');

    const isPasswordVisible = passwordField.type === 'text';

    // Toggle password visibility
    passwordField.type = isPasswordVisible ? 'password' : 'text';

    // Toggle icon
    icon.textContent = isPasswordVisible ? 'visibility_off' : 'visibility';
});

    </script>
</x-guest-layout>
