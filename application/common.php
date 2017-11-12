<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 行为绑定
*/
\think\Hook::add('app_init','app\\common\\behavior\\InitConfigBehavior');

/**
 * 返回对象
* @param $array 响应数据
*/
function resultArray($array)
{
    if(isset($array['data'])) {
        $array['error'] = '';
        $code = 200;
    } elseif (isset($array['error'])) {
        $code = 400;
        $array['data'] = '';
    }
    return [
        'code'  => $code,
        'data'  => $array['data'],
        'error' => $array['error']
    ];
}

/**
 * 调试方法
 * @param  array   $data  [description]
 */
function p($data,$die=1)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($die) die;
}

/**
 * 用户密码加密方法
 * @param  string $str      加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

/**
 * 生成符合antd树选择的格式
 * @Author       Jason
 * @CreateTime  2017/10/2 18:51
 * @param array $list
 * @param int $cid
 * @return mixed
 */
function getAntdList($list = [], $cid = 0) {
    $childs = [];
    $n = 0;
    foreach ($list AS $key => $category) {
        if ($category['pid'] == $cid) {
            $childs[$n]['label'] = $category['title'];
            $childs[$n]['value'] = $childs[$n]['key'] = strval($category['id']);
            $children = getAntdList($list, $category['id']);
            if ($children !== false)
                $childs[$n]['children'] = $children;
            $n++;
        }
    }
    if (empty($childs)) {
        return false;
    } else {
        return $childs;
    }
}
