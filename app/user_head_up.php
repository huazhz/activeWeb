<?php 
//发布商品封面图片上传
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ret=array('strings'=>$_POST,'error'=>'0');
    $fs=array();
    foreach ( $_FILES as $name=>$file ) {
        $fn=$file['name'];
        $ft=strrpos($fn,'.',0);
        $fm=substr($fn,0,$ft);
        $fe=substr($fn,$ft);
        $fp='./files/head/'.$fn;
        $fi=1;
        while( file_exists($fp) ) {
         //   $fn=$fm.'['.$fi.']'.$fe;
            $fn=date('YmdHis',time()).rand(1000,9999).$fe;          
            $fp='./files/head/'.$fn;
            $fi++;
        }
        move_uploaded_file($file['tmp_name'],$fp);
    }

    $ret['files']=$fp;

    echo json_encode($ret);
}else{
    echo "{'error':'Unsupport GET request!'}";
}


?>