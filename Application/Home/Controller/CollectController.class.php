<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;
/**
 * 收藏模型控制器
 * 文档模型列表和详情
 */
class CollectController extends HomeController {


	public function index(){
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}	

		// $collect=M("collect");
		// $data = $collect->select();
		// var_dump($data);die; 
		$type =  $_GET['type'];
		$this->assign('type',$type);
		$uid=is_login();
		$data['uid'] = $uid;
		$data['status'] = 1;
 		if($type == 2){
 			$collect = D("collect_user");
 			//$list = $collect->field('id,title,content')->where($data)->select();
 			$list = $collect->query("select a.create_time,a.id,a.goodid,b.title,b.titlepage as path from yershop_collect_user as a,yershop_document_user as b where a.goodid = b.id and a.uid = $uid");
//  			var_dump($list);die;
 			//$list = $collect->query("select ")
 			
 			$this->assign('list',$list);
 			$this->display();
 		}else{
 			$collect=D("collect"); 		
			//$this->meta_title = '我的收藏';
			//$type=I("type",0,'intval');
			$list = $collect->getLists($data);
			$this->assign('list',$list);
			$page = $collect->getPage($data);
			$this->assign('page',$page);
			$this->display();
 		}	
   }

    //增加收藏
   public function add(){
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		if(IS_POST){
			// $id=I('post.id',0,'intval'); // 用intval过滤$_POST['id']
			$id = I('post.id');
			$price=I('post.price');
			$price=safe_replace($price);//安全过滤
			//dump($p);die;
			$data["id"] = $id;
			$uid=is_login();
			$data["uid"]=$uid;
			$fav=M("collect");	
            $exsit=$fav->where("goodid='$id' and uid='$uid' and  type=1 and status=1")->getField("id");
            // echo $fav->getLastSql();
            // var_dump($exsit);die;
			if (isset($exsit)){
				$data["status"] = 1; 
				$data["type"] = 1;
				$data["msg"] = "已收藏过，请不要重复收藏";
				$this->ajaxReturn($data); 
			}
			else{	
			   // var_dump($_POST);die;
			   // $fav->create();
			   $fav->status=1;
			   $fav->type=1;
			   $fav->goodid=$id;
			   $fav->price=$price;
			   $fav->create_time=NOW_TIME;
			   $fav->uid=$uid;
			   $fav->add();
			  // echo $fav->getLastSql();
			   $data["status"] = 1; 
			   $data["type"] = 1;
			   $data["msg"] = "已收藏";
			   $this->ajaxReturn($data); 
		   }
	   }
   }
   //添加收藏home
    public function addhome(){
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		if(IS_POST){
			$id=I('post.id',0,'intval'); // 用intval过滤$_POST['id']
			$price=0.00;
			$price=safe_replace($price);//安全过滤
			//dump($p);die;
			$data["id"] = $id;
			$uid=is_login();
			$data["uid"]=$uid;
			$fav=M("collect_user");	
			//var_dump($data);die();
           $exsit=$fav->where("goodid='$id' and uid='$uid' and  type=1 and status=1")->getField("id");
			if (isset($exsit)){
				$data["status"] = 1; 
				$data["type"] = 1;
				$data["msg"] = "已收藏过，请不要重复收藏";
				$this->ajaxReturn($data); 
			}
			else{	
			   $fav->create();
			   $fav->status=1;
			   $fav->type=1;
			   $fav->goodid=$id;
			   $fav->price=$price;
			   $fav->create_time=NOW_TIME;
			   $fav->uid=$uid;
			   $fav->add();
			   $data["status"] = 1; 
			   $data["type"] = 1;
			   $data["msg"] = "已收藏";
			   $this->ajaxReturn($data); 
		   }
	   }
   }

   public function delete(){
	   // $id=I('post.id',0,'intval'); // 用intval过滤$_POST['id']
	   $id = $_GET['id'];
	   $type = $_GET['type'];
	   echo $type;
	   if($type == 1){
	   	//删除系统活动
	   	$mod = 'collect';
	   }else{
	   	//删除用户活动
	   	$mod = 'collect_user';
	   }

	   $map["id"]=array("in",$id);
	   if(M($mod)->where($map)->delete()){
		  $this->success("删除成功");
	   }else{

	     $this->error("删除失败");
	   }
   }
}
