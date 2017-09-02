<?php
// +----------------------------------------------------------------------
// | Description: 基础类，无需验证权限。
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use com\verify\HonrayVerify;
use app\common\controller\Common;
use think\Request;

class Base extends Common
{
    protected $user_model;
    
    protected function _initialize()
    {
        parent::_initialize();
        $this->user_model = model('User');
    }
    
    public function index()
    {
//        file_put_contents(__DIR__ . '/test.php', $param['authKey']);
//        $data = $this->user_model->login('Mustard', 'jasonspassword');

//        $member_model = model('Member');
//        $data = $member_model->getDataById(7);
//        return $data;

        echo phpinfo();
    }
    
    public function login()
    {   
        $param = $this->param;
        $username = $param['username'];
        $password = $param['password'];
        $verifyCode = !empty($param['verifyCode'])? $param['verifyCode']: '';
        $isRemember = !empty($param['isRemember'])? $param['isRemember']: '';
        $data = $this->user_model->login($username, $password, $verifyCode, $isRemember);
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function relogin()
    {   
        $param = $this->param;
        $data = decrypt($param['rememberKey']);
        $username = $data['username'];
        $password = $data['password'];

        $data = $this->user_model->login($username, $password, '', true, true);
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }    

    public function logout()
    {
        $param = $this->param;
        cache('Auth_'.$param['authKey'], null);
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
        $param = $this->param;
        $old_pwd = $param['old_pwd'];
        $new_pwd = $param['new_pwd'];
        $auth_key = $param['auth_key'];
        $data = $this->user_model->setInfo($auth_key, $old_pwd, $new_pwd);
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    // miss 路由：处理没有匹配到的路由规则
    public function miss()
    {
        if (Request::instance()->isOptions()) {
            return ;
        } else {
            config('default_return_type','html');
            return $this->fetch('./admin/index.html');
        }
    }
}
 