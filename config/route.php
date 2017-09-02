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
    // 域名路由
    '__domain__'  => [
        'api.jasonfj.com'      => 'api',
//        'api.alumni.app'    => 'api',
    ],

    '__rest__'	  => [
		'api/v1/member'	       => 'api/member',
        'api/v1/certify'       => 'api/memberCertified',
        'api/v1/comment'       => 'api/memberComment',
        'api/v1/contact'       => 'api/contact',
        'api/v1/square'        => 'api/square'
	],
    // 【基础】登录
    'api/v1/member/login'      => ['api/base/login', ['method' => 'POST']],
    // 【广场】获取用户
    'api/v1/square/squareuser' => ['api/square/squareUser', ['method' => 'GET']],
    // 【广场】添加留言
    'api/v1/square/comment'    => ['api/square/comment', ['method' => 'POST']],
    
    // MISS路由
    '__miss__'                 => 'api/base/miss',

];
