<?php
	namespace Home\Controller;
	use Think\Controller;
	class DocumentuserController extends Controller{
		public function issue(){
		//	var_dump($_POST);die;
		//发布内容插入后台
		$post=$_POST['starttime'].'-'.$_POST['stoptime'];
		//post传值
		$_POST['uid'] = session("user_auth.uid");
		$_POST['create_time'] = time();
		$data=I('post.');
		//var_dump($data);die;
		//$data['content']=$_POST['content'];
		
		//实例化document_user表
		$documentuser=M('document_user');
		//$this->assign('documentuser',$documentuser);
		//$this->display();
		//实例化一个上传类你
		$upload=new \Think\Upload();
		$upload->maxSize = 3145728;// 设置附件上传大小
		$upload->exts=array('jpg','png','jpeg');//设置上传类型
		$upload->savePath='../Uploads/Install/';//文件保存路径
		$upload->imageClassPath     = 'ORG.Util.Image';
		
		$info   =   $upload->uploadOne($_FILES['titlepage']);
// 		$info   = $upload->upload();

		//dump($info);
		if(!$info){
			$this->error($upload->getError());
		}else{
			
			$url_img = str_replace(".","",$info['savepath']);
			// dump($url_img);
			$_POST['titlepage']=$url_img.$info['savename'];
			
			//$data.=$_POST['titlepage'];
			//dump($_POST['titlepage']);die;
			$date=$documentuser->create();
			$id=I('post.cid');
			$data['cid']=$id;
			//var_dump(session("user_auth.uid"));die;
			
			$res=$documentuser->add($_POST);
			//$this->ajaxreturn($date);
			if($res){
		
				
				$this->success('发布成功',U('UserCoupon/index'));
				//
				
			}else{
				$this->error('发布失败');
			}
		}
		}
		
	
	}
?>