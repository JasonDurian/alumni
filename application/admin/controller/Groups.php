<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Groups extends ApiCommon
{
    protected $group_model;
    protected $rule_model;

    protected function _initialize()
    {
        parent::_initialize();
        $this->group_model = model('Group');
        $this->rule_model  = model('Rule');
    }
    
    public function index()
    {   
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $data = $this->group_model->getDataList($keywords);
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $param = $this->param;
        if ($param['id'] > 0) {
            $data = $this->group_model->getDataById($param['id']);
            if (!$data) {
                return resultArray(['error' => $this->group_model->getError()]);
            }
        }
        $data['ruleList'] = getAntdList($this->rule_model->getNormalList(), 0);
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $param = $this->param;
        $data = $this->group_model->createData($param);
        if (!$data) {
            return resultArray(['error' => $this->group_model->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update()
    {
        $param = $this->param;
        $data = $this->group_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $this->group_model->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function delete()
    {
        $param = $this->param;
        $data = $this->group_model->delDataById($param['id'], true);       
        if (!$data) {
            return resultArray(['error' => $this->group_model->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $param = $this->param;
        $data = $this->group_model->delDatas($param['ids'], true);  
        if (!$data) {
            return resultArray(['error' => $this->group_model->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $param = $this->param;
        $data = $this->group_model->enableDatas($param['ids'], $param['status'], true);  
        if (!$data) {
            return resultArray(['error' => $this->group_model->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 