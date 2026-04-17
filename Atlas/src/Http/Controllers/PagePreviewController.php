<?php

namespace Streats\Atlas\Http\Controllers;

use Illuminate\Http\Response;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\PagePreviewService;

class PagePreviewController
{
    public function __invoke(Page $page, PagePreviewService $preview): Response
    {
        return $preview->response($page);
    }
}
