# Elasticsearch vs Meilisearch Performance Comparison

A Laravel-based web application to compare Elasticsearch and Meilisearch performance interface.

## 🎯 Features

- **Real-time search comparison** between Elasticsearch and Meilisearch
- **Clean MVC architecture** with separated controllers
- **Responsive web interface** with modern UI/UX
- **Performance metrics** and comparison analytics

## 🛠 Tech Stack

- **Laravel 12** + **PHP 8.4** + **MySQL 8.0**
- **Elasticsearch 8.16.1** + **Kibana 8.16.1**
- **Meilisearch** (latest)
- **Redis**, **PHPMyAdmin**
- **Docker** + **Laravel Sail**
- **jQuery** + **Bootstrap 5** + **Font Awesome**

## 🏗 Architecture

### Controllers Structure
```
app/Http/Controllers/
├── Controller.php              # Base controller
├── SearchController.php        # Search comparison logic
├── HealthController.php        # Service health monitoring
└── IndexController.php         # Index management (create/delete)
```


## ⚡ Quick Start

```bash
# Clone and install
composer install

# Start Docker containers
./vendor/bin/sail up -d

# Setup database
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

# Access the web interface
open http://localhost
```

## 🌐 Access Points

| Service | URL |
|---------|-----|
| **Laravel App** | http://localhost |
| **Kibana** | http://localhost:5601 |
| **PHPMyAdmin** | http://localhost:8080 |
| **Elasticsearch** | http://localhost:9200 |
| **Meilisearch** | http://localhost:7700 |

## 📊 Database Structure

**Categories**: 50 records (Technology, Programming, Business, etc.)
**Blogs**: 1,000,000 records with title, content (10-30 paragraphs), author, views, category

## 🎨 Web Interface

### Main Page (`/`)
- **Modern hero section** with feature overview
- **Service status cards** with real-time health checks
- **AJAX-powered buttons** for instant status updates
- **Modal popup notifications** for service status

### Features:
- **Elasticsearch Health Check**: Click to check Elasticsearch status
- **Meilisearch Health Check**: Click to check Meilisearch status
- **Real-time Updates**: No page refresh needed
- **Responsive Design**: Works on all devices

## 📡 API Endpoints

### 1️⃣ Search Comparison
Compare search performance between Elasticsearch and Meilisearch.

```bash
GET /compare-search
```

**Parameters:**
- `query` (required) - Search query text
- `field` (optional) - Specific field to search in (e.g., `title`, `content`)
- `size` (optional, default: 10) - Number of results

### 2️⃣ Health Monitoring
Check service health status.

```bash
GET /health/elasticsearch
GET /health/meilisearch
```

**Response:**
```json
{
  "service": "Elasticsearch",
  "status": "healthy",
  "success": true,
  "timestamp": "2025-10-18T18:53:41.000000Z"
}
```

### 3️⃣ Index Management
Index or delete blog data.

```bash
POST /index-blogs     # Create indexes
POST /delete-index    # Delete indexes
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
- **Web interface** provides real-time health monitoring
- **AJAX requests** for instant status updates without page refresh
- **Modal popups** for better user experience

## 🎯 Key Improvements

### ✅ AJAX Health Monitoring
- Real-time service status checks
- No page refresh needed
- Modal popup notifications
- Loading states with spinners

### ✅ Clean Architecture
- Separated controllers for different responsibilities
- Single Responsibility Principle applied
- Easy to maintain and extend

### ✅ Modern UI/UX
- Responsive Bootstrap 5 design
- Font Awesome icons
- Gradient backgrounds
- Interactive elements

### ✅ Professional Code Structure
- Organized controller hierarchy
- Clean separation of concerns
- Maintainable codebase

---
