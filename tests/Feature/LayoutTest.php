<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutTest extends TestCase
{
    public function test_page_starts_with_the_doctype(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringStartsWith('<!DOCTYPE html>', ltrim($html));
    }

    public function test_error_flash_message_is_shown(): void
    {
        $this->withSession(['error' => 'Photo upload failed!'])
            ->get('/')
            ->assertSee('Photo upload failed!');
    }

    public function test_success_flash_message_is_shown(): void
    {
        $this->withSession(['success' => 'New Room is added!'])
            ->get('/')
            ->assertSee('New Room is added!');
    }
}
