<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Engines Comparison</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .engine-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .engine-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .comparison-winner {
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        .stat-icon {
            font-size: 1.2em;
            margin-right: 8px;
        }
        .winner-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ffd700;
            color: #000;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">

        <!-- Search Query Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-body bg-light">
                        <h5 class="card-title text-info">
                            <i class="fas fa-info-circle"></i> Search Details
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Query:</strong> "{{ $query ?? 'No query' }}"</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>Searched in:</strong>
                                    @if($field)
                                        Field: <code>{{ $field }}</code>
                                    @else
                                        <em>All fields</em> <i class="fas fa-globe text-muted"></i>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 position-relative">
                <div class="card engine-card {{ $results['comparison']['faster'] === 'elasticsearch' ? 'comparison-winner' : '' }}">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-server"></i> Elasticsearch</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-clock stat-icon text-primary"></i>
                            <strong>Total Time:</strong> <span class="text-primary">{{ $results['elasticsearch']['total_time_ms'] }} ms</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-search stat-icon text-info"></i>
                            <strong>Search Time:</strong> <span class="text-info">{{ $results['elasticsearch']['search_time_ms'] }} ms</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-check-circle stat-icon"></i>
                            <strong>Success:</strong>
                            @if($results['elasticsearch']['success'])
                                <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> No</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-list-ul stat-icon text-secondary"></i>
                            <strong>Total Results:</strong> <span class="text-secondary">{{ number_format($results['elasticsearch']['total']) }}</span>
                        </div>

                        @if($results['elasticsearch']['results'])
                        <div class="results-section">
                            <h6>Sample Results:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(array_slice($results['elasticsearch']['results'], 0, 5) as $result)
                                        <tr>
                                            <td>{{ $result['_id'] ?? 'N/A' }}</td>
                                            <td>{{ round($result['_score'] ?? 0, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> Elasticsearch uses TF-IDF scoring (can be > 1.0)
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 position-relative">
                <div class="card engine-card {{ $results['comparison']['faster'] === 'meilisearch' ? 'comparison-winner' : '' }}">
                    <div class="card-header bg-success text-white">
                        <h4><i class="fas fa-rocket"></i> Meilisearch</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-clock stat-icon text-primary"></i>
                            <strong>Total Time:</strong> <span class="text-primary">{{ $results['meilisearch']['total_time_ms'] }} ms</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-search stat-icon text-info"></i>
                            <strong>Search Time:</strong> <span class="text-info">{{ $results['meilisearch']['search_time_ms'] }} ms</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-check-circle stat-icon"></i>
                            <strong>Success:</strong>
                            @if($results['meilisearch']['success'])
                                <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> No</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-list-ul stat-icon text-secondary"></i>
                            <strong>Total Results:</strong> <span class="text-secondary">{{ number_format($results['meilisearch']['total']) }}</span>
                        </div>

                        @if($results['meilisearch']['results'])
                        <div class="results-section">
                            <h6>Sample Results:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(array_slice($results['meilisearch']['results'], 0, 5) as $result)
                                        <tr>
                                            <td>{{ $result['id'] ?? 'N/A' }}</td>
                                            <td>{{ isset($result['_rankingScore']) ? number_format($result['_rankingScore'], 2) : 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> Meilisearch uses rule-based ranking (sequential rule application)
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card engine-card">
                    <div class="card-header bg-info text-white">
                        <h4><i class="fas fa-chart-line"></i> Comparison Results</h4>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $faster = $results['comparison']['faster'];
                            $difference = $results['comparison']['difference_ms'];
                        @endphp

                        @if($faster === 'equal')
                            <h3 class="text-warning"><i class="fas fa-balance-scale"></i> Both search engines performed equally</h3>
                            <p class="text-muted fs-5">Search times were identical</p>
                            <div class="mt-3">
                                <i class="fas fa-equals text-warning fa-2x"></i>
                            </div>
                        @elseif($faster === 'elasticsearch')
                            <h3 class="text-primary"><i class="fas fa-trophy"></i> <span class="text-primary">Elasticsearch</span> was faster!</h3>
                            <p class="text-muted fs-5">By <strong class="text-primary">{{ $difference }}</strong> ms</p>
                            <div class="mt-3">
                                <i class="fas fa-server text-primary fa-3x"></i>
                            </div>
                        @else
                            <h3 class="text-success"><i class="fas fa-trophy"></i> <span class="text-success">Meilisearch</span> was faster!</h3>
                            <p class="text-muted fs-5">By <strong class="text-success">{{ $difference }}</strong> ms</p>
                            <div class="mt-3">
                                <i class="fas fa-rocket text-success fa-3x"></i>
                            </div>
                        @endif

                        <div class="alert alert-light mt-4">
                            <small class="text-muted"><i class="fas fa-info-circle"></i> {{ $results['comparison']['note'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="btn-group" role="group">
                    <a href="/search" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Search
                    </a>
                    <a href="/" class="btn btn-outline-primary">
                        <i class="fas fa-home"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
