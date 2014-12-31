<?php

namespace App\Classes\Search;

class EqualQuery extends BaseQuery implements SearchQueryInterface
{

    /**
     * 将传入的key、value数据，编码到query中并返回
     *
     * @param string key 字段
     * @param string value 搜索值
     *
     * @return Query
     */
    public function encode($key, $value)
    {
        return $this->_query->where($key, $value);
    }
}