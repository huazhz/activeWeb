<?php
// +----------------------------------------------------------------------
// | yershop [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// |  Author: 烟消云散 <1010422715@qq.com> 
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * 评论模型控制器
 */
class CommentController extends Controller {
			
    public function index() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		     $listid=I('get.id',0,'intval');
		     $shoplist=M('shoplist');
		     $list= $shoplist->find($listid);
		    //没有评论过
		if ($list["iscomment"]==1){
			  $this->assign('comment', $list);
			  $this->meta_title = '评价商品_'.$title;
			  $this->display();     
	    }else {
		   $this->error('商品评价过');
		}
    }

    public function add() {
        if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		} 
        if (IS_POST) {
			 $uid=is_login();
			 $comment=D("comments");
			 $comment->create();
			 $comment->uid = $uid; // 修改数据对象的status属性
			 $comment->create_time = NOW_TIME; // 增加time属性
			 $comment->status = 1; // 增加time属性
			 $id=$comment->add();
			 if($id>0){ 
				$this->success('商品评价成功',U('Center/index'));
				M('shoplist')->where("id='$shopid'")->setField("iscomment","2");
			  }
         }else {
	        $this->redirect('商品评价失败');
		}  
    }

    public  function lists() {  
        if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}	
	    $uid=is_login();

  		$comments = M("comments");
		 /* 评论*/
	  //  $data1 = M("comments")->query("select  a.id,a.gid,a.content,a.creattime,b.title ,c.path from yershop_comments as a,yershop_document as b,yershop_picture as c  where a.gid=b.id  and b.cover_id = c.id  and a.uid = $uid and a.status=1");
	 //   $data2 = M("comments")->query("select  a.id,a.gid,a.content,a.creattime,b.title ,b.titlepage as path from yershop_comments as a,yershop_document_user as b where a.gid=b.id  and a.uid = $uid and a.status=1");
  		$_GET['type'] = isset($_GET['type'])?$_GET['type']:0;
		$_GET['p'] = isset($_GET['p'])?$_GET['p']:1;
		if($_GET['type'] == 0){
			// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
			$data1 =$comments ->alias('a')->field('a.id,a.gid,a.content,a.creattime,b.title ,c.path')->join('__DOCUMENT__ b on a.gid=b.id')->join('__PICTURE__ c on b.cover_id = c.id')->where("a.uid = $uid and a.status=1")->order('a.creattime')->page($_GET['p'].',2')->select();
			$count      = $comments ->alias('a')->join('__DOCUMENT__ b on a.gid=b.id')->join('__PICTURE__ c on b.cover_id = c.id')->where("a.uid = $uid and a.status=1")->count();// 查询满足要求的总记录数
			$Page1       = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数
			$show1       = $Page1->show();// 分页显示输出
			$this->assign('page1',$show1);// 赋值分页输出
			$this->assign('data1', $data1);
		}else{
			// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
			$data2 =$comments ->alias('a')->field('a.id,a.gid,a.content,a.creattime,b.title ,b.titlepage as path')->join('__DOCUMENT_USER__  b on a.gid=b.id')->where("a.uid = $uid and a.status=1")->order('a.creattime')->page($_GET['p'].',2')->select();
			$count      = $comments ->alias('a')->join('__DOCUMENT_USER__  b on a.gid=b.id')->where("a.uid = $uid and a.status=1")->count();// 查询满足要求的总记录数
			$Page2       = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数
			$show2       = $Page2->show();// 分页显示输出
			$this->assign('page2',$show2);// 赋值分页输出
			$this->assign('data2', $data2);
		}
		$footer=D( 'Category' )->getfooter() ;
	    $this->assign( 'footer',$footer );
	  
	  
	    $this->assign('common',$common);
	   // $this->assign('best',$best);
	    $this->meta_title ="评价管理";
	    $this->display();
      }
      public  function delete() {
      	 $id = $_GET['id'];
      	// echo $_GET['type'];die;
      	 $comments = M('comments');
      	 $res = $comments->delete($id);
      	 if($res){
      	 	$this->success('删除成功',U("Comment/lists?type=".$_GET['type']));
      	 }else{
      	 	$this->error('删除失败');
      	 }
      }
}
