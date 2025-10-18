<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MeilisearchService
{
    protected string $host;
    protected string $apiKey;

    public function __construct()
    {
        $this->host = config('services.meilisearch.host');
        $this->apiKey = config('services.meilisearch.key');
    }

    /**
     * Get headers for requests
     */
    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];
    }

    /**
     * Create index
     */
    public function createIndex(string $index, ?string $primaryKey = null)
    {
        $body = ['uid' => $index];

        if ($primaryKey) {
            $body['primaryKey'] = $primaryKey;
        }

        $response = Http::withHeaders($this->headers())
            ->post("{$this->host}/indexes", $body);

        return $this->handleResponse($response);
    }

    /**
     * Delete index
     */
    public function deleteIndex(string $index)
    {
        $response = Http::withHeaders($this->headers())
            ->delete("{$this->host}/indexes/{$index}");

        return $this->handleResponse($response);
    }

    /**
     * Check if index exists
     */
    public function indexExists(string $index): bool
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->host}/indexes/{$index}");

        return $response->successful();
    }

    /**
     * Index documents (add or replace)
     */
    public function index(string $index, array $documents)
    {
        $response = Http::withHeaders($this->headers())
            ->post("{$this->host}/indexes/{$index}/documents", $documents);

        return $this->handleResponse($response);
    }

    /**
     * Update documents
     */
    public function update(string $index, array $documents)
    {
        $response = Http::withHeaders($this->headers())
            ->put("{$this->host}/indexes/{$index}/documents", $documents);

        return $this->handleResponse($response);
    }

    /**
     * Delete a document
     */
    public function delete(string $index, string $id)
    {
        $response = Http::withHeaders($this->headers())
            ->delete("{$this->host}/indexes/{$index}/documents/{$id}");

        return $this->handleResponse($response);
    }

    /**
     * Get a document by ID
     */
    public function get(string $index, string $id)
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->host}/indexes/{$index}/documents/{$id}");

        return $this->handleResponse($response);
    }

    /**
     * Search documents
     */
    public function search(string $index, string $query = '', array $options = [])
    {
        $body = array_merge([
            'q' => $query,
        ], $options);

        $response = Http::withHeaders($this->headers())
            ->post("{$this->host}/indexes/{$index}/search", $body);

        return $this->handleResponse($response);
    }

    /**
     * Simple text search
     */
    public function searchSimple(string $index, string $query, int $limit = 10, array $additionalOptions = [])
    {
        return $this->search($index, $query, array_merge([
            'limit' => $limit,
        ], $additionalOptions));
    }

    /**
     * Multi-field search with attributes
     */
    public function multiSearch(string $index, string $query, array $searchableAttributes, int $limit = 10)
    {
        return $this->search($index, $query, [
            'attributesToSearchOn' => $searchableAttributes,
            'limit' => $limit,
        ]);
    }

    /**
     * Get index stats
     */
    public function getStats(string $index)
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->host}/indexes/{$index}/stats");

        return $this->handleResponse($response);
    }

    /**
     * Check server health
     */
    public function health()
    {
        $response = Http::get("{$this->host}/health");

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
        sleep(2);

        // Create index
        $createResult = $this->createIndex($index, 'id');

        if (!$createResult['success']) {
            return [
                'success' => false,
                'message' => 'Failed to create index',
                'error' => $createResult['error'] ?? 'Unknown error'
            ];
        }

        sleep(2);

        // Configure index settings
        Http::withHeaders($this->headers())
            ->patch("{$this->host}/indexes/{$index}/settings", [
                'searchableAttributes' => [
                    'title',
                    'content'
                ],
                'filterableAttributes' => [
                    'author',
                    'category_id',
                    'is_published',
                    'published_at',
                    'created_at'
                ],
                'sortableAttributes' => [
                    'views',
                    'published_at',
                    'created_at'
                ]
            ]);

        // Bulk index blogs
        $indexed = 0;
        $errors = [];

        \App\Models\Blog::chunk($chunkSize, function ($blogs) use ($index, &$indexed, &$errors) {
            $documents = [];

            foreach ($blogs as $blog) {
                $documents[] = [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'content' => $blog->content,
                    'author' => $blog->author,
                    'category_id' => $blog->category_id,
                    'views' => $blog->views,
                    'is_published' => $blog->is_published,
                    'published_at' => $blog->published_at?->timestamp,
                    'created_at' => $blog->created_at->timestamp,
                ];
            }

            $response = Http::withHeaders($this->headers())
                ->post("{$this->host}/indexes/{$index}/documents", $documents);

            if ($response->successful()) {
                $indexed += $blogs->count();
            } else {
                $errors[] = 'Document insert error for chunk';
            }
        });

        sleep(2);

        // Get stats
        $stats = Http::withHeaders($this->headers())
            ->get("{$this->host}/indexes/{$index}/stats")->json();
        $docCount = $stats['numberOfDocuments'] ?? 0;

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

        Log::error('Meilisearch Error', [
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
