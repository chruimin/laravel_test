<?php

namespace App\Classes\Search;

abstract class BaseQuery implements SearchQueryInterface
{
    protected $_query;

    public function __construct($query)
    {
        $this->_query = $query;
    }

    abstract public function encode($key, $value);
}