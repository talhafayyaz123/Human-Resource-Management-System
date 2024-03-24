<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MailTemplateAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'module' => $this->module,
            'mailTemplateId' => $this->mail_template_id,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'senderMail' => $this->sender_mail,
        ];
    }
}
