<?php

namespace app\api\controller;


use app\common\controller\Common;

class Contact extends Common
{
    protected $member_certified_model;
    
    protected function _initialize()
    {
        parent::_initialize();
        $this->member_certified_model = model('MemberCertified');
    }
    
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        if (empty($this->param['type_id']) && empty($this->param['param']) &&
            empty($this->param['page']) && empty($this->param['limit'])) {
            $data = $this->member_certified_model->getCityList();
        } else {
            $type_id = !empty($this->param['type_id']) ? intval($this->param['type_id']) : '';
            $param = !empty($this->param['param']) ? trim($this->param['param']) : '';
//             $keywords = !empty($this->param['keywords']) ? $this->param['keywords'] : '';
            $page = !empty($this->param['page']) ? intval($this->param['page']) : 1;
            $limit = !empty($this->param['limit']) ? intval($this->param['limit']) : 15;
            $data = $this->member_certified_model->getDataList($type_id, $param, $page, $limit);
        }
        
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
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = $this->member_certified_model->getFullDataById($id);
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
}
