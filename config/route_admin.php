<?php
// +----------------------------------------------------------------------
// | Description: 基础框架路由配置文件
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honghaiweb.com>
// +----------------------------------------------------------------------

return [
    // 域名路由
    '__domain__'  => [
        'admin.jasonfj.com'    => 'admin',
//        'admin.alumni.app'    => 'admin',
    ],

    // 定义资源路由
    '__rest__' => [
        'admin/rules'		    => 'admin/rules',
        'admin/groups'		    => 'admin/groups',
        'admin/users'		    => 'admin/users',
        'admin/menus'		    => 'admin/menus',
        'admin/structures'	    => 'admin/structures',
        'admin/posts'           => 'admin/posts',
        'admin/members'		    => 'admin/members',
        'admin/square'		    => 'admin/square',
        'admin/comment'		    => 'admin/comment',
        'admin/message'		    => 'admin/message',
    ],

    '[admin]' => [
        // 【基础】登录
        'base/login'          => ['admin/base/login', ['method' => 'POST']],
        // 【基础】记住登录
        'base/relogin'	      => ['admin/base/relogin', ['method' => 'POST']],
        // 【基础】修改密码
        'base/setInfo'        => ['admin/base/setInfo', ['method' => 'POST']],
        // 【基础】退出登录
        'base/logout'         => ['admin/base/logout', ['method' => 'POST']],
        // 【基础】获取配置
        'base/getConfigs'     => ['admin/base/getConfigs', ['method' => 'POST']],
        // 【基础】获取验证码
        'base/getVerify'      => ['admin/base/getVerify', ['method' => 'GET']],
        // 【基础】上传图片
        'upload'              => ['admin/upload/index', ['method' => 'POST']],
        // 保存系统配置
        'systemConfigs'       => ['admin/systemConfigs/save', ['method' => 'POST']],
        // 【规则】批量删除
        'rules/deletes'       => ['admin/rules/deletes', ['method' => 'POST']],
        // 【规则】批量启用/禁用
        'rules/enables'       => ['admin/rules/enables', ['method' => 'POST']],
        // 【用户组】批量删除
        'groups/deletes'      => ['admin/groups/deletes', ['method' => 'POST']],
        // 【用户组】批量启用/禁用
        'groups/enables'      => ['admin/groups/enables', ['method' => 'POST']],
        // 【管理员】管理员缓存数据
        'users/query'         => ['admin/users/query', ['method' => 'GET']],
        // 【管理员】批量删除
        'users/deletes'       => ['admin/users/deletes', ['method' => 'POST']],
        // 【管理员】批量启用/禁用
        'users/enables'       => ['admin/users/enables', ['method' => 'POST']],
        // 【用户】用户缓存数据
        'members/query'       => ['admin/members/query', ['method' => 'GET']],
        // 【用户】批量删除
        'members/deletes'     => ['admin/members/deletes', ['method' => 'POST']],
        // 【用户】批量启用/禁用
        'members/enables'     => ['admin/members/enables', ['method' => 'POST']],
        // 【广场】批量删除
        'square/deletes'      => ['admin/square/deletes', ['method' => 'POST']],
        // 【广场】批量启用/禁用
        'square/enables'      => ['admin/square/enables', ['method' => 'POST']],
        // 【用户评论】批量删除
        'comment/deletes'     => ['admin/comment/deletes', ['method' => 'POST']],
        // 【用户评论】批量启用/禁用
        'comment/enables'     => ['admin/comment/enables', ['method' => 'POST']],
        // 【广场留言】批量删除
        'message/deletes'     => ['admin/message/deletes', ['method' => 'POST']],
        // 【广场留言】批量启用/禁用
        'message/enables'     => ['admin/message/enables', ['method' => 'POST']],
        // 【菜单】批量删除
        'menus/deletes'       => ['admin/menus/deletes', ['method' => 'POST']],
        // 【菜单】批量启用/禁用
        'menus/enables'       => ['admin/menus/enables', ['method' => 'POST']],
        // 【组织架构】批量删除
        'structures/deletes'  => ['admin/structures/deletes', ['method' => 'POST']],
        // 【组织架构】批量启用/禁用
        'structures/enables'  => ['admin/structures/enables', ['method' => 'POST']],
        // 【部门】批量删除
        'posts/deletes'       => ['admin/posts/deletes', ['method' => 'POST']],
        // 【部门】批量启用/禁用
        'posts/enables'       => ['admin/posts/enables', ['method' => 'POST']],

        // 【基础】index
        'base/index'          => ['admin/base/index', ['method' => 'GET']],

        '__miss__'         => 'admin/base/miss',
    ],

];