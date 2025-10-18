<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use App\Services\MeilisearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected ElasticsearchService $elasticsearch;
    protected MeilisearchService $meilisearch;

    public function __construct(ElasticsearchService $elasticsearch, MeilisearchService $meilisearch)
    {
        $this->elasticsearch = $elasticsearch;
        $this->meilisearch = $meilisearch;
    }

    /**
     * Compare search performance between Elasticsearch and Meilisearch
     */
    public function compareSearch(Request $request)
    {
        $query = $request->input('query');
        $index = $request->input('index');
        $field = $request->input('field');
        $size = (int) $request->input('size', 10);

        $results = [];

        // Elasticsearch search
        $elasticStart = microtime(true);
        if ($field) {
            // Single field search
            $elasticResponse = $this->elasticsearch->searchSimple($index, $field, $query, 0, $size);
        } else {
            // Search all fields (match_all with query string)
            $elasticResponse = $this->elasticsearch->search($index, [
                'query_string' => [
                    'query' => $query
                ]
            ], 0, $size);
        }
        $elasticEnd = microtime(true);
        $elasticTotalTime = round(($elasticEnd - $elasticStart) * 1000, 2);

        $results['elasticsearch'] = [
            'total_time_ms' => $elasticTotalTime,
            'search_time_ms' => $elasticResponse['data']['took'] ?? 0,
            'success' => $elasticResponse['success'] ?? false,
            'total' => $elasticResponse['data']['hits']['total']['value'] ?? 0,
            'results' => $elasticResponse['data']['hits']['hits'] ?? [],
        ];

        // Meilisearch search
        $meiliStart = microtime(true);
        if ($field) {
            // Single field search
            $meiliResponse = $this->meilisearch->search($index, $query, [
                'attributesToSearchOn' => [$field],
                'limit' => $size,
                'showRankingScore' => true,
            ]);
        } else {
            // Search all searchable attributes
            $meiliResponse = $this->meilisearch->searchSimple($index, $query, $size, ['showRankingScore' => true]);
        }
        $meiliEnd = microtime(true);
        $meiliTotalTime = round(($meiliEnd - $meiliStart) * 1000, 2);

        $results['meilisearch'] = [
            'total_time_ms' => $meiliTotalTime,
            'search_time_ms' => $meiliResponse['data']['processingTimeMs'] ?? 0,
            'success' => $meiliResponse['success'],
            'total' => $meiliResponse['data']['estimatedTotalHits'] ?? 0,
            'results' => $meiliResponse['data']['hits'] ?? [],
        ];

        // Comparison
        $elasticSearchTime = $elasticResponse['data']['took'] ?? 0;
        $meiliSearchTime = $meiliResponse['data']['processingTimeMs'] ?? 0;

        $faster = 'equal';
        if ($elasticSearchTime < $meiliSearchTime) {
            $faster = 'elasticsearch';
        } elseif ($meiliSearchTime < $elasticSearchTime) {
            $faster = 'meilisearch';
        }

        $results['comparison'] = [
            'faster' => $faster,
            'difference_ms' => abs($elasticSearchTime - $meiliSearchTime),
            'note' => $meiliSearchTime === 0
                ? 'Meilisearch processed in less than 1ms (rounded to 0)'
                : 'Comparison based on search engine internal time (search_time_ms)'
        ];

        return view('comparison', compact('results', 'query', 'field'));
    }
}
