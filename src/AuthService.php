<?php namespace Karo\Own;


use ReallySimpleJWT\Token;

class AuthService
{

    public $isValid;
    public $isSignValid;
    public $isTimeValid;
    public $payload;

    public function JWTValidate($tokenString, $key, $alg = ['HS512']) {
        if (! $tokenString)
            return false;
        if ($header = Token::getHeader($tokenString, $key))
            if (!$header['typ'] || $header['typ'] != 'JWT')
                return false;

        $this->payload     = Token::getPayload($tokenString, $key);
        $this->isSignValid = Token::validate($tokenString, $key);
        $this->isTimeValid = Token::validateExpiration($tokenString, $key);
        $this->isValid     = $this->isSignValid && $this->isTimeValid;
        return $this;
    }

    public function JWTGenerate($key, $payload = [], $expire_sec = null, $alg = ['HS512']) {
        $expire_sec = is_null($expire_sec)?604800:$expire_sec;
        $payload = [
            'iat' => time(),
            'exp' => time() + $expire_sec,
            'iss' => 'Gateway-',
            'info'=> $payload
        ];
        return Token::customPayload($payload, $key);
    }
}
