<?php

namespace App\Http\Controllers\Core;

use App\Enums\ProviderTypeCode;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderController extends Controller
{

    /**
     * 
     *
     * @param [type] $id
     * @return void
     */
    function index()
    {
        $providers = Provider::all();
        return view('providers', compact(['providers']));
    }

    // /**
    //  * 
    //  *
    //  * @param Request $request
    //  * @return void
    //  */
    // function store(Request $request)
    // {
    //     // $data = $request->all();
    //     // foreach ($this->prepareData($data) as $key => $zone) {
    //     //     $newZone = ModelsZone::where('zone_id', $zone['zone_id'])->where('parent_id', $zone['parent_id'])->first() ?? new ModelsZone();
    //     //     $newZone->forceFill($zone);
    //     //     $newZone->save();
    //     // }
    //     return redirect('zones');
    // }

    // /**
    //  * 
    //  *
    //  * @param [type] $data
    //  * @return array
    //  */
    // function prepareData($data): array
    // {
    //     unset($data['_token']);
    //     $elements = collect($data)->chunk(3)->map(function ($chunk) {
    //         $groupedData = [];
    //         foreach ($chunk as $key => $value) {
    //             $groupedData[explode('-', $key)[0]] = $value;
    //         }
    //         return $groupedData;
    //     })->toArray();

    //     return $elements;
    // }
}
