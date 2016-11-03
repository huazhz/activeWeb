<?php
namespace Home\Controller;
use OT\DataDictionary;
use User\Api\UserApi;

class ActivityController extends HomeController{
	function goactivity(){
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		
		//查询活动分类
		$mod=M('category');
		$cc=$mod->where('id>160')->select();
		$this->assign('cc',$cc);
		
		//用户信息
		$faceid=D('UcenterMember')->getfaceid($uid);
		$this->assign('faceid', $faceid);
		
		//用户发布活动数
		$where['uid'] = session("user_auth.uid");
		$count = M('document_user')->where($where)->count('id');
		$this->assign('count',$count);
		
		$this->display();
	}
	public function issue(){
		//发布内容插入后台
		$post=$_POST['starttime'].'-'.$_POST['stoptime'];
		//post传值
		$data=I('post.');
		$data['content']=$_POST['content'];
		//print_r($data);
		preg_match_all('/<div[^>]*name="content"[^>]*>(.*?) <\/div>/si',$text,$match);
		//dump($match);die;
	
		//实例化document_user表
		$documentuser=M('document');
		//$this->assign('documentuser',$documentuser);
		//$this->display();
		//实例化一个上传类你
		$upload=new \Think\Upload();
		$upload->maxSize = 3145728;// 设置附件上传大小
		$upload->exts=array('jpg','png','jpeg');//设置上传类型
		$upload->savePath='Uploads/Install/';//文件保存路径
		$info   =   $upload->uploadOne($_FILES['titlepage']);
		if(!$info){
			$this->error($upload->getError());
		}else{
			$_POST['titlepage']=$info['savepath'].$info['savename'];
			$date=$documentuser->create();
				
			$res=$documentuser->add();
			if($res){
				$this->redirect('UserCoupon/index');
				$this->assign('res',$res);
				$this->display();
			}
		}
	}
	/*
	public function activitylist(){
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
	
		//用户信息
		$faceid=D('UcenterMember')->getfaceid($uid);
		$this->assign('faceid', $faceid);
	
		//用户发布活动数
		$where['uid'] = session("user_auth.uid");
		$count = M('document_user')->where($where)->count('id');
		$this->assign('count',$count);
	
		//查询用户发布的活动信息
		$data = M('document_user')->field('id,title,create_time')->where($where)->select();
	
		$this->assign('data',$data);
	
		$this->display();
	}	
	*/
	public function activityedit(){
		//获取要修改数据的id值
		$id = $_GET['id'];
		
		//查询活动分类
		$mod=M('category');
		$cc=$mod->where('id>160')->select();
		$this->assign('cc',$cc);
		
		//用户信息
		$faceid=D('UcenterMember')->getfaceid($uid);
		$this->assign('faceid', $faceid);
		
		//用户发布活动数
		$where['uid'] = session("user_auth.uid");
		$count = M('document_user')->where($where)->count('id');
		$this->assign('count',$count);
		
		/* 地区信息 */
		$diqu= M('brand')->field("id,title")->select();
		$this->assign('diqu',$diqu);
		
		//实例化model类
		$users = M('document_user');
		//查询数据
		$where['id'] = $id;
		$data = $users->field('id,titlepage,title,cid,area,starttime,stoptime,place,tel,QQ,content')->where($where)->find(); 
		//分配数据
		$this->assign('data',$data);
		$this->display();
		
	}
	
	public function activityupdate(){
		//var_dump($_POST);die;
		
		//实例化model类
		$user = M('document_user');
		
		//实例化一个上传类你
		$upload=new \Think\Upload();
		$upload->maxSize = 3145728;// 设置附件上传大小
		$upload->exts=array('jpg','png','jpeg');//设置上传类型
		$upload->savePath='../Uploads/Install/';//文件保存路径
		$upload->imageClassPath     = 'ORG.Util.Image';		
		$info   =   $upload->uploadOne($_FILES['titlepage']);		
		if($info){
			//获取原图片路径，重新上传图片后，删除原来的图片
			$path = $user->field('titlepage')->find($id);
			unlink(APP_PATH.'..'.$path["titlepage"]);
			$url_img = str_replace(".","",$info['savepath']);
			$_POST['titlepage']=$url_img.$info['savename'];
		}
			
			//执行更新操作
			$res = $user->save($_POST);
			if($res){
				$this->success('更新成功',U('UserCoupon/index'));
			}else{
				$this->error('更新失败');
			}		
		
	}
	
	public function delete(){
		
		//获取要删除的数据id值
		$id = $_GET['id'];
		//实例化model类
		$user = M('document_user');
		
		//获取图片文件路径
		$path = $user->field('titlepage')->find($id);
		//echo APP_PATH.'..'.$path["titlepage"];die;
		//执行删除操作
		$where['id'] = $id;
		$res = $user->where($where)->delete() ;
		if($res){
			unlink(APP_PATH.'..'.$path["titlepage"]);
			$this->success('删除成功',U('UserCoupon/index'));
		}else{
			$this->error('删除失败，请重试');
		}
	}
	

}
