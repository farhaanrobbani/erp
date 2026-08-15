<?php

namespace App\Services;

use App\Models\LetterRequest;
use App\Models\MailArchive;
use App\Models\User;
use App\Notifications\LetterNumberIssued;
use Illuminate\Support\Facades\DB;

class LetterApprovalService
{
    public function __construct(private readonly LetterNumberGenerator $generator)
    {
    }

    /**
     * Approve pengajuan nomor surat:
     * - ubah status menjadi approved
     * - generate nomor surat otomatis (atomik)
     * - buat arsip surat keluar (mail_archives)
     * - kirim notifikasi ke pengaju
     */
    public function approve(LetterRequest $letterRequest, User $approver): LetterRequest
    {
        if ($letterRequest->status->value !== 'pending') {
            throw new \InvalidArgumentException('Pengajuan sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($letterRequest, $approver) {
            $letterNumber = $this->generator->generate(
                $letterRequest->letterCategory,
                $letterRequest->request_date
            );

            $letterRequest->update([
                'status' => 'approved',
                'generated_letter_number' => $letterNumber,
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            MailArchive::create([
                'type' => 'outgoing',
                'letter_number' => $letterNumber,
                'letter_category_id' => $letterRequest->letter_category_id,
                'letter_request_id' => $letterRequest->id,
                'subject' => $letterRequest->subject,
                'sender' => $approver->name,
                'recipient' => $letterRequest->recipient,
                'letter_date' => $letterRequest->request_date,
                'disposition' => null,
                'file_path' => '',
                'uploaded_by' => $approver->id,
            ]);

            $letterRequest->user->notify(new LetterNumberIssued($letterRequest));
        });

        return $letterRequest->fresh();
    }

    public function reject(LetterRequest $letterRequest, User $approver, ?string $reason): LetterRequest
    {
        $letterRequest->update([
            'status' => 'rejected',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);

        return $letterRequest->fresh();
    }
}
