<?php

namespace App\Config;

enum UserTypeEnum: string
{
    case PARTICULIER = 'part';
    case ASSOCIATION = 'assoc';
    case PROFESSIONNEL = 'pro';
}
