<?php

namespace app\admin\controller;

class Comment extends ApiCommon
{
    protected $member_comment_model;

    protected function _initialize()
    {
        parent::_initialize();
        $this->member_comment_model = model('MemberComment');
    }
    
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $time = !empty($param['time']) ? explode(',', $param['time']): [];
        $page = !empty($param['page']) ? $param['page']: 1;
        $limit = !empty($param['limit']) ? $param['limit']: 10;

        $data = $this->member_comment_model->getDataList($keywords, $page, $limit, $time);
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
        $param = $this->param;
        $param['member_id'] = $this->myself_id;
        if(!empty($param['params'])) {                                                    //活动详情
            $param['type'] = 2;
            $param['params'] = json_encode($param['params'],JSON_UNESCAPED_UNICODE);      //不让存入的中文进行UNICODE转码
        }
        
        $data = $this->member_comment_model->createData($param);
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
        }
        
        return resultArray(['data' => '发布成功']);
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
        $param = $this->param;
        $data = $this->member_comment_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
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
        $param = $this->param;
        $data = $this->member_comment_model->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function deletes()
    {
        $param = $this->param;
        $data = $this->member_comment_model->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function enables()
    {
        $param = $this->param;
        $data = $this->member_comment_model->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $this->member_comment_model->getError()]);
        }
        return resultArray(['data' => '操作成功']);
    }
}
