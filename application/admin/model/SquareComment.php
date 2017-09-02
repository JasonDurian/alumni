<?php
namespace app\admin\model;

use think\Db;

class SquareComment extends Common
{
    protected $name = 'square_comment';
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
            $map['content'] = ['like', '%'.$keywords.'%'];
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

}
