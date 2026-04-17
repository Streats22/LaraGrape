<?php

namespace Streats\Atlas\Support;

/**
 * Merges optional Tailwind / CSS classes from config onto package defaults.
 *
 * Publish `config/atlas.php` and set keys under `ui.widget_library` to append
 * classes per slot (e.g. brand colours for the widget panel).
 */
class AtlasUi
{
    /**
     * @param  non-empty-string  $slot  Config key under atlas.ui.widget_library (aside, title, help, list, item)
     */
    public static function widgetLibrary(string $slot, string $defaultClasses): string
    {
        $extra = trim((string) config("atlas.ui.widget_library.{$slot}", ''));

        return trim($defaultClasses.' '.$extra);
    }
}
