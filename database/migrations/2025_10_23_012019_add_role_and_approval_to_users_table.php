<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // role: student|staff|enterprise|admin
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student')->index();
            }

            // trạng thái duyệt: pending|approved|rejected
            if (!Schema::hasColumn('users', 'approval_status')) {
                $table->string('approval_status')->default('approved')->index();
            }

            // người duyệt + thời điểm duyệt
            if (!Schema::hasColumn('users', 'approved_by')) {
                // Thêm cột trước
                $table->unsignedBigInteger('approved_by')->nullable()->index();
            }
            if (!Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }

            // thông tin DN (nếu có)
            if (!Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable();
            }
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable();
            }
            if (!Schema::hasColumn('users', 'interest')) {
                $table->string('interest')->nullable();
            }
        });

        // Thêm khóa ngoại cho approved_by nếu có cột mà CHƯA có FK
        // (Laravel không có helper check FK sẵn; phần này là tuỳ chọn. Nếu muốn chắc chắn, bạn có thể bỏ qua,
        // hoặc tạo migration riêng để add FK.)
        if (Schema::hasColumn('users', 'approved_by')) {
            Schema::table('users', function (Blueprint $table) {
                // Tránh lỗi nếu FK đã tồn tại bằng cách đặt tên khác hoặc bỏ qua phần này
                // Ở đây KHÔNG thêm FK để đảm bảo an toàn nhiều môi trường.
                // Nếu bạn muốn thêm FK: $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xoá nhẹ nhàng nếu tồn tại
            // if (Schema::hasColumn('users','approved_by')) { $table->dropForeign(['approved_by']); } // nếu bạn có tạo FK ở up()
            if (Schema::hasColumn('users', 'approved_by')) {
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('users', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('users', 'approval_status')) {
                $table->dropColumn('approval_status');
            }

            if (Schema::hasColumn('users', 'company')) {
                $table->dropColumn('company');
            }
            if (Schema::hasColumn('users', 'position')) {
                $table->dropColumn('position');
            }
            if (Schema::hasColumn('users', 'interest')) {
                $table->dropColumn('interest');
            }

            // KHÔNG drop 'role' vì có thể đã có từ migration cũ; nếu muốn rollback hoàn toàn thì mở dòng dưới:
            // if (Schema::hasColumn('users','role')) { $table->dropColumn('role'); }
        });
    }
};