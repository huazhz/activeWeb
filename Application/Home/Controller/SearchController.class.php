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
 * 搜索控制器
 * 文档模型列表和详情
 */
class SearchController extends HomeController {

    /* 文档模型频道页 */
	public function index(){

        $keyword= I('words');
        $keyword=safe_replace($keyword);//过滤
        $key=I('get.order');
        $key=safe_replace($key);//过滤
		$sort=I('get.sort');  
        $sort=safe_replace($sort);//过滤
        if($key){  
		
		     if(!is_numeric($key)){
		         $this->error('排序ID错误！');
		     }
		    switch ($key){
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
	   else{
		 $key="1";
	     $see="asc";
		 $order="view";
	     $sort="asc";
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

	  $map['title|name|description']  = array('like','%'.$keyword.'%');
	  $map['category_id'] = array('gt',160);
	  $map['status'] = array('eq',1);
      $count=M('Document')->where($map)->count();
      $Page= new \Think\Page($count,15);

	  $Page->parameter['words'] = $keyword;
	  $Page->setConfig('prev','上一页');
      $Page->setConfig('next','下一页');
      $Page->setConfig('first','第一页');
      $Page->setConfig('last','尾页');
      
      $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
      $show= $Page->show(); 
      $document = M('Document');   
      
      
      $p=I('get.p')?I('get.p'):1;//获取页数
     $list=$document->where($map)->order($listsort)->limit($Page->firstRow.','.$Page->listRows)->select();
   
     $this->assign('list',$list);// 赋值数据集
     $this->assign('page',$show);
//      $this->assign('url_a','Search/words/'.$keyword);
    
     /*销量排行*/
	 $sales=M('document')->query("
	SELECT a.title,a.place,a.id,a.cover_id,a.baoming,a.category_id, DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d %H:%i:%s')as st,
	DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d %H:%i:%s')as de, b.path,c.zid
	from(`yershop_document` as a left join `yershop_picture` as b on a.cover_id=b.id)
	left join `yershop_document_product` as c on a.id=c.id where a.baoming!=0 and a.category_id!=56 order by a.view  limit 5");
	 $this->assign('sales', $sales);
	 /*最近访问*/
	 $recent=R('Article/view_recent');
	 $this->assign('recent', $recent);
     $this->assign('searchlist', $list);
	 $this->assign('keyword', $keyword);	
	 $this->meta_title = $keyword.'的搜索结果';
	 //echo M('Document')->getLastSql();
	 $this->display();
	 //$this->redirect('Search/index', array('words' => $keyword), 0, '');
	 
	}
}
