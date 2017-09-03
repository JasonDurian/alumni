<?php
// +----------------------------------------------------------------------
// | Description: 基础类，无需验证权限。
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;

use com\verify\HonrayVerify;
use app\common\controller\Common;
use think\Request;

class Base extends Common
{
    
    protected $member_model;
    
    protected function _initialize()
    {
        parent::_initialize();
        $this->member_model = model('Member');
    }
    
    public function index() {
//         $data = $this->member_model->login('Jason', 'jason');
//         if (!$data) {
//             echo $this->member_model->getError();
//         }
//         return $data;

//         $params = session_get_cookie_params();
//         return setcookie('enname', '', 0, $params['path'], $params['domain'], $params['secure'], true);

        $member_help_model = model('MemberSquare');
        $data = $member_help_model->getAboutMeList(1,2,8);
//         $myself_comments = Db::name('square_comment')->where(['commenter_id'=>8,'status'=>1])->column('square_id');
//         $myself_square = Db::name('member_square')->where(['member_id'=>8,'status'=>1])->column('id');
        
//         $data = array_merge($myself_comments, $myself_square);
//         $arr = array_unique($data);
//         sort($arr);

        return $data;
    }
    
    /**
     * 登陆
     */
    public function login()
    {   
        $username = $this->param['username'];
        $password = $this->param['password'];
        $data = $this->member_model->login($username, $password);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function relogin()
    {   
        $param = $this->param;
        $data = decrypt($param['rememberKey']);
        $username = $data['username'];
        $password = $data['password'];

        $data = $this->member_model->login($username, $password, '', true, true);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }    

    public function logout()
    {
        $param = $this->param;
        //清除cache
        cache('Auth_'.$param['authkey'], null);
        //清除session
//         session_start();
//         if (!empty($_SESSION)) {
//                 $_SESSION = [];
//             }
//         if (ini_get('session.use_cookies')) {
//             $params = session_get_cookie_params();
//             setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], true);
//         }
//         session_destroy();
        return resultArray(['data'=>'退出成功']);
    }

    public function getConfigs()
    {
        $systemConfig = cache('DB_CONFIG_DATA'); 
        if (!$systemConfig) {
            //获取所有系统配置
            $systemConfig = model('admin/SystemConfig')->getDataList();
            cache('DB_CONFIG_DATA', null);
            cache('DB_CONFIG_DATA', $systemConfig, 36000); //缓存配置
        }
        return resultArray(['data' => $systemConfig]);
    }

    public function getVerify()
    {
        $captcha = new HonrayVerify(config('captcha'));
        return $captcha->entry();
    }

    public function setInfo()
    {
        $this->member_model = model('User');
        $param = $this->param;
        $old_pwd = $param['old_pwd'];
        $new_pwd = $param['new_pwd'];
        $auth_key = $param['auth_key'];
        $data = $this->member_model->setInfo($auth_key, $old_pwd, $new_pwd);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    // miss 路由：处理没有匹配到的路由规则
    public function miss()
    {
        if (Request::instance()->isOptions()) {
            return ;
        } else {
//            config('default_return_type','html');
//            return $this->fetch('./dist/index.html');
            echo 'Alumni';
        }
    }
}
 