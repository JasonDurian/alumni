<?php

namespace app\api\controller;


use app\common\controller\Common;
// use think\Request;

class Member extends Common
{
    protected $member_model;
    
    protected function _initialize()
    {
        parent::_initialize();
        $this->member_model = model('Member');
    }
    
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $type_id = !empty($this->param['type_id']) ? $this->param['type_id'] : '';
        $param = !empty($this->param['param']) ? $this->param['param'] : '';
        $keywords = !empty($this->param['keywords']) ? $this->param['keywords'] : '';
        $page = !empty($this->param['page']) ? $this->param['page'] : '';
        $limit = !empty($this->param['limit']) ? $this->param['limit'] : '';    
        $data = $this->member_model->getDataList($type_id, $param, $keywords, $page, $limit);
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
        
        $data = $this->member_model->createData($this->param);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        }
        
        return resultArray(['data' => $data]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = $this->member_model->getDataById($this->param['id']);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
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
        return resultArray(['data' => $id]);
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
        action('CheckAuth/index');
        $data = $this->member_model->updateDataById($this->param, $id);
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $data = $this->member_model->delDataById($id);       
        if (!$data) {
            return resultArray(['error' => $this->member_model->getError()]);
        } 
        return resultArray(['data' => '删除成功']);
    }
}
