<?php

namespace App\Http\Controllers\Client\Core;

use Accurate\Shipping\Enums\Fields\ZoneField;
use Accurate\Shipping\Models\Filters\ListZonesFilter;
use Accurate\Shipping\Services\Zone as ServicesZone;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone as ModelsZone;
use App\Models\Area;
use App\Models\City;

class Zone extends Controller
{
    /**
     * 
     *
     * @param [type] $id
     * @return void
     */
    function index($id = null)
    {
        /** @var mixed */
        $response = (new ServicesZone())->listZones(input: new ListZonesFilter(
            parentId: $id,
        ), output: [ZoneField::ID, ZoneField::CODE, ZoneField::NAME]);

        $zones = $response->original['data']['listZonesDropdown'];

        foreach ($zones as &$zone) {
            $zone['mapped_zone'] =  ModelsZone::where('parent_id', $id)->where('zone_id', $zone['id'])->first()?->mapped_zone;
        }
        $mappedZones = $id ? Area::whereHas('city', fn ($q) => $q->whereHas('zones'))->get() : City::all();
        $parentId = $id;
        return view('zones', compact(['zones', 'mappedZones', 'parentId']));
    }

    /**
     * 
     *
     * @param Request $request
     * @return void
     */
    function store(Request $request)
    {
        $data = $request->all();
        foreach ($this->prepareData($data) as $key => $zone) {
            $newZone = ModelsZone::where('zone_id', $zone['zone_id'])->where('parent_id', $zone['parent_id'])->first() ?? new ModelsZone();
            $newZone->forceFill($zone);
            $newZone->save();
        }
        return redirect('zones');
    }

    /**
     * 
     *
     * @param [type] $data
     * @return array
     */
    function prepareData($data): array
    {
        unset($data['_token']);
        $elements = collect($data)->chunk(3)->map(function ($chunk) {
            $groupedData = [];
            foreach ($chunk as $key => $value) {
                $groupedData[explode('-', $key)[0]] = $value;
            }
            return $groupedData;
        })->toArray();

        return $elements;
    }
}
