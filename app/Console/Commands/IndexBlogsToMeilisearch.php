<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class IndexBlogsToMeilisearch extends Command
{
    protected $signature = 'meilisearch:index';
    protected $description = 'MySQLdeki blog verilerini Meilisearche indexler';

    public function handle()
    {
        $meilisearchUrl = 'http://meilisearch:7700';
        $indexName = 'blogs';
        
        $this->info('Meilisearch bağlantısı kontrol ediliyor...');
        
        $response = Http::get("{$meilisearchUrl}/health");
        if (!$response->successful()) {
            $this->error('Meilisearch\'e bağlanılamadı!');
            return 1;
        }
        
        $this->info('Meilisearch bağlantısı başarılı!');
        
        $this->info("Mevcut '{$indexName}' index'i siliniyor...");
        Http::delete("{$meilisearchUrl}/indexes/{$indexName}");
        sleep(3);
        
        $this->info("Yeni '{$indexName}' index'i oluşturuluyor...");
        $response = Http::post("{$meilisearchUrl}/indexes", [
            'uid' => $indexName,
            'primaryKey' => 'id'
        ]);
        
        if (!$response->successful()) {
            $this->error('Index oluşturulamadı!');
            return 1;
        }
        
        sleep(3);
        
        $this->info('Index ayarları yapılandırılıyor...');
        Http::patch("{$meilisearchUrl}/indexes/{$indexName}/settings", [
            'searchableAttributes' => [
                'title',
                'content'
            ],
            'filterableAttributes' => [
                'author',
                'category_id',
                'is_published',
                'published_at',
                'created_at'
            ],
            'sortableAttributes' => [
                'views',
                'published_at',
                'created_at'
            ]
        ]);
        
        $this->info('Index başarıyla oluşturuldu!');
        
        $totalBlogs = Blog::count();
        $this->info("Toplam {$totalBlogs} blog indexlenecek...");
        
        $bar = $this->output->createProgressBar($totalBlogs);
        $bar->start();
        
        $chunkSize = 500;
        $indexed = 0;
        
        Blog::chunk($chunkSize, function ($blogs) use ($meilisearchUrl, $indexName, &$indexed, $bar) {
            $documents = [];
            
            foreach ($blogs as $blog) {
                $documents[] = [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'content' => $blog->content,
                    'author' => $blog->author,
                    'category_id' => $blog->category_id,
                    'views' => $blog->views,
                    'is_published' => $blog->is_published,
                    'published_at' => $blog->published_at?->timestamp,
                    'created_at' => $blog->created_at->timestamp,
                ];
            }
            
            $response = Http::post("{$meilisearchUrl}/indexes/{$indexName}/documents", $documents);
            
            if (!$response->successful()) {
                $this->newLine();
                $this->error('Document insert hatası!');
                return false;
            }
            
            $indexed += $blogs->count();
            $bar->advance($blogs->count());
        });
        
        $bar->finish();
        $this->newLine(2);
        
        sleep(3);
        
        $stats = Http::get("{$meilisearchUrl}/indexes/{$indexName}/stats")->json();
        $docCount = $stats['numberOfDocuments'] ?? 0;
        
        $this->info("✅ Toplam {$indexed} blog başarıyla indexlendi!");
        $this->info("📊 Meilisearch'te {$docCount} döküman bulunuyor.");
        
        return 0;
    }
}
