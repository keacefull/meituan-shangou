<?php

namespace Keacefull\MeituanShangou\Request;


class Token extends BaseRequest
{


    /**
     * 获取商户token
     * @return mixed
     * @throws \Exception
     */
    public function getToken(string $code)
    {
        $response = $this->get('oauth/authorize',
            [
                'response_type' => 'code',
                'code' => $code
            ]
        );

        if (empty($response['access_token'])) {
            throw new \Exception('Failed to get access_token.');
        }

        return $response;
    }

    /**
     * 刷新token
     * @param string $refresh_token
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function refreshToken(string $refresh_token)
    {
        $response = $this->get('oauth/authorize',
            [
                'response_type' => 'refresh_token',
                'refresh_token' => $refresh_token
            ]
        );
        if (empty($response['access_token'])) {
            throw new \Exception('Failed to get access_token.');
        }
        return $response;
    }


}
