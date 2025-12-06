<?php

namespace Tests\Feature;

use App\Models\ScientificNew;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AdminNewsCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin()
    {
        return User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
            'role' => 'admin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_update_and_delete_news(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        // Create
        $create = $this->post(route('admin.news.store'), [
            'title' => 'T1',
            'description' => 'Mo ta',
            'content' => 'Noi dung',
            'published_date' => Carbon::today()->format('Y-m-d'),
            'category' => 'AI',
            'author' => 'A',
            'source' => 'https://example.com',
            'image_url' => 'https://example.com/img.jpg',
        ]);
        $create->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('scientific_news', ['title' => 'T1']);

        $news = ScientificNew::first();

        // Update
        $update = $this->put(route('admin.news.update', $news), [
            'title' => 'T1 updated',
            'description' => 'Mo ta',
            'content' => 'Noi dung',
            'published_date' => Carbon::today()->format('Y-m-d'),
            'category' => 'AI',
            'author' => 'B',
            'source' => 'https://example.com',
            'image_url' => 'https://example.com/img2.jpg',
        ]);
        $update->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('scientific_news', ['title' => 'T1 updated', 'author' => 'B']);

        // Delete
        $delete = $this->delete(route('admin.news.destroy', $news));
        $delete->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseMissing('scientific_news', ['id' => $news->id]);
    }
}

