<?php

namespace App\Services;

use App\Models\Blog;

class StatsService
{
    protected ElasticsearchService $elasticsearch;
    protected MeilisearchService $meilisearch;

    public function __construct(ElasticsearchService $elasticsearch, MeilisearchService $meilisearch)
    {
        $this->elasticsearch = $elasticsearch;
        $this->meilisearch = $meilisearch;
    }

    /**
     * Get all blog statistics from database, elasticsearch and meilisearch
     */
    public function getBlogStats(): array
    {
        return [
            'database' => $this->getDatabaseStats(),
            'elasticsearch' => $this->getElasticsearchStats(),
            'meilisearch' => $this->getMeilisearchStats(),
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Get database statistics
     */
    private function getDatabaseStats(): array
    {
        $totalBlogs = Blog::query()->count();

        return [
            'total_blogs' => $totalBlogs,
        ];
    }

    /**
     * Get Elasticsearch statistics
     */
    private function getElasticsearchStats(): array
    {
        try {
            $response = $this->elasticsearch->getStats('blogs');

            if ($response['success']) {
                $data = $response['data'];
                $sizeInBytes = $data['indices']['blogs']['total']['store']['size_in_bytes'] ?? 0;

                return [
                    'success' => true,
                    'total_documents' => $data['indices']['blogs']['total']['docs']['count'] ?? 0,
                    'size' => $this->formatBytes($sizeInBytes),
                    'size_in_bytes' => $sizeInBytes,
                ];
            }

            return [
                'success' => false,
                'error' => $response['error'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get Meilisearch statistics
     */
    private function getMeilisearchStats(): array
    {
        try {
            $response = $this->meilisearch->getStats('blogs');

            if ($response['success']) {
                $data = $response['data'];

                // Debug: Log the actual response to see what's available
                \Log::info('Meilisearch stats response', ['data' => $data]);

                $sizeInBytes = $this->extractMeilisearchSize($data);

                return [
                    'success' => true,
                    'total_documents' => $data['numberOfDocuments'] ?? 0,
                    'size' => $this->formatBytes($sizeInBytes),
                    'size_in_bytes' => $sizeInBytes,
                    'raw_response' => $data, // For debugging
                ];
            }

            return [
                'success' => false,
                'error' => $response['error'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Extract size from Meilisearch stats response
     */
    private function extractMeilisearchSize(array $data): int
    {
        // Try different size fields that Meilisearch might provide
        $sizeFields = [
            'rawDocumentDbSize',
            'size',
            'databaseSize',
            'indexSize'
        ];

        foreach ($sizeFields as $field) {
            if (isset($data[$field])) {
                $value = $data[$field];

                // Handle NaN, null, or empty values
                if ($value === null || $value === '' || $value === 'NaN' || $value === 'null') {
                    continue;
                }

                // Convert to numeric and check if valid
                $numericValue = is_numeric($value) ? (float) $value : 0;

                if ($numericValue > 0) {
                    return (int) $numericValue;
                }
            }
        }

        // If no valid size found, return 0
        return 0;
    }

    /**
     * Format bytes to human readable format (GB, MB, KB, or Bytes)
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $index = floor(log($bytes, 1024));

        // Cap at GB to avoid showing TB
        $index = min($index, 3);

        $formattedSize = round($bytes / pow(1024, $index), 2);

        return $formattedSize . ' ' . $units[$index];
    }
}
