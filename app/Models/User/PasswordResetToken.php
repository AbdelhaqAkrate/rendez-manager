<?php

namespace App\Models\User;

use Carbon\Carbon;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetToken extends Model
{
    public const TABLE = 'password_reset_tokens';

    public const EMAIL_COLUMN = 'email';
    public const TOKEN_COLUMN = 'token';

    public const EXPIRE_AFTER_MINUTES = 5;

    protected $guarded = [];

    protected $dates = [
        self::CREATED_AT,
    ];

    public $timestamps = false;

    public function getEmail(): string
    {
        return $this->getAttribute(self::EMAIL_COLUMN);
    }

    public function isExpired(): bool
    {
        return  Carbon::parse($this->getCreatedAt())->diffInMinutes(Carbon::now()) > self::EXPIRE_AFTER_MINUTES;
    }

    public function getToken(): string
    {
        return $this->getAttribute(self::TOKEN_COLUMN);
    }

    public function getCreatedAt()
    {
        return $this->getAttribute(self::CREATED_AT);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
