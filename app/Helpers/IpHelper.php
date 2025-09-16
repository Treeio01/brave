<?php

namespace App\Helpers;

class IpHelper
{
    /**
     * Получить реальный IP адрес клиента с учетом Cloudflare
     */
    public static function getRealIp()
    {
        $request = request();
        
        // Cloudflare передает реальный IP в заголовке CF-Connecting-IP
        if ($request->hasHeader('CF-Connecting-IP')) {
            return $request->header('CF-Connecting-IP');
        }
        
        // Альтернативные заголовки для других прокси
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                
                // Проверяем, что IP валидный и не приватный
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        // Fallback на стандартный метод Laravel
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
        
        // Проверяем, что IP валидный
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return [
                'country' => 'Unknown',
                'country_code' => 'XX',
                'flag' => '🏳️'
            ];
        }
        
        // Используем бесплатный API ipapi.co
        try {
            $response = file_get_contents("http://ipapi.co/{$ip}/json/");
            $data = json_decode($response, true);
            
            if ($data && isset($data['country_name'])) {
                return [
                    'country' => $data['country_name'],
                    'country_code' => $data['country_code'],
                    'flag' => self::getCountryFlag($data['country_code']),
                    'city' => $data['city'] ?? null,
                    'region' => $data['region'] ?? null
                ];
            }
        } catch (\Exception $e) {
            // В случае ошибки возвращаем неизвестную страну
        }
        
        return [
            'country' => 'Unknown',
            'country_code' => 'XX',
            'flag' => '🏳️'
        ];
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
        
        // Конвертируем буквенный код в эмодзи флаг
        $flag = '';
        for ($i = 0; $i < 2; $i++) {
            $flag .= mb_chr(ord($countryCode[$i]) + 127397, 'UTF-8');
        }
        
        return $flag;
    }
}
