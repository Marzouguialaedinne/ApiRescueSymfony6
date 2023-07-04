<?php

namespace App\Config;

enum OrderStatusEnum: string
{
    case STATUS_SAVED = 'Sauvée';
    case STATUS_AWAITING_PAYMENT = 'En attente de règlement';
    case STATUS_PAID = 'Reglée';
    case STATUS_AWAITING_REFUND = 'En attente de remboursement';
    case STATUS_REFUNDED = 'Remboursée';
    case STATUS_CANCELLED = 'Annulée';
}
