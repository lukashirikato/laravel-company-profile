<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ClassModel;
use App\Models\Customer;
use App\Models\Package;
use Carbon\Carbon;

/**
 * App\Models\Schedule
 *
 * @property int $id
 * @property int|null $package_id (deprecated - use packages pivot)
 * @property int|null $class_id
 * @property string|null $schedule_label (required - unique label for schedule)
 * @property string|null $day
 * @property date|null $schedule_date
 * @property string|null $class_time
 * @property string|null $instructor
 * @property bool|null $show_on_landing
 * @property int|null $series_id
 * @property int|null $parent_schedule_id
 * @property bool $is_series_parent
 * @property bool $expand_to_month
 * @property-read \Illuminate\Database\Eloquent\Collection $packages (via pivot)
 * @property-read \Illuminate\Database\Eloquent\Collection $customers (via pivot)
 * @property-read ClassModel $classModel
 */
class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    // ✅ TAMBAHKAN SEMUA KOLOM YANG ADA DI DATABASE
    protected $fillable = [
        'class_id',
        'schedule_label',
        'day',
        'schedule_date',
        'class_time',
        'instructor',
        'show_on_landing',
        'series_id',
        'parent_schedule_id',
        'is_series_parent',
        'expand_to_month',
    ];

    // ℹ️ Tidak perlu mutator package_ids - Filament handle sync via relationship


    protected $casts = [
        'show_on_landing' => 'boolean',
        'class_time' => 'string',
        'schedule_date' => 'date',
        'is_series_parent' => 'boolean',
        'expand_to_month' => 'boolean',
    ];

    // ❌ REMOVE appends untuk mencegah N+1 queries di Filament
    // protected $appends = ['class_name'];

    // add the summary field so Filament can access it without needing to eager-load
    protected $appends = ['packageNames', 'packageSummary'];

    /**
     * Relasi parent schedule untuk series.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_schedule_id');
    }

    /**
     * Relasi child schedules untuk series.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_schedule_id');
    }

    /**
     * Ambil seluruh schedule dalam series yang sama.
     */
    public function getSeries()
    {
        if (!$this->series_id) {
            return collect([$this]);
        }

        return self::query()
            ->where('series_id', $this->series_id)
            ->orderBy('schedule_date')
            ->orderBy('class_time')
            ->get();
    }

    /**
     * Status series untuk tampilan tabel.
     */
    public function getSeriesStatusAttribute(): string
    {
        if ($this->is_series_parent) {
            $childCount = $this->children_count ?? $this->children()->count();

            return '👑 Parent' . ($childCount > 0 ? ' (' . $childCount . ')' : '');
        }

        if ($this->parent_schedule_id) {
            return '📅 Child';
        }

        return 'Single';
    }


    /**
     * Relasi ke satu package (opsional, legacy)
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Accessor untuk nama package (multi-package support)
     */
    public function getPackageNamesAttribute()
    {
        // Jika relasi packages sudah di-load, ambil semua nama
        if ($this->relationLoaded('packages')) {
            return $this->packages->pluck('name')->implode(', ');
        }
        // Fallback ke relasi package_id (legacy)
        return $this->package ? $this->package->name : '-';
    }

    /**
     * Simplified summary for display in tables.
     *
     * Instead of listing every package associated with a schedule, this
     * accessor returns a single representative label.  The rules are:
     *
     * 1. Prefer a known "category" if the package name contains one of the
     *    keywords defined below (e.g. "Reformer Pilates", "Exclusive Class").
     * 2. Otherwise use the first package's name, stripping any parenthetical
     *    details and appending "+N" when more than one package is attached.
     *
     * This keeps the admin table compact and avoids the long, wrapped lists
     * shown in the screenshot.
     */
    public function getPackageSummaryAttribute()
    {
        $packages = $this->relationLoaded('packages') ?
            $this->packages : $this->packages()->get();

        if ($packages->isEmpty()) {
            return '—';
        }

        // look for a category keyword inside the first package name
        $firstName = $packages->first()->name;
        $categories = [
            'Exclusive Class Program',
            'Reformer Pilates',
            'Single Visit Class',
            'Private Program',
            'Private Group Program',
        ];
        foreach ($categories as $cat) {
            if (str_contains($firstName, $cat)) {
                return $cat . ($packages->count() > 1 ? ' +' . ($packages->count() - 1) : '');
            }
        }

        // no keyword matched – just strip parentheses and optionally append a count
        $name = preg_replace('/\s*\(.*/', '', $firstName);
        if ($packages->count() > 1) {
            $name .= ' +' . ($packages->count() - 1);
        }

        return $name;
    }

    /**
     * Accessor untuk nama class
     */
    public function getClassNameDisplayAttribute()
    {
        return $this->classModel ? $this->classModel->class_name : '-';
    }

    /**
     * Accessor untuk class_name
     * Karena Blade template butuh $schedule->class_name
     */
    public function getClassNameAttribute()
    {
        // Priority: schedule_label > classModel->class_name
        return $this->schedule_label 
            ?? ($this->classModel ? $this->classModel->class_name : null) 
            ?? 'N/A';
    }

    /**
     * Accessor untuk schedule_date_formatted
     * Menggunakan kolom schedule_date dari DB jika ada,
     * fallback ke kalkulasi otomatis dari kolom 'day'
     * Format: d/M/Y (contoh: 27/Feb/2026)
     */
    public function getScheduleDateFormattedAttribute()
    {
        // Gunakan kolom DB jika terisi
        if ($this->attributes['schedule_date'] ?? null) {
            return Carbon::parse($this->attributes['schedule_date'])->format('d/M/Y');
        }

        // Fallback ke kalkulasi dari day
        if (!$this->day) {
            return '-';
        }

        try {
            $date = Carbon::now()->startOfWeek()->next($this->day);
            return $date->format('d/M/Y');
        } catch (\Exception $e) {
            return '-';
        }
    }

    /**
     * Relationships
     */
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }


    // Hapus relasi package() jika tidak dipakai, gunakan packages() untuk multi-package

    public function packages()
    {
        return $this->belongsToMany(
            Package::class,
            'package_schedules',
            'schedule_id',
            'package_id'
        );
    }

    public function customers()
    {
        return $this->belongsToMany(
            Customer::class,
            'customer_schedules',
            'schedule_id',
            'customer_id'
        )
        ->withPivot(['order_id', 'status', 'joined_at'])
        ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeVisible($query)
    {
        return $query->where('show_on_landing', 1);
    }

    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Get class start time (from class_time column)
     * 
     * @return \Carbon\Carbon|null
     */
    public function getClassStartTime(): ?Carbon
    {
        if (!$this->class_time || !$this->schedule_date) {
            return null;
        }

        try {
            return Carbon::parse($this->schedule_date->format('Y-m-d') . ' ' . $this->class_time);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get class end time (assume 60 minutes duration)
     * 
     * @return \Carbon\Carbon|null
     */
    public function getClassEndTime(): ?Carbon
    {
        $classStart = $this->getClassStartTime();
        if (!$classStart) {
            return null;
        }

        return $classStart->copy()->addMinutes(60);
    }

    /**
     * Get automatic checkout time based on class end + 15 minutes buffer.
     */
    public function getAutoCheckoutTime(): ?Carbon
    {
        $classEnd = $this->getClassEndTime();

        if (!$classEnd) {
            return null;
        }

        return $classEnd->copy()->addMinutes(15);
    }

    /**
     * Check apakah jam sekarang dalam time window check-in.
     * Window: 60 menit sebelum kelas sampai 30 menit setelah kelas dimulai.
     * 
     * @return bool
     */
    public function isWithinTimeWindow(): bool
    {
        $classStart = $this->getClassStartTime();
        if (!$classStart) {
            return true; // Jika tidak ada jadwal lengkap, allow check-in
        }

        try {
            $windowStart = $classStart->copy()->subMinutes(60);
            $windowEnd = $classStart->copy()->addMinutes(30);

            return now()->between($windowStart, $windowEnd);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error checking schedule time window: ' . $e->getMessage());
            return true; // Jika error, allow check-in
        }
    }

    /**
     * Get formatted time window untuk display.
     * 
     * @return string Contoh: "09:00 - 10:30"
     */
    public function getTimeWindowFormatted(): string
    {
        $classStart = $this->getClassStartTime();
        if (!$classStart) {
            return '-';
        }

        try {
            $windowStart = $classStart->copy()->subMinutes(60);
            $windowEnd = $classStart->copy()->addMinutes(30);
            return $windowStart->format('H:i') . ' - ' . $windowEnd->format('H:i');
        } catch (\Exception $e) {
            return '-';
        }
    }
}