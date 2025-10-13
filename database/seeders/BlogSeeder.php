<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalBlogs = 1000000; 
        $chunkSize = 5000;
        $chunks = ceil($totalBlogs / $chunkSize);
        
        $this->command->info("1 milyon blog kaydı oluşturuluyor...");
        $this->command->info("Bu işlem uzun sürebilir, lütfen bekleyin...");
        $this->command->getOutput()->progressStart($chunks);
        
        for ($i = 0; $i < $chunks; $i++) {
            \App\Models\Blog::factory()->count($chunkSize)->create();
            $this->command->getOutput()->progressAdvance();
            
            if (($i + 1) % 100 === 0) {
                $created = ($i + 1) * $chunkSize;
                $this->command->newLine();
                $this->command->info(number_format($created) . " kayıt oluşturuldu...");
            }
        }
        
        $this->command->getOutput()->progressFinish();
        $this->command->newLine();
        $this->command->info("✅ 1.000.000 blog kaydı başarıyla oluşturuldu!");
    }
}
