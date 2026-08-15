<?php

namespace App\Notifications;

use App\Models\LetterRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LetterNumberIssued extends Notification
{
    use Queueable;

    public function __construct(public readonly LetterRequest $letterRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'letter_request_id' => $this->letterRequest->id,
            'title' => 'Nomor surat telah diterbitkan',
            'body' => sprintf(
                'Nomor surat Anda untuk "%s" telah diterbitkan: %s',
                $this->letterRequest->subject,
                $this->letterRequest->generated_letter_number
            ),
            'url' => '/admin/letter-requests/' . $this->letterRequest->id,
        ];
    }
}
