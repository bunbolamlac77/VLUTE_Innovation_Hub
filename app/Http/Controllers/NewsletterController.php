<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Đăng ký nhận bản tin (subscribe)
     */
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email','max:255'],
            'name' => ['nullable','string','max:255'],
        ]);

        // Upsert theo email
        $sub = NewsletterSubscription::updateOrCreate(
            ['email' => strtolower($data['email'])],
            [
                'name' => $data['name'] ?? null,
                'source' => $request->input('source', 'homepage'),
                'status' => 'active',
            ]
        );

        return back()->with('status', 'Đã đăng ký nhận bản tin thành công!');
    }
}

