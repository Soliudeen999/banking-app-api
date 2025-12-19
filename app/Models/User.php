<?php

namespace App\Models;

use App\Enums\AccountCurrency;
use App\Enums\AccountType;
use App\Mail\VerificationEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'transaction_pin',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => AccountType::class,
            'currency' => AccountCurrency::class,
        ];
    }

    public function nextOfKins(): HasMany
    {
        return $this->hasMany(NextOfKin::class);
    }

    public function otps(): HasMany
    {
        return $this->hasMany(Otp::class);
    }

    public function sendEmailVerificationNotification()
    {
        $code = random_int(1000, 9999);

        $this->otps()->create([
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        if (! $this->hasVerifiedEmail()) {
            Mail::to($this)->send(new VerificationEmail($code, $this->name));
        }
    }

    public function scopeWhereEmailIs($query, $email)
    {
        return $query->where('email', $email);
    }
}
