<?php namespace Karo\Own;


use ReallySimpleJWT\Token;

class AuthService
{
    public function JWTValidate($tokenString, $alg = ['HS512'], $key = null) {
        if (! $key)
            $key = config('app.key');
        if (! $tokenString)
            return false;
        if ($header = Token::getHeader($tokenString, $key))
            if (!$header['typ'] || $header['typ'] != 'JWT')
                return false;
        $payload = Token::getPayload($tokenString, $key);
        return Token::validate($tokenString, $key);
    }

    public function JWTGenerate($payload = [], $expire = null, $alg = ['HS512'], $key = null) {
        if (! $key)
            $key = config('app.key');
        $payload = [
            'iat' => time(),
            'exp' => time() + 1000,
            'iss' => 'Gateway',
            'info'=> [
                'u'=> 1,
                'r'=> 'user'
            ]
        ];
        return Token::customPayload($payload, $key);
    }
}
