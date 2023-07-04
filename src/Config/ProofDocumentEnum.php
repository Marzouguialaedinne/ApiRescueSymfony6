<?php

namespace App\Config;

enum ProofDocumentEnum: string
{
    case ASSOCIATION_STATUS = 'Statut de l\'association';
    case ASSOCIATION_MEMBER_LIST = 'Liste des membres de l\'association';
    case ID_CARD = 'Carte d\'identité';
    case BANK_DETAILS = 'RIB';
    case OFFICIAL_JOURNAL_COPY = 'Copie de la parution au journal officiel';
    case PREFECTURAL_DECLARATION_RECEIPT = 'Récépissé de déclaration en préfecture';
}
