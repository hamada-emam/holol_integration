<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/users">Users</a></li>
                </ol>
            </div>
            <div class="col d-flex justify-content-end">
                <div class="text-right">
                    <!-- Add New User -->
                    <x-primary-button type="button" data-target="#addUserModal" id="addNew" data-toggle="modal"
                        data-toggle="modal">{{ __('Add New') }}</x-primary-button>

                </div>
            </div>
        </div>
        <table class="table table-striped-columns">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Backend Url</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['username'] }}</td>
                        <td>{{ $user['backend_url'] }}</td>
                        <td>{{ date('d-m-Y', strtotime($user['created_at'])) }}</td>
                        <td>
                            <div class="row">
                                <div class="col-auto">
                                    <x-edit-button type="button" data-target="#editUserModal_{{ $user->id }}"
                                        id="editUser" data-toggle="modal"
                                        data-toggle="modal">{{ __('Edit') }}</x-edit-button>
                                </div>
                                <div class="col-auto">
                                    <x-danger-button type="button" data-target="#deleteUserModal" id="editUser"
                                        data-toggle="modal" data-toggle="modal">{{ __('Delete') }}</x-edit-button>
                                </div>
                        </td>
                    </tr>
                    <!-- Modal for delete user -->
                    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog"
                        aria-labelledby="editUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete User</h5>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-2 fs-6">Are you sure you want to delete: <span
                                            class="text-danger">{{ $user->name }}</span> ? </h1>
                                    <form action="{{ route('users.delete', ['user' => $user->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-text-input id="id{{ $user->id }}" name="id"
                                            value="{{ $user->id }}" type="hidden" />
                                        <div class="modal-footer">
                                            <div class="flex items-center gap-4">
                                                <x-primary-button type="submit" id="deleteUser_{{ $user->id }}"
                                                    data-toggle="modal"
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
                    <!-- Modal for edit user -->
                    <div class="modal fade" id="editUserModal_{{ $user->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                </div>
                                <div class="modal-body">
                                    <!-- Your form for adding a new user -->
                                    <form method="post" action="{{ route('users.update', ['user' => $user->id]) }}"
                                        class="mt-6 space-y-6">
                                        @csrf
                                        @method('put')
                                        <x-text-input id="id{{ $user->id }}" name="id"
                                            value="{{ $user->id }}" type="hidden" />
                                        <div>
                                            <x-input-label for="name" :value="__('Name')" />
                                            <x-text-input id="name_{{ $user->id }}" name="name" type="text"
                                                value="{{ $user['name'] }}" class="mt-1 block w-full"
                                                autocomplete="new-name" />
                                            <x-input-error :messages="$errors->updateUsers->get('name')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="username" :value="__('UserName')" />
                                            <x-text-input id="username_{{ $user->id }}" name="username"
                                                type="text" value="{{ $user['username'] }}"
                                                class="mt-1 block w-full" autocomplete="new-username" />
                                            <x-input-error :messages="$errors->updateUsers->get('username')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="backend_url" :value="__('Backend Url')" />
                                            <x-text-input id="backend_url_{{ $user->id }}" name="backend_url"
                                                type="text" value="{{ $user['backend_url'] }}"
                                                class="mt-1 block w-full" autocomplete="new-backend_url" />
                                            <x-input-error :messages="$errors->updateUsers->get('backend_url')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="password" :value="__('Password')" />
                                            <x-text-input id="password_{{ $user->id }}" name="password"
                                                type="text" class="mt-1 block w-full"
                                                autocomplete="new-password" />
                                            <x-input-error :messages="$errors->updateUsers->get('password')" class="mt-2" />
                                        </div>

                                        <div class="modal-footer">
                                            <div class="flex items-center gap-4">
                                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                                <x-close-button
                                                    id="closeEditForm_{{ $user->id }}">{{ __('Close') }}</x-primary-button>
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
    <!-- Modal for adding new user -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                </div>
                <div class="modal-body">
                    <!-- Your form for adding a new user -->
                    <form id="adduser" method="post" action="{{ route('users.store') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('post')
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ old('name') }}" autocomplete="new-name" />
                            <x-input-error :messages="$errors->userStoreBag->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="username" :value="__('UserName')" />
                            <x-text-input id="username" value="{{ old('username') }}" name="username"
                                type="text" class="mt-1 block w-full" autocomplete="new-username" />
                            <x-input-error :messages="$errors->userStoreBag->get('username')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="backend_url" :value="__('Backend Url')" />
                            <x-text-input id="backend_url" name="backend_url" type="text"
                                value="{{ old('backend_url') }}" class="mt-1 block w-full"
                                autocomplete="new-backend_url" />
                            <x-input-error :messages="$errors->userStoreBag->get('backend_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" name="password" type="text" class="mt-1 block w-full"
                                value="{{ old('password') }}" autocomplete="new-password" />
                            <x-input-error :messages="$errors->userStoreBag->get('password')" class="mt-2" />
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
                @if ($errors->userStoreBag->any())
                    $('#addUserModal').modal('show');
                @endif
                @if ($errors->updateUsers->any())
                    var edit = "#editUserModal_" + {{ old('id') }};
                    $(edit).modal('show');
                @endif

                $('#addNew').on('click', function() {
                    $('#addUserModal').modal('show');
                });
                $('#closeForm').on('click', function() {
                    $('#addUserModal').modal('hide');
                });
                $('#closeDeleteForm').on('click', function() {
                    $('#deleteUserModal').modal('hide');
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
