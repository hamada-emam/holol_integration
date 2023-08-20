<x-app-layout>
    <div class="container-fluid row">
        {{-- table of failed jobs --}}
        @if ($message = Session::get('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)" class="alert alert-success">
                <p class="text-sm text-gray-600">{{ $message }}</p>
            </div>
        @endif

        <div class="my-2 d-flex justify-content-end"> <a class="btn btn-primary" style="width: 150px"
                href="{{ route('client.create') }}">Add Client</a></div>
        <div style="overflow-x: scroll;">
            <table class="table table-hover table-responsive" style="white-space: nowrap;">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Company</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Url</th>
                        <th>Zones</th>
                        <th>Failed jobs</th>
                        {{-- <th>token</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                @foreach ($clients as $key => $client)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $client['company_code'] }} </td>
                        <td>{{ $client['client_code'] }} </td>
                        <td>{{ $client['type_code'] }} </td>
                        <td>{{ $client['url'] }} </td>
                        <td><a href="{{ route('zones', ['client_id' => $key]) }}"><span class="badge bg-primary"
                                    style="width: 100px">zones</span></a></td>
                        <td><a href="{{ route('failed', ['client_id' => $key]) }}"><span class="badge bg-danger"
                                    style="width: 100px">failed jobs</span></a></td>
                        {{-- <td>{{ $client['token'] }} </td> --}}
                        <td class="d-flex">
                            <a class="btn btn-primary mx-2"
                                href="{{ route('client.edit', ['id' => $client->id]) }}">Edit</a>
                            {{-- <a class="btn btn-danger" href="{{ route('client.delete', $client['id']) }}">delete</a> --}}
                            <form action="{{ route('client.delete', ['id' => $client->id]) }}" method="POST">
                                @csrf
                                <!-- Button to trigger delete with confirmation alert -->
                                <button class="btn btn-danger bg-danger text-white" type="submit"
                                    onclick="return confirmDelete()">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    </div>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this item?');
        }
    </script>
</x-app-layout>
