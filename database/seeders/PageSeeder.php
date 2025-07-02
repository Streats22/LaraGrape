<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Published Home Page
        $home = Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'content' => '<h1>Welcome to LaralGrape!</h1><p>This is your homepage.</p>',
                'grapesjs_data' => null,
                'grapesjs_css' => null,
                'grapesjs_html' => null,
                'template' => 'default',
                'featured_image' => null,
                'meta_title' => 'Home - LaralGrape',
                'meta_description' => 'Welcome to your new LaralGrape-powered site.',
                'meta_keywords' => 'home, laravel, grapesjs, filament',
                'is_published' => true,
                'show_in_menu' => true,
                'sort_order' => 1,
                'published_at' => now(),
            ]
        );
        if (!$home->wasRecentlyCreated) {
            $this->command->info('Page "home" already exists, skipping.');
        } else {
            $this->command->info('Page "home" created.');
        }

        // Draft About Page
        $about = Page::firstOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About',
                'content' => '<h1>About Us</h1><p>Learn more about this project.</p>',
                'grapesjs_data' => null,
                'grapesjs_css' => null,
                'grapesjs_html' => null,
                'template' => 'default',
                'featured_image' => null,
                'meta_title' => 'About - LaralGrape',
                'meta_description' => 'About this project.',
                'meta_keywords' => 'about, laravel, grapesjs, filament',
                'is_published' => false,
                'show_in_menu' => true,
                'sort_order' => 2,
                'published_at' => null,
            ]
        );
        if (!$about->wasRecentlyCreated) {
            $this->command->info('Page "about" already exists, skipping.');
        } else {
            $this->command->info('Page "about" created.');
        }
    }
} 