<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 yershop All rights reserved.
// +----------------------------------------------------------------------
// |  官方网址: yershop.com
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
 /* 会员中心控制器*/
class CenterController extends HomeController {
	 /* 会员中心首页*/
	public  function index() {  
		if ( !is_login() ) {
				$this->error( "您还没有登陆",U("User/login") );
		}	
		$uid=is_login();		   
		
		 //自动发货
		$this->auto_express($uid);
		$this->assign('uid', $uid);
		$p = I('p');
		/* 数据分页*/
		$map['uid']=$uid;
		$map['total']=array('gt',0);
		// echo "<pre>";
		// $list = M('order')->select();
		// var_dump($list);die;
		$list=D("order")->getLists($map);
		$this->assign('list',$list);// 赋值数据集      
		$page=D("order")->getPage($map);
		$this->assign('page',$page);//
	
		$faceid=D('UcenterMember')->getfaceid($uid);
		$this->assign('faceid', $faceid);
		
		/*优惠券数量*/
		$num=M("UserCoupon")->where("uid='$uid'")->count();
		$this->assign('num', $num);
		/*购物车中数量*/
		$shopnum=M("shopcart")->where("uid='$uid'")->count();
		$this->assign('shopnum', $shopnum);

		/*待支付*/

		$baoming = M('baoming');
		/* 数据分页*/
		$_GET['p'] = isset($_GET['p'])?$_GET['p']:1;
		$list = $baoming ->alias('a')->field('a.id,a.goodid,a.order_code,a.bm_time,a.price,a.if_baoming,b.title')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 0 and a.if_pay = 0 and a.uid=$uid")->order('a.bm_time')->page($_GET['p'].',3')->select();
		$this->assign('list',$list);// 赋值数据集
		$count      = $baoming ->alias('a')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 0 and a.if_pay = 0 and a.uid=$uid")->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('onum', $count);
		
		
		/*待发货*/
		$dnum=M("order")->where("uid='$uid' and status='1'")->count();
		$this->assign('dnum', $dnum);
		/*已完成订单*/
		$cnum=M("baoming")->alias('a')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 1 and a.uid=$uid")->count();
		$this->assign('cnum', $cnum);
		/*最后一次登录时间*/
		$time=M("member")->where("uid='$uid'")->limit(1)->find();
		$this->assign('time', $time);	
		
		//站内信数量
		$ecount=D("UserEnvelope")->getNum($uid);
		$this->assign('ecount', $ecount);

		$this->meta_title = get_username().'的个人中心';
		$this->display();
    }
	
    public  function reason() {       
		if ( !is_login() ) {
				$this->error( "您还没有登陆",U("User/login") );
	    }
		$this->display();

    }
       /*****全部订单****/
	public  function allorder(){
		if ( !is_login() ) {
				$this->error( "您还没有登陆",U("User/login") );
	    }
		$uid=is_login();
// 		//实例化model类
// 		$baoming = M('baoming');
// 		//$list = $baoming->where("uid=$uid")->select();
// 		$list = $baoming->query("select  a.id,a.order_code,a.bm_time,a.price,a.if_baoming,b.title from yershop_baoming as a,yershop_document as b where a.goodid = b.id and a.uid=$uid ");
// 		$this->assign('list',$list);
// 		//var_dump($list);die;
		$_GET['p'] = isset($_GET['p'])?$_GET['p']:1;
		$baoming = M('baoming');// 实例化User对象
	 /* 数据分页*/
		$list = $baoming ->alias('a')->field('a.id,a.goodid,a.order_code,a.bm_time,a.price,a.if_baoming,b.title')->join("__DOCUMENT__  b on a.goodid = b.id")->where(" a.uid=$uid and if_baoming != -1")->order('a.bm_time')->page($_GET['p'].',5')->select();
		$this->assign('list',$list);// 赋值数据集
		$count      = $baoming ->alias('a')->join("__DOCUMENT__  b on a.goodid = b.id")->where(" a.uid=$uid and if_baoming != -1")->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
	

 		$this->meta_title = '我的所有订单';
		$this->display();
     }

     /* 待支付订单*/
    public  function needpay(){
	    if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
	    
		$uid=is_login();
		
		/* 数据分页*/	 
       $baoming = M('baoming');  
       /* 数据分页*/
       $list = $baoming ->alias('a')->field('a.id,a.goodid,a.order_code,a.bm_time,a.price,a.if_baoming,b.title')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 0 and a.if_pay = 0 and a.uid=$uid")->order('a.bm_time')->page($_GET['p'].',5')->select();
       $this->assign('list',$list);// 赋值数据集
       $count      = $baoming ->alias('a')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 0 and a.if_pay = 0 and a.uid=$uid")->count();// 查询满足要求的总记录数
       $Page       = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $this->assign('page',$show);// 赋值分页输出
		$this->meta_title = '待支付订单'; 
		$this->display();
    }     
       /* 待发货订单*/
    public  function tobeshipped(){
	    if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}		
	   	$uid=is_login();
	
			$_GET['p'] = isset($_GET['p'])?$_GET['p']:1;
		$baoming = M('baoming');// 实例化User对象
	 /* 数据分页*/
		$list = $baoming ->alias('a')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 1 and a.uid=$uid")->order('a.bm_time')->page($_GET['p'].',5')->select();
		$this->assign('list',$list);// 赋值数据集
		$count      = $baoming ->alias('a')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 1 and a.uid=$uid")->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出     
		$this->meta_title = '已完成订单'; 
		$this->display();
    }
    /* 完成订单*/
    public  function tobeconfirmed(){
	    if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
	    }
         $uid=is_login();
// 		 //自动发货
// 		$this->auto_express($uid);
		
// 		$map['uid']=$uid;
// 		$map['status']=2;
// 		$list=D("order")->getLists($map);
// 	    $this->assign('list',$list);// 赋值数据集     
// 		$page=D("order")->getPage($map);
// 		$this->assign('page',$page);//  
// 	    $this->meta_title = '我的收藏';
// 	    $this->display();
// 	    $baoming = M('baoming'); // 实例化User对象
// 	    // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
// 	    //$list = $baoming->where("if_baoming=0 and if_pay=0 and uid = $uid")->order('bm_time')->page($_GET['p'].',4')->select();
// 	    $list = $baoming->query("select  a.id,a.order_code,a.bm_time,a.price,a.if_baoming,b.title from yershop_baoming as a,yershop_document as b where a.goodid = b.id and a.if_baoming=1  and a.uid=$uid");
// 	    //var_dump($list);die;
// 	    $this->assign('list',$list);// 赋值数据集
// 	    $count      = $baoming->where('if_baoming=0 and if_pay')->count();// 查询满足要求的总记录数
// 	    $Page       = new \Think\Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数
// 	    $show       = $Page->show();// 分页显示输出
// 	    $this->assign('page',$page);// 
       		$_GET['p'] = isset($_GET['p'])?$_GET['p']:1;
		$baoming = M('baoming');// 实例化User对象
	 /* 数据分页*/
		$list = $baoming ->alias('a')->field('a.id,a.goodid,a.order_code,a.bm_time,a.price,a.if_baoming,b.title')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 1 and a.uid=$uid")->order('a.bm_time')->page($_GET['p'].',5')->select();
		$this->assign('list',$list);// 赋值数据集
		$count      = $baoming ->alias('a')->join("__DOCUMENT__  b on a.goodid = b.id")->where("a.if_baoming = 1 and a.uid=$uid")->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出 
         
	    $this->meta_title = '待支付订单';
	    $this->display();
    }

	public  function auto_express($uid){
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
	    }
        if (empty($uid)) {
			$uid=is_login();
	    }
		//判断是否开启自动发货
		if(1==C('AUTO_SEND')){
		   $Express = M("Express");
		   $map['status']=1;
		   $map['uid']=$uid;
		   $list = M("order")->where($map)->select();
			//判断快递单号库存是否充足
           $condition['status']  = 1; 
		   $data=$Express->where($condition)->order('id desc')->select();
		   if ($list&&!$data){
			   addUserLog('快递单号库存不足，请尽快补充', $uid);
		   } 
		   if ($list){
			   foreach($list as $n=> $v){	
				   $data['status']=2; 
                   $condition2['status']  = 1;
				   $id=$v['id'];
				   $orderid=$v['orderid'];
				   $info=$Express->where($condition2)->order('id desc')->limit(1)->find();
				   if($info){
					  $data['express']=$info['title'];
					  $data['express_code']=$info['code'];
					  $data['send_name']=C('SHOPNAME');
					  $data['send_contact']=C('CONTACT');
					  $data['send_address']=C('ADDRESS');
					  $data['create_time']=NOW_TIME;
					  M("order")->where("id='$id'")->save($data);
					  M("Express")->where("id='$info[id]'")->setField('status',2);
					  addUserLog('自动发货成功，订单号'.$orderid, $uid);						  
				   }
			   }
		   }
	   }	 
    }
}
