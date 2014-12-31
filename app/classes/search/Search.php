<?php

namespace App\Classes\Search;

class Search
{

    /*
     * 精确搜索(equal)、模糊搜索(like)、范围搜索(between)
     */
    private $_types = ['equal', 'like', 'between'];

    /*
     * 储存格式化后的允许搜索配置
     */
    private $_enables = [
        'equal'=>[],
        'like'=>[],
        'between'=>[],
    ];

    /*
     * 使用者传入的query
     */
    private $_query;

    /**
     * 使用方法:
     * $query = (new CSearch($query, $enableOnSearch))->query($data);
     *
     * @param Query $query
     * @param array $enableOnSearch 各个字段被允许搜索的能力,包含精确搜索(equal)、模糊搜索(like)、范围搜索(between)
     * 可接受格式例子：(like和equal只能接受一个，如果都传进来了，默认是equal)
     * 子数组只有一个元素就表示，这个元素中所有字段都能用任意方式进行搜索
     * 子数组有两个元素表示，第一个元素中的所有字段都能用第二个元素中的方式进行搜索
     * 用数组或者带逗号分隔的字符串来表示多个
     * [
     *      ['id'], // 表示id允许所有的查询方式
     *      ['name', ['equal']], // 表示name允许精确搜索
     *      ['content, tags', 'like'], // 表示content、tag允许模糊搜索
     *      [['created_at', 'updated_at'], ['between', 'equal']], // 表示created_at、updated_at允许范围搜索和精确搜索
     * ]
     */
    public function __construct($query, array $enableOnSearch)
    {
        $this->_query = $query;
        $this->_enableOnSearchArrayParse($enableOnSearch);
    }

    /**
     * 执行搜索
     *
     * @param array $data 表单数据
     *
     * @return Query 组合完数据的Query对象 
     */
    public function query(array $data)
    {
        foreach($data as $key=>$value) {
            $this->_runDataParse($key, $value);
        }

        return $this->_query;
    }

    /**
     * 对每条input，根据预配置数据设定的允许搜索类型进行处理
     *
     * @param string $key 字段
     * @param string $value 搜索值
     *
     * @return void
     */
    private function _runDataParse($key, $value)
    {
        if(is_array($value)) {
            if(in_array($key, $this->_enables['between'])) {
                // 范围搜索
                $this->_query = (new BetweenQuery($this->_query))->encode($key, $value);
            }
        } elseif(in_array($key, $this->_enables['equal'])) {
            // 精确搜索
            $this->_query = (new EqualQuery($this->_query))->encode($key, $value);
        } elseif(in_array($key, $this->_enables['like'])) {
            // 模糊搜索
            $this->_query = (new LikeQuery($this->_query))->encode($key, $value);
        }
    }

    /**
     * 解析传入数组并分类好放入$_enables数组中
     *
     * @param array $enableOnSearch
     *
     * @return void
     */
    private function _enableOnSearchArrayParse(array $enableOnSearch)
    {
        // make all strings to array
        $enableOnSearch = $this->_beforeEnableOnSearchArrayParse($enableOnSearch);

        foreach($enableOnSearch as $entry) {
            $arrSize = count($entry);
            switch($arrSize) {
                case 1:
                    foreach($this->_types as $type) {
                        $this->_enablesAdd($type, $entry[0]);
                    }
                    break;
                case 2:
                    foreach($entry[1] as $type) {
                        if(in_array($type, $this->_types)) {
                            $this->_enablesAdd($type, $entry[0]);
                        }
                    }
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * 传入数组的预处理，主要是把子数组string全部转为array的表现方式
     *
     * @param array $enableOnSearch
     *
     * @return array 格式化后的数组
     */
    private function _beforeEnableOnSearchArrayParse(array $enableOnSearch)
    {
        for($i=0; $i<count($enableOnSearch); $i++) {
            for($j=0; $j<count($enableOnSearch[$i]); $j++) {
                $enableOnSearch[$i][$j] = $this->_stringToArray($enableOnSearch[$i][$j]);
            }
        }

        return $enableOnSearch;
    }

    /**
     * 为_enables数组添加内容
     *
     * @param string $type 类型
     * @param array $values 数据
     *
     * @return void
     */
    private function _enablesAdd($type, array $values)
    {
        if (is_array($values)) {
            foreach($values as $value) {
                $this->_enables[$type][] = trim($value);
            }
        }
    }

    /**
     * 把使用逗号分隔的字符串全部转为数组，其他数据不处理直接返回
     *
     * @param string $values
     *
     * @return array
     */ 
    private function _stringToArray($values)
    {
        if (is_string($values)) {
            $values = explode(',', $values);
        }

        return $values;
    }

}