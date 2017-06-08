<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;


class ApiCommon extends Common
{
    protected $authKey;
    protected $userCache;
    
    protected function _initialize()
    {
        parent::_initialize();
        
        $request = Request::instance();
        /*获取头部信息*/ 
        $header = $request->header();
        
        $this->authKey = $header['authkey'];
        $sessionId = cookie('PHPSESSID');       //获取前端传回cookie中的PHPSESSID，真正跨域的时候可能会失败
        session_id($sessionId);                 //设置当前的sessionID,指定要用的session
        $this->userCache = cache('Auth_'.$this->authKey);
        
        // 校验sessionid和authKey
        if ((session('enname')!=='jason')||empty($this->authKey)||empty($this->userCache)) {
            exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
        }

        // 检查账号有效性
        $userId = $this->userCache['member_id'];
        $map['member_id'] = $userId;
        $map['status'] = 1;
        if (!Db::name('member')->where($map)->value('member_id')) {
            exit(json_encode(['code'=>103, 'error'=>'账号已被删除或禁用']));
        }
        
//         $authAdapter = new AuthAdapter($authKey);
//         $ruleName = $request->module().'-'.$request->controller() .'-'.$request->action(); 
//         if (!$authAdapter->checkLogin($ruleName, $userId)) {
//             exit(json_encode(['code'=>102,'error'=>'没有权限']));
//         }
//         $GLOBALS['userInfo'] = $userInfo;
    }
}
