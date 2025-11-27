<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'enrollment_course_id',
        'date',
        'status',
        'notes',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the enrollment course that owns the attendance.
     */
    public function enrollmentCourse(): BelongsTo
    {
        return $this->belongsTo(EnrollmentCourse::class);
    }

    /**
     * Get the user who created this attendance record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to filter by date.
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by course (via enrollment_course).
     */
    public function scopeByCourse($query, $courseId)
    {
        return $query->whereHas('enrollmentCourse', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        });
    }

    /**
     * Scope a query to filter by student (via enrollment_course).
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->whereHas('enrollmentCourse.enrollment', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        });
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Check if attendance status is 'present'.
     */
    public function isPresent(): bool
    {
        return $this->status === 'present';
    }

    /**
     * Check if attendance status is 'absent'.
     */
    public function isAbsent(): bool
    {
        return $this->status === 'absent';
    }

    /**
     * Check if attendance status is 'late'.
     */
    public function isLate(): bool
    {
        return $this->status === 'late';
    }

    /**
     * Check if attendance status is 'justified'.
     */
    public function isJustified(): bool
    {
        return $this->status === 'justified';
    }

    /**
     * Get translated status label.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'present' => 'Presente',
            'absent' => 'Ausente',
            'late' => 'Tarde',
            'justified' => 'Justificado',
            default => ucfirst($this->status),
        };
    }
}
