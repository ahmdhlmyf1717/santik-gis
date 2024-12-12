<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class GeoDataController extends Controller
{
    public function index()
    {
        $data = DB::table('geo_data')->get();

        $response = $data->map(function ($item) {
            return [
                'name' => $item->name,
                'geometry' => json_encode([
                    'type' => 'Polygon',
                    'coordinates' => json_decode($item->geometry)
                ])
            ];
        });

        return response()->json($response);
    }
}
