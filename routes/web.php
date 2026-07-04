<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Swagger UI for API docs
Route::get('/docs', function () {
    return file_get_contents(public_path('swagger.html'));
})->name('swagger.ui');

Route::get('/docs/openapi.yaml', function () {
    $path = base_path('docs/openapi.yaml');
    if (!file_exists($path)) {
        abort(404);
    }

    $content = file_get_contents($path);
    return response($content, 200)->header('Content-Type', 'application/x-yaml');
});
