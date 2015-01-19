<?php

class PermissionFilter
{
    public function topicCheck()
    {
        $method = Request::method()
        $teamId = Route::input('teamId');

        if ($method == 'get') {
            // 获取action
            $action = $this->getAction(URL::current());

            if (!$teamId && !$action) {
                // 表示是index
                // check permission
            } elseif (!$teamId && $action == "create") {
                // 表示是create
                // check permission
            } elseif (is_int($teamId)) {
                if (!$action) {
                    // 表示是show
                    // check permission
                } elseif ($action == "edit") {
                    // 表示是edit
                    // check permission
                }
            }
        } elseif ($method == 'post' && !$teamId) {
            // 表示是store
            // check permission
        } elseif ($method == 'put' && is_int($teamId)) {
            // 表示是update
            // check permission
        } elseif ($method == 'delete' && is_int($teamid)) {
            // 表示是destroy
            // check permission
        }
        // 未在上面判断规则中的都不属于restful
    }


    private function getAction($url)
    {
        $value = substr(strrchr($url, '/'), 1);

        if (in_array($value, ['create', 'edit'])) {
            return $value;
        } else {
            return null;
        }
    }

}