<?php

namespace App\Models;

// IMPORTANT: For MongoDB, we must use this specific Model class instead of the default Laravel one.
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Startup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This protects against "mass assignment" vulnerabilities.
     * Only these fields can be directly inserted via forms.
     */
    protected $fillable = [
        'title',
        'description',
        'funding_goal',
        'current_funding',
        'valuation',
        'category',
        'startup_stage',
        'deadline',
        'logo',
        'banner',
        'founder_id',
        'status',
    ];

    /**
     * Define the relationship to the User model.
     * A Startup "belongs to" a User (specifically, a founder).
     * 
     * In MongoDB, this will look for a document in the 'users' collection 
     * where the _id matches this startup's founder_id.
     */
    public function founder()
    {
        return $this->belongsTo(User::class, 'founder_id');
    }

    /**
     * Define the relationship to the Investment model.
     * A Startup "has many" Investments.
     */
    public function investments()
    {
        return $this->hasMany(Investment::class, 'startup_id');
    }

    /**
     * Define the relationship to the Bookmark model.
     * A Startup can be bookmarked by many users.
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Define the relationship to the Comment model.
     * A Startup can have many comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Define the relationship to the Review model.
     * A Startup can have many reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calculate the average rating.
     */
    public function averageRating()
    {
        if ($this->reviews()->count() === 0) {
            return 0;
        }
        return number_format($this->reviews()->avg('rating'), 1);
    }
}
