<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class Member extends Validate{

	protected $rule = array(
		'username'  		=> 'require|unique:member',
	);
	protected $message = array(
		'username.require'    	=> '用户名必须填写',
		'username.unique'    	=> '用户名已存在',
	);
}