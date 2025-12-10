<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * After how many failed attempts a CAPTCHA token is required (if enabled).
     */
    private const CAPTCHA_THRESHOLD = 3;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'captcha_token' => ['sometimes', 'string'],
        ];
    }

    /**
     * Add extra validation to ensure password does not contain the email name.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $email = Str::lower((string) $this->input('email', ''));
            $password = Str::lower((string) $this->input('password', ''));
            $emailName = Str::before($email, '@');

            if ($emailName && Str::contains($password, $emailName)) {
                $validator->errors()->add('password', 'Mật khẩu không được chứa phần tên email.');
            }
        });
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $this->validateCaptchaIfNeeded();

        $throttleKey = $this->throttleKey();
        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($throttleKey);
            $this->logFailedAttempt('invalid_credentials', $throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Kiểm tra tài khoản có bị khóa không
        $user = Auth::user();
        if ($user && method_exists($user, 'isActive') && !$user->isActive()) {
            Auth::logout();
            $this->logFailedAttempt('inactive_account', $throttleKey, $user?->id);
            throw ValidationException::withMessages([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
            ]);
        }

        // Kiểm tra trạng thái phê duyệt (đối với các role cần duyệt: staff, enterprise)
        if ($user && method_exists($user, 'needsApproval') && method_exists($user, 'isApproved')) {
            if ($user->needsApproval() && !$user->isApproved()) {
                Auth::logout();
                $this->logFailedAttempt('not_approved', $throttleKey, $user?->id);
                throw ValidationException::withMessages([
                    'email' => 'Tài khoản của bạn đang chờ quản trị viên phê duyệt.',
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }

    /**
     * Determine if CAPTCHA should be required for this request.
     */
    protected function shouldRequireCaptcha(string $throttleKey = null): bool
    {
        $key = $throttleKey ?? $this->throttleKey();

        return $this->captchaEnabled() && RateLimiter::attempts($key) >= self::CAPTCHA_THRESHOLD;
    }

    /**
     * Validate CAPTCHA token when the threshold is reached.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateCaptchaIfNeeded(): void
    {
        if (!$this->shouldRequireCaptcha()) {
            return;
        }

        $token = (string) $this->input('captcha_token', '');
        if ($token === '') {
            throw ValidationException::withMessages([
                'captcha_token' => 'Vui lòng hoàn thành CAPTCHA để tiếp tục.',
            ]);
        }

        if (!$this->verifyCaptcha($token)) {
            throw ValidationException::withMessages([
                'captcha_token' => 'Xác thực CAPTCHA không hợp lệ. Vui lòng thử lại.',
            ]);
        }
    }

    /**
     * Verify CAPTCHA token with the configured provider.
     */
    protected function verifyCaptcha(string $token): bool
    {
        if (!$this->captchaEnabled()) {
            return true;
        }

        $secret = config('services.recaptcha.secret');

        $response = rescue(function () use ($secret, $token) {
            return Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $this->ip(),
            ]);
        }, null, false);

        return (bool) ($response?->json('success') ?? false);
    }

    /**
     * Determine if CAPTCHA is enabled via configuration.
     */
    protected function captchaEnabled(): bool
    {
        return (bool) config('services.recaptcha.secret');
    }

    /**
     * Log failed login attempts for security monitoring.
     */
    protected function logFailedAttempt(string $reason, string $throttleKey, ?int $userId = null): void
    {
        Log::warning('Failed login attempt', [
            'email' => $this->input('email'),
            'ip' => $this->ip(),
            'user_agent' => $this->userAgent(),
            'user_id' => $userId,
            'reason' => $reason,
            'attempts' => RateLimiter::attempts($throttleKey),
            'captcha_required' => $this->shouldRequireCaptcha($throttleKey),
            'throttle_key' => $throttleKey,
        ]);
    }
}
