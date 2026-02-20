<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property int|null $school_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $phone
 * @property string|null $avatar
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read School|null $school
 * @property-read Student|null $student
 * @property-read Teacher|null $teacher
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'school_id',
        'phone',
        'avatar',
        'is_active',
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
     * Get the school
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get student profile
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get teacher profile
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if user is school admin
     */
    public function isSchoolAdmin(): bool
    {
        return $this->hasRole('school_admin');
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Get the dashboard route name for the user.
     */
    public function dashboardRoute(): string
    {
        if ($this->hasRole('super_admin'))
            return 'admin.dashboard';
        if ($this->hasRole('school_admin'))
            return 'school.dashboard';
        if ($this->hasRole('teacher'))
            return 'teacher.dashboard';
        if ($this->hasRole('student'))
            return 'student.dashboard';

        return 'login'; // Fallback: redirect unknown roles to login
    }
}
