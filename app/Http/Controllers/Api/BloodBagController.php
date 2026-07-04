<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodBag;
use App\Http\Requests\BloodBagRequest;
use App\Http\Resources\BloodBagResource;

use Illuminate\Support\Facades\Gate;

class BloodBagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = BloodBag::query()->with('refrigerator.bloodBank');

        // simple role-based filtering example
        if (auth()->user()->role === 'staff') {
            $query->whereHas('refrigerator', function ($q) {
                $q->where('blood_bank_id', auth()->user()->bloodBanks()->pluck('id')->toArray());
            });
        }

        return BloodBagResource::collection($query->paginate(15));
    }

    public function expiringSoon()
    {
        $bags = BloodBag::with('refrigerator')
            ->whereBetween('expiry_date', [now(), now()->addDay()])
            ->get();

        return BloodBagResource::collection($bags);
    }

    public function expired()
    {
        $bags = BloodBag::with('refrigerator')
            ->whereDate('expiry_date', '<', now()->toDateString())
            ->get();

        return BloodBagResource::collection($bags);
    }

    public function nearRiskPercentage()
    {
        $total = BloodBag::count();
        $near = BloodBag::whereBetween('expiry_date', [now(), now()->addDay()])->count();
        $percent = $total > 0 ? ($near / $total) * 100 : 0;
        return response()->json(['near_risk_percentage' => round($percent, 2)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = app(BloodBagRequest::class)->validated();

        $bag = BloodBag::create($data);

        return new BloodBagResource($bag->load('refrigerator'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bag = BloodBag::with('refrigerator.bloodBank')->findOrFail($id);
        return new BloodBagResource($bag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bag = BloodBag::findOrFail($id);
        $data = app(BloodBagRequest::class)->validated();
        $bag->update($data);
        return new BloodBagResource($bag->fresh('refrigerator'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bag = BloodBag::findOrFail($id);
        $bag->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
