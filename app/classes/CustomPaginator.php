<?php

namespace App\Classes;

use Illuminate\Pagination\BootstrapPresenter;
use URL;
use Input;

class CustomPaginator
{

    /**
     * 使用方法 CustomPaginator::links($paginator)
     *
     * @param Paginator $paginator 可分页的模型
     * @param int $middleCount 中间部分显示的个数
     *
     * @return string html代码
     */
    public static function links($paginator, $middleCount=5)
    {
        static::validateMiddleCount($middleCount);

        $data = Input::all();
        unset($data['page']);
        $paginator->appends($data);

        $result = '';

        if ($paginator->getLastPage() > 1) {
            $result .= '<ul class="pagination">';
            $result .= static::getFirst($paginator);
            $result .= static::getPrevious($paginator);
            $result .= static::getMiddle($paginator, $middleCount);
            $result .= static::getNext($paginator);
            $result .= static::getLast($paginator);

            // 共xx页 和 输入页数跳转
            $result .= '<li>共<b class="page-total">' . $paginator->getLastPage() . '</b>页</li>';
            $result .= '<li><form action="' . URL::current() . '" method="GET">';
            $result .= '<input name="page" />';
            foreach($data as $key=>$value) {
                $result .= '<input class="hide" name="' . $key . '" value="' . $value . '" />';
            }
            $result .= '<button class="go ml5">GO</button>';
            $result .= '</form></li>';
            $result .= '</ul>';
        }

        return $result;
    }

    /**
     * 确保$middleCount不能是偶数，如果是偶数就抛出异常
     *
     * @param int $middleCount
     *
     * @return void
     */
    private static function validateMiddleCount($middleCount)
    {
        if($middleCount % 2 == 0) {
            throw new MiddleCountEvenException("middleCount 不能是偶数！！！");
        }
    }

    /**
     * 获取中间部分的代码
     *
     * @param Paginator $paginator 可分页的模型
     * @param int $middleCount
     *
     * @return string html代码
     */
    private static function getMiddle($paginator, $middleCount)
    {
        $presenter = new BootstrapPresenter($paginator);
        $half = ($middleCount - 1) / 2;

        if($paginator->getLastPage() <= $middleCount) {

            return $presenter->getPageRange(1, $paginator->getLastPage());

        } elseif($paginator->getCurrentPage() <= $half + 1) {

            return $presenter->getPageRange(1, $middleCount) . static::addThreePoints();

        } elseif($paginator->getLastPage() - $paginator->getCurrentPage() <= $half) {

            return static::addThreePoints() . $presenter->getPageRange($paginator->getLastPage()-$middleCount+1, $paginator->getLastPage());

        } else {

            return static::addThreePoints()
                            . $presenter->getPageRange($paginator->getCurrentPage()-$half, $paginator->getCurrentPage()+$half)
                            . static::addThreePoints();

        }

        // $presenter->getPageRange(1, $paginator->getLastPage());
    }

    /**
     * 获取“首页”的代码
     *
     * @param Paginator $paginator 可分页的模型
     *
     * @return string html代码
     */
    private static function getFirst($paginator)
    {
        if ($paginator->getCurrentPage() <= 1)
            return '<li class="first disabled"><span>首页</span></li>';
        else
           return '<li class="first"><a class="icon-chevron-left" href="' . $paginator->getUrl(1) . '">首页</a></li>';
    }

    /**
     * 获取“末页”的代码
     *
     * @param Paginator $paginator 可分页的模型
     *
     * @return string html代码
     */
    private static function getLast($paginator)
    {
        if ($paginator->getCurrentPage() >= $paginator->getLastPage())
            return '<li class="last disabled"><span>末页</span></li>';
        else
            return '<li class="last"><a href="' . $paginator->getUrl($paginator->getLastPage()) . '">末页</a></li>';
    }

    /**
     * 获取“上一页”的代码
     *
     * @param Paginator $paginator 可分页的模型
     *
     * @return string html代码
     */
    private static function getPrevious($paginator)
    {
        if ($paginator->getCurrentPage() <= 1)
            return '<li class="previous disabled"><span>上一页</span></li>';
        else
           return '<li class="previous"><a href="' . $paginator->getUrl($paginator->getCurrentPage()-1) . '">上一页</a></li>';
    }

    /**
     * 获取“下一页”的代码
     *
     * @param Paginator $paginator 可分页的模型
     *
     * @return string html代码
     */
    private static function getNext($paginator)
    {
        if ($paginator->getCurrentPage() >= $paginator->getLastPage())
            return '<li class="next disabled"><span>下一页</span></li>';
        else
            return '<li class="next"><a href="' . $paginator->getUrl($paginator->getCurrentPage()+1) . '">下一页</a></li>';
    }

    /**
     * 获取“...”的代码
     *
     * @return string html代码
     */
    private static function addThreePoints()
    {
        return '<li class="previous disabled"><span>...</span></li>';
    }

}

/**
 * 中间个数为偶数的异常
 */
class MiddleCountEvenException extends \Exception
{

}