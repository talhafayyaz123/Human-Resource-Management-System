<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopTemplateCard extends BaseModel
{
    use HasFactory;

    protected $table = "workshop_templates_cards";

    protected $fillable = ["workshop_template_id", "configuration"];

    protected $casts = ["configuration" => "array"];

    public function workshopTemplate()
    {
        return $this->belongsTo(WorkshopTemplate::class);
    }

    public function rows()
    {
        return $this->hasMany(WorkshopTemplateRow::class, 'workshop_templates_card_id');
    }
}
