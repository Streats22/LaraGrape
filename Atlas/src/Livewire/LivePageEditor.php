<?php

namespace Streats\Atlas\Livewire;

use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Streats\Atlas\Blocks\BlockRegistry;
use Streats\Atlas\Livewire\Concerns\ManagesPageBlocks;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\PagePreviewService;
use Streats\Atlas\Services\Renderer;

#[Layout('atlas::layouts.livewire-editor-shell')]
#[Title('Live editor')]
class LivePageEditor extends Component
{
    use ManagesPageBlocks;

    public bool $paletteOpen = true;

    public function mount(Page $page): void
    {
        $this->pageId = $page->id;
        $this->blockTypes = array_keys(BlockRegistry::all());
        $this->restoreCanvasMode();
    }

    public function togglePalette(): void
    {
        $this->paletteOpen = ! $this->paletteOpen;
    }

    public function render(Renderer $renderer, PagePreviewService $pagePreview)
    {
        $page = Page::query()->with(['blocks.fields'])->findOrFail($this->pageId);

        $blockPreviews = [];
        foreach ($page->blocks as $block) {
            if ($this->selectedBlockId === $block->id) {
                $blockPreviews[$block->id] = $renderer->renderBlockWithOverrides($block, $this->fieldValues);
            } else {
                $blockPreviews[$block->id] = $renderer->renderBlock($block);
            }
        }

        $previewUrl = Route::has('atlas.page.preview')
            ? $pagePreview->signedUrl($page)
            : null;

        $dashboardUrl = Route::has('atlas.dashboard')
            ? route('atlas.dashboard')
            : url('/');

        return view('atlas::livewire.live-page-editor', [
            'page' => $page,
            'blockTypes' => $this->blockTypes,
            'blockPreviews' => $blockPreviews,
            'previewUrl' => $previewUrl,
            'dashboardUrl' => $dashboardUrl,
        ]);
    }
}
