<?php

use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [MapController::class, 'index']);
Route::get('/api/bangunan/{id}', function ($id) {
    return App\Models\Bangunan::findOrFail($id);
});

Route::put('/api/bangunan/{id}', function ($id, Request $request) {
    $b = App\Models\Bangunan::findOrFail($id);
    $b->x = $request->x;
    $b->y = $request->y;
    $b->save();
    return response()->json(['status' => 'success']);
});

Route::put('/api/petak/{id}', function ($id, Request $request) {
    $p = App\Models\Petak::findOrFail($id);
    $p->x = $request->x;
    $p->y = $request->y;
    $p->save();
    return response()->json(['status' => 'success']);
});


Route::put('/api/saluran/{id}', function ($id, Request $request) {
    $s = \App\Models\Saluran::findOrFail($id);
    $s->x1 = $request->x1;
    $s->y1 = $request->y1;
    $s->x2 = $request->x2;
    $s->y2 = $request->y2;
    $s->save();

    return response()->json(['status' => 'success']);
});
Route::get('/api/petak/{id}', function ($id) {
    return \App\Models\Petak::findOrFail($id);
});

Route::get('/api/saluran/{id}', function ($id) {
    return \App\Models\Saluran::findOrFail($id);
});
