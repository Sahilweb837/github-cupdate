<?php

namespace App\Console\Commands;

use App\Services\SeoService;
use Illuminate\Console\Command;

class GenerateSeoFiles extends Command
{
    protected $signature = 'seo:generate';
    protected $description = 'Generate static public/sitemap.xml and public/robots.txt for CupDate';

    public function handle(SeoService $seo): int
    {
        $seo->writeStaticFiles();
        $this->info('Generated public/sitemap.xml and public/robots.txt successfully.');

        return self::SUCCESS;
    }
}
