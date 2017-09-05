<?php

namespace app\api\controller;

use app\common\controller\ApiCommon;

class MemberComment extends ApiCommon
{
    protected $member_comment_model;
    protected $commenter_id;
    
    protected function _initialize()
    {
        parent::_initialize();

        if ($this->userCache['check_status'] !== 1) {
            exit(json_encode(['code'=>400, 'error'=>'此功能暂不对非认证用户开放']));
        }
        
        $this->member_comment_model = model('MemberComment');
        $this->commenter_id = $this->userCache['member_id'];            //评论人一定是现在登录的用户
        
        // 更新缓存 为了释放不用的内存
        cache('Auth_'.$this->authKey, $this->userCache, config('login_session_vaild'));
    }
    
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
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
        $info['commenter_id'] = $this->commenter_id;
        if ($info['commenter_id'] === $this->param['member_id']) {                   //是否为自己说说
            $info['type'] = 2;
        }
        $param = array_merge($this->param, $info);
        $data = $this->member_comment_model->createData($param); 
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
        }
        
        return resultArray(['data' => '发表成功']);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = $this->member_comment_model->getDataById($id);
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
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
        $data = $this->member_comment_model->updateDataById($this->param, $id, $this->commenter_id);
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
        }
        
        return resultArray(['data' =>  '编辑成功']);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $data = $this->member_comment_model->delDataById($id, $this->commenter_id);
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
        }
        
        return resultArray(['data' => '删除成功']);
    }
}
