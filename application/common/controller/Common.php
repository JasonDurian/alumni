<?php
// +----------------------------------------------------------------------
// | Description: 解决跨域问题
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    protected $param;
    
    protected function _initialize()
    {
        parent::_initialize();
        /*防止跨域*/
        $allow_origin = array(
            'http://admin.alumni.app',
            'http://admin.jasonfj.com',
        );
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        if(in_array($origin, $allow_origin)){
            header('Access-Control-Allow-Origin: ' . $origin);
        }
//        header('Access-Control-Allow-Origin: admin.jasonfj.com');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey');
        header('Content-Type:application/json; charset=utf-8');
        $param =  Request::instance()->param();            
        $this->param = $param;
    }

    public function object_array($array) 
    {  
        if (is_object($array)) {  
            $array = (array)$array;  
        } 
        if (is_array($array)) {  
            foreach ($array as $key=>$value) {  
                $array[$key] = $this->object_array($value);  
            }  
        }  
        return $array;  
    }
}
 