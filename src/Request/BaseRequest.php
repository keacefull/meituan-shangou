<?php

namespace Keacefull\MeituanShangou\Request;

use Keacefull\MeituanShangou\Config;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Psr\SimpleCache\CacheInterface;

class BaseRequest
{
    private $config;

    protected $client;

    protected CacheInterface $cache;

    protected $key = 'meituan.shangou.access_token';
    protected $refresh_key = 'meituan.shangou.refresh_token';

    public function __construct(Config $config, $client)
    {
        $this->config = $config;
        $this->client = $client;
        $this->cache = new Psr16Cache(new FilesystemAdapter(namespace: 'meituanshaoshou', defaultLifetime: 1500));
    }

    protected function get($action, array $options = [])
    {
        $params_head = [
            'timestamp' => time(),
            'app_id' => $this->config->app_id,
            'app_poi_code' => $this->config->app_poi_code
        ];
        if ($action != 'oauth/authorize' && $action != 'oauth/token') {
            $options['access_token'] = $this->config->access_token;
        }
        $options = array_merge($params_head, $options);
        $sig = $this->generateSignature($this->config->request_url . $action, $options);
        $options['sig'] = $sig;

        $url = $this->config->request_url . $action;

        return $this->client->request("GET", ltrim($url, '/'), ['query' => $options])->toArray();
    }

    protected function post($action, array $params = [])
    {
        $params_head = [
            'timestamp' => time(),
            'app_id' => $this->config->app_id,
            'app_poi_code' => $this->config->app_poi_code
        ];
        $params = array_merge($params_head, $params);
        if ($action != 'oauth/authorize' && $action != 'oauth/token') {
            $params['access_token'] = $this->config->access_token;
        }
        $url = $this->config->request_url . $action;
        $sig = $this->generateSignature($url, $params);
        $params['sig'] = $sig;
        return $this->client->request("POST", $url, ['body' => $params])->toArray();
    }
    
    private function generateSignature($action, $params)
    {
        $params = $this->concatParams($params);
        $str = $action . '?' . $params . $this->config->app_secret;

        return md5($str);
    }

    private function concatParams($params)
    {
        if (isset($params['img_data'])) {
            unset($params['img_data']);
        }
        ksort($params);
        $pairs = [];
        foreach ($params as $key => $val) {
            array_push($pairs, $key . '=' . $val);
        }

        return join('&', $pairs);
    }

}
