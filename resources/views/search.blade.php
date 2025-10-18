<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Elasticsearch vs Meilisearch</title>
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
        .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-index {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }
        .btn-index:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(86, 171, 47, 0.4);
        }
        .btn-delete {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(235, 51, 73, 0.4);
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
            <h1><i class="fas fa-cogs"></i> Search Operations</h1>
            <a href="/" class="btn btn-light mt-2">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>

        <!-- Search Section -->
        <div class="search-container">
            <div class="action-card">
                <h3><i class="fas fa-search"></i> Search</h3>
                <form action="/compare-search" method="GET">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="query" class="form-label">
                                <i class="fas fa-keyboard"></i> Search Query *
                            </label>
                            <input type="text" class="form-control" id="query" name="query"
                                   placeholder="Ex: laravel, php, programming..." required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="field" class="form-label">
                                <i class="fas fa-filter"></i> Field (Optional)
                            </label>
                            <select class="form-select" id="field" name="field">
                                <option value="">All fields</option>
                                <option value="title">Title</option>
                                <option value="content">Content</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="index" class="form-label">
                                <i class="fas fa-database"></i> Index Name
                            </label>
                            <input type="text" class="form-control" id="index" name="index"
                                   value="blogs" placeholder="blogs">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="size" class="form-label">
                                <i class="fas fa-list-ol"></i> Result Count
                            </label>
                            <input type="number" class="form-control" id="size" name="size"
                                   value="10" min="1" max="100">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-action btn-search w-100">
                        <i class="fas fa-search"></i> Search and Compare
                    </button>
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Search will be executed on both engines and results will be displayed side by side.
                    </div>
                </form>
            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
