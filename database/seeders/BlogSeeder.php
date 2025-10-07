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
        $totalBlogs = 100000;
        $chunkSize = 1000;
        $chunks = ceil($totalBlogs / $chunkSize);
        
        $this->command->info("100 bin blog kaydı oluşturuluyor...");
        $this->command->getOutput()->progressStart($chunks);
        
        for ($i = 0; $i < $chunks; $i++) {
            \App\Models\Blog::factory()->count($chunkSize)->create();
            $this->command->getOutput()->progressAdvance();
        }
        
        $this->command->getOutput()->progressFinish();
        $this->command->info("100.000 blog kaydı başarıyla oluşturuldu!");
    }
}
