<?php

namespace App\Http\Controllers;

use App\Services\StatsService;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    protected StatsService $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Get blog statistics from both search engines
     */
    public function blogs(): JsonResponse
    {
        try {
            $results = $this->statsService->getBlogStats();

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching blog statistics',
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }


}
