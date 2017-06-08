<?php
namespace app\normal\controller;

use think\Controller;

class Citypicker extends Controller {
    
    protected $region_model;
    
    protected function _initialize()
    {
        parent::_initialize();
        
        $this->region_model= db('Region',[
            // 数据库类型
            'type'            => 'mysql',
            // 服务器地址
            'hostname'        => 'localhost',
            // 数据库名
            'database'        => 'store',
            // 用户名
            'username'        => 'root',
            // 密码
            'password'        => '123456',
            // 端口
            'hostport'        => '3306',
            // 连接dsn
            'dsn'             => '',
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => 'utf8',
            // 数据库表前缀
            'prefix'          => 'yz_',
            // 数据库调试模式
            'debug'           => true,
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'          => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'     => false,
            // 读写分离后 主服务器数量
            'master_num'      => 1,
            // 指定从服务器序号
            'slave_no'        => '',
            // 是否严格检查字段是否存在
            'fields_strict'   => true,
            // 数据集返回类型
            'resultset_type'  => 'array',
            // 自动写入时间戳字段
            'auto_timestamp'  => false,
            // 时间字段取出后的默认时间格式
            'datetime_format' => 'Y-m-d H:i:s',
            // 是否需要进行SQL性能分析
            'sql_explain'     => false,
        ]);
    }
    
    /**
     * 从所有地区数据表获取城市信息
     * @return \think\Collection|\think\db\false|PDOStatement|string
     */
    public function initCities() {
        
        $hotCities = $this->region_model
        ->field('id,fullname')
        ->where(['type' => 2, 'is_hot' => 1])
        ->select();
        
        $aliaCities = $this->region_model
        ->field('id,fullname,pinyin_f')
        ->where('type',  2)
        ->select();
        
        $cities = [];
        
        foreach ($aliaCities as $k => $vo) {
            $firstLetter = strtoupper(substr($vo['pinyin_f'], 0, 1));   //取城市拼音的首字母，并且大写
            unset($vo['pinyin_f']);                                     //删除城市数组中的拼音列
            $firstLetter && $cities[$firstLetter][] = $vo;              //首字母不为空时
        }
        
        ksort($cities);                                                 //按键值 对数组进行排序
        
        return $cities;
        
    }
    
    /**
     * 把地区数据表生成的城市数据插入城市数据表
     */
    public function dbCities()
    {
        $aliaCities = $this->region_model
        ->field('fullname,pinyin_f')
        ->where('type',  2)
        ->select();
        
        $cities = [];
        
        foreach ($aliaCities as $k => $vo) {
            $firstLetter = strtoupper(substr($vo['pinyin_f'], 0, 1));   //取城市拼音的首字母，并且大写
            unset($vo['pinyin_f']);                                     //删除城市数组中的拼音列
            if(!empty($firstLetter)) {
                $cities[] = [
                    'first_letter' => $firstLetter,
                    'fullname'         => $vo['fullname']
                ];
            }
        }
        
        $data = db('City')->insertAll($cities);
        
//         ksort($cities);                                                 //按键值 对数组进行排序
        
        dump($data);
    }
    
    public function originalCities()
    {
        $hotCities = db('City')
        ->field('id,fullname')
        ->where('is_hot',  1)
        ->select();
        
        $aliaCities = db('City')
        ->field('id,fullname,first_letter')
        ->select();
        
        $cities = [];
        
        foreach ($aliaCities as $k => $vo) {
            if(!empty($vo['first_letter'])) {
                $cities[$vo['first_letter']][] = [
                    'id'        => $vo['id'],
                    'fullname'  => $vo['fullname']
                ];
            }
        }
        
        ksort($cities);
        
        return $cities;
    }
    
}