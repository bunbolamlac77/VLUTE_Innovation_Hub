<?php
return [
    // Nếu true: yêu cầu phải có ít nhất 1 mentor để được phép nộp ý tưởng
    'require_mentor_to_submit' => env('IDEAS_REQUIRE_MENTOR', true),

    // Nếu true: cho phép mentor chỉnh sửa nội dung ý tưởng (ngoài chủ sở hữu)
    'mentors_can_edit' => env('IDEAS_MENTORS_CAN_EDIT', false),

    // Giới hạn số mentor tối đa cho mỗi ý tưởng
    'max_mentors' => env('IDEAS_MAX_MENTORS', 3),

    // Nếu true: sau khi Trung tâm duyệt, chuyển lên BGH; nếu false: duyệt cuối luôn tại Trung tâm
    'require_board_approval' => env('IDEAS_REQUIRE_BOARD_APPROVAL', true),
];

