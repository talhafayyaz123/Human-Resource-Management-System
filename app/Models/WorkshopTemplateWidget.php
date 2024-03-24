<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopTemplateWidget extends BaseModel
{
    use HasFactory;

    protected $table = "workshop_templates_widgets";

    protected $fillable = ["type", "workshop_templates_column_id", "configuration"];

    protected $casts = ["configuration" => "array"];

    public function column()
    {
        return $this->belongsTo(WorkshopTemplateColumn::class, "workshop_templates_column_id");
    }
}
