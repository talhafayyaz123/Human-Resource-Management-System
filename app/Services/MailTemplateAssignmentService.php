<?php

namespace App\Services;

use App\Models\MailTemplateAssignment;

class MailTemplateAssignmentService
{
    public function saveMailTemplateAssignment(array $data)
    {
        foreach ($data['modules'] as $item) {
            MailTemplateAssignment::updateOrCreate(['module' => $item['module']], [
                'mail_template_id' => isset($item['mailTemplateId']) ? $item['mailTemplateId'] : '',
                'cc' => isset($item['cc']) ? $item['cc'] : '',
                'bcc' => isset($item['bcc']) ? $item['bcc'] : '',
                'sender_mail' => isset($item['senderMail']) ? $item['senderMail'] : '',
            ]);
        }
    }
}
