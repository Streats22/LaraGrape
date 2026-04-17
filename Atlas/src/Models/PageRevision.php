<?php

namespace Streats\Atlas\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageRevision extends Model
{
    protected $table = 'atlas_page_revisions';

    protected $fillable = [
        'page_id',
        'snapshot',
        'label',
    ];

    protected function casts(): array
    {
        return [
            'snapshot' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
