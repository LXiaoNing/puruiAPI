<?php
//命名空间
namespace app\index\controller;
//加载核心类
use think\Controller;
//加载模型类
use app\index\controller\Common;
use \think\Loader;
//获取数据累
use think\Request;
use think\Db; 

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");
class Index extends Common{
	/**  
	 * 
	 * 添加分类
	 * @access string $classes_name 分类名称
	 * @param number  $classes_belong 分类所属（空为最大类）
	 * @param  string  $classes_explain 分类介绍
	 * @return 
	 
	 * */
    public function addclasses(){
    	$data = $this->params;
		$arr=Db::name('classes')->insert($data);
			if($arr>0){
			$this->return_msg(200,'添加成功!');
		}else{
			$this->return_msg(400,'添加失败!');	
		}
	}	
//   列表	

	 public function deleteclasses(){
    		$data = $this->params;
		
		$arr=Db::name('classes')->where('id',$data['id'])->delete();	
			if($arr>0){
			$this->return_msg(200,'添加成功!');
			
		}else{
			$this->return_msg(400,'添加失败!');	
			
		}
	}
	 /**  
	  * 
	  * 查询分类   git
	  * @access  public
	  * @param  null  $ 参数属性及说明
	  * @return 
	  
	  * */
	  public function classes(){
		$classes=Db::name('classes')->where('classes_belong',null)->select();
//		$res=Db::name('classes')->where('classes_belong','>',$classes[1]['id'])->select();
//		$aa=$classes[1];
//		$aa['aa']=1;

			for($i = 0; $i < count($classes); ++$i){
				$arr=$classes[$i];
    			$arr['classesils'] =Db::name('classes')->where('classes_belong',$arr['id'])->select();
				$classes[$i]=$arr;
				}
			print_r(json_encode($classes));	
	}
	
	/**  
	 * 
	 * 增加单位名称
	 * @access  public
	 * @param  string$units_name 单位名称（英文或中文）
	 * @param  number  $units_classes 单位类型
	 *
	 * @return 
	 
	 * */
	public function unitsadd(){
		// 启动事务
		Db::startTrans();
		try{
			$data=$this->params;
			Db::name('units')->insert($data);
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
	 * 删除单位名称 
	 * @access  public
	 * @param number  $units_id  主键
	 * @param  array|object|string|bool|number  $ 参数属性及说明
	 * @return 
	 
	 * */
	public function unitsdelete(){
			$data = $this->params;
		$arr=Db::name('units')->where('units_id',$data['units_id'])->delete();			
		if($arr>0){
			$this->return_msg(200,'删除成功!');
		}else{
			$this->return_msg(400,'删除失败!');	
		}
	}
	
	public function units(){
		$res=Db::name('company')->select();
		print_r(json_encode($res));	
	}
}

