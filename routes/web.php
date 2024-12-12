<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeoDataController;

Route::get('/map', function () {
    return view('map');
});

Route::get('/geo-data', [GeoDataController::class, 'index']);

Route::post('/geo-data', [GeoDataController::class, 'store']);

Route::get('/geojson/{filename}', function ($filename) {
    $path = public_path('geojson/' . $filename . '.geojson');
    if (!file_exists($path)) {
        abort(404, 'File not found');
    }
    return response()->file($path);
});
