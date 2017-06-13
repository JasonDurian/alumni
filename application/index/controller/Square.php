<?php

namespace app\index\controller;


use app\common\controller\ApiCommon;

class Square extends ApiCommon
{
    protected $member_square_model;
    protected $myself_id;
    
    protected function _initialize()
    {
        parent::_initialize();
        
        if (empty($this->userCache['id'])) {
            exit(json_encode(['code'=>400, 'error'=>'此功能暂不对非认证用户开放']));
        }
        
        $this->member_square_model = model('MemberSquare');
        $this->myself_id = $this->userCache['member_id'];
        
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
        $page = !empty($this->param['page']) ? $this->param['page'] : '';
        $limit = !empty($this->param['limit']) ? $this->param['limit'] : '';
        $param = !empty($this->param['param']) ? $this->param['param'] : '';
        
        if ($param == 2) {
            $data = $this->member_square_model->getAboutMeList($page, $limit, $this->myself_id);
        } else {
            $data = $this->member_square_model->getDataList($page, $limit);
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
