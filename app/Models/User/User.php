<?php

namespace App\Models\User;

use Carbon\Carbon;
use App\Models\AbstructModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use App\Mail\ResetPassword\ResetPasswordEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Spatie\Permission\Traits\HasRoles;

class User extends AbstructModel implements AuthenticatableContract, AuthorizableContract, JWTSubject, CanResetPassword
{
    use Authenticatable, Authorizable, Notifiable;
    use HasRoles;

    public const TABLE = 'users';

    protected $table = self::TABLE;

    public const EMAIL_COLUMN = 'email';
    public const NAME_COLUMN = 'first_name';
    public const PRENOM_COLUMN = 'last_name';
    public const PHONE_COLUMN = 'phone';
    public const GENDER_COLUMN = 'gender';
    public const ADDRESS_COLUMN = 'address';
    public const EMAIL_VERIFIED_AT_COLUMN = 'email_verified_at';
    public const REMEMBER_TOKEN_COLUMN = 'remember_token';
    public const PASSWORD_COLUMN = 'password';
    public const LAST_CONNECTION_COLUMN = 'last_connection';
    public const COUNTRY_COLUMN = 'country';
    public const STATUS_COLUMN = 'active';
    public const START_DATE_COLUMN = 'start_date';
    public const END_DATE_COLUMN = 'end_date';

    public const ADMIN_ROLE = 'admin';

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    protected $guarded = [];
    protected $hidden = [
        self::PASSWORD_COLUMN,
    ];

    protected $casts = [
        self::EMAIL_VERIFIED_AT_COLUMN => 'date',
    ];

    public function getPassword(): string
    {
        return $this->getAttribute(self::PASSWORD_COLUMN);
    }

    public function getEmail(): string
    {
        return $this->getAttribute(self::EMAIL_COLUMN);
    }

    public function getName(): ?string
    {
        return $this->getAttribute(self::NAME_COLUMN);
    }

    public function getLastName() : ?string
    {
        return $this->getAttribute(self::PRENOM_COLUMN);
    }

    public function getPhone(): ?string
    {
        return $this->getAttribute(self::PHONE_COLUMN);
    }

    public function getGender(): int
    {
        return $this->getAttribute(self::GENDER_COLUMN);
    }

    public function getAddress(): ?string
    {
        return $this->getAttribute(self::ADDRESS_COLUMN);
    }

    public function getCountry(): string
    {
        return $this->getAttribute(self::COUNTRY_COLUMN);
    }

    public function getStartDate()
    {
        return $this->getAttribute(self::START_DATE_COLUMN);
    }

    public function getEndDate()
    {
        return $this->getAttribute(self::END_DATE_COLUMN);
    }

    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->getAttribute(self::EMAIL_VERIFIED_AT_COLUMN);
    }

    public function getStatus(): int
    {
        return $this->getAttribute(self::STATUS_COLUMN);
    }

    public function isActive(): bool
    {
        return $this->getStatus() === self::STATUS_ACTIVE;
    }

    public function getPrenom(): ?string
    {
        return $this->getAttribute(self::PRENOM_COLUMN);
    }

    public function getJWTIdentifier(): string
    {
        return $this->getId();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'user' => [
                self::ID_COLUMN    => $this->getId(),
                self::EMAIL_COLUMN => $this->getEmail(),
            ],
        ];
    }

    public function getEmailForPasswordReset()
    {
        return $this->getEmail();
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::send(new ResetPasswordEmail($this->getEmail(), $token));
    }
}
