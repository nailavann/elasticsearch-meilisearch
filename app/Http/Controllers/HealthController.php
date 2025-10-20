<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use App\Services\MeilisearchService;

class HealthController extends Controller
{
    protected ElasticsearchService $elasticsearch;
    protected MeilisearchService $meilisearch;

    public function __construct(ElasticsearchService $elasticsearch, MeilisearchService $meilisearch)
    {
        $this->elasticsearch = $elasticsearch;
        $this->meilisearch = $meilisearch;
    }

    /**
     * Check Elasticsearch health
     */
    public function elasticsearch()
    {
        try {
            $response = $this->elasticsearch->health();

            return response()->json([
                'service' => 'Elasticsearch',
                'status' => $response['success'] ? 'healthy' : 'unhealthy',
                'success' => $response['success'],
                'data' => $response['data'] ?? null,
                'error' => $response['error'] ?? null,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'service' => 'Elasticsearch',
                'status' => 'unhealthy',
                'success' => false,
                'data' => null,
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Check Meilisearch health
     */
    public function meilisearch()
    {
        try {
            $response = $this->meilisearch->health();

            return response()->json([
                'service' => 'Meilisearch',
                'status' => $response['success'] ? 'healthy' : 'unhealthy',
                'success' => $response['success'],
                'data' => $response['data'] ?? null,
                'error' => $response['error'] ?? null,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'service' => 'Meilisearch',
                'status' => 'unhealthy',
                'success' => false,
                'data' => null,
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
}
