<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Setup the integration settings to start Syncing Shipments.') }}
        </p>
    </header>

    <form method="post" action="{{ route('setting.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')
        <div>
            <x-input-label for="url" :value="__('Api Url')" />
            <x-text-input id="url" name="url" type="text" value="{{ $setting['url'] }}"
                class="mt-1 block w-full" autocomplete="new-url" />
            <x-input-error :messages="$errors->updateSetting->get('url')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="token" :value="__('Token')" />
            <x-text-input id="token" name="token" type="text" value="{{ $setting['token'] }}"
                class="mt-1 block w-full" autocomplete="new-token" />
            <x-input-error :messages="$errors->updateSetting->get('token')" class="mt-2" />
        </div>
        {{-- {{dd($userType)}} --}}
        <div>
            <x-input-label for="type_code" :value="__('Integration Type')" />
            {{-- TODO make it as dropdown from enum --}}
            <x-text-input id="type_code" name="type_code" type="text" value="{{ $setting['type_code'] }}"
                class="mt-1 block w-full" autocomplete="new-typeCode" />
            <x-input-error :messages="$errors->updateSetting->get('type_code')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'setting-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-gray-600">{{ __(' Setting Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
