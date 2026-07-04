<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodBank;
use App\Http\Requests\BloodBankRequest;
use App\Http\Resources\BloodBankResource;

class BloodBankController extends Controller
{
    public function index()
    {
        $banks = BloodBank::withCount('refrigerators')->paginate(15);
        return BloodBankResource::collection($banks);
    }

    public function store(BloodBankRequest $request)
    {
        $b = BloodBank::create($request->validated());
        return new BloodBankResource($b);
    }

    public function show($id)
    {
        return new BloodBankResource(BloodBank::with('refrigerators')->findOrFail($id));
    }

    public function update(BloodBankRequest $request, $id)
    {
        $b = BloodBank::findOrFail($id);
        $b->update($request->validated());
        return new BloodBankResource($b->fresh('refrigerators'));
    }

    public function destroy($id)
    {
        $b = BloodBank::findOrFail($id);
        $b->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
