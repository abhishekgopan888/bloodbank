<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Blood Bank API']);
});

Route::get('/docs', function () {
    return view('docs.index', [
        'specUrl' => url('/api/docs/openapi.json'),
        'yamlUrl' => url('/docs/openapi.yaml'),
    ]);
});

Route::get('/docs/openapi.yaml', function () {
    $path = base_path('docs/openapi.yaml');
    if (!file_exists($path)) {
        abort(404);
    }

    $content = file_get_contents($path);
    return response($content, 200)->header('Content-Type', 'application/x-yaml');
});
