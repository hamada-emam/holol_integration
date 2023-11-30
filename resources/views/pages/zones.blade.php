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

    <div class="mx-1 mx-md-2 mx-lg-3  mt-4">
        <form method="post" action="{{ route('submit') }}">
            @csrf
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
                        <x-primary-button type="submit">{{ __('Save') }}</x-primary-button>
                    </div>
                </div>
            </div>
            <table class="table table-striped-columns">
                <thead>
                    <tr>
                        <th scope="col">Code</th>
                        <th scope="col">Name</th>
                        <th scope="col">Mapped Name</th>
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
                                <a href="{{ route('zones', ['integrationId' => $integration['id'], 'parentId' => $item['id']]) }}"
                                    style="color: red">{{ $item['name'] }}</a>
                            </td>
                            <td>
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
