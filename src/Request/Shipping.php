<?php

namespace Keacefull\MeituanShangou\Request;

class Shipping extends BaseRequest
{
    /**
     * 创建或更新门店配送范围.
     *
     * @param array $params
     * @return mixed
     */
    public function save(array $params)
    {
        return $this->post('shipping/save', $params);
    }

    /**
     * 获取门店配送范围自配送
     *
     * @param array $params
     * @return mixed
     */
    public function list(array $params)
    {
        return $this->get('shipping/list', $params);
    }

    /**
     * 批量创建或更新配送范围.
     *
     * @param array $params
     * @return mixed
     */
    public function batchsave(array $params)
    {
        return $this->post('shipping/batchsave', $params);
    }

    /**
     * 获取门店配送范围混合送
     *
     * @param array $params
     * @return mixed
     */
    public function fetch(array $params)
    {
        return $this->get('shipping/fetch', $params);
    }

    /**
     * 删除配送范围.
     *
     * @param array $params
     * @return mixed
     */
    public function delete(array $params)
    {
        return $this->post('shipping/delete', $params);
    }

    /**
     * 新增或更新特殊时段配送范围.
     *
     * @param array $params
     * @return mixed
     */
    public function specSave(array $params)
    {
        return $this->post('shipping/spec/save', $params);
    }

    /**
     * 查询门店配送范围（企客专用）.
     *
     * @param array $params
     * @return mixed
     */
    public function corporateList(array $params)
    {
        return $this->get('shipping/corporate/list', $params);
    }

    /**
     * 批量创建/更新门店配送范围（企客专用）.
     *
     * @param array $params
     * @return mixed
     */
    public function corporateBatchSave(array $params)
    {
        return $this->post('shipping/corporate/batchsave', $params);
    }
}
