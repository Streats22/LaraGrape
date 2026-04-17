<?php

namespace Streats\Atlas\Livewire;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Streats\Atlas\Models\Page;
use Streats\Atlas\Services\PagePreviewService;

#[Layout('atlas::layouts.livewire-shell')]
#[Title('Atlas dashboard')]
class AtlasDashboard extends Component
{
    public string $createTitle = '';

    public string $createSlug = '';

    public ?int $editingPageId = null;

    public string $editTitle = '';

    public string $editSlug = '';

    public function startEdit(int $pageId): void
    {
        $page = Page::query()->findOrFail($pageId);
        $this->editingPageId = $pageId;
        $this->editTitle = $page->title;
        $this->editSlug = $page->slug;
        $this->resetErrorBag();
    }

    public function cancelEdit(): void
    {
        $this->editingPageId = null;
        $this->editTitle = '';
        $this->editSlug = '';
    }

    public function savePage(): void
    {
        if ($this->editingPageId === null) {
            return;
        }

        $this->validate([
            'editTitle' => ['required', 'string', 'max:255'],
            'editSlug' => ['required', 'string', 'max:255'],
        ]);

        $slug = Str::slug($this->editSlug);
        if ($slug === '') {
            $this->addError('editSlug', 'Slug must contain at least one letter or number.');

            return;
        }

        $taken = Page::query()
            ->where('slug', $slug)
            ->where('id', '!=', $this->editingPageId)
            ->exists();

        if ($taken) {
            $this->addError('editSlug', 'That slug is already used by another page.');

            return;
        }

        $page = Page::query()->findOrFail($this->editingPageId);
        $page->update([
            'title' => $this->editTitle,
            'slug' => $slug,
        ]);

        $this->cancelEdit();
        session()->flash('atlas-status', 'Page updated.');
    }

    public function createPage(): void
    {
        $this->validate([
            'createTitle' => ['required', 'string', 'max:255'],
            'createSlug' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = filled(trim($this->createSlug))
            ? Str::slug($this->createSlug)
            : Str::slug($this->createTitle);

        if ($slug === '') {
            $this->addError('createTitle', 'Choose a title or slug that produces a valid URL segment.');

            return;
        }

        $base = $slug;
        $n = 1;
        while (Page::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        Page::query()->create([
            'title' => $this->createTitle,
            'slug' => $slug,
        ]);

        $this->createTitle = '';
        $this->createSlug = '';
        $this->resetValidation();
        session()->flash('atlas-status', 'Page created.');
    }

    public function render(PagePreviewService $pagePreview)
    {
        $pages = Page::query()->withCount('blocks')->orderByDesc('updated_at')->get();

        $previewUrls = [];
        if (Route::has('atlas.page.preview')) {
            foreach ($pages as $page) {
                $previewUrls[$page->id] = $pagePreview->signedUrl($page);
            }
        }

        return view('atlas::livewire.atlas-dashboard', [
            'pages' => $pages,
            'previewUrls' => $previewUrls,
            'hasPreview' => Route::has('atlas.page.preview'),
            'hasBuilder' => Route::has('atlas.page.builder'),
            'hasLive' => Route::has('atlas.page.live'),
            'hasSetup' => Route::has('atlas.setup'),
        ]);
    }
}
