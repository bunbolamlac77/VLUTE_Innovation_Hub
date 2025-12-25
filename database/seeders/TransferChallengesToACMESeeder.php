<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransferChallengesToACMESeeder extends Seeder
{
    /**
     * Chuyển tất cả challenges sang Organization của ACME Corp
     */
    public function run(): void
    {
        // Tìm user ACME Corp
        $acmeUser = User::where('email', 'hr@acme.example')
            ->orWhere('company', 'ACME Corp')
            ->where('role', 'enterprise')
            ->first();

        if (!$acmeUser) {
            $this->command->warn('Không tìm thấy tài khoản ACME Corp. Vui lòng kiểm tra lại.');
            return;
        }

        $this->command->info("Tìm thấy tài khoản: {$acmeUser->name} ({$acmeUser->email})");

        // Tìm hoặc tạo Organization ACME Corp
        $acmeOrg = Organization::firstOrCreate(
            ['name' => 'ACME Corp'],
            [
                'type' => 'company',
                'website' => 'https://acme.example.com',
                'contact_email' => $acmeUser->email,
                'description' => 'Doanh nghiệp lĩnh vực bán lẻ và công nghệ.',
            ]
        );

        $this->command->info("Sử dụng Organization: {$acmeOrg->name} (ID: {$acmeOrg->id})");

        // Đếm số challenges hiện có
        $totalChallenges = Challenge::count();
        
        if ($totalChallenges === 0) {
            $this->command->warn('Không có challenge nào trong database.');
            return;
        }

        // Cập nhật tất cả challenges sang ACME Corp
        $updated = Challenge::query()
            ->where('organization_id', '!=', $acmeOrg->id)
            ->update(['organization_id' => $acmeOrg->id]);

        // Cập nhật profile của user để trỏ đến organization đúng
        if ($acmeUser->profile) {
            $acmeUser->profile->update(['organization_id' => $acmeOrg->id]);
            $this->command->info("✓ Đã cập nhật profile của user sang organization_id = {$acmeOrg->id}");
        } else {
            // Tạo profile nếu chưa có
            $acmeUser->profile()->create(['organization_id' => $acmeOrg->id]);
            $this->command->info("✓ Đã tạo profile mới cho user với organization_id = {$acmeOrg->id}");
        }

        $this->command->info("✓ Đã chuyển {$updated} challenges sang Organization ACME Corp");
        $this->command->info("  Tổng số challenges: {$totalChallenges}");
        $this->command->info("  Tất cả challenges hiện thuộc về: {$acmeOrg->name}");
    }
}

