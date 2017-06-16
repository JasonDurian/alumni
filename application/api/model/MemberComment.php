<?php
namespace app\api\model;

use think\Db;
use think\Model;

class MemberComment extends Model
{
    protected $name = 'member_comment';
    protected $createTime = 'create_time';
    protected $updateTime = 'create_time';
    protected $autoWriteTimestamp = true;
    protected $insert = [
        'status' => 1,
    ];
    
    /**
     * 认证
     * @param  array   $param  [description]
     */
    public function createData($param)
    {
        
        $commentCount = $this
                    ->where([
                        'commenter_id' => $param['commenter_id'],
                        'member_id'    => $param['member_id']
                    ])
                    ->count();
        if ($commentCount > 0) {
            $this->error = '每人只能对相同用户评价一次';
            return false;
        }
    
        $this->startTrans();
    
        try {
    
            $this->data($param)->allowField(true)->save();
            $this->commit();
            return true;
    
        } catch(\Exception $e) {
    
            $this->rollback();
            $this->error = '评论失败';
            return false;
        }
    }
    
    /**
     * [getDataById 根据主键获取详情]
     * @DateTime  2017-02-10T21:16:34+0800
     * @param     string                   $member_id [外键]
     * @return    [array]
     */
    public function getDataById($member_id = '')
    {
        $member = Db::name('member')->field('username,avatar')->where(['member_id' => $member_id])->find();
        if (!$member) {
            $this->error = '暂无此数据';
            return false;
        }
        
        $data['comments'] = $this
            		    ->view('Member','username')
            		    ->view('MemberComment','commenter_id,type,content,create_time','MemberComment.commenter_id=Member.member_id')
            			->where(['MemberComment.member_id' => $member_id])
            			->order(['MemberComment.type'=>'desc'])                           //将自己的评价放在开头
            			->select();
        $data['userInfo'] = $member;
        return $data;
    }
    
    /**
     * 通过id修改评价
     * @param  array   $param         [description]
     * @param  string  $member_id     [评价页被修改用户的ID]
     * @param  string  $commenter_id  [当前修改评价的用户ID]
     */
    public function updateDataById($param, $member_id, $commenter_id)
    {
         
        $this->startTrans();
    
        try {
    
            $this->allowField(true)->save([
                'content'=>$param['content']        //$param中会包含id参数
            ], [
                'member_id' => $member_id,
                'commenter_id' => $commenter_id
            ]);
            $this->commit();
            return true;
    
        } catch(\Exception $e) {
    
            $this->rollback();
            $this->error = '编辑失败';
            return false;
        }
    
    }
    
    /**
     * [delDataById 根据id删除数据]
     * @linchuangbin
     * @DateTime  2017-02-11T20:57:55+0800
     * @param     string                   $member_id     [外键]
     * @param     string                   $commenter_id  [外键]
     * @param     boolean                  $delSon [是否删除子孙数据]
     * @return    [type]                           [description]
     */
    public function delDataById($member_id = '',  $commenter_id = '', $delSon = false)
    {
        if(!empty($commenter_id)) {
            $map = [
              'member_id'       => $member_id,
              'commenter_id'    => $commenter_id
            ];
        } else {
            return false;
        }
        
        $this->startTrans();
        try {
            $this->where($map)->delete();
            
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        }
    }
    
}
