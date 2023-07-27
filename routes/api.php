<?php

use Accurate\Shipping\Enums\Fields\ZoneField;
use Accurate\Shipping\Models\Filters\ListZonesFilter;
use Accurate\Shipping\Services\Zone;
use App\Http\Controllers\Client\Imile\Customer\Order;
use App\Models\Shipment;
use App\Models\Zone as ModelsZone;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

$key = config('app.secre_key');
Route::post("/webhook_callback/$key", [Order::class, 'callback']);

Route::get('/read_synced', function () {
    return Shipment::all();
});

Route::get('/sync_zones', function () {
    ModelsZone::truncate();

    /** @var mixed */
    $response = (new Zone())->listZones(input: new ListZonesFilter(
        parentId: null,
    ), output: [ZoneField::ID, ZoneField::CODE, ZoneField::NAME]);
    $zones = $response->original['data']['listZonesDropdown'];

    foreach ($zones  as $key => $zone) {
        $newZone = new ModelsZone();
        $newZone->zone_id = $zone['id'];
        $newZone->mapped_zone = $zone['name'];
        $newZone->parent_id = null;
        $newZone->save();

        /** @var mixed */
        $responseForsubZones = (new Zone())->listZones(input: new ListZonesFilter(
            parentId: $zone['id'],
        ), output: [ZoneField::ID, ZoneField::CODE, ZoneField::NAME]);
        $subZones = $responseForsubZones->original['data']['listZonesDropdown'];

        foreach ($subZones  as $key => $subZone) {
            $newSubZone = new ModelsZone();
            $newSubZone->zone_id = $subZone['id'];
            $newSubZone->mapped_zone = $subZone['name'];
            $newSubZone->parent_id = $zone['id'];
            $newSubZone->save();
        }
    }
    return ModelsZone::all();
});
