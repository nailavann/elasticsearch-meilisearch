<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ElasticsearchService
{
    protected string $host;

    public function __construct()
    {
        $this->host = config('services.elasticsearch.host');
    }

    /**
     * Create index
     */
    public function createIndex(string $index, array $mappings = [], array $settings = [])
    {
        $body = [];
        
        if (!empty($mappings)) {
            $body['mappings'] = $mappings;
        }
        
        if (!empty($settings)) {
            $body['settings'] = $settings;
        }

        $response = Http::put("{$this->host}/{$index}", $body);

        return $this->handleResponse($response);
    }

    /**
     * Delete index
     */
    public function deleteIndex(string $index)
    {
        $response = Http::delete("{$this->host}/{$index}");

        return $this->handleResponse($response);
    }

    /**
     * Check if index exists
     */
    public function indexExists(string $index): bool
    {
        $response = Http::head("{$this->host}/{$index}");

        return $response->successful();
    }

    /**
     * Index a document
     */
    public function index(string $index, array $document, ?string $id = null)
    {
        if ($id) {
            $response = Http::put("{$this->host}/{$index}/_doc/{$id}", $document);
        } else {
            $response = Http::post("{$this->host}/{$index}/_doc", $document);
        }

        return $this->handleResponse($response);
    }

    /**
     * Update a document
     */
    public function update(string $index, string $id, array $document)
    {
        $response = Http::post("{$this->host}/{$index}/_update/{$id}", [
            'doc' => $document
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Delete a document
     */
    public function delete(string $index, string $id)
    {
        $response = Http::delete("{$this->host}/{$index}/_doc/{$id}");

        return $this->handleResponse($response);
    }

    /**
     * Get a document by ID
     */
    public function get(string $index, string $id)
    {
        $response = Http::get("{$this->host}/{$index}/_doc/{$id}");

        return $this->handleResponse($response);
    }

    /**
     * Search documents
     */
    public function search(string $index, array $query = [], int $from = 0, int $size = 10)
    {
        $body = [
            'from' => $from,
            'size' => $size,
        ];

        if (!empty($query)) {
            $body['query'] = $query;
        }

        $response = Http::post("{$this->host}/{$index}/_search", $body);

        return $this->handleResponse($response);
    }

    /**
     * Simple text search
     */
    public function searchSimple(string $index, string $field, string $query, int $from = 0, int $size = 10)
    {
        return $this->search($index, [
            'match' => [
                $field => $query
            ]
        ], $from, $size);
    }

    /**
     * Multi-field search
     */
    public function multiSearch(string $index, array $fields, string $query, int $from = 0, int $size = 10)
    {
        return $this->search($index, [
            'multi_match' => [
                'query' => $query,
                'fields' => $fields
            ]
        ], $from, $size);
    }

    /**
     * Check cluster health
     */
    public function health()
    {
        $response = Http::get("{$this->host}/_cluster/health");

        return $this->handleResponse($response);
    }

    /**
     * Index blogs from database
     */
    public function indexBlogs(int $chunkSize = 500): array
    {
        $index = 'blogs';

        // Delete existing index
        $this->deleteIndex($index);

        // Create index with mapping
        $mapping = [
            'properties' => [
                'title' => ['type' => 'text', 'analyzer' => 'standard'],
                'content' => ['type' => 'text', 'analyzer' => 'standard'],
                'author' => ['type' => 'keyword'],
                'category_id' => ['type' => 'integer'],
                'views' => ['type' => 'integer'],
                'is_published' => ['type' => 'boolean'],
                'published_at' => ['type' => 'date'],
                'created_at' => ['type' => 'date'],
            ]
        ];

        $createResult = $this->createIndex($index, $mapping);

        if (!$createResult['success']) {
            return [
                'success' => false,
                'message' => 'Failed to create index',
                'error' => $createResult['error'] ?? 'Unknown error'
            ];
        }

        // Bulk index blogs
        $indexed = 0;
        $errors = [];

        \App\Models\Blog::chunk($chunkSize, function ($blogs) use ($index, &$indexed, &$errors) {
            $bulkData = '';

            foreach ($blogs as $blog) {
                $action = json_encode([
                    'index' => [
                        '_index' => $index,
                        '_id' => $blog->id
                    ]
                ]);

                $document = json_encode([
                    'title' => $blog->title,
                    'content' => $blog->content,
                    'author' => $blog->author,
                    'category_id' => $blog->category_id,
                    'views' => $blog->views,
                    'is_published' => $blog->is_published,
                    'published_at' => $blog->published_at?->toIso8601String(),
                    'created_at' => $blog->created_at->toIso8601String(),
                ]);

                $bulkData .= $action . "\n" . $document . "\n";
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/x-ndjson'
            ])->withBody($bulkData, 'application/x-ndjson')
              ->post("{$this->host}/_bulk");

            if ($response->successful()) {
                $indexed += $blogs->count();
            } else {
                $errors[] = 'Bulk insert error for chunk';
            }
        });

        // Get stats
        $stats = Http::get("{$this->host}/{$index}/_stats")->json();
        $docCount = $stats['indices'][$index]['total']['docs']['count'] ?? 0;

        return [
            'success' => true,
            'indexed' => $indexed,
            'documents_in_index' => $docCount,
            'errors' => $errors
        ];
    }

    /**
     * Handle response
     */
    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'status' => $response->status()
            ];
        }

        Log::error('Elasticsearch Error', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return [
            'success' => false,
            'error' => $response->json() ?? $response->body(),
            'status' => $response->status()
        ];
    }
}

