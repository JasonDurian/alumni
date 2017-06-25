<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;


class ApiCommon extends Common
{
    protected $adminCache;
    
    protected function _initialize()
    {
        parent::_initialize();
        
        $request = Request::instance();
        /*获取头部信息*/ 
        $header = $request->header();
        $sessionId = cookie('PHPSESSID');       //获取前端传回cookie中的PHPSESSID，真正跨域的时候可能会失败
        
        // 针对未登陆的校验
        if (empty($header['authkey']) || empty($sessionId)) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
        }
        
        $authKey = $header['authkey'];
        session_id($sessionId);                 //设置当前的sessionID,指定要用的session
        $this->adminCache = cache('Auth_'.$authKey);
        
        // 校验sessionid和authKey
        if ((session('adminname')!=='jason')||empty($this->adminCache)) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
        }

        // 检查账号有效性
        $userInfo = $this->adminCache['userInfo'];
        $map['id'] = $userInfo['id'];
        $map['status'] = 1;
        if (!Db::name('admin_user')->where($map)->value('id')) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>103, 'error'=>'账号已被删除或禁用']));   
        }
        // 更新缓存
        cache('Auth_'.$authKey, $this->adminCache, config('LOGIN_SESSION_VALID'));
        $authAdapter = new AuthAdapter($authKey);
        $ruleName = $request->module().'-'.$request->controller() .'-'.$request->action(); 
        if (!$authAdapter->checkLogin($ruleName, $this->adminCache['userInfo']['id'])) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>102,'error'=>'没有权限']));
        }
        $GLOBALS['userInfo'] = $userInfo;
    }
}
