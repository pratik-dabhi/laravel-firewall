<?php

namespace Pratik\Firewall\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Get the base query builder for firewall logs.
     */
    protected function getLogQuery()
    {
        $modelClass = config('firewall.logging.model');
        if ($modelClass && class_exists($modelClass)) {
            return $modelClass::query();
        }
        
        $table = config('firewall.logging.table', 'firewall_logs');
        return DB::table($table);
    }

    /**
     * Display the firewall dashboard.
     *
     * @return View
     */
    public function index(): View
    {
        $today = now()->startOfDay();
        
        [$todayBlocks, $totalLogs, $threats] = $this->getDashboardStats($today);

        return view('firewall::dashboard', [
            'todayBlocks' => $todayBlocks,
            'totalLogs' => $totalLogs,
            'threats' => $threats,
            'status' => $this->getFirewallStatus(),
        ]);
    }
    
    /**
     * Display firewall logs with filtering options.
     *
     * @param Request $request
     * @return View
     */
    public function logs(Request $request): View
    {
        $query = $this->getLogQuery()->orderByDesc('created_at');
        
        $this->applyFilters($query, $request);
        
        $logs = $query->paginate(50)->appends($request->query());
        
        return view('firewall::logs', [
            'logs' => $logs,
            'totalEntries' => $this->getLogQuery()->count(),
            'latestEvent' => $this->getLogQuery()
                ->select(['id', 'ip_address', 'action', 'created_at'])
                ->latest('created_at')
                ->first(),
        ]);
    }
    
    /**
     * Get dashboard statistics.
     *
     * @param \Carbon\Carbon $today
     * @return array
     */
    protected function getDashboardStats($today): array
    {
        $todayBlocks = $this->getLogQuery()->where('created_at', '>=', $today)->count();
        
        $totalLogs = $this->getLogQuery()->count();
        
        $threats = $this->getLogQuery()
            ->select('action')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('action')
            ->orderBy('total', 'desc')
            ->pluck('total', 'action')
            ->toArray();
        
        return [$todayBlocks, $totalLogs, $threats];
    }
    
    /**
     * Get firewall status from configuration.
     *
     * @return bool
     */
    protected function getFirewallStatus(): bool
    {
        return (bool) config('firewall.enabled', true);
    }
    
    /**
     * Apply filters to the logs query.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param Request $request
     * @return void
     */
    protected function applyFilters($query, Request $request): void
    {
        // Search filter
        if ($search = $request->input('search')) {
            $query->where('ip_address', 'LIKE', "%{$search}%");
        }
        
        // Action filter
        if ($action = $request->input('action')) {
            if ($action !== 'all') {
                $query->where('action', $action);
            }
        }
        
        // Date filter
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }
    }
}