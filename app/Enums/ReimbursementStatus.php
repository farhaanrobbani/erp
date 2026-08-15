<?php

namespace App\Enums;

enum ReimbursementStatus: string
{
    case Pending = 'pending';
    case PmApproved = 'pm_approved';
    case FinanceApproved = 'finance_approved';
    case DirectorApproved = 'director_approved';
    case Paid = 'paid';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending (PM)',
            self::PmApproved => 'Disetujui PM',
            self::FinanceApproved => 'Disetujui Finance',
            self::DirectorApproved => 'Disetujui Direktur',
            self::Paid => 'Dibayar',
            self::Rejected => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::PmApproved => 'info',
            self::FinanceApproved => 'primary',
            self::DirectorApproved => 'success',
            self::Paid => 'success',
            self::Rejected => 'danger',
        };
    }
}
