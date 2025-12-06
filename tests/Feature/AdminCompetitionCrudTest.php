<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCompetitionCrudTest extends TestCase
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

    public function test_admin_can_create_update_and_delete_competition(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        // Create
        $create = $this->post(route('admin.competitions.store'), [
            'title' => 'Comp 1',
            'description' => 'Mo ta',
            'banner_url' => 'https://example.com/banner.jpg',
            'start_date' => now()->format('Y-m-d H:i:s'),
            'end_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => 'open',
        ]);
        $create->assertRedirect(route('admin.competitions.index'));
        $this->assertDatabaseHas('competitions', ['title' => 'Comp 1', 'status' => 'open']);

        $comp = Competition::first();

        // Update
        $update = $this->put(route('admin.competitions.update', $comp), [
            'title' => 'Comp 1 updated',
            'description' => 'Mo ta 2',
            'banner_url' => 'https://example.com/banner2.jpg',
            'start_date' => now()->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'status' => 'judging',
        ]);
        $update->assertRedirect(route('admin.competitions.index'));
        $this->assertDatabaseHas('competitions', ['id' => $comp->id, 'title' => 'Comp 1 updated', 'status' => 'judging']);

        // Delete (soft delete)
        $delete = $this->delete(route('admin.competitions.destroy', $comp));
        $delete->assertRedirect(route('admin.competitions.index'));
        $this->assertSoftDeleted('competitions', ['id' => $comp->id]);
    }
}

