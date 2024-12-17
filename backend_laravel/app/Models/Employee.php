<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'department_id',
        'status',
        'salary'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'hire_date' => 'date'
    ];

    // Relationships
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByDepartment(Builder $query, $departmentId): Builder
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    // Accessors & Mutators
    public function getFormattedSalaryAttribute(): string
    {
        return '₱' . number_format($this->salary, 2);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }
} 