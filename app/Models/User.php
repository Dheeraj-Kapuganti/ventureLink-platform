<?php

namespace App\Models;

// Instead of extending Illuminate\Foundation\Auth\User as Authenticatable,
// we extend the MongoDB specific Authenticatable class.
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * 
     * We include 'role' to allow assigning admin/founder/investor
     * during registration.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'contact_info',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define the relationship to the Startup model.
     * A User (founder) "has many" Startups.
     */
    public function startups()
    {
        return $this->hasMany(Startup::class, 'founder_id');
    }

    /**
     * Define the relationship to the Investment model.
     * A User (investor) "has many" Investments.
     */
    public function investments()
    {
        return $this->hasMany(Investment::class, 'investor_id');
    }

    /**
     * Define the relationship to the Bookmark model.
     * A User can save many startups.
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Define the relationship to the Comment model.
     * A User can write many comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Define the relationship to the Review model.
     * A User can write many reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Define the relationship to the Notification model.
     * Overrides the default Notifiable trait to use MongoDB.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * Define the relationship to unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }
}
