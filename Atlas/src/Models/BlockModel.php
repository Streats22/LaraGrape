<?php

namespace Streats\Atlas\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlockModel extends Model
{
    protected $table = 'atlas_blocks';

    protected $fillable = [
        'page_id',
        'type',
        'style',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * @return HasMany<BlockField, $this>
     */
    public function fields(): HasMany
    {
        return $this->hasMany(BlockField::class, 'block_id');
    }
}
