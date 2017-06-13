<?php
namespace app\index\model;

use think\Model;

class MemberCertified extends Model
{
    protected $name = 'member_certified';
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
     * 视图查询范围
     * @param unknown $query
     */
    protected function scopeMember($query)
    {
        $query->view('Member','avatar');
    }
    
    /**
     * 认证
     * @param  array   $param  [description]
     */
    public function certify($param)
    {
//         $id = $param['member_id'];
//         $checkData = Db::name('member')->where(['member_id'=>$id])->find();
//         if (!$checkData) {
//             $this->error = '暂无此数据';
//             return false;
//         }
    
        $this->startTrans();
    
        try {
    
            $this->data($param)->allowField(true)->save();
            $this->commit();
            return $this->id;
    
        } catch(\Exception $e) {
    
            $this->rollback();
            $this->error = '认证失败';
            return false;
        }
    }
    
    /**
     * [getDataById 根据主键获取详情]
     * @DateTime  2017-02-10T21:16:34+0800
     * @param     string                   $id [主键]
     * @return    [array]
     */
    public function getDataById($id = '')
    {
        $data = $this->get($id);
        if (!$data) {
            $this->error = '暂无此数据';
            return false;
        }
        return $data;
    }
    
    /**
     * [getDataById 根据主键获取完整详情]
     * @DateTime  2017-02-10T21:16:34+0800
     * @param     string                   $member_id [外键]
     * @return    [array]
     */
    public function getFullDataById($member_id = '')
    {
//         $data = $this
// 		      ->alias('a')
// 		      ->field('avatar,name,city,mobile,hide_mobile,email,wechat,qq,department,grade,work,company,position')
// 		      ->join('__MEMBER__ c','a.member_id=c.member_id')
// 		      ->where(['a.member_id'=>$member_id])
//            ->find();

        $data = $this
            ->scope('member')
	        ->view('MemberCertified','name,city,mobile,hide_mobile,email,wechat,qq,department,grade,work,company,position','MemberCertified.member_id=Member.member_id')
            ->where(['Member.member_id' => $member_id, 'Member.status' => 1])
            ->find();
        
        if (!$data) {
            $this->error = '暂无此数据';
            return false;
        }
       
        return $data;
    }
    
    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [int]                      $type_id  [类型：1、院系；2、年级；3、行业；4、城市；7、关键词搜索]
     * @param     [string]                   $param    [类型关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [每页数量]
     * @return    [array]                             [description]
     */
    public function getDataList($type_id, $param, $page, $limit)
	{
		$map = ['Member.status'=>1];
		if ($type_id && $param) {
    		switch ($type_id) {
    		    case 1: $map['department'] = $param; break;
    		    case 2: {                                             //年级需要配合学院
    		        $departGrade = explode('_', $param);
    		        $map['department'] = $departGrade[0];
    		        $map['grade'] = $departGrade[1];
    		    } break;
    		    case 3: $map['work'] = $param; break;
    		    case 4: $map['city'] = $param; break;
    		    case 7: $map['CONCAT(name,department,grade,city,work)'] = ['like', '%'.$param.'%']; break;
    		    default:break;
    		}
    		
    		$cacheName = $param . $type_id;
		}
		
		if ($type_id && empty($param)) {                              //全部行业
		    $cacheName = 'all' . $type_id;
		}
		
		if (!empty($cacheName)) {
		    $dataCount = $this->where(['status'=>1])->cache($cacheName, 300)->count('id');       //将符合条件的列表总数缓存5分钟
		}
		
// 		$list = $this
// 		      ->alias('a')
// 		      ->field('avatar,a.member_id,name,department,grade,company,position')
// 		      ->join('__MEMBER__ c','a.member_id=c.member_id')
// 		      ->where($map);
		
		$list = $this
		      ->scope('member')
		      ->view('MemberCertified','member_id,name,department,grade,company,position','MemberCertified.member_id=Member.member_id')
		      ->where($map);
		
		if ($page && $limit) {                                        // 若有分页
			$list = $list->page($page, $limit);
		}

		$list = $list->select();
		
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		
		return $data;
	}
	
	/**
	 * 获取通讯录城市列表
	 */
	public function getCityList() {
	    
	    $data = $this
    	    ->field('city,count(city) as num')
//     	    ->distinct(true)
    	    ->group('city')
            ->select();
	    
        if (!$data) {
            $this->error = '暂无此数据';
            return false;
        }
	    return $data;
	}
    
    /**
     * 通过id修改用户
     * @param  array   $param  [description]
     * @param  string  $id     [description]
     */
    public function updateDataById($param, $id)
    {
         
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此数据';
            return false;
        }
    
        $this->startTrans();
    
        try {
    
            $this->allowField(true)->save($param, ['id' => $id]);
            $this->commit();
            return true;
    
        } catch(\Exception $e) {
    
            $this->rollback();
            $this->error = '编辑失败';
            return false;
        }
    
    }
}
