<?php 
	
		
		//$arr['upload']=base64_decode($_POST['upload']);
			
		if(!empty($_FILES['upload'])){
			$ret=array(
					'files'=>'',//�ϴ��ļ������·��
					'message'=>''//�ϴ��ļ�1�ɹ�  0ʧ�� 
			);
		
			//�ϴ��ļ���
			$fn=$_FILES['upload']['name'];
			//�ϴ��ļ������
			$error=$_FILES['upload']['error'];
			//��ʱ�ļ���
			$tmp_name = $_FILES['upload']['tmp_name'];
			//�ļ���ŵ�·��
			$uploadpath= "./files/head";
			
			//�����ļ��ϴ�����
			$_houzui = pathinfo($fn,PATHINFO_EXTENSION);
			$allow_houzui = array("jpg","jpeg","png","gif");
			if(!in_array($_houzui,$allow_houzui)){
				$ret['message']= "���ϴ����ļ����Ͳ�����";
				continue;
			}
			//�Դ���Ž����ж�
			if( $error> 0){
				switch($error){
					case 1:
					$ret['message']= "�ϴ��ļ��Ĵ�С�����˴�";
					break;
					case 2:
					$ret['message']="�ϴ��ļ��Ĵ�С������ HTML ���� MAX_FILE_SIZE ѡ��ָ����ֵ.";
					break;
					case 3:
					$ret['message']= "�ļ�ֻ�в����ϴ�";
					break;
					case 4:
					$ret['message']= "û���ļ����ϴ���";
					break;
					case 6:
					echo "�Ҳ�����ʱ�ļ���";
					break;
					case 7:
					$ret['message']= "�ļ�д��ʧ��";
					break;
				}
				continue;
			}
			//�����ļ��ϴ��Ĵ�С
			/*$maxsize =  77783000;
			if($_FILES['upload']['size']> $maxsize){
				echo json_encode('���ϴ����ļ�̫���ˣ��������ϴ�');
				continue;
			}*/
			
		
		
			//����ļ�����ʵ��
			if(!getimagesize($tmp_name)){
			$ret['message']='���ϴ��Ĳ�����ЧͼƬ';
				continue;
			}
			//�ϴ�·��
			
			$uploadfile = $uploadpath."/".date('YmdHis',time()).rand(1000,9999).".".$_houzui;
		
			//�����ļ���
			if(!file_exists($uploadpath)){
				mkdir($uploadpath,0777,true);
				}
		
			
		
			//�ϴ��ļ���������
			if(move_uploaded_file($tmp_name,$uploadfile)){
				    $ret['message']=1;//�ɹ�
				}else{
					$ret['message']=0;//ʧ��
				}
				
				$ret['files']=$uploadfile;
				
				echo json_encode($ret);
			}else{
				$ret=array(
					'message'=>0
				);
				echo json_encode($ret);
			}
?>