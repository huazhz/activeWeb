<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// |  Author: 烟消云散 <1010422715@qq.com> 
// +----------------------------------------------------------------------
namespace Home\Controller;
use OT\DataDictionary;
use User\Api\UserApi;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 $url= $_SERVER[HTTP_HOST]; //获取当前域名  
 */
class IndexController extends HomeController {
 

	/**系统首页**/
    public function index(){

        // cookie自动登录
		if(!is_login()&&cookie('username')&&cookie('password')){
		    $username=cookie('username'); 
            $password=cookie('password'); 
            $username =safe_replace($username);//过滤
			$user = new UserApi;
			$uid = $user->login($username, $password);
			if(0 < $uid){ //UC登录成功
					/* 登录用户 */
				$Member = D("Member");
				if($Member->login($uid)){ 
						//登录用户，记录日志
						addUserLog('cookie登陆成功',$uid);
			    }
		    }
       }
	   if( 1==C('IP_TONGJI') ){
		     $title="index";	   
		      /**首页统计代码实现 status=1**/
			 $record=IpLookup("",1,$title);
		}		
		/** 幻灯片* */
		$slide=D( 'slide' )->get_slide( );
		$this->assign( 'slide',$slide );
        /** 顶级栏目* */
		$tree=D( 'Category' )->maketree( ) ;
		$this->assign ( 'tree', $tree );
         /** 公告分类**/
		$notice=M('document')->order('id desc')->where("status=1 and category_id='56'")->limit(8)->select();		
		$this->assign( 'notice',$notice );
       /** 活动分类**/
		$activity=M( 'document' )->order('id desc')->where("category_id='70'")->limit(8)->select();		
		$this->assign( 'activity',$activity );	
		/** 分类数组 **/
		$cate11 = array();
		$lei = M('Category')->query("select id,title from yershop_category");
		foreach($lei as $value){
			$cate11[$value['id']] = $value['title'];
		}
		$this->assign( 'cate11',$cate11 );
		/* *最新活动**/
			
		$ob=M('document');
// 		$inc=$ob->query("
// 				SELECT  a.id,a.title,a.place,
// DATE_FORMAT(FROM_UNIXTIME(a.create_time),'%Y-%m-%d %H:%i:%s') AS st,
// DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d %H:%i:%s') AS de,
// a.place,b.path,c.id,c.area 
// FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
// LEFT JOIN `yershop_document_product` AS c ON a.id=c.id where a.category_id>160 and a.status =1
// ORDER BY a.create_time  DESC LIMIT 0,6;
// 				");
		
		$inc=$ob->query("
				SELECT  a.id,a.title,a.place,a.category_id,
				DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d %H:%i:%s') AS st,
				DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d %H:%i:%s') AS de,
				b.path,c.area
				FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id)
				LEFT JOIN `yershop_document_product` AS c ON a.id=c.id where a.category_id>160 and a.status =1
				ORDER BY a.create_time  DESC LIMIT 0,6;
				");
		//$inc = array_unique($inc);
		//echo var_dump($inc);die;
		//echo $ob->getLastSql();
		$this->assign('inc',$inc);
		

		/*活动聚焦*/
		$oc=M('Document');
		$oca=$oc->query("
			select  a.id,a.title,a.place,
DATE_FORMAT(FROM_UNIXTIME(a.create_time),'%Y-%m-%d ') as st,
DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d ') as de,
b.path,c.area 
from (`yershop_document` as a LEFT JOIN `yershop_picture` as b ON a.cover_id=b.id) 
LEFT JOIN `yershop_document_product` AS c ON a.id=c.id where a.category_id>160 and a.status = 1
ORDER BY a.view  DESC LIMIT 0,12;
				");
		
		$this->assign('oca',$oca);
		
		/*今日推荐*/
		$od=M('document');
		$obd=$od->query("
			select  a.id,a.title,a.place,a.sale,
DATE_FORMAT(FROM_UNIXTIME(a.create_time),'%Y-%m-%d') as st,
DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') as de,
b.path,c.area 
from (`yershop_document` as a LEFT JOIN `yershop_picture` as b ON a.cover_id=b.id) 
LEFT JOIN `yershop_document_product` AS c ON a.id=c.id where a.category_id>160 and a.status = 1
ORDER BY a.sale   LIMIT 0,4;
				");
				
		$this->assign('obd',$obd);
		$this->meta_title = '首页';	
		/*今日推荐右侧*/
		$od=M('document');
		$obd=$od->query("
			select  a.id,a.title,a.place,a.update_time,
DATE_FORMAT(FROM_UNIXTIME(a.create_time),'%Y-%m-%d') as st,
DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') as de,
b.path,c.area 
from (`yershop_document` as a LEFT JOIN `yershop_picture` as b ON a.cover_id=b.id) 
LEFT JOIN `yershop_document_product` AS c ON a.id=c.id where a. category_id>160 and a.status = 1
ORDER BY a.update_time   LIMIT 0,5;
				");
				
		$this->assign('yc',$obd);	
		$od=M('document');
		$obd=$od->query("
			select  a.id,a.title,a.place,a.update_time,a.status,
DATE_FORMAT(FROM_UNIXTIME(a.create_time),'%Y-%m-%d') as st,
DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') as de,
b.path,c.area 
from (`yershop_document` as a LEFT JOIN `yershop_picture` as b ON a.cover_id=b.id) 
LEFT JOIN `yershop_document_product` AS c ON a.id=c.id where a.category_id>160 and a.status=1
ORDER BY a.id desc  LIMIT 0,1;
				");
				
		$this->assign('dy',$obd);

		$this->display();
	}
	
	

    public function getGoodlist($cateid=null){
        $cateid=I( 'cateid',0,'intval' ); // 用intval过滤$_POST['id'];  
		$data = D( 'Category' )->getDatalist( $cateid );
        $this->ajaxReturn($data);
    }
}