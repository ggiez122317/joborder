<?php

namespace Tests\Feature;

use App\Models\IdTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdTemplateUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_local_template_urls_do_not_depend_on_app_url(): void
    {
        config([
            'app.url' => 'https://expired-tunnel.example',
            'filesystems.disks.public.driver' => 'local',
            'filesystems.disks.public.url' => 'https://expired-tunnel.example/storage',
        ]);

        $template = IdTemplate::create([
            'name' => 'Local template',
            'image_path' => 'id-templates/front.jpg',
            'back_image_path' => 'id-templates/back.jpg',
            'is_active' => true,
        ]);

        $this->assertSame('/storage/id-templates/front.jpg', IdTemplate::getActiveImageUrl());
        $this->assertSame('/storage/id-templates/back.jpg', IdTemplate::getActiveBackImageUrl());
        $this->assertSame('/storage/id-templates/front.jpg', $template->getImageUrl());
        $this->assertSame('/storage/id-templates/back.jpg', $template->getBackImageUrl());
    }
}
