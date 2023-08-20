<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Client') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Setup the integration settings to start Syncing Shipments.') }}
        </p>
    </header>

    <form method="post" action="{{ route('client.update') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="company_code" :value="__('Company Name')" />
            <x-text-input id="company_code" name="company_code" type="text" class="mt-1 block w-full"
                value="{{ $client['company_code'] }}" autocomplete="company_code" />
            <x-input-error :messages="$errors->createClient->get('company_code')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="client_code" :value="__('Integration Name')" />
            <x-text-input id="client_code" name="client_code" type="text" class="mt-1 block w-full"
                value="{{ $client['client_code'] }}" autocomplete="client_code" />
            <x-input-error :messages="$errors->createClient->get('client_code')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="secret_key" :value="__('Secret key')" />
            <x-text-input id="secret_key" name="secret_key" type="text" class="mt-1 block w-full"
                value="{{ $client['secret_key'] }}" autocomplete="new-secretKey" />
            <x-input-error :messages="$errors->createClient->get('secret_key')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="type_code" :value="__('Integration Type')" />
            {{-- TODO make it as dropdown from enum --}}
            <x-text-input id="type_code" name="type_code" type="text" class="mt-1 block w-full"
                value="{{ $client['type_code'] }}" autocomplete="new-typeCode" />
            <x-input-error :messages="$errors->createClient->get('type_code')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="url" :value="__('Api Url')" />
            <x-text-input id="url" name="url" type="text" class="mt-1 block w-full"
                value="{{ $client['url'] }}" autocomplete="new-url" />
            <x-input-error :messages="$errors->createClient->get('url')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="token" :value="__('Token')" />
            <x-text-input id="token" name="token" type="text" class="mt-1 block w-full"
                value="{{ $client['token'] }}" autocomplete="new-token" />
            <x-input-error :messages="$errors->createClient->get('token')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'client-update')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-gray-600">{{ __(' Client updated.') }}</p>
            @endif
        </div>
    </form>
</section>
