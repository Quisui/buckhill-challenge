<?php

namespace App\Helpers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenHelper
{
    public static function jwtEncode($payload)
    {
        if (!isset($payload['exp'])) {
            $hours = config('confirmation_tokens.exp_hours');
            $payload['exp'] = strtotime("+{$hours} hours");
        }

        return JWT::encode($payload, config('app.jwt_secret'), config('app.jwt_algs')[0]);
    }

    public static function jwtDecode($token)
    {
        try {
            return JWT::decode($token, new Key(config('app.jwt_secret'), config('app.jwt_algs')[0]));
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return throw new Exception("Couldn't Decrypt your Token", 500);
        }
    }

    public static function jwtEncrypt($args)
    {
        return encrypt(self::jwtEncode($args));
    }

    public static function jwtDecrypt($token)
    {
        return self::jwtDecode(decrypt($token));
    }
}
