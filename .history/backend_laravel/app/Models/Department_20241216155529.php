<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id'
    ];

    // Relationships
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    // Scopes
    public function scopeWithEmployeeCount(Builder $query): Builder
    {
        return $query->withCount('employees');
    }

    public function scopeWithActiveEmployeeCount(Builder $query): Builder
    {
        return $query->withCount(['employees' => function ($query) {
            $query->where('status', 'active');
        }]);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // Accessors
    public function getEmployeeCountAttribute(): int
    {
        return $this->employees()->count();
    }

    public function getActiveEmployeeCountAttribute(): int
    {
        return $this->employees()->where('status', 'active')->count();
    }

    public function getTotalPayrollAttribute(): float
    {
        return $this->employees()->where('status', 'active')->sum('salary');
    }
} 