<?php

namespace App\Libraries;

use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class TokenHelper
{
    public static function returns($message = "", $code = 401)
    {
        return [
            'data'    => "",
            'code'    => ($message ? $code : 200),
            'message' => ($message ? "$message." : "")
        ];
    }

    public static function token_encode($id, $time)
    {
        $res = self::returns();

        if ($id && $time) {
            $payload = [
                'sub'   => $id,
                'time'  => $time,
                'iat'   => Carbon::now()->timestamp,
                'exp'   => Carbon::now()->addSeconds($time)->timestamp
            ];
            $res['data']    = JWTAuth::getJWTProvider()->encode($payload);
            $res['payload'] = $payload;
        } else {
            $res = self::returns("Invalid Type");
        }

        return $res;
    }

    public static function token_decode($token, $isCheckOnly = 0)
    {

        

        if ($token) {
            try {
                $res = self::returns();
                $payload = JWTAuth::getJWTProvider()->decode($token);

                if (isset($payload['exp']) && Carbon::now()->timestamp > $payload['exp']) {
                    return self::returns("Token has expired", 401);
                }

                if (!$isCheckOnly) $res['data'] = $payload['sub'];
                return $res;
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return self::returns("Token has expired", 401);
            } catch (\Exception $e) {
                return self::returns("Invalid token", 401);
            }
        } else {
            return self::returns("Token is missing", 401);
        }
    }
}
