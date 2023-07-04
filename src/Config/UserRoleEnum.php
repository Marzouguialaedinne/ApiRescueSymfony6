<?php

namespace App\Config;

enum UserRoleEnum: string
{
    case USER = 'ROLE_USER';
    case ASSOCIATION = 'ROLE_ASSOCIATION';
    case ADMIN = 'ROLE_ADMIN';
}
