<?php

namespace Streats\Atlas\Blocks;

/**
 * Built-in blocks you can register with Atlas::registerDefaultBlocks().
 */
class DefaultBlocks
{
    /**
     * @return list<class-string<Block>>
     */
    public static function all(): array
    {
        return [
            HeadingBlock::class,
            ParagraphBlock::class,
            HeroBlock::class,
            ButtonBlock::class,
            ImageBlock::class,
            DividerBlock::class,
            SpacerBlock::class,
            QuoteBlock::class,
            AlertBlock::class,
            CardBlock::class,
            TwoColumnBlock::class,
            EmbedBlock::class,
            StatsBlock::class,
            BadgeBlock::class,
            SectionHeaderBlock::class,
            ListBlock::class,
            FeatureBlock::class,
        ];
    }
}
