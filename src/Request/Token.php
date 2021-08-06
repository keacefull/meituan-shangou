<?php

namespace Keacefull\MeituanShangou\Request;


class Token extends BaseRequest
{


    /**
     * 获取 access_token
     * @return mixed
     * @throws \Exception
     */
    public function getAccessToken()
    {
        $response = $this->get('oauth/authorize',
            [
                'response_type' => 'token'
            ]
        );

        if (empty($response['access_token'])) {
            throw new \Exception('Failed to get access_token.');
        }
        return $response;
    }

}
