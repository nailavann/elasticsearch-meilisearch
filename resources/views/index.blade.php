<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Index - Elasticsearch vs Meilisearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .search-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }
        .action-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .action-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .action-card h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .btn-action {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            border-radius: 8px;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-index {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }
        .btn-index:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(86, 171, 47, 0.4);
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .info-box i {
            color: #2196F3;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-database"></i> Create Index</h1>
            <p>Index blog data to both search engines</p>
            <a href="/" class="btn btn-light mt-2">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>

        <!-- Index Section -->
        <div class="search-container">
            <div class="action-card">
                <h3><i class="fas fa-database"></i> Create Index</h3>
                <form action="/index-blogs" method="POST" id="indexForm">
                    @csrf
                    <div class="mb-3">
                        <label for="chunk_size" class="form-label">
                            <i class="fas fa-layer-group"></i> Chunk Size (How many records per batch?)
                        </label>
                        <input type="number" class="form-control" id="chunk_size" name="chunk_size"
                               value="500" min="100" max="5000">
                    </div>
                    <button type="submit" class="btn btn-action btn-index w-100">
                        <i class="fas fa-play"></i> Start Indexing
                    </button>
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <strong>Warning:</strong> This process will index blog data to both Elasticsearch and Meilisearch. The process may take a long time.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confirm before index
        document.getElementById('indexForm').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to start the indexing process? This process may take a long time.')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
