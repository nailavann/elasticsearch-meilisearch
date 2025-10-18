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
        $response = $this->elasticsearch->health();

        return response()->json([
            'service' => 'Elasticsearch',
            'status' => $response['success'] ? 'healthy' : 'unhealthy',
            'success' => $response['success'],
            'data' => $response['data'] ?? null,
            'error' => $response['error'] ?? null,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Check Meilisearch health
     */
    public function meilisearch()
    {
        $response = $this->meilisearch->health();

        return response()->json([
            'service' => 'Meilisearch',
            'status' => $response['success'] ? 'healthy' : 'unhealthy',
            'success' => $response['success'],
            'data' => $response['data'] ?? null,
            'error' => $response['error'] ?? null,
            'timestamp' => now()->toISOString()
        ]);
    }
}
