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
        if (!isset($options['response_type']) || $options['response_type'] != 'token')
        {
            $options['access_token'] = $this->getToken();
        }
        $options = array_merge($params_head, $options);
        $sig = $this->generateSignature($this->config->request_url.$action, $options);
        $options['sig'] = $sig;

        $url = $this->config->request_url.$action;

        return $this->client->request("GET", ltrim($url, '/'), ['query' => $options])->toArray();
    }

    private function generateSignature($action, $params)
    {
        $params = $this->concatParams($params);
        $str = $action.'?'.$params.$this->config->app_secret;

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
            array_push($pairs, $key.'='.$val);
        }

        return join('&', $pairs);
    }

    protected function post($action, array $params = [])
    {
        $params_head = [
            'timestamp' => time(),
            'app_id' => $this->config->app_id,
            'app_poi_code' => $this->config->app_poi_code
        ];
        $params = array_merge($params_head, $params);
        $params['access_token'] = $this->getToken();
        $url = $this->config->request_url.$action;
        $sig = $this->generateSignature($url, $params);
        $params['sig'] = $sig;
        return $this->client->request("POST", ltrim($url, '/'), ['body' => $params])->toArray();
    }


    public function getToken(): string
    {

        if ($token = $this->cache->get($this->key)) {
            return $token;
        }

        $response = $this->get('oauth/authorize',
            [
                'response_type' => 'token'
            ]
        );

        if (empty($response['access_token'])) {
            throw new \Exception('Failed to get access_token.');
        }

        $this->cache->set($this->key, $response['access_token'], \abs(\intval($response['expires_in']) - 100));
        $this->cache->set($this->refresh_key, $response['refresh_token'], \abs(\intval($response['re_expires_in']) - 100));

        return $response['access_token'];
    }

}
