<?php

namespace Tests\Feature;

use App\Models\ScientificNew;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ScientificNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_news_list(): void
    {
        ScientificNew::create([
            'title' => 'Tin 1',
            'description' => 'Mo ta',
            'content' => 'Noi dung',
            'author' => 'Tac gia',
            'source' => 'https://example.com',
            'image_url' => null,
            'published_date' => Carbon::today(),
            'category' => 'AI',
        ]);

        $res = $this->get('/scientific-news');
        $res->assertStatus(200);
        $res->assertSee('Tin 1');
    }

    public function test_show_displays_a_news_item(): void
    {
        $news = ScientificNew::create([
            'title' => 'Tin chi tiet',
            'description' => 'Mo ta',
            'content' => 'Noi dung chi tiet',
            'author' => null,
            'source' => null,
            'image_url' => null,
            'published_date' => Carbon::today(),
            'category' => 'Y hoc',
        ]);

        $res = $this->get('/scientific-news/'.$news->id);
        $res->assertStatus(200);
        $res->assertSee('Tin chi tiet');
        $res->assertSee('Bản tin mới');
    }
}

