<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/providers">Providers</a></li>
                </ol>
            </div>
            <div class="col d-flex justify-content-end">
                <div class="text-right">
                    <!-- Add New Integration Provider -->
                    <x-primary-button type="button" data-target="#addProviderModal" id="addNew" data-toggle="modal"
                        data-toggle="modal">{{ __('Add New') }}</x-primary-button>

                </div>
            </div>
        </div>
        <table class="table table-striped-columns">
            <thead>
                <tr>
                    <th scope="col">code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Api Url</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($providers as $provider)
                    <tr>
                        <td>{{ $provider['code'] }}</td>
                        <td>{{ $provider['name'] }}</td>
                        <td>{{ $provider['api_url'] }}</td>
                        <td>
                            @if ($provider['active'])
                                <span class="bg-success p-1 rounded-2 text-white">active</span>
                            @else
                                <span class="bg-danger p-1 rounded-2 text-white">inactive</span>
                            @endif
                        </td>
                        <td>{{ date('d-m-Y', strtotime($provider['created_at'])) }}</td>
                        <td>
                            <div class="row">
                                <div class="col-auto">
                                    <x-edit-button type="button" data-target="#editProviderModal_{{ $provider->id }}"
                                        id="editProvider" data-toggle="modal"
                                        data-toggle="modal">{{ __('Edit') }}</x-edit-button>
                                </div>
                                <div class="col-auto">
                                    <x-danger-button type="button"
                                        data-target="#deleteProviderModal_{{ $provider->id }}" id="editProvider"
                                        data-toggle="modal" data-toggle="modal">{{ __('Delete') }}</x-edit-button>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal for delete provider -->
                    <div class="modal fade" id="deleteProviderModal_{{ $provider->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="deleteProviderModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Provider<h5>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-2 fs-6">Are you sure you want to delete: <span
                                            class="text-danger">{{ $provider->name }}</span> ? </h1>
                                    <form action="{{ route('providers.delete', ['provider' => $provider->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-text-input id="id{{ $provider->id }}" name="id"
                                            value="{{ $provider->id }}" type="hidden" />
                                        <div class="modal-footer">
                                            <div class="flex items-center gap-4">
                                                <x-primary-button type="submit"
                                                    id="deleteProvider_{{ $provider->id }}" data-toggle="modal"
                                                    data-toggle="modal">{{ __('Confirm') }}</x-primary-button>
                                                <x-close-button
                                                    id="closeDeleteForm">{{ __('Close') }}</x-primary-button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for edit provider -->
                    <div class="modal fade" id="editProviderModal_{{ $provider->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editProviderModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProviderModalLabel">Edit Provider<h5>
                                </div>
                                <div class="modal-body">
                                    <!-- Your form for edit a providers -->
                                    <form method="post"
                                        action="{{ route('providers.update', ['provider' => $provider->id]) }}"
                                        class="mt-6 space-y-6">
                                        @csrf
                                        @method('put')
                                        <x-text-input id="id{{ $provider->id }}" name="id"
                                            value="{{ $provider->id }}" type="hidden" />
                                        <div>
                                            <x-input-label for="code" :value="__('Code')" />
                                            <x-text-input id="code_{{ $provider->id }}" name="code" type="text"
                                                value="{{ $provider['code'] }}" class="mt-1 block w-full"
                                                autocomplete="new-code" />
                                            <x-input-error :messages="$errors->updateProviders->get('name')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="name" :value="__('Name')" />
                                            <x-text-input id="name{{ $provider->id }}" name="name" type="text"
                                                value="{{ $provider['name'] }}" class="mt-1 block w-full"
                                                autocomplete="new-name" />
                                            <x-input-error :messages="$errors->updateProviders->get('name')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="api_url" :value="__('Api Url')" />
                                            <x-text-input id="api_url{{ $provider->id }}" name="api_url"
                                                type="text" value="{{ $provider['api_url'] }}"
                                                class="mt-1 block w-full" autocomplete="new-api_url" />
                                            <x-input-error :messages="$errors->updateProviders->get('api_url')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="active" :value="__('Active')" />
                                            <x-form-dropdown id="active" name="active">
                                                <x-slot name="options">
                                                    <option value="1"
                                                        {{ $provider['active'] == 1 ? 'selected' : '' }}>
                                                        True
                                                    </option>
                                                    <option value="0"
                                                        {{ $provider['active'] ? '' : 'selected' }}>False
                                                    </option>
                                                </x-slot>

                                            </x-form-dropdown>

                                            <x-input-error :messages="$errors->updateProviders->get('active')" class="mt-2" />
                                        </div>

                                        <div class="modal-footer">
                                            <div class="flex items-center gap-4">
                                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                                <x-close-button
                                                    id="closeEditForm_{{ $provider->id }}">{{ __('Close') }}</x-primary-button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal for adding new provider -->
    <div class="modal fade" id="addProviderModal" tabindex="-1" role="dialog"
        aria-labelledby="addProviderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProviderModalLabel">Add New Provider<h5>
                </div>
                <div class="modal-body">
                    <!-- Your form for adding a new provider -->
                    <form id="addProvider" method="post" action="{{ route('providers.store') }}"
                        class="mt-6 space-y-6">
                        @csrf
                        @method('post')
                        <div>
                            <x-input-label for="code" :value="__('Code')" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full"
                                value="{{ old('code') }}" autocomplete="new-code" />
                            <x-input-error :messages="$errors->providerStoreBag->get('code')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ old('name') }}" autocomplete="new-name" />
                            <x-input-error :messages="$errors->providerStoreBag->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="api_url" :value="__('Api Url')" />
                            <x-text-input id="api_url" name="api_url" type="text" class="mt-1 block w-full"
                                value="{{ old('api_url') }}" autocomplete="new-api_url" />
                            <x-input-error :messages="$errors->providerStoreBag->get('api_url')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="active" :value="__('Active')" />
                            <x-form-dropdown id="active" name="active">
                                <x-slot name="options">
                                    {{-- add x-options --}}
                                    <option value="1" {{ old('active') ? 'selected' : '' }}>True
                                    </option>
                                    <option value="0"{{ old('active') ? '' : 'selected' }}>False
                                    </option>
                                </x-slot>

                            </x-form-dropdown>
                            <x-input-error :messages="$errors->providerStoreBag->get('active')" class="mt-2" />
                        </div>

                        <div class="modal-footer">
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                <x-close-button id="closeForm">{{ __('Close') }}</x-primary-button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            // JavaScript to show the modal when clicking "Add New" button
            $(document).ready(function() {
                @if ($errors->providerStoreBag->any())
                    $('#addProviderModal').modal('show');
                @endif
                @if ($errors->updateProviders->any())
                    var edit = "#editProviderModal_" + {{ old('id') }};
                    $(edit).modal('show');
                @endif

                $('#addNew').on('click', function() {
                    $('#addProviderModal').modal('show');
                });
                $('#closeForm').on('click', function() {
                    $('#addProviderModal').modal('hide');
                });

                $('[data-toggle="modal"]').click(function() {
                    var targetModal = $(this).data('target');
                    $(targetModal).modal('show');
                });
                // Close modal on close button click
                $('[data-dismiss="modal"]').click(function() {
                    var targetModal = $(this).closest('.modal');
                    $(targetModal).modal('hide');
                });
            });
        </script>
    @endsection
</x-app-layout>
