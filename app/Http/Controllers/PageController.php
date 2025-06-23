<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class PageController extends Controller
{
    /**
     * Display a page by its slug
     */
    public function show(string $slug): View|Response
    {
        $page = Page::where('slug', $slug)
            ->published()
            ->firstOrFail();
        
        // Get the rendered HTML and CSS from GrapesJS data
        $renderedHtml = $this->renderGrapesJsContent($page);
        
        return view('pages.show', compact('page', 'renderedHtml'));
    }
    
    /**
     * Display the homepage
     */
    public function home(): View
    {
        $page = Page::where('slug', 'home')
            ->published()
            ->first();
        
        if (!$page) {
            // Create a default homepage if it doesn't exist
            $page = $this->createDefaultHomepage();
        }
        
        $renderedHtml = $this->renderGrapesJsContent($page);
        
        return view('pages.show', compact('page', 'renderedHtml'));
    }
    
    /**
     * Render GrapesJS content to HTML
     */
    private function renderGrapesJsContent(Page $page): string
    {
        if (empty($page->grapesjs_data)) {
            return $page->content ?? '';
        }
        
        $data = $page->grapesjs_data;
        
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        
        $html = $data['html'] ?? '';
        $css = $data['css'] ?? '';
        
        // Wrap the content with CSS
        if (!empty($css)) {
            $html = "<style>{$css}</style>" . $html;
        }
        
        return $html;
    }
    
    /**
     * Create a default homepage
     */
    private function createDefaultHomepage(): Page
    {
        return Page::create([
            'title' => 'Welcome to LaralGrape',
            'slug' => 'home',
            'content' => '<h1>Welcome to LaralGrape</h1><p>This is your new Laravel + GrapesJS + Filament boilerplate!</p>',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
