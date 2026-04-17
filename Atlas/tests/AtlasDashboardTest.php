<?php

namespace Streats\Atlas\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Streats\Atlas\Livewire\AtlasDashboard;
use Streats\Atlas\Models\Page;

class AtlasDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('atlas.routes.enabled', true);
    }

    public function test_create_page_from_dashboard(): void
    {
        Livewire::test(AtlasDashboard::class)
            ->set('createTitle', 'About us')
            ->set('createSlug', '')
            ->call('createPage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('atlas_pages', [
            'title' => 'About us',
            'slug' => 'about-us',
        ]);
    }

    public function test_create_page_respects_custom_slug(): void
    {
        Livewire::test(AtlasDashboard::class)
            ->set('createTitle', 'Home')
            ->set('createSlug', 'landing')
            ->call('createPage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('atlas_pages', [
            'title' => 'Home',
            'slug' => 'landing',
        ]);
    }

    public function test_create_page_makes_unique_slug_when_taken(): void
    {
        Page::query()->create(['title' => 'A', 'slug' => 'dup']);

        Livewire::test(AtlasDashboard::class)
            ->set('createTitle', 'B')
            ->set('createSlug', 'dup')
            ->call('createPage')
            ->assertHasNoErrors();

        $this->assertTrue(Page::query()->where('slug', 'dup-1')->exists());
    }

    public function test_edit_page_updates_title_and_slug(): void
    {
        $page = Page::query()->create(['title' => 'Old', 'slug' => 'old']);

        Livewire::test(AtlasDashboard::class)
            ->call('startEdit', $page->id)
            ->assertSet('editTitle', 'Old')
            ->assertSet('editSlug', 'old')
            ->set('editTitle', 'New title')
            ->set('editSlug', 'new-slug')
            ->call('savePage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('atlas_pages', [
            'id' => $page->id,
            'title' => 'New title',
            'slug' => 'new-slug',
        ]);
    }
}
