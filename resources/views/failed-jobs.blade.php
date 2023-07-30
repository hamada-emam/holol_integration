<x-app-layout>
    <div class="container-fluid row">
        {{-- table of failed jobs --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="my-2 d-flex justify-content-end"> <a class="btn btn-primary" style="width: 100px"
                href="{{ route('failed.retry') }}">retry all</a></div>
        <div style="overflow-x: scroll;">
            <table class="table table-hover table-responsive" style="white-space: nowrap;">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Failed At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @foreach ($data as $key => $job)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td><span class="badge bg-success" style="width: 100px">{{ $job['shipmentCode'] }}</span>
                        </td>
                        <td>{{ $job['failedAt'] }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('failed.retry', $job['failedJobID']) }}">retry</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    </div>
</x-app-layout>
