<?php

namespace Domain\User\Models;

use App\Support\Traits\Uuid;
use Database\Factories\UserFactory;
use Domain\Subject\Models\Subject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


/**
 * Class User
 * 
 * @property string $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $user_avatar_path
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Uuid;

    /**
     * @property-read string DEFAULT_USER_AVATAR
     */
    public const DEFAULT_USER_AVATAR = 'default_user_avatar.png';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'user_avatar_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Encrypt the user password
     *  
     * @param mixed $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {

        $this->attributes['password'] = bcrypt($password);
    }


    /**
     * Get the value of user_avatar_path
     */
    public function getUserAvatarPathAttribute()
    {
        return $this->attributes['user_avatar_path'] ??             self::DEFAULT_USER_AVATAR;
    }

    /**
     * Get all of the subjects for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
