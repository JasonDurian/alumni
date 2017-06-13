<?php
namespace app\index\model;

use think\Model;

class SquareComment extends Model
{
    protected $name = 'square_comment';
    protected $createTime = 'create_time';
    protected $updateTime = false;
    protected $autoWriteTimestamp = true;
    protected $insert = [
        'status' => 1,
    ];
    
    /**
     * 添加留言
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
            $this->error = '留言失败';
            return false;
        }
    }
    
}
