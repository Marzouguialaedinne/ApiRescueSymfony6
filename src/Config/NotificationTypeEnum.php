<?php

namespace App\Config;

enum NotificationTypeEnum: string
{
    case NOTIFICATION_CANCEL = 'Annulation';
    case NOTIFICATION_WITHDRAWAL_HOUR = 'Heure de retrait';
    case NOTIFICATION_SICKNESS = 'Maladie';
}
