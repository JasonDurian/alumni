<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class Member extends Validate{

	protected $rule = array(
		'username'  		=> 'require|length:6,12',
		'check_status'      => 'require',
		'status'      	    => 'require',
	);
	protected $message = array(
		'username.require'    	=> '用户名必须填写',
		'username.length'    	=> '用户名长度在6到12位',
		'check_status.require'  => '审核状态必须选择',
		'status.require'    	=> '用户状态必须选择',
	);
}