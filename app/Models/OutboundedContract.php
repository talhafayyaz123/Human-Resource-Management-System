<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutboundedContract extends BaseModel
{
    use HasFactory;

    protected $fillable = ['receiver_id', 'receiver_type', 'contract_type_id', 'contract_number', 'status', 'start_date', 'person_in_charge', 'termination_date', 'sign_date'];

    public function receiver()
    {
        return $this->belongsTo(Company::class, 'receiver_id');
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }

    public function attachments()
    {
        return $this->belongsToMany(Attachment::class, 'contract_attachment', 'contract_id', 'attachment_id')
            ->withPivot([
                'id', 'attachment_number', 'sla_infrastructure_id', 'sla_infrastructure_user_support', 'sla_service_time_id', 'sla_level_change_id', 'sla_level_incident_id',
                'is_fixed_price', 'hourly_rate', 'hours_per_year', 'total_price', 'user_id', 'start_date', 'termination_date', 'signed_date', 'sla_level_id', 'created_at'
            ])
            ->withTimestamps()->using(ContractAttachment::class);
    }

    public function contractFields()
    {
        return $this->belongsToMany(ContractField::class, 'selected_contract_fields', 'contract_id', 'contract_field_id')
            ->withPivot(['id', 'text', 'number', 'date', 'time', 'invoice', 'customer', 'offer', 'performance_record', 'offer_confirmation'])
            ->withTimestamps()->using(SelectedContractField::class);
    }

    public function personInCharge()
    {
        return $this->belongsTo(UserProfileData::class, 'person_in_charge');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    //this relationship is only for sorting purposes to be used 
    public function company()
    {
        return $this->belongsTo(Company::class, 'receiver_id');
    }
}
