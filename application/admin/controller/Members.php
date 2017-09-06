<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Members extends ApiCommon
{
    protected $member_model;
    
    protected function _initialize()
    {
        parent::_initialize();
        $this->member_model = model('Member');
    }

    public function index()
    {   
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords'] : '';
        $time = !empty($param['time']) ? explode(',', $param['time']) : [];
        $status = isset($param['status']) && $param['status'] != '' ? intval($param['status']) : '';
        $page = !empty($param['page']) ? $param['page'] : 1;
        $limit = !empty($param['limit']) ? $param['limit'] : 10;

        $data = $this->member_model->getDataList($keywords, $page, $limit, $time, $status);
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $param = $this->param;
        $data = $this->member_model->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $param = $this->param;
        $data = $this->member_model->createData($param);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update()
    {
        $param = $this->param;
        $data = $this->member_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    public function delete()
    {
        $param = $this->param;
        $data = $this->member_model->delDataById($param['id']);       
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $param = $this->param;
        $data = $this->member_model->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $param = $this->param;
        $data = $this->member_model->enableDatas($param['ids'], $param['status']);  
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }

    public function checks()
    {
        $param = $this->param;
        $data = $this->member_model->checkMembers($param['ids'], $param['check_status']);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        }
        return resultArray(['data' => '操作成功']);
    }

    public function query()
    {
        $data = $this->adminCache['userInfo'];                                             //读取cache中的用户信息
        return resultArray(['data' => $data]);
    }
    
}
 