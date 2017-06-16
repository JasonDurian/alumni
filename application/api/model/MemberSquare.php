<?php
namespace app\api\model;

use think\Db;
use think\Model;

class MemberSquare extends Model
{
    protected $name = 'member_square';
    protected $createTime = 'create_time';
    protected $updateTime = false;
    protected $autoWriteTimestamp = true;
    protected $insert = [
        'status' => 1,
    ];
    
    /**
     * 与用户表的一对一模型（主从表之间的数据会有从属关系）
     * @return \think\model\relation\BelongsTo
     */
    /*public function member()
    {
        return $this->belongsTo('Member','member_id','member_id')->field('avatar');
    }*/
    
    /**
     * 与广场留言表的一对多模型
     */
    public function squareComment()
    {
        return $this->hasMany('SquareComment','square_id','id')->field('commenter_id,commenter_name,content');          //关联表数据中要有对应的主键(外键)
    }
    
    /**
     * 发布帮帮忙及活动
     * @param  array   $param  [description]
     */
    public function createData($param)
    {

        $this->startTrans();
    
        try {
    
            $this->data($param)->allowField(true)->save();
            $this->commit();
            return true;
    
        } catch(\Exception $e) {
    
            $this->rollback();
            $this->error = '发布失败';
            return false;
        }
    }
    
    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [每页数量]
     * @return    [array]                             [description]
     */
    public function getDataList($page, $limit)
    {
        $map = [
            'MemberSquare.status'   => 1,
        ];
    
        $dataCount = $this->where(['status'=>1])->count('id');      //不缓存的原因是在缓存时间内任意发布的帮帮忙都不会更新列表
        
        $list = $this
            ->view('MemberSquare','id,title,type,create_time')
            ->view('Member','avatar','Member.member_id=MemberSquare.member_id')
            ->where($map)
            ->order(['MemberSquare.id'=>'desc']);
    
        if ($page && $limit) {                                        // 若有分页
            $list = $list->page($page, $limit);
        }
    
        $list = $list->select();
        
        foreach ($list as $key => $val) {
            $val->squareComment;
        }
    
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;
    
        return $data;
    }
    
    /**
     * [getAboutMeList 与我有关列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [number]                   $page         [当前页数]
     * @param     [number]                   $limit        [每页数量]
     * @param     [number]                   $myself_id    [当前用户id]
     * @return    [array]                                  [description]
     */
    public function getAboutMeList($page, $limit, $myself_id)
    {
        $myself_comments = Db::name('square_comment')
            ->where(['commenter_id'=>$myself_id,'status'=>1])
//             ->cache('aboutmelistcomment', 300)      //将符合条件的列表总数缓存5分钟
            ->column('square_id');
        $myself_square = $this
            ->where(['member_id'=>$myself_id,'status'=>1])
//             ->cache('aboutmelistsquare', 300)
            ->column('id');
        //合并数组中的数值
        $merge = array_merge($myself_comments, $myself_square);
        $arr = array_unique($merge);                             
    
        $list = $this
            ->view('MemberSquare','id,title,type,create_time')
            ->view('Member','avatar','Member.member_id=MemberSquare.member_id')
            ->where('id','IN',$arr)
            ->order(['MemberSquare.id'=>'desc']);
    
        if ($page && $limit) {                                        // 若有分页
            $list = $list->page($page, $limit);
        }
    
        $list = $list->select();
    
        foreach ($list as $key => $val) {
            $val->squareComment;
        }
    
        $data['list'] = $list;
        $data['dataCount'] = count($arr,COUNT_NORMAL);
    
        return $data;
    }
    
    /**
     * [getDataById 根据主键获取详情]
     * @DateTime  2017-02-10T21:16:34+0800
     * @param     string                   $id [主键]
     * @return    [array]
     */
    public function getDataById($id = '')
    {
        $data = $this
            ->view('MemberSquare','id,title,content,params,type,create_time')
            ->view('Member','avatar','Member.member_id=MemberSquare.member_id')
            ->view('MemberCertified','name','MemberCertified.member_id=MemberSquare.member_id')            
			->where(['MemberSquare.id' => $id, 'MemberSquare.status' => 1])
			->find();
        
	    $data->squareComment;
		
        return $data;
    }
    
}
