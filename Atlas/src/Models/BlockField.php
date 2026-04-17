<?php

namespace Streats\Atlas\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockField extends Model
{
    protected $table = 'atlas_block_fields';

    protected $fillable = [
        'block_id',
        'key',
        'value',
    ];

    /**
     * @return BelongsTo<BlockModel, $this>
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(BlockModel::class, 'block_id');
    }
}

