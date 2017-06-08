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
        'contact'   => 'index/contact'
	],
    // 【基础】登录
    'member/login' => ['index/base/login', ['method' => 'POST']],

];
