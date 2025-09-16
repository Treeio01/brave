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
            $ip = $request->header('CF-Connecting-IP');
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
                return $ip;
            }
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
                
                // Проверяем, что IP валидный (IPv4 или IPv6)
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
                    // Для IPv4 проверяем, что не приватный
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                            return $ip;
                        }
                    } else {
                        // Для IPv6 возвращаем как есть (приватные IPv6 сложнее определить)
                        return $ip;
                    }
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
        
        // Проверяем, что IP валидный (IPv4 или IPv6)
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
            return [
                'country' => 'Unknown',
                'country_code' => 'XX',
                'flag' => '🏳️'
            ];
        }
        
        // Проверяем кэш (кэшируем на 1 час)
        $cacheKey = 'ip_country_' . md5($ip);
        $cached = \Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }
        
        // Используем бесплатный API ipapi.co с поддержкой IPv6
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
                
                // Кэшируем результат на 1 час
                \Cache::put($cacheKey, $result, 3600);
                return $result;
            }
        } catch (\Exception $e) {
            // Логируем ошибку для отладки
            \Log::warning('IP geolocation failed for IP: ' . $ip . ' Error: ' . $e->getMessage());
        }
        
        // Fallback: попробуем другой API для IPv6
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
                    
                    // Кэшируем результат на 1 час
                    \Cache::put($cacheKey, $result, 3600);
                    return $result;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Fallback IP geolocation failed for IP: ' . $ip . ' Error: ' . $e->getMessage());
        }
        
        $result = [
            'country' => 'Unknown',
            'country_code' => 'XX',
            'flag' => '🏳️'
        ];
        
        // Кэшируем даже неудачный результат на 10 минут
        \Cache::put($cacheKey, $result, 600);
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
        
        // Конвертируем буквенный код в эмодзи флаг
        $flag = '';
        for ($i = 0; $i < 2; $i++) {
            $flag .= mb_chr(ord($countryCode[$i]) + 127397, 'UTF-8');
        }
        
        return $flag;
    }
}
