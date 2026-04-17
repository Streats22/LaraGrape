<?php

namespace Streats\Atlas\Tests;

use Streats\Atlas\Support\AtlasUi;

class AtlasUiTest extends TestCase
{
    public function test_widget_library_appends_config_classes(): void
    {
        config(['atlas.ui.widget_library.aside' => 'ring-2 ring-pink-500']);

        $out = AtlasUi::widgetLibrary('aside', 'flex p-4');

        $this->assertStringContainsString('flex p-4', $out);
        $this->assertStringContainsString('ring-2 ring-pink-500', $out);
    }
}
