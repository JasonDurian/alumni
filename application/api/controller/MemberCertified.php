<?php

namespace app\api\controller;


use app\common\controller\ApiCommon;
// use think\Request;

class MemberCertified extends ApiCommon
{
    protected $member_certified_model;
    protected $member_model;

    protected function _initialize()
    {
        parent::_initialize();
        $this->member_certified_model = model('MemberCertified');
        $this->member_model = model('Member');
    }
    
    /**
     * 刷新用户页面
     *
     * @return \think\Response
     */
    public function index()
    {
//         $id = empty($this->userCache['id']) ? '' : $this->userCache['id'];
        $data = $this->updateCache();                                                       //读取cache中的用户信息
//         if (!$data) {
//             return resultArray(['error' => $this->member_certified_model->getError()]);
//         }
        return resultArray(['data' => $data]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {
//         action('CheckAuth/index');
        $data = $this->member_certified_model->certify($this->param);
        $userList = $this->updateCache($data);
        if ((!$data) || (!$userList)) {
            return resultArray(['error' => $this->member_certified_model->getError()]);
        }
//        $userList['check_status'] = 2;
        return resultArray([
            'data' => [
                'message'   => '信息保存成功，等待审核',
                'userInfo'  => $userList
            ]
        ]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = $this->member_certified_model->getDataById($id);
        if (!$data) {
            return resultArray(['error' => $this->member_certified_model->getError()]);
        }
        
        return resultArray(['data' => $data]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id)
    {
//         action('CheckAuth/index');
        $data = $this->member_certified_model->updateDataById($this->param, $id);
        $userList = $this->updateCache($id);
        if ((!$data) || (!$userList)) {
            return resultArray(['error' => $this->member_certified_model->getError()]);
        }
        return resultArray([ 
            'data' => [
                'message'   => '编辑成功',
                'userInfo'  => $userList
            ] 
            
        ]);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
    }
    
    /**
     * 用来获取最新信息和更新缓存
     * 
     * @param mixed $id
     * @return array|bool 更新过后的用户信息
     */
    protected function updateCache($id = 0)
    {
        /* 实时更新认证状态信息 */
        $member_id = !empty($this->userCache['member_id']) ? $this->userCache['member_id'] : $id;
        $check_status = $this->member_model->getCheckStatus($member_id);
        if ($check_status) $this->userCache['check_status'] = $check_status;

        if (!empty($id)) {
            $data = $this->member_certified_model->getDataById($id);
            if (!$data) {
                return $data;
            }
            
            $userList = array_merge($this->userCache, json_decode($data, true));
        } else {
            $userList = $this->userCache;
        }
        
        // 更新缓存 为了释放不用的内存
        cache('Auth_'.$this->authKey, $userList, config('login_session_vaild'));
        return $userList;
    }
}
