<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Common;

use think\Request;
use think\Db; 
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");

class Goods extends Common{
	
    public function index(){
		$id = $this->params;
		dump($id);  
		print_r(json_encode($id));	
		
}
/**  
 * 获取商品数据 
 * @access  public 
 * @param  number  $id
 * @param  number  $classes 类型 如果传classes优先查询clss
 * @return array
 * */
    public function goods(){
//		$request=Request::instance();
		$arr=$this->params;
	
//		dump(intval($arr['goods_id']));
//	die;
//		id和class都不传，查询所以商品
	if(!isset($arr['goods_id'])&&!isset($arr['goods_classes'])){
		$res=Db::name('goods')->select();
		print_r(json_encode($res));
		dump('meiid');
		die;	
	}
	else{
		//		只传classes 查询一类
		if(isset($arr['goods_classes'])){
			$res=Db::name('goods')->where('goods_classes',$arr['goods_classes'])->select();			
				print_r(json_encode($res));	
			}
		else{
			//		只传ID 查询单个
			$res=Db::name('goods')->where('goods_id',$arr['goods_id'])->find();
			print_r(json_encode($res));	
			die;	
			}
		}

	}  
	/**  
	 * 
	 * 增加商品 
	 * @access  public
	 * @param  string  $goods_name 名称
	 * @param  number  $goods_surplus 库存
	 * @param  number  $goods_price 价格
	 * @param  string  $goods_spec 个体规格
	 * @param  number  $goods_vdate 保质期（天）
	 * @param  number  $goods_unitsvalue 整件规格
	 * @param  string  $goods_origin 产地
	 * @param  number  goods_classes 分类
	 * @param  string  goods_units 单位
	 * @return 
	 
	 * */
	public function goodsadd(){
			// 启动事务
	Db::startTrans();
		try{
			$data=$this->params;
			Db::table('goods')->insert($data);
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
	 * 商品出入库 
	 * @access  public
	 * @param  string $record_date 时间戳
	 * @param  string $record_goods 商品名
	 * @param  number  $record_quantity 数量
	 * @param  number  $recod_goodeid 商品id（主键）
	 * @param  number  $record_type 记录类型
	 * @return 
	 
	 * */
	public function recordadd(){
			$data=$this->params;
			print_r(json_encode($data));	
	
		// 启动事务
	Db::startTrans();
		try{
			Db::name('record')->insert($data);
				if($data['record_type']==1){
					Db::name('goods')->where('goods_id',$data['recod_goodeid'])->setInc('goods_surplus',$data['record_quantity']);
					}
				elseif($data['record_type']==2||$data['record_type']==3){
					Db::name('goods')
					->where('goods_id',$data['recod_goodeid'])
					->setDec('goods_surplus',$data['record_quantity']);
					}

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
	 * 所有入库记录 
	 * @access  public
	 * @param null $ 参数属性及说明
	 * @return 
	 
	 * */
	public function record(){
		$res=Db::name('record')->order('id','DESC')->select();
			print_r(json_encode($res));		
	}
	
	/**  
	 * 
	 * 单个商品入库记录 
	 * @access  public
	 * @param  number  $recod_goodeid 商品id（主键）
	 * @return 
	 
	 * */
	public function recordlist(){
			$data=$this->params;
		
		$res=Db::name('record')
		->order('id','DESC')
		->where('recod_goodeid',$data['recod_goodeid'])
		->select();
			print_r(json_encode($res));		
	}
	
	

}


?>