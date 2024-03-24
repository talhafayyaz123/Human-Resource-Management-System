<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ContractAttachment extends Pivot
{
    use HasFactory;

    protected $table = 'contract_attachment';

    public $incrementing = true;

    protected $fillable = ['id'];
    public function ams()
    {
        return $this->hasOne(ApplicationManagementService::class, 'attachment_id');
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class, 'attachment_id');
    }

    public function slaInfrastructure()
    {
        return $this->belongsTo(SlaInfrastructure::class, 'sla_infrastructure_id');
    }

    public function slaServiceTime()
    {
        return $this->belongsTo(SlaServiceTime::class, 'sla_service_time_id');
    }

    public function slaLevel()
    {
        return $this->belongsTo(SlaLevel::class, 'sla_level_id');
    }

    public function slaLevelIncident()
    {
        return $this->belongsTo(SlaLevelIncident::class, 'sla_level_incident_id');
    }

    public function slaLevelChange()
    {
        return $this->belongsTo(SlaLevelChange::class, 'sla_level_change_id');
    }

    public function attachmentProducts()
    {
        return $this->hasMany(ContractAttachmentProduct::class, 'contract_attachment_id');
    }

    public function selectedContractFields()
    {
        return $this->hasMany(SelectedContractField::class, 'contract_attachment_id');
    }

    public function license()
    {
        return $this->hasMany(License::class, 'contract_attachment_id');
    }

    public function outboundedContract()
    {
        return $this->belongsTo(OutboundedContract::class, 'contract_id');
    }
}
