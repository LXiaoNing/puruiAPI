<?php
//	命名空间
namespace app\index\controller;
//获取数据类
use think\Request;
//核心类
use think\Controller;
//数据库类
use think\Db;
//数据验证类
use think\Validate;
class Common extends Controller{
	protected $request;//接受数据
	protected $validater;//验证数据、参数
	protected $params;//过滤后的数据
	
//	过滤规则
 protected $rules=array(
			'Index'=>array(
				'index'=>array(
					'directiry2_id'=>['require','number'],
					'directiry2_name'=>['require','chsDash'],
				),
				'classes'=>array(
					
				),
				'addclasses'=>array(
					
				),
			),
			'Goods'=>array(
				'index'=>array(

				),
				'goods'=>array(
					'goods_id'=>['number'],
					'goods_classes'=>['number'],
					
				),
				'recordlist'=>array(
//					'units_name'=>['require','chsAlpha'],
//					'units_classes'=>['require','number'],
				),
			),
			'User'=>array(
				'companydelete'=>array(
				),
				'login'=>array(
					'user_passwoder'=>['require'],
					'user_users'=>['require','alphaNum','min:6','max:20'],
				),
				'regsi'=>array(
					'authcode_val'=>['require','number'],
					'users_name'=>['chsAlphaNum','length:3,6'],
					'user_passwoder'=>['require','alphaDash','length:8,16'],
					'user_users'=>['require','alphaNum','min:6','max:20'],
				),
				'company'=>array(
				
				),
			),
			'Order'=>array(
				'orderadd'=>array(

				),
			
				'order'=>array(
//					'units_name'=>['require','chsAlpha'],
//					'units_classes'=>['require','number'],
				),
			),
		);
	
	protected  function _initialize(){
		
//		parent::_initialize();
//		获取数据
	$this->request=Request::instance();
		//提取时间戳
//		$this->check_time($this->request->only(['time']));
//		验证token
//		$this->check_token($this->request->param());
//		过滤数据
		$this->params=$this->check_params($this->request->except(['time','token']));
//		$this->params=Request::instance();
	
		
	}
	/**验证时间戳	
	 * @param [array] $arr[包含时间戳的参数数组]
	 * @return [json]  [返回的结果]
	 */

	public  function check_time($arr){
		if(!isset($arr['time'])||intval($arr['time'])<=1){
			$this->return_msg(400,'时间戳不存在!');
		}
		if(time()-intval($arr['time'])>300){
			$this->return_msg(400,'时间超时!');
		}
	}

/**  
 * 
 * 方法说明 
 * @access  public|private|protected 
 * @param  object|string 	$data
 * @param  string			$msg 提示内容
 * @param  number  			$code 状态码
 * @return $return_data
 
 * */
	public function return_msg($code,$msg='',$data=[]){
		$return_data['code']=$code;
		$return_data['msg']=$msg;
		$return_data['data']=$data;
		print_r(json_encode($return_data));die;
	}

/**  
 * 验证数据格式 
 * @access  public
 * @param  array|object  $arr  需要过滤的数组或对象
 * @param  array		 $rule  过滤规则，存放在属性$rules里
 * @return $arr			原数据
 * */

public function check_params($arr){
//	自动根据控制器和方法获取验证规则
		$rule=$this->rules[$this->request->controller()][$this->request->action()];

		//	验证参数
		$this->validater=new Validate($rule);
	
		if(!$this->validater->check($arr)){
			$this->return_msg(401,$this->validater->getError());
		};
//		dump($arr);	die;
		return $arr;
	
	}


/**  
 * 
 * 验证token 
 * @access  public
 * @param  array|object  $ token 32位md5
 * @return return_mag
 
 * */
public function check_token($arr){
	if(!isset($arr['token'])||empty($arr['token'])){
			$this->return_msg(400,'token不能为空!');
		};
		$api_token=$arr['token'];
		unset($arr['token']);
		$service_token='';
		foreach($arr as $key=>$value){
			$service_token=md5($value);
			}
		$service_token=md5('api_'.$service_token.'purui');
		if($service_token!==$api_token){
			
			$this->return_msg(400,'token错误!');
		}		
	
	}
/**  
 * 
 * 生成13位时间戳 
 * @access  public|private|protected 
 * @param  array|object|string|bool|number  $ 参数属性及说明
 * @return 
 
 * */
public function msectime() {
	list($msec, $sec) = explode(' ', microtime());
	$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	return $msectime;
	}
}

?>