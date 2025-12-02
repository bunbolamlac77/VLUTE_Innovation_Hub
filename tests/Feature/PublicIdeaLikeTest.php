<?php

namespace Tests\Feature;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicIdeaLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_and_unlike_public_approved_idea(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('secret'),
            'role' => 'student',
            'approval_status' => 'approved',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $idea = Idea::create([
            'owner_id' => $owner->id,
            'title' => 'Y tuong A',
            'slug' => 'y-tuong-a',
            'description' => 'Mo ta',
            'summary' => 'Tom tat',
            'content' => 'Noi dung',
            'status' => 'approved_final',
            'visibility' => 'public',
            'like_count' => 0,
        ]);

        $liker = User::create([
            'name' => 'Liker',
            'email' => 'liker@example.com',
            'password' => bcrypt('secret'),
            'role' => 'student',
            'approval_status' => 'approved',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($liker);

        // Like
        $res1 = $this->post('/ideas/'.$idea->id.'/like');
        $res1->assertStatus(200)->assertJson(['success' => true, 'liked' => true]);
        $this->assertDatabaseHas('ideas', ['id' => $idea->id, 'like_count' => 1]);

        // Unlike
        $res2 = $this->post('/ideas/'.$idea->id.'/like');
        $res2->assertStatus(200)->assertJson(['success' => true, 'liked' => false]);
        $this->assertDatabaseHas('ideas', ['id' => $idea->id, 'like_count' => 0]);
    }
}

