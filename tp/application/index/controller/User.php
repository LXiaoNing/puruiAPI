<?php

namespace app\index\controller;
use think\Controller;
use app\index\controller\Common;
use think\Db; 
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");

class User extends Common{
	
    public function index(){	
	 
	//	print_r(json_encode($id));			
}
	
	/**  
	 * 
	 * 登录 
	 * @access  public
	 * @param number  $user_users 账号
	 * @param string $user_passwoder 密码 md5加密
	 * @return 
	 
	 * */
	
  public function login(){
  		$arr = $this->params;
		//	验证账号
		$user=Db::name('user')->where('user_users',$arr['user_users'])->find();
		$passwoder=md5('api_'.md5($user['user_passwoder']).'purui');
//			dump($passwoder);
		if(empty($user)){
    		$this->return_msg(400,'账号或密码错误!');	
   	 	}
//		密码错误
elseif($passwoder!==$arr['user_passwoder']){
   	 	$this->return_msg(400,'密码错误!');	
   	 	}
		echo ('登录成功');
		
		
}
  /**  
   * 
   * 注册 
   * @access public
   * @param  number 	$authcode 验证码
   * @param  string 	$user_name 姓名 可不填
   * @param  string $user_passwoder 密码 长度8,16 必须英文 数字 下划线
   * @return return_msg
   * */
    public function regsi(){   		
		$arr = $this->params;
//		查询验证码		
		$authcode=Db::name('authcode')->where('authcode_val',$arr['authcode_val'])->find();
//	查询账号
		$user=Db::name('user')->where('user_users',$arr['user_users'])->find();
   	 if(empty($authcode)){
    		$this->return_msg(400,'验证码不正确!');	
   	 	}
   	 elseif(!empty($user)){
    		$this->return_msg(400,'账号已存在!');	
   	 	}
   	 else{
//*********************使用事务添加账号并删除验证码*****************
// 启动事务
		Db::startTrans();
		try{
			Db::name('user')->insert($this->params);
			Db::name('authcode')->where('id',$authcode['id'])->delete();
		// 提交事务
			Db::commit();
			$this->return_msg(200,'注册成功!');
		} 
		catch (\Exception $e) {
		// 回滚事务
			Db::rollback();
			$this->return_msg(400,'注册失败!');	
				}	
	
		}
    	
		
			 
	}
	/**  
	 * 
	 * 生成注册   post 
	 * @access  public
	 * @param  array|object|string|bool|number  $ 参数属性及说明
	 * @return null
	 
	 * */
    public function addauthcode(){
		$val=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		dump('生成注册码'); 
		$data = ['authcode_val' => $val];
		Db::name('authcode')->insert($data);
			print_r(json_encode($val));	
	}
	/**  
	 * 
	 * 添加客户 
	 * @access  public
	 * @param  string $company_name 公司名称
	 * @param  string $company_call 电话
	 * @param  string $company_consignee 收货人
	 * @param  string  $ company_site	地址
	 * @return 
	 
	 * */
	 public function companyadd(){
		$data = $this->params;
		
		
			$arr=Db::name('company')->insert($data);

		
			print_r(json_encode($arr));	
		if($arr>0){
			$this->return_msg(200,'添加成功!');
		}else{
			$this->return_msg(400,'添加失败!');	
		}

	 }
/**  
 * 
 * 删除客户 
 * @access  public
 * @param  number  $id 主键
 * @return 
 * */
	 public function companydelete(){
		$data = $this->params;
		$arr=Db::name('company')->where('id',$data['id'])->delete();			
		if($arr>0){
			$this->return_msg(200,'删除成功!');
		}else{
			$this->return_msg(400,'删除失败!');	
		}
	}
	  public function company(){
//		$data = $this->params;
		$res=Db::name('company')->select();
	print_r(json_encode($res));		
		
		}
	}

	
	
	
	
	
	

?>