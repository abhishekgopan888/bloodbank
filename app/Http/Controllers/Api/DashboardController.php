<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodBag;
use App\Models\Refrigerator;
use App\Models\TemperatureLog;
use App\Models\Alert;


class DashboardController extends Controller
{
    public function index()
    {
        $totalBags = BloodBag::count();

        $availableByGroup = BloodBag::selectRaw('blood_group, sum(quantity) as total')
            ->where('status', 'Available')
            ->groupBy('blood_group')
            ->get()
            ->keyBy('blood_group')
            ->map(fn($r) => $r->total);

        $activeRefrigerators = Refrigerator::where('status', 'active')->count();

        $expiredBags = BloodBag::whereDate('expiry_date', '<', now()->toDateString())->count();

        $criticalAlerts = Alert::where('type', 'critical_temperature')->latest()->take(10)->get();

        // average temperature today across refrigerators
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $avgTemp = TemperatureLog::whereBetween('recorded_at', [$todayStart, $todayEnd])->avg('temperature') ?: 0;

        // refrigerator health score (simple heuristic): 100 - avg risk
        $riskPercent = 0;
        $totalLogs = TemperatureLog::whereBetween('recorded_at', [$todayStart, $todayEnd])->count();
        if ($totalLogs > 0) {
            $unsafe = TemperatureLog::whereBetween('recorded_at', [$todayStart, $todayEnd])->where('temperature', '>', 6)->count();
            $riskPercent = ($unsafe / $totalLogs) * 100;
        }

        $healthScore = max(0, 100 - round($riskPercent, 2));

        return response()->json([
            'total_bags' => $totalBags,
            'available_by_group' => $availableByGroup,
            'refrigerator_health_score' => $healthScore,
            'critical_alerts' => $criticalAlerts,
            'average_temperature_today' => round($avgTemp, 2),
            'total_expired_bags' => $expiredBags,
            'active_refrigerators' => $activeRefrigerators,
        ]);
    }
}
