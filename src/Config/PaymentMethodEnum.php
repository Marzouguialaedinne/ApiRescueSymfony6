<?php

namespace App\Config;

enum PaymentMethodEnum: string
{
    case CREDIT_CARD = 'Carte de crédit';
    case CASH = 'Espèce';
    case CHEQUE = 'Chèque';
    case BANK_TRANSFERT = 'Virement banquaire';
}
