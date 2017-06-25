<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Users extends ApiCommon
{
    protected $user_model;
    
    protected function _initialize()
    {
        parent::_initialize();
        $this->user_model = model('User');
    }

    public function index()
    {   
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';    
        $data = $this->user_model->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $param = $this->param;
        $data = $this->user_model->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $param = $this->param;
        $data = $this->user_model->createData($param);
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update()
    {
        $param = $this->param;
        $data = $this->user_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function delete()
    {
        $param = $this->param;
        $data = $this->user_model->delDataById($param['id']);       
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $param = $this->param;
        $data = $this->user_model->delDatas($param['ids']);  
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $param = $this->param;
        $data = $this->user_model->enableDatas($param['ids'], $param['status']);  
        if (!$data) {
            return resultArray(['error' => $this->user_model->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
    
    public function query()
    {
        $data = $this->adminCache['userInfo'];                                             //读取cache中的用户信息
        return resultArray(['data' => $data]);
    }
    
}
 