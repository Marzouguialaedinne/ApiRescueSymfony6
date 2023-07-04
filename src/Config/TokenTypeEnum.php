<?php

namespace App\Config;

enum TokenTypeEnum: string
{
    case TOKEN_CONFIRM_REGISTER = 'confirmRegister';
    case TOKEN_RESET_PASS = 'resetPass';
}
