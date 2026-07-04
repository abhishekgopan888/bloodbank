<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refrigerator;
use App\Http\Requests\RefrigeratorRequest;
use App\Http\Resources\RefrigeratorResource;

class RefrigeratorController extends Controller
{
    public function index()
    {
        $query = Refrigerator::with('bloodBank')->paginate(15);
        return RefrigeratorResource::collection($query);
    }

    public function store(RefrigeratorRequest $request)
    {
        $data = $request->validated();
        $r = Refrigerator::create($data);
        return new RefrigeratorResource($r->load('bloodBank'));
    }

    public function show($id)
    {
        return new RefrigeratorResource(Refrigerator::with('bloodBank')->findOrFail($id));
    }

    public function update(RefrigeratorRequest $request, $id)
    {
        $r = Refrigerator::findOrFail($id);
        $r->update($request->validated());
        return new RefrigeratorResource($r->fresh('bloodBank'));
    }

    public function destroy($id)
    {
        $r = Refrigerator::findOrFail($id);
        $r->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
