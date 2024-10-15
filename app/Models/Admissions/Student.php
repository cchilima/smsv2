<?php

namespace App\Models\Admissions;

use App\Models\Users\User;
use App\Models\Academics\CourseLevel;
use App\Models\Academics\Grade;
use App\Models\Academics\PeriodType;
use App\Models\Academics\Program;
use App\Models\Academics\ProgramCourses;
use App\Models\Academics\StudyMode;
use App\Models\Academics\AcademicPeriodInformation;
use App\Models\Accounting\{Invoice, InvoiceDetail, Quotation, QuotationDetail, Statement, Receipt};
use App\Models\Enrollments\Enrollment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Student extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    /**
     * Update attributes using query builder and log audit.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return int
     */
    public static function updateAndAudit(array $attributes, array $values)
    {
        // Perform the update
        $affectedRows = static::where($attributes)->update($values);

        // Manually log the audit for each affected row
        foreach (static::where($attributes)->get() as $model) {
            Auditor::execute($model);
        }

        return $affectedRows;
    }

    protected $fillable = [
        'id',
        'graduated',
        'program_id',
        'academic_period_intake_id',
        'study_mode_id',
        'course_level_id',
        'period_type_id',
        'user_id',
        'admission_year',
        'semester'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
    public function intake()
    {
        return $this->belongsTo(AcademicPeriodIntake::class, 'academic_period_intake_id');
    }
    public function study_mode()
    {
        return $this->belongsTo(StudyMode::class, 'study_mode_id');
    }
    public function level()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }
    public function period_type()
    {
        return $this->belongsTo(PeriodType::class, 'period_type_id');
    }

    public function academic_info()
    {
        return $this->belongsTo(AcademicPeriodInformation::class, 'study_mode_id', 'study_mode_id')->where('academic_period_intake_id', $this->academic_period_intake_id);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function invoicesDetails()
    {
        return $this->hasManyThrough(InvoiceDetail::class, Invoice::class);
    }

    public function statements()
    {
        return $this->hasMany(Statement::class, 'collected_from');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function statementsWithoutInvoice()
    {
        return $this->hasMany(Statement::class, 'collected_from')->whereNull('invoice_id')->where('amount', '>', 0)->orderBy('created_at');
    }
    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id', 'id');
    }

    public function programCourses()
    {
        return $this->hasManyThrough(ProgramCourses::class, Grade::class, 'student_id', 'course_id', 'id', 'course_id');
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'id');
    }

    public function receiptsNonInvoiced()
    {
        return $this->hasMany(Receipt::class, 'student_id')->whereNull('invoice_id')->where('amount', '>', 0)->orderBy('created_at');
    }
}
