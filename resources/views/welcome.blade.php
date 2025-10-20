<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elasticsearch vs Meilisearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .hero-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        .hero-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .hero-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }
        .hero-body {
            padding: 3rem 2rem;
        }
        .feature-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
            transition: transform 0.3s ease;
        }
        .feature-box:hover {
            transform: translateX(5px);
        }
        .feature-box i {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .feature-box h4 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .feature-box p {
            color: #666;
            margin: 0;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 2rem;
        }
        .btn-custom {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 10px;
            border: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .btn-success-custom {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }
        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .btn-danger-custom {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }
        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .vs-badge {
            display: inline-block;
            background: #ffd700;
            color: #333;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="hero-container">
                    <div class="hero-header">
                        <h1><i class="fas fa-search"></i> Search Engine Comparison</h1>
                        <p>Elasticsearch vs Meilisearch Performance Testing</p>
                        <span class="vs-badge">
                            <i class="fas fa-bolt"></i> Real-time Comparison Tool
                        </span>
                    </div>

                    <div class="hero-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="fas fa-server"></i>
                                    <h4>Elasticsearch</h4>
                                    <p>Powerful, scalable full-text search engine. Detailed scoring with BM25 algorithm.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="fas fa-rocket"></i>
                                    <h4>Meilisearch</h4>
                                    <p>Fast, user-friendly search engine. Instant search experience with rule-based ranking.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="feature-box">
                                    <i class="fas fa-chart-line"></i>
                                    <h4>What Does This Application Do?</h4>
                                    <p>This application allows you to compare the performance of Elasticsearch and Meilisearch engines.
                                    Run the same query on both engines and see speed, accuracy and result quality side by side.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="feature-box text-center">
                                    <i class="fas fa-database"></i>
                                    <h4>Create Index</h4>
                                    <p>Index blog data to both engines</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-box text-center">
                                    <i class="fas fa-search-plus"></i>
                                    <h4>Search</h4>
                                    <p>Search for keywords and compare results</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-box text-center">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <h4>View Performance</h4>
                                    <p>Analyze speed and accuracy metrics</p>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="/search" class="btn btn-custom btn-primary-custom">
                                <i class="fas fa-search"></i> Search
                            </a>
                            <!-- Production: Index management disabled for security -->
                            <!--
                            <a href="/index" class="btn btn-custom btn-success-custom">
                                <i class="fas fa-database"></i> Create Index
                            </a>
                            <a href="/delete" class="btn btn-custom btn-danger-custom">
                                <i class="fas fa-trash"></i> Delete Index
                            </a>
                            -->
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-heartbeat"></i> Service Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                                                    <div>
                                                        <strong><i class="fas fa-server text-primary"></i> Elasticsearch</strong>
                                                        <div class="small text-muted">Search engine status</div>
                                                    </div>
                                                    <button onclick="checkHealth('elasticsearch')" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-stethoscope"></i> Check
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                                                    <div>
                                                        <strong><i class="fas fa-rocket text-success"></i> Meilisearch</strong>
                                                        <div class="small text-muted">Search engine status</div>
                                                    </div>
                                                    <button onclick="checkHealth('meilisearch')" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-stethoscope"></i> Check
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-info small">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Tip:</strong> Use the buttons above to check if services are running.
                                        </div>
                                    </div>
                                </div>

                                <!-- Index Statistics Section -->
                                <div class="card border-warning mt-4">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Index Statistics</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="stats-container">
                                            <div class="col-md-4 text-center">
                                                <div class="p-3 border rounded">
                                                    <i class="fas fa-database fa-2x text-info mb-2"></i>
                                                    <h6>Database</h6>
                                                    <div id="db-stats">
                                                        <div class="spinner-border spinner-border-sm text-info" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="p-3 border rounded">
                                                    <i class="fas fa-server fa-2x text-primary mb-2"></i>
                                                    <h6>Elasticsearch</h6>
                                                    <div id="es-stats">
                                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="p-3 border rounded">
                                                    <i class="fas fa-rocket fa-2x text-success mb-2"></i>
                                                    <h6>Meilisearch</h6>
                                                    <div id="meili-stats">
                                                        <div class="spinner-border spinner-border-sm text-success" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button onclick="loadStats()" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-sync-alt"></i> Refresh Stats
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkHealth(service) {
            const button = $(event.target);
            const originalText = button.html();

            // Loading state
            button.html('<i class="fas fa-spinner fa-spin"></i> Checking...');
            button.prop('disabled', true);

            $.get(`/health/${service}`)
                .done(function(data) {
                    let message = '';
                    let alertType = '';

                    if (data.success) {
                        message = `${data.service} is running! ✅\n\nStatus: ${data.status}\nTime: ${new Date(data.timestamp).toLocaleString('en-US')}`;
                        alertType = 'success';
                    } else {
                        message = `${data.service} is not running! ❌\n\nError: ${data.error?.message || 'Unknown error'}\nTime: ${new Date(data.timestamp).toLocaleString('en-US')}`;
                        alertType = 'danger';
                    }

                    showAlert(message, alertType);
                })
                .fail(function() {
                    showAlert(`${service} health check failed! Network error occurred.`, 'warning');
                })
                .always(function() {
                    button.html(originalText);
                    button.prop('disabled', false);
                });
        }

        function showAlert(message, type) {
            // Önceki alert'ları temizle
            $('.custom-alert').remove();

            // Bootstrap alert renkleri için icon seçimi
            const icons = {
                success: 'check-circle',
                danger: 'times-circle',
                warning: 'exclamation-triangle'
            };

            const icon = icons[type] || 'info-circle';

            // Modal HTML
            const modalHtml = `
                <div class="modal fade custom-alert" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-${type} text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-${icon}"></i>
                                    Service Status
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="fas fa-${icon} fa-3x text-${type} mb-3"></i>
                                    <div style="white-space: pre-line;">${message}</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Modal'ı body'ye ekle ve göster
            $('body').append(modalHtml);
            $('.custom-alert').modal('show');

            // Modal kapatıldığında temizle
            $('.custom-alert').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        }

        // Sayfa yüklendiğinde istatistikleri getir
        $(document).ready(function() {
            loadStats();
        });

        function loadStats() {
            // Tüm spinner'ları göster
            $('#db-stats, #es-stats, #meili-stats').html(`
                <div class="spinner-border spinner-border-sm text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `);

            $.get('/stats/blogs')
                .done(function(data) {
                    displayStats(data);
                })
                .fail(function() {
                    $('#db-stats, #es-stats, #meili-stats').html(`
                        <span class="text-danger">Failed to load</span>
                    `);
                });
        }

        function displayStats(data) {
            // Database stats
            const dbStats = data.database;
            $('#db-stats').html(`
                <div class="mb-1"><strong>${numberFormat(dbStats.total_blogs)}</strong> Total Blogs</div>
            `);

            // Elasticsearch stats
            const esStats = data.elasticsearch;
            if (esStats.success) {
                $('#es-stats').html(`
                    <div class="mb-1"><strong>${numberFormat(esStats.total_documents)}</strong> Documents</div>
                    <div class="text-muted">${esStats.size}</div>
                `);
            } else {
                $('#es-stats').html(`<span class="text-danger">${esStats.error}</span>`);
            }

            // Meilisearch stats
            const meiliStats = data.meilisearch;
            if (meiliStats.success) {
                $('#meili-stats').html(`
                    <div class="mb-1"><strong>${numberFormat(meiliStats.total_documents)}</strong> Documents</div>
                    <div class="text-muted">${meiliStats.size}</div>
                `);
            } else {
                $('#meili-stats').html(`<span class="text-danger">${meiliStats.error}</span>`);
            }
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('en-US').format(number);
        }
    </script>
</body>
</html>
