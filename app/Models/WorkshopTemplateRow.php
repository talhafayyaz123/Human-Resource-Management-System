<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopTemplateRow extends BaseModel
{
    use HasFactory;

    protected $table = "workshop_templates_rows";

    protected $fillable = ["workshop_templates_card_id", "configuration"];

    protected $casts = ["configuration" => "array"];

    public function card()
    {
        return $this->belongsTo(WorkshopTemplateCard::class, 'workshop_templates_card_id');
    }

    public function columns()
    {
        return $this->hasMany(WorkshopTemplateColumn::class, 'workshop_templates_row_id');
    }
}
