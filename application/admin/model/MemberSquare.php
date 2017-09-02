<?php
namespace app\admin\model;

use think\Db;

class MemberSquare extends Common
{
    protected $name = 'member_square';
    protected $createTime = 'create_time';
    protected $updateTime = false;

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
        return $this->hasMany('SquareComment','square_id','id')
            ->field('commenter_id,commenter_name AS commenter,content AS comment_con');          //关联表数据中要有对应的主键(外键)
    }

    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [string]                   $keywords [类型关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [每页数量]
     * @param     [array]                   $time    [创建时间区间]
     * @return    [array]                             [description]
     */
    public function getDataList($keywords = '', $page = 1, $limit = 10, $time = [])
    {
        $map = [];
        if (!empty($keywords)) {
            $map['title|content'] = ['like', '%'.$keywords.'%'];
        }

        if (!empty($time)) {
            $map['create_time'] = ['between time', $time];
        }

        $dataCount = $this->where($map)->count('id');

        $list = $this->where($map)->order('id','DESC');

        if ($page && $limit) {                                        // 若有分页
            $list = $list->page($page, $limit);
        }

        $list = $list->select();

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

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
            ->view('MemberCertified','name','MemberCertified.member_id=MemberSquare.member_id')
			->where(['MemberSquare.id' => $id, 'MemberSquare.status' => 1])
			->find();
        
	    $data->squareComment;
		
        return $data;
    }
    
}
