<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Department extends Model
{
    use HasFactory, Sortable;

    public $sortable = [
        'name',
        'location',
        'description',
        'updated_at'
    ];

    protected $fillable = [
        'name',
        'description',
        'location',
        'head_doctor_id'
    ];

    public function headDoctor()
    {
        return $this->belongsTo(Doctor::class, 'head_doctor_id');
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
