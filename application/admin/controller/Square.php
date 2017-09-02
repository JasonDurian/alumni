<?php

namespace app\admin\controller;

class Square extends ApiCommon
{
    protected $member_square_model;

    protected function _initialize()
    {
        parent::_initialize();
        $this->member_square_model = model('MemberSquare');
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

        $data = $this->member_square_model->getDataList($keywords, $page, $limit, $time);
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
        
        $data = $this->member_square_model->createData($param);
        if (!$data) {
            return resultArray(['error' => $this->member_square_model->getError()]);
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
        $data = $this->member_square_model->getDataById($id);
        if (!$data) {
            return resultArray(['error' => $this->member_square_model->getError()]);
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
        $data = $this->member_square_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $this->member_square_model->getError()]);
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
        $data = $this->member_square_model->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $this->member_square_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function deletes()
    {
        $param = $this->param;
        $data = $this->member_square_model->delDatas($param['ids']);
        if (!$data) {
            return resultArray(['error' => $this->member_square_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function enables()
    {
        $param = $this->param;
        $data = $this->member_square_model->enableDatas($param['ids'], $param['status']);
        if (!$data) {
            return resultArray(['error' => $this->member_square_model->getError()]);
        }
        return resultArray(['data' => '操作成功']);
    }
    
    /**
     * 获取本人头像和姓名信息
     */
    public function squareUser()
    {
//         $member_model = model('Member');
//         $data = $member_model->getSimpleUser($this->myself_id);
//         if (!$data) {
//             return resultArray(['error' => $member_model->getError()]);
//         }

        $data['avatar']   = $this->userCache['avatar'];
        $data['username'] = $this->userCache['name'] ? : $this->userCache['username'];
        return resultArray(['data' => $data]);
    }
    
    /**
     * 保存新建的留言
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function comment()
    {
        $square_comment_model = model('SquareComment');
        
        $param = $this->param;
        $param['commenter_id'] = $this->myself_id;
        $param['commenter_name'] = $this->userCache['name'] ? : $this->userCache['username'];
        $data = $square_comment_model->createData($param);
        if (!$data) {
            return resultArray(['error' => $square_comment_model->getError()]);
        }
    
        return resultArray(['data' => '留言成功']);
    }
    
}
