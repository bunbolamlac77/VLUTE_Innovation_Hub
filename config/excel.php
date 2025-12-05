<?php

return [
    // Chỉ cần đảm bảo map đúng phần mở rộng -> writer type của PhpSpreadsheet
    'extension_detector' => [
        'xlsx' => 'Xlsx',
        'xlsm' => 'Xlsx',
        'xls'  => 'Xls',
        'csv'  => 'Csv',
        'tsv'  => 'Csv',
        'html' => 'Html',
        'ods'  => 'Ods',
    ],

    // Các cấu hình khác dùng mặc định của package
];

