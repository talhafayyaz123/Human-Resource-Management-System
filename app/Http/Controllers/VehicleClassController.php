<?php

namespace App\Http\Controllers;

use App\Models\VehicleClass;
use Illuminate\Http\Request;

class VehicleClassController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $vehicle_classes = VehicleClass::all();
        $vehicle_classes = $vehicle_classes->map(function ($vehicle_class) {
            return [
                'id' => $vehicle_class->id,
                'name' => $vehicle_class->name,
            ];
        });
        return response()->json([
            'data' => $vehicle_classes,
        ]);
    }
}
