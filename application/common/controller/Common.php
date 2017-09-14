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

        /* 指定允许访问的源 */
        $allow_origin = [
//            'http://admin.alumni.app',
//            'http://api.alumni.app',
//            'http://www.alumni.app',
//            'http://alumni.app',
//            'http://localhost:8000',
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            /* GET没有'HTTP_ORIGIN' */
            if (isset($_SERVER['HTTP_REFERER'])) {
                $http_origin = explode('/',$_SERVER['HTTP_REFERER']);
                $origin = $http_origin[0] . '//' . $http_origin[2];
            } else {
                $origin = '';
            }
        } else {
            /* 其余restful REQUEST_METHOD */
            $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        }

        if(in_array($origin, $allow_origin) || $this->isOriginAllowed($origin, config('cors_allow_origin'))) {
            header('Access-Control-Allow-Origin: ' . $origin);
        } else {
            exit('CSRF protection in POST request: detected invalid Origin header: ' . $origin);
//            header('Access-Control-Allow-Origin: *');
        }
        /*防止跨域*/
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, authKey');
        header('Content-Type:application/json; charset=utf-8');
        $param =  Request::instance()->param();            
        $this->param = $param;
    }

    /**
     * @param $incomingOrigin
     * @param $allowOrigin
     * @return bool
     */
    private function isOriginAllowed($incomingOrigin, $allowOrigin)
    {
        $pattern = '/^http:\/\/([\w_-]+\.)*' . $allowOrigin . '$/';

        $allow = preg_match($pattern, $incomingOrigin);

        return $allow ? true : false;
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
 