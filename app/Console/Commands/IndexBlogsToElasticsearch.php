<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class IndexBlogsToElasticsearch extends Command
{
    protected $signature = 'elasticsearch:index';
    protected $description = 'MySQLdeki blog verilerini Elasticsearche indexler';

    public function handle()
    {
        $elasticsearchUrl = 'http://elasticsearch:9200';
        $indexName = 'blogs';
        
        $this->info('Elasticsearch bağlantısı kontrol ediliyor...');
        
        // Elasticsearch bağlantısını test et
        $response = Http::get($elasticsearchUrl);
        if (!$response->successful()) {
            $this->error('Elasticsearch\'e bağlanılamadı!');
            return 1;
        }
        
        $this->info('Elasticsearch bağlantısı başarılı!');
        
        // Mevcut index'i sil (varsa)
        $this->info("Mevcut '{$indexName}' index'i siliniyor...");
        Http::delete("{$elasticsearchUrl}/{$indexName}");
        
        $this->info("Yeni '{$indexName}' index'i oluşturuluyor...");
        $mapping = [
            'mappings' => [
                'properties' => [
                    'title' => ['type' => 'text', 'analyzer' => 'standard'],
                    'content' => ['type' => 'text', 'analyzer' => 'standard'],
                    'author' => ['type' => 'keyword'],
                    'category_id' => ['type' => 'integer'],
                    'views' => ['type' => 'integer'],
                    'is_published' => ['type' => 'boolean'],
                    'published_at' => ['type' => 'date'],
                    'created_at' => ['type' => 'date'],
                ]
            ]
        ];
        
        $response = Http::put("{$elasticsearchUrl}/{$indexName}", $mapping);
        
        if (!$response->successful()) {
            $this->error('Index oluşturulamadı!');
            return 1;
        }
        
        $this->info('Index başarıyla oluşturuldu!');
        
        $totalBlogs = Blog::count();
        $this->info("Toplam {$totalBlogs} blog indexlenecek...");
        
        $bar = $this->output->createProgressBar($totalBlogs);
        $bar->start();
        
        $chunkSize = 500;
        $indexed = 0;
        
        Blog::chunk($chunkSize, function ($blogs) use ($elasticsearchUrl, $indexName, &$indexed, $bar) {
            $bulkData = '';
            
            foreach ($blogs as $blog) {
                $action = json_encode([
                    'index' => [
                        '_index' => $indexName,
                        '_id' => $blog->id
                    ]
                ]);
                
                $document = json_encode([
                    'title' => $blog->title,
                    'content' => $blog->content,
                    'author' => $blog->author,
                    'category_id' => $blog->category_id,
                    'views' => $blog->views,
                    'is_published' => $blog->is_published,
                    'published_at' => $blog->published_at?->toIso8601String(),
                    'created_at' => $blog->created_at->toIso8601String(),
                ]);
                
                $bulkData .= $action . "\n" . $document . "\n";
            }
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-ndjson'
            ])->withBody($bulkData, 'application/x-ndjson')
              ->post("{$elasticsearchUrl}/_bulk");
            
            if (!$response->successful()) {
                $this->newLine();
                $this->error('Bulk insert hatası!');
                return false;
            }
            
            $indexed += $blogs->count();
            $bar->advance($blogs->count());
        });
        
        $bar->finish();
        $this->newLine(2);
        
        $stats = Http::get("{$elasticsearchUrl}/{$indexName}/_stats")->json();
        $docCount = $stats['indices'][$indexName]['total']['docs']['count'] ?? 0;
        
        $this->info("✅ Toplam {$indexed} blog başarıyla indexlendi!");
        $this->info("📊 Elasticsearch'te {$docCount} döküman bulunuyor.");
        
        return 0;
    }
}
