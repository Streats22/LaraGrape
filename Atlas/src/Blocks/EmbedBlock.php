<?php

namespace Streats\Atlas\Blocks;

use Streats\Atlas\Fields\TextField;

/**
 * Embeds a video or page by URL (YouTube / Vimeo friendly).
 */
class EmbedBlock extends Block
{
    public function name(): string
    {
        return 'embed';
    }

    public function fields(): array
    {
        return [
            new TextField('url', 'Embed URL'),
        ];
    }

    public function styles(): array
    {
        return ['default', 'rounded'];
    }

    public function rules(): array
    {
        return [
            'url' => ['required', 'string', 'max:2048'],
        ];
    }
}
