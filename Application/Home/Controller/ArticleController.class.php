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
* 文档模型控制器
* 文档模型列表和详情
*/
class ArticleController extends HomeController {
		/* 频道封面页 */
    public function index(){
    	//右侧热门推荐
		$pos=M('document')->query("
		SELECT a.title,a.place,a.id,a.cover_id,a.baoming,a.category_id, DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d %H:%i:%s')as st,
		DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d %H:%i:%s')as de, b.path,c.zid
		from(`yershop_document` as a left join `yershop_picture` as b on a.cover_id=b.id)
		left join `yershop_document_product` as c on a.id=c.id where a.baoming!=0 and a.category_id >160 and a.status=1  order by a.view  limit 6");
		//dump($pos);die;//$pos=M('document')->where("baoming!=0")->limit('6')->select();
	//	var_dump($pos);die;

	 $this->assign("postlist",$pos);

		$pid=I('get.pid',0,'intval'); 	//分类id
		$quxian_get= I('get.quxian'); 	
		$quxian_get=safe_replace($quxian_get);//过滤		区县		
		$quxian_id= I('get.quxian_id'); 	
		$quxian_id=safe_replace($quxian_id);//过滤		区县	id				                  
//		$paixu_get= I('get.paixu'); 
//		$paixu_get=safe_replace($paixu_get);//过滤		排序
		$page= I('get.page'); 
		$page=safe_replace($page);//过滤		页码
		$yong_shang= I('get.yong_shang');	
		$yong_shang=safe_replace($yong_shang);//过滤		区分用户还是商家
	 	$this->assign('dq_quxian',$quxian_get);	//当前区县
	 	$this->assign('quxian_ids',$quxian_id);	//当前区县id
//	 	var_dump($quxian_get);
	 	$this->assign('dq_pid',$pid);	//当前pid
	 	$this->assign('dq_y_s',$yong_shang);	//当前用户还是商家
        if(!is_numeric($pid)){
		         $this->error('分类ID错误！');
		   }
		$category = D('Category')->info($pid);
	 
		/* 分类信息 */
		 $fenlei= M('category')->where("if_class=1")->order("id desc")->field("id,title")->select();
		 $this->assign('fenlei',$fenlei);	
//		 var_dump($fenlei[2]['id']);die;
		 	/* 地区信息 */
		$diqu= M('brand')->where()->order("id")->field("id,title")->select();
		 $this->assign('diqu',$diqu);	

		// dump($fenlei);die;
//		$category = $this->category();
//		$cid = D('Category')->getChildrenId($pid);
//		$map['category_id']=array("in",$cid);
//      //品牌
//		$condition['ypid'] = array('in',$cid); 
//		$condition['status'] = 1;
//      $bdata= M('brand')->where($condition)->order("id desc")->select();
//      $this->assign('bdata',$bdata);		

		 //判断活动是用户还是商家发起的，给出对应的路径参数
		 if($yong_shang == 'yh'){
		 	$froms = 'detailUser';
		 }else{
		 	$froms = 'detail';
		 }
		 $this->assign('froms',$froms);
		 
	   //推荐商品
		$pos=M('document')->where("position!=0")->select();
		$this->assign("poslist",$pos);
//		$key=I('get.order');
//		$key=safe_replace($key);//过滤
//		$sort=I('get.sort');  
//      $sort=safe_replace($sort);//过滤
		if($key){ 
		   if(!is_numeric($key)){
		         $this->error('排序ID错误！');
		   }
		   switch ($key) { 
		        case '1': 
				    $listsort="view"." ".$sort;
                break;
				case '2':
				   $listsort="id"." ".$sort;
                  break;				
			   case '3': 
				   $listsort="price"." ".$sort;
                  break;
                case '4':                    
				   $listsort="sale"." ".$sort;
					 break;              
		   }  	
	   } 
	   else {
		 $key="1";$see="asc";
		 $order="view";$sort="asc";
		 $listsort=$order." ".$sort;			
	   }
		
       if($sort=="asc"){
		  $see="desc";
	   }
       if($sort=="desc"){
		  $see="asc";
	   }
       $this->assign('see',$see);
       $this->assign('order',$key);
	   $this->assign('value',$sort);
	   if(isset($_GET['brandid'])){
			$brandid=I('get.brandid',0,'intval');
			$title=M('brand')->where("id='$brandid'")->order("id desc")->getField('title');
            $map['brand']  = array('like', '%'.$title.'%');
            $this->assign('brandid',$brandid);
        }
        if ( isset($_GET['start_price']) ) {
            $map['price'][] = array('egt',I('start_price',0,'intval'));
           $this->assign('start_price',$_GET['start_price']);
        }
        if ( isset($_GET['end_price']) ) {
            $map['price'][] = array('elt',I('end_price',0,'intval'));
            $this->assign('end_price',$_GET['end_price']);
        }
		$map['status']=1;   
		/* 数据分页*/
		//$list=D("Document")->getList($map,$category['list_row'],$listsort);
		$this->assign('list',$list);// 赋值数据集      
		$page=D("Document")->getPage($map,$category['list_row'],$listsort);
		$this->assign('page',$page);//
		

		$cate=M('category');
		$cates=$cate->query('select * from yershop_category where extend_id=1  order by id limit 15');
		$this->assign('cates',$cates);


		//获取分类的name
		$name=$category['name'];
		$child=M('Category')->where("pid='$pid'")->select();
		$this->assign('num', $count);
		$this->assign('childlist', $child);
		
		/*栏目页统计代码实现，status=2*/
		if(1==C('IP_TONGJI')){
		   $record=IpLookup("",2,$name);
		}
		
		//频道页循环3级分类
		$this->meta_title = $category['title'];
		/*销量排行*/
		$sales=$this->ranks();
		$this->assign('sales', $sales);
		/*最近访问*/
		$recent=$this->view_recent();
		$this->assign('recent', $recent);
		/* 模板赋值并渲染模板 */
		//分类的俩表关联查询
		$id=$_GET['pid'];
		//dump($id);die;
//		$mod=M('category');
//		$class_info=$mod->join('yershop_document_user on yershop_document_user.cid=yershop_category.id')->where("cid={$id}")->select();
//		//dump($class_info);
//		//die();
//		$pid=I('get.pid');
//		$this->assign('class_info',$class_info);
		//数据的分页
//		$user=M('document_user');

if ($yong_shang=='yh'){//判断用户发的活动
		$user=M('document_user');//用户活动
		$pagecount=6;//每页的数据
		//计算数据总条数
		if($pid==''){
			$p=I('get.p')?I('get.p'):1;//获取页数
				$count=$user->where(" area LIKE '%{$quxian_get}%'")->count();
				$page=new \Think\Page($count,$pagecount);
						//查询第N页的数据
		$users=$user->where("area LIKE '%{$quxian_get}%'")->page($p,$pagecount)->select();
		//var_dump($users);die;
		}else{
			$p=I('get.p')?I('get.p'):1;//获取页数
				$count=$user->where("cid={$pid} and area LIKE '%{$quxian_get}%'")->count();
					//实例化分页类
		$page=new \Think\Page($count,$pagecount);
				//查询第N页的数据
		$users=$user->where("cid={$pid} and area LIKE '%{$quxian_get}%'")->page($p,$pagecount)->select();
		}
	
	

//		$users=$user->query("");
		//设置首页，上一页，下一页，尾页
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','尾页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        
		$show=$page->show();
		$this->assign('page',$show);
		$this->assign('users',$users);
		$this->assign('url_a','detailUser');
// $this->ajaxReturn($data);
		$this->assign('empty',"<center><tr><td colspan=13><h3>暂时没有您要查询的数据</h3></td></tr></center>");
}else{
	

		$user=M('document');
		$pagecount=6;//每页的数据
		$p=I('get.p')?I('get.p'):1;//获取页数

		//接收为空显示全部
	if($pid=='' || $pid == 168){
		//计算数据总条数
			$count=$user->where("category_id >160 AND status=1 AND place LIKE '%{$quxian_get}%'")->count();    
		//实例化分页类
		$page=new \Think\Page($count,$pagecount);

	$page_sum=ceil($count/$pagecount);//一共几页
	if($p>$page_sum){
		$p=1;
	}
	$dijiye=($p-1)*$pagecount;//数据库用第几页
		$users=$user->query("
			SELECT a.id,a.title,a.from,DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS starttime,
			DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS stoptime,
			a.place,b.path as titlepage FROM `yershop_document` AS a 
			LEFT JOIN `yershop_picture` AS b 
			ON a.cover_id=b.id 
			WHERE  place LIKE '%{$quxian_get}%' and  a.category_id >160 and a.status=1
			ORDER BY a.id DESC
			LIMIT {$dijiye},{$pagecount}
			;
		");
	}elseif($pid == 52){		//最新活动
		//计算数据总条数
		$count=$user->where("category_id >160 AND status=1 AND place LIKE '%{$quxian_get}%'")->count();
		//实例化分页类
		$page=new \Think\Page($count,$pagecount);
		
		$page_sum=ceil($count/$pagecount);//一共几页
		if($p>$page_sum){
			$p=1;
		}
		$dijiye=($p-1)*$pagecount;//数据库用第几页
		$users=$user->query("
				SELECT a.id,a.title,a.from,DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS starttime,
				DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS stoptime,
				a.place,b.path as titlepage FROM `yershop_document` AS a
				LEFT JOIN `yershop_picture` AS b
				ON a.cover_id=b.id
				WHERE  place LIKE '%{$quxian_get}%' and  a.category_id >160 and a.status=1
				ORDER BY a.create_time DESC
				LIMIT {$dijiye},{$pagecount}
				;
				");

	}elseif($pid == 76){
			//计算数据总条数
		$count=$user->where("category_id >160 AND status=1 AND place LIKE '%{$quxian_get}%'")->count();
		//实例化分页类
		$page=new \Think\Page($count,$pagecount);
		
		$page_sum=ceil($count/$pagecount);//一共几页
		if($p>$page_sum){
			$p=1;
		}
		$dijiye=($p-1)*$pagecount;//数据库用第几页
		$users=$user->query("
				SELECT a.id,a.title,a.from,DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS starttime,
				DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS stoptime,
				a.place,b.path as titlepage FROM `yershop_document` AS a
				LEFT JOIN `yershop_picture` AS b
				ON a.cover_id=b.id
				WHERE  place LIKE '%{$quxian_get}%' and  a.category_id >160 and a.status=1
				ORDER BY a.view DESC
				LIMIT {$dijiye},{$pagecount}
				;
				");
	}else{
		$count=$user->where("category_id={$pid}  AND status=1 AND  place LIKE '%{$quxian_get}%'")->count();
		//实例化分页类
		$page=new \Think\Page($count,$pagecount);
		
		$page_sum=ceil($count/$pagecount);//一共几页
		if($p>$page_sum){
			$p=1;
		}
		$dijiye=($p-1)*$pagecount;//数据库用第几页
		$users=$user->query("
				SELECT a.id,a.title,a.from,DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS starttime,
				DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS stoptime,
				a.place,b.path as titlepage FROM `yershop_document` AS a
				LEFT JOIN `yershop_picture` AS b
				ON a.cover_id=b.id
				WHERE category_id={$pid} and a.category_id >160 and a.status=1
				AND place LIKE '%{$quxian_get}%'
				ORDER BY a.id DESC
				LIMIT {$dijiye},{$pagecount}
				;
				");
		}//接收不为空
		//dump($users);die;
		//设置首页，上一页，下一页，尾页
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','尾页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        
		$show=$page->show();
		$this->assign('page',$show);
		$this->assign('users',$users);
// $this->ajaxReturn($data);
		$this->assign('empty',"<center><tr><td colspan=13><h3>暂时没有您要查询的数据</h3></td></tr></center>");
		$this->assign('url_a','detail');
}//不是用户就是商家

		
		$this->assign('ctg', $category);

		$this->display($category['template_index']);
		
//		$pos=M('document')->where("baoming!=0")->limit('6')->select();
//		//dump($pos);die;
//		$this->assign("pos",$pos);
		//$this->display();
	
	}
//分类活动列表 ajax
	 public function ajax_active_list()//ajax_active_list
		{

      	$pid=I('get.pid',0,'intval'); 	//分类id
		$quxian_get= I('get.quxian'); 	
		$quxian_get=safe_replace($quxian_get);//过滤		区县						                  
//		$paixu_get= I('get.paixu'); 
//		$paixu_get=safe_replace($paixu_get);//过滤		排序
//		$page= I('get.page'); 
//		$page=safe_replace($page);//过滤		页码
		$yong_shang= I('get.yong_shang'); 
		$yong_shang=safe_replace($yong_shang);//过滤		区分用户还是商家
				$p=I('get.p')?I('get.p'):1;//获取页数
				//数据的分页
		$user=M('document_user');//用户活动
		$pagecount=6;//每页的数据
		//计算数据总条数
		$count=$user->where("cid={$pid}")->count();
		//实例化分页类
		$page=new \Think\Page($count,$pagecount);
		//查询第N页的数据
		$users=$user->where("cid={$pid}")->page($p,$pagecount)->select();
//		$users=$user->query("");
		//设置首页，上一页，下一页，尾页
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','尾页');
		$show=$page->show();
		$this->assign('page',$show);
	         	$fund = M('fund_value');
			    $this->comment_data= $user->where("cid={$pid}")->page($p,$pagecount)->select();	
				$this->page_box=$page->show();
                $data['content']= $this->fetch('Article/ajax_active_list');				
			    $this->ajaxReturn($data);	 
			
		}

    /* 列表页 */
    public function lists($p = 1){
		$pid=I('get.pid',0,'intval'); 	
        $category = D('Category')->info($pid);
		/* 分类信息 */
		$category = $this->category();
		$cid = D('Category')->getChildrenId($pid);
		$map['category_id']=array("in",$cid);
       
		//品牌
		$condition['ypid'] = array('in',$cid); 
		$condition['status'] = 1;
        $bdata= M('brand')->where($condition)->order("id desc")->select();
        $this->assign('bdata',$bdata);

		$map['status']=1;      
	   //推荐商品
		$pos=M('document')->where("baoming!=0")->limit('6')->select();
	    $this->assign("poslist",$pos);
	    $this->display();
		$key=I('get.order');
		/* 标识正确性检测 */
		
		
		$key=safe_replace($key);//过滤
		$sort=I('get.sort');  
        $sort=safe_replace($sort);//过滤
		if($key){ 
		   if(!is_numeric($key)){
		         $this->error('排序ID错误！');
		   }
		   switch ($key) { 
		        case '1': 
				    $listsort="view"." ".$sort;
                break;
				case '2':
				   $listsort="id"." ".$sort;
                  break;				
			   case '3': 
				   $listsort="price"." ".$sort;
                  break;
                case '4':                    
				   $listsort="sale"." ".$sort;
					 break;              
		   }  	
	   } 
	   else {
		 $key="1";$see="asc";
		 $order="view";$sort="asc";
		 $listsort=$order." ".$sort;			
	   }
		
       if($sort=="asc"){
		  $see="desc";
	   }
       if($sort=="desc"){
		  $see="asc";
	   }
       $this->assign('see',$see);
       $this->assign('order',$key);
	   $this->assign('value',$sort);
		if(isset($_GET['brandid'])){
			$brandid=I('get.brandid',0,'intval');
			$title=M('brand')->where("id='$brandid'")->order("id desc")->getField('title');
            $map['brand']  = array('like', '%'.$title.'%');
            $this->assign('brandid',$brandid);
        }
        if ( isset($_GET['start_price']) ) {
            $map['price'][] = array('egt',I('start_price',0,'intval'));
           $this->assign('start_price',$_GET['start_price']);
        }
        if ( isset($_GET['end_price']) ) {
            $map['price'][] = array('elt',I('end_price',0,'intval'));
            $this->assign('end_price',$_GET['end_price']);
        }

		/* 数据分页*/
		$list=D("Document")->getLists($map,$category['list_row'],$listsort);
		$this->assign('list',$list);// 赋值数据集      
		$page=D("Document")->getPage($map,$category['list_row'],$listsort);
		$this->assign('page',$page);//


		//获取分类的id
		$name=$category['name'];
		$child=M('Category')->where("pid='$pid'")->select();
		$this->assign('num', $count);
		$this->assign('childlist', $child);
		
		/*栏目页统计代码实现，tag=2*/
		if(1==C('IP_TONGJI')){
		$record=IpLookup("",2,$name);
		}
		
		//频道页循环3级分类
		$this->meta_title = $category['title'];

		/*销量排行*/
		$sales=$this->ranks();
		$this->assign('sales', $sales);
		/*最近访问*/
		$recent=$this->view_recent();
		$this->assign('recent', $recent);
		/* 模板赋值并渲染模板 */
		$this->assign('ctg', $category);
		$this->display($category['template_lists']);
    }
    

    
      /* 用户商品详情页 */
    public function detailUser($id = 0, $p = 1){	
		/* 浏览量排行前7个商品*/
		$view=M('Document')->where("display=1 and status=1")->order("view desc")->select();
		$this->assign('viewlist', $view);
		//用户头像
		$faceid=D('UcenterMember')->getfaceid($uid);
		$this->assign('faceid', $faceid);
		//echo D('UcenterMember')->getLastSql();
// 		var_dump($faceid);die;
 		$id=I('get.id');
		$consumer=M('ucenter_member');
		$consumers=$consumer->query(
				"select a.username,a.id,a.face,b.uid  from yershop_ucenter_member as a left join
				yershop_document_user as b on a.id=b.uid where b.id={$id};
				");
				//dump($consumers);die;
				$this->assign('consumers',$consumers);
		//echo D('UcenterMember')->getLastSql();
// 		var_dump($faceid);die;
		//$ob=M("attribute");
			//热门推荐
		 $pos=M('document')->query("
	SELECT a.title,a.place,a.id,a.cover_id,a.baoming,a.category_id, DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d %H:%i:%s')as st,
	DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d %H:%i:%s')as de, b.path,c.zid
	from(`yershop_document` as a left join `yershop_picture` as b on a.cover_id=b.id)
	left join `yershop_document_product` as c on a.id=c.id where a.baoming!=0 and a.category_id!=56 order by a.view  limit 6");
		//dump($pos);die;//$pos=M('document')->where("baoming!=0")->limit('6')->select();
	    $this->assign("poslist",$pos);

	    
	    /*商品详情*/
		$hid=I('get.id');
		$ob=M('document');
		$obc=$ob->query(
// 				"SELECT  a.id,a.cid,b.title,a.title,a.place,a.qq,a.tel,a.content,a.starttime,a.from  AS st,a.stoptime AS de,
// 				a.titlepage as path,a.from
// 				FROM `yershop_document_user` AS a left join `yershop_document` as b on a.id=b.category_id 
// 				WHERE a.id={$hid};"
				"select  id,title,content,titlepage,qq,tel,starttime AS st,stoptime AS de,place,cid,a.from from yershop_document_user as a  where id = {$hid}"
				);	
		//dump($obc);die;
		$this->assign('obc',$obc);
		/*上级分类id*/
		$cid=M('document')->query(
// 				"SELECT a.id,a.category_id,b.id,b.title FROM `yershop_document_user` AS a  LEFT JOIN `yershop_category` AS b ON
// 				a.category_id=b.id WHERE a.id={$hid};
				"SELECT b.title FROM `yershop_document_user` AS a  LEFT JOIN `yershop_category` AS b ON
				a.category_id=b.id WHERE a.id={$hid};
				");
		$this->assign('cid',$cid);
   /*活动详情*/
		$xiangqing=M('document')->query("
				SELECT a.id,b.content FROM `yershop_document_user` AS a LEFT JOIN `yershop_document_product` AS b ON
				a.id=b.id WHERE a.id={$hid};
				");
		$this->assign('xiangqing',$xiangqing);
		/*开始报名显示与隐藏*/
		$show=M('document')->query("select * from `yershop_document_user` where id={$hid}");
		$this->assign('show',$show);
			
		$member=M('member')->query('select nickname,face from yershop_member');	
		$this->assign('member',$member);
/*
		/* 标识正确性检测 */
		if(!($id && is_numeric($id))){
		   $this->error('文档ID错误！');
		}	
		/* 获取详细信息 */
	//	$Document = D('Document');
		//$info = $Document->detail($id);
	//	if(!$info){
		//   $this->error($Document->getError());
	//	} 
		/* 分类信息 */
//	$category = $this->category($info['category_id']);
	//	print_r($category);
		/* 获取模板 */
	//	if(!empty($info['template'])){//已定制模板
	//	   $tmpl = $info['template'];
	//	} 
	//	else if (!empty($category['template_detail'])){ 
	//		//分类已定制模板
	//	  $tmpl = $category['template_detail'];
	//	} else { 
			//使用默认模板
	//	    $tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detail';
	//	}
		
		/* 更新浏览数 */
	//	$map = array('id' => $id);
	//	$Document->where($map)->setInc('view');
		/*内容页统计代码实现，tag=3*/
		if(1==C('IP_TONGJI')){
		   $record=IpLookup("",3,$id);
		}		
		/*获取商品所有评论*/
		$comment = M('comment');	
		$count = $comment->where("status='1' and goodid='$id'")->count(); //计算记录数
        $this->assign('count', $count);
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"comment"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$id'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);

         /* 咨询管理 */
		$message=M("message");
		$reply=M("reply");
		$countmessage=$message->where(" goodid='$id'")->count();
		$Pagequestion=new \Think\AjaxPage($countmessage,5,"quest");	
		$limitquestion = $Pagequestion->firstRow . "," . $Pagequestion->listRows;
		$showquestion= $Pagequestion->show();
		$listquestion=$message->where("goodid='$id'")->order('id desc')->limit($limitquestion)->select();
		foreach($listquestion as $n=> $val){
		   $listquestion[$n]['id']=$reply->where('messageid=\''.$val['id'].'\'')->select();
		}
		$this->assign('listquestion',$listquestion);// 赋值数据集
		$this->assign('pagequestion',$showquestion);//
	   
		/* 查询所有评论 ， 查询评论用户的用户名、昵称、头像*/
		$comments = M('comments');
		$data = $comments->query("select a.content,a.creattime,b.username,b.face from yershop_comments as a,yershop_ucenter_member as b where a.uid = b.id and a.gid = $id and a.status = 1");
		$num = $comments->where("gid=$id")->count();
		$this->assign('num',$num);
		$this->assign('data',$data);
		//$this->assign('info', $info);
		//$this->meta_title = $info["title"];
		$this->display();
	}
    
    public function baoming()
    {
    	 $id =  $_POST['id'];
    	 $info = M('document');
     	$result = $info->field('baoming_info')->where('id = '.$id)->select();
    	echo json_encode($result);
    }

     /* 商品详情页 */
    public function detail($id = 0, $p = 1){	
		/* 浏览量排行前7个商品*/
		$view=M('document')->where("display=1 and status=1")->order("view desc")->select();
		$this->assign('view', $view);
		//print_r($view);
		//用户头像
		 $faceid=D('UcenterMember')->getfaceid($uid);//dump($faceid);die;
		$this->assign('faceid', $faceid);
		$id=I('get.id');
		
		$consumer=M('ucenter_member');
		$consumers=$consumer->query(
// 				"select a.username,a.id,a.face,b.uid,b.id from yershop_ucenter_member as a left join 
// 				yershop_document as b on a.id=b.uid where b.id={$id};
				"select a.username,a.id,a.face,b.uid  from yershop_ucenter_member as a left join 
				yershop_document as b on a.id=b.uid where b.id={$id};
				");
		$this->assign('consumers',$consumers);
		//热门推荐
		 $pos=M('document')->query("
	SELECT a.title,a.place,a.id,a.cover_id,a.baoming,a.category_id, DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d %H:%i:%s')as st,
	DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d %H:%i:%s')as de, b.path,c.zid
	from(`yershop_document` as a left join `yershop_picture` as b on a.cover_id=b.id)
	left join `yershop_document_product` as c on a.id=c.id where a.baoming!=0 and a.category_id > 160 and a.status = 1 order by a.view  limit 6");
	//dump($pos);die;//$pos=M('document')->where("baoming!=0")->limit('6')->select();
	    $this->assign("poslist",$pos);
		//$ob=M("attribute");
     /*商品详情*/
		$hid=I('get.id');
		$ob=M('document');
		$obc=$ob->query("
				SELECT  a.id,a.title,a.place,a.category_id,a.baoming,a.price,a.from,a.view,
	DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d %H:%i:%s') AS st,
	DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d %H:%i:%s') AS de,
	a.place,b.path,c.area 
	FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
	 LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
		WHERE a.id={$hid};
				");

		if((time() - session('overtime'))>10 || $_SERVER["REMOTE_ADDR"] !== session('uip')){
			session('overtime',time());
			session('uip',$_SERVER["REMOTE_ADDR"] );

			$arr['view'] = $obc[0]['view'] + 1;
			$res = $ob->where(array('id'=>$hid))->save($arr);
		}
// 		//var_dump($obc);die;
// 		var_dump(session());
// 		if(!session('view')){
//		session(array('view'=>1,'expire'=>15));
		//session(null);
 	//	var_dump(session());
// 			var_dump($obc[0]['view']);
 	//		var_dump(session('view'));
// 			$arr['view'] = $obc[0]['view'] + 1;
// 			$res = $ob->where(array('id'=>$hid))->save($arr);
// 		}	
		
		// var_dump($obc);die;
		// foreach ($obc as $v){
		// 	if(!in_array($v['id'],$brr)){
		// 		$arr[] = $v;
		// 		$brr[] = $v['id'];
		// 	}
		// }
		// $obc = $arr;

		//$obc = array_unique($obc);
		$this->assign('obc',$obc);
		/*上级分类id*/
		$cid=M('document')->query(
// 				"SELECT a.id,a.category_id,b.id,b.title FROM `yershop_document` AS a  LEFT JOIN `yershop_category` AS b ON
// 				a.category_id=b.id WHERE a.id={$hid};
				"SELECT b.title FROM `yershop_document` AS a  LEFT JOIN `yershop_category` AS b ON
				a.category_id=b.id WHERE a.id={$hid};
				");
		//dump($cid);die;
		$this->assign('cid',$cid);
   /*活动详情*/
		$xiangqing=M('document')->query("
				SELECT a.id,b.content FROM `yershop_document` AS a LEFT JOIN `yershop_document_product` AS b ON
				a.id=b.id WHERE a.id={$hid};
				");
		$this->assign('xiangqing',$xiangqing);
	/*开始报名显示与隐藏*/
		$show=M('document')->query("select * from `yershop_document` where id={$hid}");
		$this->assign('show',$show);
		
/*
		/* 标识正确性检测 */
		if(!($id && is_numeric($id))){
		   $this->error('文档ID错误！');
		}	
		//用户的头像用户名遍历
	
		
	
		
		/* 更新浏览数 */
	//	$map = array('id' => $id);
	//	$Document->where($map)->setInc('view');
		/*内容页统计代码实现，tag=3*/
		if(1==C('IP_TONGJI')){
		   $record=IpLookup("",3,$id);
		}		
	/* 查询所有评论 ， 查询评论用户的用户名、昵称、头像*/
	$comments = M('comments');
	$data = $comments->query("select a.content,a.creattime,b.username,b.face from yershop_comments as a,yershop_ucenter_member as b where a.uid = b.id and a.gid = $id and a.status = 1");
	$num = $comments->where("gid=$id")->count();
	$this->assign('num',$num);
	$this->assign('data',$data);


	
	
		//$this->assign('info', $info);
		//$this->meta_title = $info["title"];
		$this->display();
	}
    /* ajax评论-所有评论 */
    public function comment(){	
	    if($_POST["goodid"]){  
			$goodid=I('post.goodid',0,'intval');	 	
		    $this->assign('goodid',$goodid);
		}	
		session('goodid',null);//ajax评论session
	    session('goodid',$goodid);
        $comment = M('comment');
		$count = $comment->where("status='1' and goodid='$goodid'")->count(); //计算记录数
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"comment"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and id='$goodid'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->display(); 
     }
      /* ajax评论-好评 */
     public function commentgood(){
	     if($_POST["goodid"]){  
		    $goodid=I('post.goodid',0,'intval');	 	
		    $this->assign('goodid',$goodid);
	     }	
	    session('goodid',null);//ajax评论session
	    session('goodid',$goodid);
        $comment = M('comment');
		$count = $comment->where("status='1' and goodid='$goodid' and score='3'")->count(); //计算记录数
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"commentgood"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$goodid' and score='3'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->display(); 
     }
        /* ajax评论-中评 */
     public function commentmiddle(){
	     if($_POST["goodid"]) {  
		    $goodid=I('post.goodid',0,'intval');	
		    $this->assign('goodid',$goodid);
		 }	
		session('goodid',null);//ajax评论session
	    session('goodid',$goodid);
        $comment = M('comment');
		$count = $comment->where("status='1' and goodid='$goodid' and score='2'")->count(); //计算记录数
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"commentmiddle"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$goodid' and score='2'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->display(); 
     }
      /* ajax评论-差评 */
     public function commentworse(){	
	     if($_POST["goodid"]){
			 $goodid=I('post.goodid',0,'intval');	
		     $this->assign('goodid',$goodid);
		}	
		session('goodid',null);//ajax评论session
	    session('goodid',$goodid);
        $comment = M('comment');
		$count = $comment->where("status='1' and goodid='$goodid' and score='1'")->count(); //计算记录数
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"commentworse"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$goodid' and score='1'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->display(); 
    }
    public function quest(){	
	    if($_POST["goodid"]){   
		   $goodid=I('post.goodid',0,'intval');
		   $this->assign('goodid',$goodid);
	    }	
	    session('goodid',null);//ajax评论session
	    session('goodid',$goodid);
        $message=M("message");
		$reply=M("reply");
		$count=$message->where(" goodid='42'")->count();
		$p=new \Think\AjaxPage($count,5,"quest");	
		$limit= $p->firstRow . "," . $p->listRows;
		$page= $p->show();
		$list=$message->where("goodid='42'")->order('id desc')->limit($limit)->select();
		foreach($list as $n=> $val){
		    $list[$n]['id']=$reply->where('messageid=\''.$val['id'].'\'')->select();
		}
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->display(); 
     }
      /* 文档分类检测 */
     private function category($id = 0){
		/* 标识正确性检测 */
		$id = $id ? $id : I('get.pid', 0);
		
		if(empty($id)){
		   $this->error('没有指定文档分类！');
		}
		/* 获取分类信息 */
		$category = D('Category')->info($id);//dump($category);die;
		if($category && 1 == $category['status']){
		   switch ($category['display']) {
		       case 0:
		           $this->error('该分类禁止显示！');
		       break;
		       //TODO: 更多分类显示状态判断
		       default:
		           return $category;
		    }
		} else {
		    $this->error('分类不存在或被禁用！');
		}
	}

		//销量排行
    public function ranks($name){
		////获取商品访问来源来自url的商品数组，tag=3
		$list=M('document')->limit(5)->order("sale desc")->select();	
		return $list;
	}
   //最近浏览
    public function view_recent($name){
		//访客ip
		$ip=getip();
		//根据ip获取会员最近浏览商品，status=3
		$list=M('records')->where(" status='3' and ip='$ip'")->limit(5)->order("id desc")->select();
		return $list;
	}
	//开始报名的提交
	public function submint(){
		$baoming=M('baoming');
		$post=I('post.');
		$post['uid'] =  session('uid');
		$post['user_ip'] = $_SERVER[REMOTE_ADDR];
		$post['bm_time'] = date('Y-m-d H:i:s',time());
		$post['order_code'] = date('Ymd').mt_rand(100000000000000,999999999999999);
		
		//查询报名费
		$document = M('document');
		$where['id'] = $post['goodid'];
		$data = $document->field('price')->where($where)->find();
		$post['price'] = $data['price'];
		$apply=$baoming->add($post);
		
 		if(!empty($apply)){

 			//$this->success('报名成功',U('order/add?id='.$post['shopid'].'&bid='.$apply));
 		// redirect(U('order/add?id='.$post['shopid'].'&bid='.$apply), 3, '');
 		
 			if($data['price']>0){
 				redirect(U('order/add?id='.$post['goodid'].'&bid='.$apply),3,iconv("UTF-8","GB2312",'报名成功'));
 				//$this->success('报名成功',U('order/add?id='.$post['shopid'].'&bid='.$apply));
 			}else{
 				$this->error('报名成功');
 			}
 		
 		}else{
 			$this->error('报名失败');
				
 		}
// 		$this->assign('apply',$apply);
// 		$this->display();  
	}
	//评论提交
	public function commen(){
		$comment=M('comment');
		$post=I('post.');
		//dump($post);die;
		$review=$comment->add($post);
	
		if(!empty($post['content'])){
			$this->success('感谢您提供的宝贵意见',U('__CONTROLLER__/index'));
		}else{
			$this->error('评论不能为空');
		}
	}
	
	/* ajax评论-所有评论 */
	public function comments(){
	
		//实例化model类
		$data['uid'] = session('user_auth.uid');
		$data['gid'] = $_POST['gid'];
		$data['content'] = $_POST['content'];
		$data['creattime'] = time();
		//echo json_encode($data);
		$comments = M('comments');
		//插入数据
		$res = $comments->add($data);
		if($res){
			echo 1;
		}else{
			echo 0;
		}
	}
}
