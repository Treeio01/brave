<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class IpHelper
{
    /**
     * Получить реальный IP адрес клиента с учетом Cloudflare
     */
    public static function getRealIp()
    {
        $request = request();


        if ($request->hasHeader('CF-Connecting-IP')) {
            $ip = $request->header('CF-Connecting-IP');
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
                return $ip;
            }
        }


        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);


                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                            return $ip;
                        }
                    } else {

                        return $ip;
                    }
                }
            }
        }


        return $request->ip();
    }

    /**
     * Получить информацию о стране по IP адресу
     */
    public static function getCountryInfo($ip = null)
    {
        if (!$ip) {
            $ip = self::getRealIp();
        }


        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
            return [
                'country' => 'Unknown',
                'country_code' => 'XX',
                'flag' => '🏳️'
            ];
        }


        $cacheKey = 'ip_country_' . md5($ip);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }


        try {
            $url = "http://ipapi.co/{$ip}/json/";
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Mozilla/5.0 (compatible; BraveApp/1.0)'
                ]
            ]);

            $response = file_get_contents($url, false, $context);

            if ($response === false) {
                throw new \Exception('Failed to fetch IP info');
            }

            $data = json_decode($response, true);

            if ($data && isset($data['country_name']) && $data['country_name'] !== null) {
                $result = [
                    'country' => $data['country_name'],
                    'country_code' => $data['country_code'] ?? 'XX',
                    'flag' => self::getCountryFlag($data['country_code'] ?? 'XX'),
                    'city' => $data['city'] ?? null,
                    'region' => $data['region'] ?? null
                ];


                Cache::put($cacheKey, $result, 3600);
                return $result;
            }
        } catch (\Exception $e) {

            Log::warning('IP geolocation failed for IP: ' . $ip . ' Error: ' . $e->getMessage());
        }


        try {
            $url = "http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,city,region";
            $context = stream_context_create([
                'http' => [
                    'timeout' => 3,
                    'user_agent' => 'Mozilla/5.0 (compatible; BraveApp/1.0)'
                ]
            ]);

            $response = file_get_contents($url, false, $context);

            if ($response !== false) {
                $data = json_decode($response, true);

                if ($data && $data['status'] === 'success' && isset($data['country'])) {
                    $result = [
                        'country' => $data['country'],
                        'country_code' => $data['countryCode'] ?? 'XX',
                        'flag' => self::getCountryFlag($data['countryCode'] ?? 'XX'),
                        'city' => $data['city'] ?? null,
                        'region' => $data['region'] ?? null
                    ];


                    Cache::put($cacheKey, $result, 3600);
                    return $result;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Fallback IP geolocation failed for IP: ' . $ip . ' Error: ' . $e->getMessage());
        }

        $result = [
            'country' => 'Unknown',
            'country_code' => 'XX',
            'flag' => '🏳️'
        ];


        Cache::put($cacheKey, $result, 600);
        return $result;
    }

    /**
     * Получить флаг страны по коду
     */
    private static function getCountryFlag($countryCode)
    {
        if (!$countryCode || strlen($countryCode) !== 2) {
            return '🏳️';
        }

        $countryCode = strtoupper($countryCode);


        $flag = '';
        for ($i = 0; $i < 2; $i++) {
            $flag .= mb_chr(ord($countryCode[$i]) + 127397, 'UTF-8');
        }

        return $flag;
    }
}
