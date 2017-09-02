<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class MemberComment extends Validate{

	protected $rule = [
		'content'      	=> 'require',
		'status'      	=> 'require|number|max:2',
    ];
	protected $message = [
		'content.require'       => '内容必须填写',
		'status'    	        => '状态（启用1|禁用2）',
    ];
}