<?php

namespace Streats\Atlas\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $table = 'atlas_pages';

    protected $fillable = [
        'title',
        'slug',
    ];

    /**
     * @return HasMany<BlockModel, $this>
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(BlockModel::class, 'page_id')->orderBy('order');
    }

    /**
     * @return HasMany<PageRevision, $this>
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class, 'page_id')->latest('id');
    }
}
