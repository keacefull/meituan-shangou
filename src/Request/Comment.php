<?php

namespace Keacefull\MeituanShangou\Request;

class Comment extends BaseRequest
{
    /**
     * 根据门店id批量查询评价信息.
     *
     * @param array $params
     * @return mixed
     */
    public function query(array $params)
    {
        return $this->get('comment/query', $params);
    }

    /**
     * 根据评价id添加商家回复.
     *
     * @param array $params
     * @return mixed
     */
    public function addReply(array $params)
    {
        return $this->post('poi/comment/add_reply', $params);
    }

    /**
     * 通过门店ID获取当前门店评分.
     *
     * @param array $params
     * @return mixed
     */
    public function score(array $params)
    {
        return $this->get('comment/score', $params);
    }
}
