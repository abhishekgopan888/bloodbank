<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alert;
use App\Http\Resources\BloodBagResource;

class AlertController extends Controller
{
    public function index()
    {
        return response()->json(Alert::latest()->paginate(25));
    }

    public function show($id)
    {
        return response()->json(Alert::with('refrigerator')->findOrFail($id));
    }

    public function resolve($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->update(['metadata' => array_merge($alert->metadata ?? [], ['resolved_at' => now()->toDateTimeString()])]);
        return response()->json(['message' => 'Resolved']);
    }
}
