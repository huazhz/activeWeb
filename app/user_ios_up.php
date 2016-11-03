
<?php 
	
		
		//$arr['upload']=base64_decode($_POST['upload']);
			
		if(!empty($_FILES['upload'])){
			$ret=array(
					'files'=>'',//上传文件储存的路径
					'message'=>''//上传文件1成功  0失败 
			);
		
			//上传文件名
			$fn=$_FILES['upload']['name'];
			//上传文件错误号
			$error=$_FILES['upload']['error'];
			//临时文件名
			$tmp_name = $_FILES['upload']['tmp_name'];
			//文件存放的路径
			$uploadpath= "./files/head";
			
			//设置文件上传类型
			$_houzui = pathinfo($fn,PATHINFO_EXTENSION);
			$allow_houzui = array("jpg","jpeg","png","gif");
			if(!in_array($_houzui,$allow_houzui)){
				$ret['message']= "您上传的文件类型不符合";
				continue;
			}
			//对错误号进行判断
			if( $error> 0){
				switch($error){
					case 1:
					$ret['message']= "上传文件的大小超出了大";
					break;
					case 2:
					$ret['message']="上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值.";
					break;
					case 3:
					$ret['message']= "文件只有部分上传";
					break;
					case 4:
					$ret['message']= "没有文件被上传。";
					break;
					case 6:
					echo "找不到临时文件夹";
					break;
					case 7:
					$ret['message']= "文件写入失败";
					break;
				}
				continue;
			}
			//设置文件上传的大小
			/*$maxsize =  77783000;
			if($_FILES['upload']['size']> $maxsize){
				echo json_encode('您上传的文件太大了！请重新上传');
				continue;
			}*/
			
		
		
			//检测文件的真实性
			if(!getimagesize($tmp_name)){
			$ret['message']='你上传的不是有效图片';
				continue;
			}
			//上传路径
			
			$uploadfile = $uploadpath."/".date('YmdHis',time()).rand(1000,9999).".".$_houzui;
		
			//创建文件夹
			if(!file_exists($uploadpath)){
				mkdir($uploadpath,0777,true);
				}
		
			
		
			//上传文件到服务器
			if(move_uploaded_file($tmp_name,$uploadfile)){
				    $ret['message']=1;//成功
				}else{
					$ret['message']=0;//失败
				}
				
				$ret['files']='/app/'.$uploadfile;
				
				echo json_encode($ret);
			}else{
				$ret=array(
					'message'=>0
				);
				echo json_encode($ret);
			}
?>