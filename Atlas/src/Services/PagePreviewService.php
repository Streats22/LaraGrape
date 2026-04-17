<?php

namespace Streats\Atlas\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Streats\Atlas\Models\Page;

class PagePreviewService
{
    public function __construct(
        protected Renderer $renderer
    ) {}

    public function response(Page $page): Response
    {
        $page->loadMissing('blocks.fields');
        $html = $this->renderer->render($page);

        return response()->view('atlas::layouts.preview-document', [
            'content' => $html,
            'title' => $page->title.' · Preview',
        ], 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function signedUrl(Page $page, ?\DateTimeInterface $expiration = null): string
    {
        return URL::temporarySignedRoute(
            'atlas.page.preview',
            $expiration ?? now()->addMinutes(60),
            ['page' => $page->id]
        );
    }
}
