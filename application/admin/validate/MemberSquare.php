<?php

namespace app\admin\validate;
use think\Validate;
/**
* 设置模型
*/
class MemberSquare extends Validate{

	protected $rule = [
//		'member_id'     => 'require|number',
		'title'      	=> 'require',
		'type'      	=> 'require|number|max:2',
		'status'      	=> 'require|number|max:2',
    ];
	protected $message = [
//		'member_id.require'    	=> '用户ID必须',
//		'member_id.number'    	=> '用户ID格式不正确',
		'title.require'         => '标题必须填写',
		'type'    	            => '类型（帮帮忙1|活动2）',
		'status'    	        => '状态（启用1|禁用2）',
    ];
}