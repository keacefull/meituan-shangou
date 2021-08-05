<?php

namespace Keacefull\MeituanShangou\Request;

class Image extends BaseRequest
{
    /**
     * 图片上传API.
     *
     * @param array $params
     * @return mixed
     */
    public function upload(array $params)
    {
        return $this->post('image/upload', $params);
    }
}
