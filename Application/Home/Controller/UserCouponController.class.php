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
 * 用户优惠券模型控制器
 * 文档模型列表和详情
 */
class UserCouponController extends HomeController {


	public  function index() {
		if (!is_login()) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		/* 会员调用*/
		$uid=is_login();

		/*推荐活动*/
		//实例化model类
		$document = M('document');
		$data = $document->query("select a.id,a.title,b.path from yershop_document as a,yershop_picture as b where a.cover_id = b.id and a.category_id>160 and a.status = 1 order by a.create_time desc  limit 4;");
		$this->assign('data',$data);
		/* 优惠券调用
		$coupon=M("UserCoupon")->where("uid='$uid' ")->select();
		$this->assign('couponlist', $coupon);
		$fcoupon=M("fcoupon")->where("display='1' and status='1' ")->select();;
		$this->assign('fcouponlist', $fcoupon);
		$this->meta_title = '我的优惠券';
			*/
		
		$coupons=M('document_user');
		
		//$uid=I('post.uid');
		//import('ORG.Util.Page');//导入分页类
		
		$count=$coupons->where("uid='$uid'")->count();//查询满足查询的条件

		$Page=new \Think\Page($count,6);
		$list=$coupons->field('id,title,titlepage,starttime,stoptime')->where("uid='$uid'")->limit($Page->firstRow.','.$Page->listRows)->select();
		$Page->setConfig('prev','上一页');
       	$Page->setConfig('next','下一页');
       	$Page->setConfig('first','第一页');
       	$Page->setConfig('last','尾页');
       	$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

		$show=$Page->show();		
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('coupons',$coupons);

		$this->display();
		
	}
      /*****领优惠券**********/
	public  function get() {
		if ( !is_login() ) {
		     $this->error( "您还没有登陆",U("User/login") );
		}

		$id=I('post.couponid',0,'intval'); // 用intval过滤$_POST["couponid"];
		$uid=is_login();
		$coupon=M("UserCoupon");
		if($coupon->where("uid='$uid'and couponid='$id'")->select() ){
			$data["msg"] = "已领取过";
			$data["status"] = "0";
			$this->ajaxreturn($data);
		}else{ 
			$data["uid"] = $uid;
			$data["couponid"] = $id;
			$data["time"] = NOW_TIME;
			$data["status"] = "1";
			$data["info"] = "未使用";
			$coupon->add($data);
			$data["msg"] = "已成功领取，请刷新查看";
			$this->ajaxreturn($data);
	    }	   
    }
    
}
