# Elasticsearch vs Meilisearch Performance Comparison

A Laravel-based testing environment to compare Elasticsearch and Meilisearch performance with 100,000 blog records.

## 🎯 Goal

Compare search performance, indexing speed, and resource usage between Elasticsearch and Meilisearch using real-world blog data.

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

# Setup database with 100k records
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

# Index to both search engines
./vendor/bin/sail artisan elasticsearch:index
./vendor/bin/sail artisan meilisearch:index
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
**Blogs**: 100,000 records with title, content (10-30 paragraphs), author, views, category

## 🔍 Search Commands

```bash
# Index to Elasticsearch (uses Bulk API)
./vendor/bin/sail artisan elasticsearch:index

# Index to Meilisearch
./vendor/bin/sail artisan meilisearch:index
```

## 🧪 Example Queries

**Elasticsearch:**
```bash
curl -X GET "http://localhost:9200/blogs/_search" -H 'Content-Type: application/json' -d '{
  "query": { "match": { "title": "laravel" } }
}'
```

**Meilisearch:**
```bash
curl -X POST "http://localhost:7700/indexes/blogs/search" -H 'Content-Type: application/json' -d '{
  "q": "laravel"
}'
```

## 🔧 Useful Commands

```bash
./vendor/bin/sail up -d              # Start containers
./vendor/bin/sail down               # Stop containers
./vendor/bin/sail logs elasticsearch # View logs
./vendor/bin/sail artisan migrate:fresh --seed  # Reset DB
```

## 📝 Notes

- First `sail up` takes 5-10 minutes (builds images)
- Database seeding takes ~10 minutes
- Indexing 100k records takes ~2-5 minutes each

---
