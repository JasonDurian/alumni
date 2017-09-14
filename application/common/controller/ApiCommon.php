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
use Firebase\JWT\JWT;


class ApiCommon extends Common
{
    protected $authKey;
    protected $userCache;
    
    protected function _initialize()
    {
        parent::_initialize();
        
        $request = Request::instance();
        // 获取头部信息
        $header = $request->header();

        $secret_key = config('jwt_secret_key');
        $jwt = isset($header['authorization']) ? $header['authorization'] : '';
        // 去掉authorization中的Bearer
        $jwt = explode(' ', $jwt)[1];

        try {
            $decoded = (array) JWT::decode($jwt, $secret_key, array('HS256'));
        } catch(\Exception $e) {
            exit(json_encode(['code'=>101, 'error'=>$e->getMessage()]));
        }

        // 校验token
        if ($decoded['iss']!=='Jason'||$decoded['aud']!==config('cors_allow_origin')||empty($decoded['member_id'])) {
            exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
        }

        /*$this->authKey = $header['authkey'];
        $sessionId = cookie('PHPSESSID');       //获取前端传回cookie中的PHPSESSID，真正跨域的时候可能会失败
        session_id($sessionId);                 //设置当前的sessionID,指定要用的session
        $this->userCache = cache('Auth_'.$this->authKey);
        
        // 校验sessionid和authKey
        if ((session('enname')!=='jason')||empty($this->authKey)||empty($this->userCache)) {
            exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
        }*/

        // 检查账号有效性
        $map['member_id'] = $decoded['member_id'];
        $map['status'] = 1;
        // 获取用户认证信息，在需要认证用户特权的地方要用
        $decoded['check_status'] = Db::name('member')->where($map)->value('check_status');
        if ($decoded['check_status'] === false) {
            exit(json_encode(['code'=>103, 'error'=>'账号已被删除或禁用']));
        }

        // jwt负载
        $this->userCache = [
            'member_id' => $decoded['member_id'],
            'username' => $decoded['username'],
            'avatar' => $decoded['avatar'],
            'check_status' => $decoded['check_status'],
        ];

        // 更新到期时间
        $decoded['exp'] = time() + config('login_session_vaild');
        $jwtSecret = JWT::encode($decoded, $secret_key);
        header('id_token: ' . $jwtSecret);
        
//         $authAdapter = new AuthAdapter($authKey);
//         $ruleName = $request->module().'-'.$request->controller() .'-'.$request->action(); 
//         if (!$authAdapter->checkLogin($ruleName, $userId)) {
//             exit(json_encode(['code'=>102,'error'=>'没有权限']));
//         }
//         $GLOBALS['userInfo'] = $userInfo;
    }
}
