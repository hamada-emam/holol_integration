<x-app-layout>
    @section('css')
        <style>
            .listInput {
                outline: none;
                border: 0;
                border-bottom: 1px solid rgba(0, 0, 0, 0.42);
            }
        </style>
    @endsection
    <div class="container">

        <form method="post" action="{{ route('submit') }}">
            @csrf
            <div class="my-2 d-flex justify-content-end"><button class="btn btn-success btn-outline-success" type="submit"
                    style="width: 80px">save</button></div>
            <table class="table table-striped-columns">
                <thead>
                    <tr>
                        {{-- <th scope="col">#</th> --}}
                        <th scope="col">Code</th>
                        <th scope="col">Name</th>
                        <th scope="col">Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($zones as $item)
                        <tr>
                            <input type="hidden" class="parent_id-{{ $item['code'] }}"
                                name="parent_id-{{ $item['code'] }}" value="{{ $parentId }}">
                            <input type="hidden" class="zone_id-{{ $item['code'] }}" name="zone_id-{{ $item['code'] }}"
                                value="{{ $item['id'] }}">
                            <td>{{ $item['id'] }}</td>
                            <td>
                                <a href="{{ route('zones', [$item['id']]) }}" style="color: red">{{ $item['name'] }}</a>
                            </td>
                            <td>
                                {{-- {{ die($item['mapped_zone']) }} --}}
                                <input list="brow" class="mapped_zone-{{ $item['id'] }} listInput form-control"
                                    name="mapped_zone-{{ $item['id'] }}" value="{{ $item['mapped_zone'] }}">
                                <datalist id="brow">
                                    @foreach ($mappedZones as $mappedzone)
                                        <option value="{{ $mappedzone['name'] }}" label="{{ $mappedzone['name'] }}">
                                    @endforeach
                                </datalist>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
    @section('js')
        <script></script>
    @endsection
</x-app-layout>
