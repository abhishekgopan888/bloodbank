<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemperatureLog;
use App\Models\Refrigerator;
use App\Jobs\ProcessCriticalAlertJob;
use Illuminate\Support\Facades\Bus;

class TemperatureLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TemperatureLog::latest()->paginate(50);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'refrigerator_id' => 'required|exists:refrigerators,id',
            'temperature' => 'required|numeric',
            'recorded_at' => 'nullable|date',
        ]);

        $log = TemperatureLog::create([
            'refrigerator_id' => $data['refrigerator_id'],
            'temperature' => $data['temperature'],
            'recorded_at' => $data['recorded_at'] ?? now(),
        ]);

        // dispatch processing job
        ProcessCriticalAlertJob::dispatch($data['refrigerator_id']);

        return response()->json($log, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return TemperatureLog::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $log = TemperatureLog::findOrFail($id);
        $data = $request->validate([
            'temperature' => 'nullable|numeric',
            'recorded_at' => 'nullable|date',
        ]);
        $log->update($data);
        return response()->json($log);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $log = TemperatureLog::findOrFail($id);
        $log->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function stats($id)
    {
        $refrigerator = Refrigerator::findOrFail($id);

        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $query = TemperatureLog::where('refrigerator_id', $id)
            ->whereBetween('recorded_at', [$todayStart, $todayEnd]);

        $total = $query->count();
        $avg = $query->avg('temperature') ?: 0;
        $max = $query->max('temperature') ?: 0;
        $min = $query->min('temperature') ?: 0;

        $unsafeMinutes = TemperatureLog::where('refrigerator_id', $id)
            ->whereBetween('recorded_at', [$todayStart, $todayEnd])
            ->where('temperature', '>', 6)
            ->count();

        $risk = $total > 0 ? ($unsafeMinutes / $total) * 100 : 0;

        return response()->json([
            'refrigerator_id' => $id,
            'average' => round($avg, 2),
            'highest' => $max,
            'lowest' => $min,
            'unsafe_minutes' => $unsafeMinutes,
            'total_minutes' => $total,
            'risk_percentage' => round($risk, 2),
        ]);
    }
}
