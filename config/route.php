<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__rest__'	  => [
		'member'	=> 'index/member',
        'certify'   => 'index/memberCertified',
        'comment'   => 'index/memberComment',
        'contact'   => 'index/contact',
        'square'    => 'index/square'
	],
    // 【基础】登录
    'member/login' => ['index/base/login', ['method' => 'POST']],
    // 广场获取用户
    'square/squareuser' => ['index/square/squareUser', ['method' => 'GET']],
    // 广场添加留言
    'square/comment' => ['index/square/comment', ['method' => 'POST']],
    
    // MISS路由
    '__miss__'  => 'index/base/miss',

];
