<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\ElasticsearchService;
use App\Services\MeilisearchService;
use Illuminate\Http\Request;
use App\Http\Requests\DeleteIndexRequest;

class IndexController extends Controller
{
    protected ElasticsearchService $elasticsearch;
    protected MeilisearchService $meilisearch;

    public function __construct(ElasticsearchService $elasticsearch, MeilisearchService $meilisearch)
    {
        $this->elasticsearch = $elasticsearch;
        $this->meilisearch = $meilisearch;
    }

    /**
     * Delete index from both Elasticsearch and Meilisearch
     */
    public function delete(DeleteIndexRequest $request)
    {
        try {
            $index = $request->input('index');

            $results = [];

            // Delete from Elasticsearch
            $elasticResponse = $this->elasticsearch->deleteIndex($index);
            $results['elasticsearch'] = [
                'success' => $elasticResponse['success'] ?? false,
                'status' => $elasticResponse['status'] ?? 0,
                'message' => $elasticResponse['success'] ? 'Index deleted successfully' : 'Failed to delete index',
            ];

            // Delete from Meilisearch
            $meiliResponse = $this->meilisearch->deleteIndex($index);
            $results['meilisearch'] = [
                'success' => $meiliResponse['success'] ?? false,
                'status' => $meiliResponse['status'] ?? 0,
                'message' => $meiliResponse['success'] ? 'Index deleted successfully' : 'Failed to delete index',
            ];

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting indexes',
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Index blogs to both Elasticsearch and Meilisearch
     */
    public function create(Request $request)
    {
        try {
            $chunkSize = $request->input('chunk_size', 500);

            $totalBlogs = Blog::query()->count();

            if ($totalBlogs === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No blogs found to index'
                ], 400);
            }

            $results = [
                'total_blogs' => $totalBlogs,
                'elasticsearch' => [],
                'meilisearch' => [],
                'processing_time' => []
            ];

            // Index to Elasticsearch
            $elasticStart = microtime(true);
            $results['elasticsearch'] = $this->elasticsearch->indexBlogs($chunkSize);
            $elasticEnd = microtime(true);
            $results['processing_time']['elasticsearch'] = round(($elasticEnd - $elasticStart) * 1000, 2);

            // Index to Meilisearch
            $meiliStart = microtime(true);
            $results['meilisearch'] = $this->meilisearch->indexBlogs($chunkSize);
            $meiliEnd = microtime(true);
            $results['processing_time']['meilisearch'] = round(($meiliEnd - $meiliStart) * 1000, 2);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while indexing blogs',
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
}
