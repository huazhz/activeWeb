
<?php 
		
		
		$arr=base64_decode($_POST['upload']);
		$path='';
		$ml='';//目录
		if(isset($_POST['fabu_image'])){
			if($_POST['fabu_image']==1){
						$path='./files/';
						
					}
				
				}else{
					$path='./files/head/';
					$ml='/app/';
				}
		
		
		
		$filename=date('YmdHis',time()).rand(1000,9999).".".'jpg';
		$size=file_put_contents($path.$filename, $arr);
		if($size>0){
			echo json_encode($ml.$path.$filename);
		}else{
			if(file_exists($path.$filename)){
				unlink($path.$filename);
			}
			echo json_encode('文件上传失败');
		}	
		
		
?>