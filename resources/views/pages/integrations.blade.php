<x-app-layout>
    <div class="mx-1 mx-md-2 mx-lg-3 mt-4">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/integrations">Integrations</a></li>
                </ol>
            </div>
            @if ($isAdmin)
                <div class="col d-flex justify-content-end">
                    <div class="text-right">
                        <!-- Add New Integration Integration -->
                        <x-primary-button type="button" data-target="#addIntegrationModal" id="addNew"
                            data-toggle="modal" data-toggle="modal">{{ __('Add New') }}</x-primary-button>
                    </div>
                </div>
            @endif
        </div>
        <table class="table table-striped-columns">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Client</th>
                    <th scope="col">Client Url</th>
                    <th scope="col">Client Username </th>
                    <th scope="col">Provider</th>
                    <th scope="col">Provider Url</th>
                    <th scope="col">Provider Username</th>
                    <th scope="col">Active</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($integrations as $integration)
                    <tr>
                        <td>{{ $integration['id'] }}</td>
                        <td>{{ $integration->user->name }}</td>
                        <td>{{ $integration->user->backend_url }}</td>
                        <td>{{ $integration->user_username }}</td>
                        <td>{{ \App\Models\provider::find($integration['provider_id'])->name }}</td>
                        <td>{{ $integration['provider_url'] }}</td>
                        <td>{{ $integration['provider_username'] }}</td>
                        <td>
                            @if ($integration['active'])
                                <span class="bg-success p-1 rounded-2 text-white">active</span>
                            @else
                                <span class="bg-danger p-1 rounded-2 text-white">inactive</span>
                            @endif
                        </td>
                        <td>{{ date('d-m-Y', strtotime($integration['created_at'])) }}</td>
                        <td>
                            <div class="row">
                                <div class="col-auto">
                                    <a href="{{ route('zones', ['integrationId' => $integration['id']]) }}"
                                        class="btn btn-warning text" tabindex="-1" role="button"
                                        aria-disabled="true">Mapp Zones</a>
                                </div>
                                @if ($isAdmin)
                                    <div class="col-auto">
                                        <x-edit-button type="button"
                                            data-target="#editIntegrationModal_{{ $integration->id }}"
                                            id="editIntegration" data-toggle="modal"
                                            data-toggle="modal">{{ __('Edit') }}</x-edit-button>
                                    </div>
                                    <div class="col-auto">
                                        <x-danger-button type="button"
                                            data-target="#deleteIntegrationModal_{{ $integration->id }}"
                                            id="deleteIntegration_{{ $integration->id }}" data-toggle="modal"
                                            data-toggle="modal">{{ __('Delete') }}</x-edit-button>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal for delete integration -->
                    <div class="modal fade" id="deleteIntegrationModal_{{ $integration->id }}" tabindex="-1"
                        aria-labelledby="deleteIntegrationModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Integration<h5>
                                </div>
                                <div class="modal-body">
                                    <h1 class="mb-2 fs-6">Are you sure you want to delete this integration ?</h1>
                                    <form
                                        action="{{ route('integrations.delete', ['integration' => $integration->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-text-input id="id{{ $integration->id }}" name="id"
                                            value="{{ $integration->id }}" type="hidden" />
                                        <div class="modal-footer">
                                            <div class="flex items-center gap-4">
                                                <x-primary-button type="submit"
                                                    id="deleteIntegration_{{ $integration->id }}" data-toggle="modal"
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

                    <!-- Modal for edit Integration -->
                    <div class="modal fade" id="editIntegrationModal_{{ $integration->id }}" tabindex="-1"
                        role="dialog" aria-labelledby="editIntegrationModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editIntegrationModalLabel">Edit Integration<h5>
                                </div>
                                <div class="modal-body">
                                    {{ $errors->updateIntegrations }}
                                    <!-- Your form for edit a integrations -->
                                    <form method="post"
                                        action="{{ route('integrations.update', ['integration' => $integration->id]) }}"
                                        class="mt-6 space-y-6">
                                        @csrf
                                        @method('put')
                                        <x-text-input id="id_{{ $integration->id }}" name="id"
                                            value="{{ $integration->id }}" type="hidden" />
                                        @if ($isAdmin)
                                            <div>
                                                <x-input-label for="user_id" :value="__('User')" />
                                                <x-form-dropdown id="user_id" name="user_id">
                                                    <x-slot name="options">
                                                        @foreach ($users as $key => $user)
                                                            <option value={{ $user->id }}
                                                                {{ $user->id == $integration->user_id ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach

                                                    </x-slot>
                                                </x-form-dropdown>
                                                <x-input-error :messages="$errors->integrationStoreBag->get('user_id')" class="mt-2" />
                                            </div>
                                        @endif
                                        <div>
                                            <x-input-label for="provider_id" :value="__('Provider')" />
                                            <x-form-dropdown id="provider_id" name="provider_id">
                                                <x-slot name="options">
                                                    @foreach ($providers as $key => $provider)
                                                        <option value={{ $provider->id }}
                                                            {{ $provider->id == $integration->provider_id ? 'selected' : '' }}>
                                                            {{ $provider->name }}
                                                        </option>
                                                    @endforeach

                                                </x-slot>
                                            </x-form-dropdown>
                                            <x-input-error :messages="$errors->integrationStoreBag->get('provider_id')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="api_url" :value="__('Api Url')" />
                                            <x-text-input id="api_url_{{ $integration->id }}" name="api_url"
                                                type="text" value="{{ $integration['api_url'] }}"
                                                class="mt-1 block w-full" autocomplete="new-api_url" />
                                            <x-input-error :messages="$errors->updateIntegrations->get('api_url')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="user_token" :value="__('User Token')" />
                                            <x-text-input id="user_token{{ $integration->id }}" name="user_token"
                                                type="text" value="{{ $integration['user_token'] }}"
                                                class="mt-1 block w-full" autocomplete="new-user_token" />
                                            <x-input-error :messages="$errors->updateIntegrations->get('user_token')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="active" :value="__('Active')" />
                                            <x-form-dropdown id="active" name="active">
                                                <x-slot name="options">
                                                    <option value="1"
                                                        {{ $integration['active'] == 1 ? 'selected' : '' }}>
                                                        True
                                                    </option>
                                                    <option value="0"
                                                        {{ $integration['active'] ? '' : 'selected' }}>False
                                                    </option>
                                                </x-slot>
                                            </x-form-dropdown>
                                            <x-input-error :messages="$errors->updateIntegrations->get('active')" class="mt-2" />
                                        </div>

                                        <div class="modal-footer">
                                            <div class="flex items-center gap-4">
                                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                                <x-close-button
                                                    id="closeEditForm_{{ $integration->id }}">{{ __('Close') }}</x-primary-button>
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

    <!-- Modal for adding new integration -->
    <div class="modal fade" id="addIntegrationModal" tabindex="-1" role="dialog"
        aria-labelledby="addIntegrationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIntegrationModalLabel">Add New Integration<h5>
                </div>
                <div class="modal-body">
                    <!-- Your form for adding a new integration -->
                    <form id="addIntegration" method="post" action="{{ route('integrations.store') }}"
                        class="mt-6 space-y-6">
                        @csrf
                        @method('post')
                        <div>
                            <x-input-label for="user_id" :value="__('User')" />
                            <x-form-dropdown id="user_id" name="user_id">
                                <x-slot name="options">
                                    @foreach ($users as $key => $user)
                                        <option value={{ $user->id }}
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach

                                </x-slot>
                            </x-form-dropdown>
                            <x-input-error :messages="$errors->integrationStoreBag->get('user_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="provider_id" :value="__('Provider')" />
                            <x-form-dropdown id="provider_id" name="provider_id">
                                <x-slot name="options">
                                    @foreach ($providers as $key => $provider)
                                        <option value={{ $provider->id }}
                                            {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                                            {{ $provider->name }}
                                        </option>
                                    @endforeach

                                </x-slot>
                            </x-form-dropdown>
                            <x-input-error :messages="$errors->integrationStoreBag->get('provider_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="provider_url" :value="__('Provider Url')" />
                            <x-text-input id="provider_url" name="provider_url" type="text"
                                class="mt-1 block w-full" value="{{ old('provider_url') }}"
                                autocomplete="new-provider_url" />
                            <x-input-error :messages="$errors->integrationStoreBag->get('provider_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="provider_username" :value="__('Provider Username')" />
                            <x-text-input id="provider_username" name="provider_username" type="text"
                                class="mt-1 block w-full" value="{{ old('provider_username') }}"
                                autocomplete="new-provider_username" />
                            <x-input-error :messages="$errors->integrationStoreBag->get('provider_username')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="provider_password" :value="__('Provider Password')" />
                            <x-text-input id="provider_password" name="provider_password" type="text"
                                class="mt-1 block w-full" value="{{ old('provider_password') }}"
                                autocomplete="new-provider_password" />
                            <x-input-error :messages="$errors->integrationStoreBag->get('provider_password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="user_username" :value="__('Client Username')" />
                            <x-text-input id="user_username" name="user_username" type="text"
                                class="mt-1 block w-full" value="{{ old('user_username') }}"
                                autocomplete="new-user_username" />
                            <x-input-error :messages="$errors->integrationStoreBag->get('user_username')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="user_password" :value="__('Client Password')" />
                            <x-text-input id="user_password" name="user_password" type="text"
                                class="mt-1 block w-full" value="{{ old('user_password') }}"
                                autocomplete="new-user_password" />
                            <x-input-error :messages="$errors->integrationStoreBag->get('user_password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="active" :value="__('Active')" />
                            <x-form-dropdown id="active" name="active">
                                <x-slot name="options">
                                    <option value="1" {{ old('active') ? 'selected' : '' }}>True
                                    </option>
                                    <option value="0"{{ old('active') ? '' : 'selected' }}>False
                                    </option>
                                </x-slot>
                            </x-form-dropdown>
                            <x-input-error :messages="$errors->integrationStoreBag->get('active')" class="mt-2" />
                        </div>

                        <div class="modal-footer">
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                <x-close-button id="closeForm">{{ __('Close') }}</x-primary-button>
                            </div>
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
                @if ($errors->integrationStoreBag->any())
                    $('#addIntegrationModal').modal('show');
                @endif
                @if ($errors->updateIntegrations->any())
                    var edit = "#editIntegrationModal_" + {{ $integration->id }};
                    $(edit).modal('show');
                @endif

                $('#addNew').on('click', function() {
                    $('#addIntegrationModal').modal('show');
                });
                $('#closeForm').on('click', function() {
                    $('#addIntegrationModal').modal('hide');
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
