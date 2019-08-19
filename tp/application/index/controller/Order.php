<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Common;
use \think\Loader;
use think\Request;
use think\Db; 
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");

class Order extends Common{
	
    public function index(){
		
		dump(111);  

	}
	
	
	/**  
	 * 
	 * 生成订单 
	 * @access  public|private|protected 
	 * @param  array|object|string|bool|number  $ 参数属性及说明
	 * @return 
	 * */
    public function orderadd(){
		dump(111);  
		$data=$this->params;
//			生成订单号
			$order_number=date('ymd').substr(time(),-5).substr(microtime(),2,3).rand(0,9).rand(0,9);					
			$data['order_number']=$order_number;
			$orderdetails=$data['order_details'];
//			给订单详细生成订单号
			for($i = 0; $i < count($orderdetails); ++$i){
    			$orderdetails[$i]['orderdetails_number'] =$order_number ;
				}
//		dump($data) ;
//		开始事务
		Db::startTrans();
		try{
//			添加订单
			Db::name('order')->insert($data);
//			添加订单详细
			Db::name('orderdetails')->insertAll($orderdetails);
			// 提交事务
			Db::commit();
			$this->return_msg(200,'添加成功!');
		   } 
		catch (\Exception $e) {
		// 回滚事务
		Db::rollback();
		$this->return_msg(400,'添加失败!');	
		}

	}
	
	
	
	 
	 /**  
	  * 
	  * 查询订单 
	  * @access  public|private|protected 
	  * @param  array|object|string|bool|number  $ 参数属性及说明
	  * @return 
	  
	  * */
	
	 public function order(){
		dump(111);  
		$data=$this->params;
			$res=Db::name('order')->where('order_number',$data['order_number'])->find();
			$res['orderdetails']=Db::name('orderdetails')->where('orderdetails_number',$data['order_number'])->select();
		print_r(json_encode($res));
			
	 }

//	 public function orderdelete(){
//		dump(111);  
//			
//		}

	}

	
?>