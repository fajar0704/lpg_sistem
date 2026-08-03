<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'sub_pangkalan_id',
        'is_active',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is sub pangkalan.
     */
    public function isSubPangkalan(): bool
    {
        return $this->role === 'sub_pangkalan';
    }

    /**
     * Get the sub pangkalan relation.
     */
    public function subPangkalan()
    {
        return $this->belongsTo(SubPangkalan::class);
    }

    /**
     * Get the sub pangkalan transactions relation.
     */
    public function subPangkalanTransactions()
    {
        return $this->hasMany(SubPangkalanTransaction::class);
    }

    /**
     * Get the validated sub pangkalan transactions relation.
     */
    public function validatedSubPangkalanTransactions()
    {
        return $this->hasMany(SubPangkalanTransaction::class, 'validated_by');
    }

    /**
     * Get the reports relation.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
