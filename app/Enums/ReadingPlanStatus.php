<?php

namespace App\Enums;

enum ReadingPlanStatus: string
{
    case Planned = 'planned';
    case Overdue = 'overdue';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Planned => '計画中',
            self::Overdue => '期限切れ',
            self::Completed => '読了',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Planned => 'bg-blue-100 text-blue-800',
            self::Overdue => 'bg-red-100 text-red-800',
            self::Completed => 'bg-green-100 text-green-800',
        };
    }
}