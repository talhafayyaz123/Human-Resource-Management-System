<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopTemplateColumn extends BaseModel
{
    use HasFactory;

    protected $table = 'workshop_templates_columns';

    protected $fillable = ['workshop_templates_row_id', 'configuration'];

    protected $casts = ['configuration' => 'array'];

    public function row()
    {
        return $this->belongsTo(WorkshopTemplateRow::class, 'workshop_templates_row_id');
    }

    public function widgets()
    {
        return $this->hasMany(WorkshopTemplateWidget::class, 'workshop_templates_column_id');
    }
}
