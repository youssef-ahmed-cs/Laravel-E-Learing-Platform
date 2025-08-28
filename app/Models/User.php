<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'avatar',
        'phone',
        'username',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot(): void
    {
        parent::boot();
//        static ::created(static function (User $user) {
//            $user->email = strtolower($user->email);
//            $user->username = strtolower($user->username);
//            $user->bio = $user->bio ?? 'This user prefers to keep an air of mystery about them.';
//            $user->save();
//        });

//        static ::creating(static function (User $user) {
//            $user->email = strtolower($user->email);
//            $user->username = strtolower($user->username);
//            $user->bio = $user->bio ?? 'This user prefers to keep an air of mystery about them.';
//            #$user->save();
//        });

        static ::saving(static function (User $user) {
            $user->email = strtolower($user->email);
            $user->username = strtolower($user->username);
            $user->bio = $user->bio ?? 'This user prefers to keep an air of mystery about them.';
            #$user->save();
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

}
