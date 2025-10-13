# Elasticsearch vs Meilisearch Performance Comparison

A Laravel-based testing environment to compare Elasticsearch and Meilisearch performance with 1 million blog records.

## 🎯 Goal

Compare search performance, indexing speed, and resource usage between Elasticsearch and Meilisearch using real-world blog data through REST API endpoints.

## 🛠 Tech Stack

- **Laravel 12** + **PHP 8.4** + **MySQL 8.0**
- **Elasticsearch 8.16.1** + **Kibana 8.16.1**
- **Meilisearch** (latest)
- **Redis**, **PHPMyAdmin**
- **Docker** + **Laravel Sail**

## ⚡ Quick Start

```bash
# Clone and install
composer install

# Start Docker containers
./vendor/bin/sail up -d

# Setup database with 1M records
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

# Index via API endpoint (recommended)
curl -X POST "http://localhost/api/index/blogs"

# Or index via commands
./vendor/bin/sail artisan elasticsearch:index
./vendor/bin/sail artisan meilisearch:index

# Test search comparison
curl "http://localhost/api/search/compare?query=laravel&size=10"
```

## 🌐 Access Points

| Service | URL |
|---------|-----|
| Laravel | http://localhost |
| Kibana | http://localhost:5601 |
| PHPMyAdmin | http://localhost:8080 |
| Elasticsearch | http://localhost:9200 |
| Meilisearch | http://localhost:7700 |

## 📊 Database Structure

**Categories**: 50 records (Technology, Programming, Business, etc.)  
**Blogs**: 1,000,000 records with title, content (10-30 paragraphs), author, views, category

## 📡 API Endpoints

### 1️⃣ Search Comparison

Compare search performance between Elasticsearch and Meilisearch.

```bash
GET /api/search/compare
```

**Parameters:**
- `query` (required) - Search query text
- `field` (optional) - Specific field to search in (e.g., `title`, `content`)
- `size` (optional, default: 10) - Number of results

**Examples:**

```bash
# Search in all fields
curl "http://localhost/api/search/compare?query=laravel&size=20"

# Search in specific field
curl "http://localhost/api/search/compare?query=laravel&field=title&size=10"
```

**Response:**
```json
{
  "elasticsearch": {
    "total_time_ms": 15.23,
    "search_time_ms": 12,
    "success": true,
    "total": 42,
    "results": [...]
  },
  "meilisearch": {
    "total_time_ms": 8.45,
    "search_time_ms": 5,
    "success": true,
    "total": 42,
    "results": [...]
  },
  "comparison": {
    "faster": "meilisearch",
    "difference_ms": 7,
    "note": "Comparison based on search engine internal time (search_time_ms)"
  }
}
```

### 2️⃣ Index Blogs

Index all blog data to both Elasticsearch and Meilisearch.

```bash
POST /api/index/blogs
```

**Parameters:**
- `chunk_size` (optional, default: 500) - Number of records per batch

**Example:**

```bash
curl -X POST "http://localhost/api/index/blogs?chunk_size=1000"
```

**Response:**
```json
{
  "total_blogs": 1000000,
  "elasticsearch": {
    "success": true,
    "indexed": 1000000,
    "documents_in_index": 1000000,
    "errors": []
  },
  "meilisearch": {
    "success": true,
    "indexed": 1000000,
    "documents_in_index": 1000000,
    "errors": []
  },
  "processing_time": {
    "elasticsearch": 45320.45,
    "meilisearch": 38765.32
  }
}
```

### 3️⃣ Delete Index

Delete index from both search engines.

```bash
DELETE /api/index/delete
```

**Parameters:**
- `index` (required) - Index name to delete

**Example:**

```bash
curl -X DELETE "http://localhost/api/index/delete?index=blogs"
```

**Response:**
```json
{
  "elasticsearch": {
    "success": true,
    "status": 200,
    "message": "Index deleted successfully"
  },
  "meilisearch": {
    "success": true,
    "status": 204,
    "message": "Index deleted successfully"
  }
}
```

## 🔧 Useful Commands

```bash
# Docker
./vendor/bin/sail up -d              # Start containers
./vendor/bin/sail down               # Stop containers
./vendor/bin/sail logs elasticsearch # View logs

# Database
./vendor/bin/sail artisan migrate           # Run migrations
./vendor/bin/sail artisan db:seed           # Seed 1M records
./vendor/bin/sail artisan migrate:fresh --seed  # Reset & seed

# Indexing (Alternative to API)
./vendor/bin/sail artisan elasticsearch:index   # Index to Elasticsearch
./vendor/bin/sail artisan meilisearch:index     # Index to Meilisearch
```

## 📝 Notes

- **First `sail up`** takes 5-10 minutes (builds Docker images)
- **Database seeding** takes ~15-30 minutes for 1 million records
- **Indexing** via API endpoint provides real-time performance metrics
- **Search comparison** shows both total time (network + processing) and engine internal time
- Both services use **HTTP APIs** without SDKs for fair comparison

## 🎯 Performance Metrics

The API responses include:
- `total_time_ms`: Total request time (network + processing)
- `search_time_ms`: Engine's internal processing time
  - Elasticsearch: `took` field
  - Meilisearch: `processingTimeMs` field
- Fair comparison using engine internal times

---
