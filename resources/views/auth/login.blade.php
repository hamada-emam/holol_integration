<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <div class="container-fluid d-flex justify-content-center flex-column align-items-center mt-5 rounded p-4 vh-100">
        <img src="images/logo.png" width=200 alt="Logo" class="mb-4">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- username Address -->
            <div>
                <x-input-label for="username" :value="__('username')" />
                <x-text-input id="username" class="block mt-1 w-full h4" type="text" name="username" :value="old('username')"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full h4" type="password" name="password" required
                    autocomplete="current-pass  word" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-3 fs-8">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</x-guest-layout>
