<?php
/**
 * Random tokens using random_bytes() (e.g. private coaching session links, CSRF).
 */
class Token
{
    /** Token::hex(16) returns 32 hex characters. */
    public static function hex(int $bytes = 16): string
    {
        return bin2hex(random_bytes($bytes));
    }
}
