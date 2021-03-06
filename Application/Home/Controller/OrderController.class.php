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
 * 订单模型控制器
 * 文档模型列表和详情
 */


class OrderController extends HomeController {
/**
 * 生成订单信息
 * 
 */
    public function add() {
		if ( !is_login() ) {
				$this->error( "您还没有登陆",U("User/login") );
			}
			/* uid调用*/
		$uid=is_login();
		$score=get_score($uid);		  
		/* 积分兑换*/
// 		$ratio= $score/C('RATIO');
// 		$this->assign('ratio', $ratio);
// 		$this->assign('uid', $uid);
		/* 创建订单*/
		//活动id
		$id = $_GET['id'];
		$bid = $_GET['bid'];
		$price = M('document')->where("id='$id'")->select();
		$user = M('baoming')->where("id='$bid'")->select();
		if(IS_GET){
			$goodlist=M("shoplist");
			$tag=$this->ordersn(); //创建支付订单号

		    for($i=0;$i<count($_GET["id"]);$i++){
				$id = $_GET ["id"] [$i];
				//$num = $_GET ["num"] [$i];
				//if($num<1){
				//   $this->error( "无效数量");
			    //}
				$goodlist->goodid = $id;
				$goodlist->status = 1;
				$goodlist->orderid ='';
				$goodlist->parameters =$_GET ["parameters"] [$i];
				$goodlist->sort =$_GET ["sort"] [$i];
				//$goodlist->num = $num;
				$goodlist->uid=$uid;
				$goodlist->tag=$tag;//标识号必须相同
				$goodlist->create_time= NOW_TIME;

				// $goodprice=$_GET ["price"] [$i];dump($_GET ["price"]);
				$goodlist->price =$user[0]['price'];
				$title = $price[0]['title'];//dump($title);
				//$goodtotal=$num*$goodprice;
				$goodlist->total =$user[0]['price'];
				$goodlist->add();
		    } 
			$defaultaddress=get_address($uid);
			$this->assign('address',$defaultaddress);
			// echo $tag;
			$a= M("shoplist")->where(" tag='$tag'")->select();

			$total='';$num='';
			foreach ($a as $k=>$val) {
					// $total += $val['total'] ;
					$total += $val['price'];
					$num+=$val['num'];
			}
			
			if($total<C('LOWWEST')){
			   $trans=C('SHIPMONEY');//dump(C('LOWWEST'));
		    }else{
			   $trans=0;
		    } 
		   $all=$total+$trans;
		   $shoplist= M('shoplist')->where("tag='$tag'")->select();
		   			

		   $this->assign('shoplist',$shoplist);
		   
		   $map["pid"]=0;
		   $list=M("area")->where($map)->select();
		   $this->assign('list', $list);
		   $this->assign('all', $all);
		   $this->assign('title', $title);
		   $this->assign('num',$num);
		   $this->assign('tag',$tag);
		   $this->assign('name',$user[0]['real_name']);
	   		$this->assign('tel',$user[0]['tel']);
	   		$this->assign('email',$user[0]['email']);
	   		$this->assign('order_code',$user[0]['order_code']);
	    	$this->assign('price',$user[0]['price']);
		   $this->assign('total',$user[0]['price']);
		   $this->assign('trans',$trans);
		   $this->meta_title = '订单结算';
		   $this->display('Shopcart/add');
	    }
// 	    $baoming=M("baoming")->where('id='.$bid)->select();
	    
// 	   // dump($user);
// 	    $shop=M("document")->where('id='.$baoming[0]['shopid'])->select();
// 	   // dump($baoming);die;

	  
// 	   // var_dump($shop);
// 	    $this->display('Shopcart/add');
    }

/**
 * 保存数据到订单
 * @paragram $data
 */
    public function save() {
		if ( !is_login() ) {
		    $this->error( "您还没有登陆",U("User/login") );
		}
		// echo 1;die;
		//$order=D("order");
		
		// 获取支付订单号
		//$tag=I('post.tag');
	//if($tag == 11){
		
		//$tag=safe_replace($tag);//过滤
       // $data['tag']=$tag;//支付订单号
        
		//防止重复提交
		//$info=$order->where("tag='$tag'")->find();
		//isset($info)&& $this->error('重复提交订单');
		
		//获取会员uid
		$uid=is_login();
		//设置订单状态
		// $data['status'] = -1;
		// $data['ispay'] = 1;
		//验证价格并清空购物车
		// $list=M("shoplist")->where("tag='$tag'")->select();
		         //遍历
	  //   foreach($list as $k=>$val){
			// 	    //获取购物清单数据表产品id，字段goodid				
			// $id=$val["goodid"];		
			// //提交的价格
			//$goodprice=$val["price"];			
			// $Document = D('Document');
			// $info= $Document->detail($id);
					
			// //系统价格
			// if($info['groupprice']){
			// 	$string=$info['groupprice'].'、'.$info['price'];
			// 	$array= explode('、',$string);			
			// 	//验证，多个价格
			// 	if (!in_array($goodprice,$array)) {
			// 		  $this->error('商品价格与系统不符,商品id'.$val["goodid"].'商品价格'.$safeprice.'系统价格'.$string);
			// 	}
			// }		
			// //验证，普通价格
			// else if($goodprice!==$info['price']){
			// 	$this->error('商品价格与系统不符,商品id'.$val["goodid"].'商品价格'.$safeprice.'系统价格'.$safe['price']);
			// }
		 //   else{
			//  #your code
			//    addUserLog('价格正确', $uid)  ;//日志
		 //   }
			// 		    //删除购物车中产品id
		 //  M("shopcart")->where("goodid='$id'and uid='$uid'")->delete();
	  //  }


		//订单号
		//$data['orderid']=date('Ym',time()).time().$uid;//订单号


		//计算商品总额
		//$total=$this->getPricetotal($tag);
		//$data['price']=$total;

		
		
		//计算使用地址id
		// $senderid=I('post.sender'); 
		// $senderid=safe_replace($senderid);//过滤
		// $data['addressid']=$senderid;
		
		
		//计算应付金额
		// $all=$total+$trans-$ratio-$decfee;
		// $data['total_money']=$all;//应付金额
		
		//$data['create_time']=NOW_TIME;//创建时间

	
		//支付类型PayType，1-货到付款
		// if($_POST["PayType"]=="1"){
			// echo 11;die();
			//创建订单
			//$data['status']=-1;
			//$data['ispay']=1;//货到付款
			
			//$orderid=$order->add($data);
			//dump($orderid);die;
			//$this->success('报名成功',U('order/save'));
// 			$this->assign('total',$total);
// 			 $this->display('Pay/index');
// 		}else{
			$this->assign('codeid',$_POST['order_code']);
			$this->assign('goodprice',$_POST['price']);
			$this->display('Pay/index');
	//	}
			
			// M("shoplist")->where("tags='$tag'")->setField('orderid',$orderid);
			// var_dump($orderid);
			
			//保存货到付款支付数据
			// $pay=M("pay");
			// $pay->create();
			// $pay->money=$all;
			// $pay->ratio=$ratio;
			// $pay->total=$total;
			// $pay->out_trade_no=$tag;
			// $pay->yunfee=$trans;
			// $pay->coupon=$defee;
			// $pay->uid=$uid;
			// $pay->addressid=$senderid;
			// $pay->create_time=NOW_TIME;
			// $pay->type=2;//货到付款
			// $pay->status=2;
			// $pay->add();
						
            //发送邮件
			// $mail=get_email($uid);//获取会员邮箱
			// $title="交易提醒";
			// $content="您在<a href=\"".C('DAMAIN')."\" target='_blank'>".C('SITENAME').'</a>提交了订单，订单号'.$tag;
			// if ( C('MAIL_PASSWORD')){
			// 	    SendMail($mail,$title,$content);
			// }

            //保存日志
			// addUserLog('货到付款订单已提交', $uid)  ;
			// $this->meta_title = '提交成功';
			// $this->display('Shopcart/success');
		// }
		//支付类型PayType，2-在线支付
		// if($_POST["PayType"]=="2")  {
	       
		//     //创建订单
		// 	$data['ispay']="1";
		// 	$data['status']="-1";//待支付
		// 	//根据订单id保存对应的费用数据
		// 	$orderid=$order->add($data);
		// 	M("shoplist")->where("tag='$tag'")->setField('orderid',$orderid);


		//发起支付时thinkpay会自动创建
		     $pay=M("pay");//dump($pay);die;
		     $pay->create();
		     $pay->money=$all;
		     $pay->ratio=$ratio;
		     $pay->total=$total;
		     $pay->out_trade_no=$tag;
		     $pay->yunfee=$trans;
		     $pay->coupon=$deccode;
		     $pay->uid=$uid;
		     $pay->addressid=$senderid;
		       $pay->create_time=NOW_TIME;
		       $pay->type=1;//在线支付
		     $pay->status=-1;//待支付
		     $pay->add();



			//记录日志
			addUserLog('在线支付订单已提交', $uid)  ;

			$this->meta_title = '订单支付';

   //          //输出支付订单号和支付金额
			 $this->assign('codeid',$tag);
			 $this->assign('goodprice',$all);


			//支付页
			
		// }	
    }
    public function ordersn_old(){
		$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
		$orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%04d%02d', rand(1000, 9999),rand(0,99));
		return $orderSn;
	}

    // 生成支付订单号
   public function ordersn(){
        if ( is_login() ) {
		      $uid=is_login();
		      $code=date('Ym',time()).time().$uid;
	       return $code;
		}
    }

    public function getPricetotal($tag) { 
        $tag=safe_replace($tag);//过滤

        //$data = M("shoplist")->where("tag='$tag'")->select();
         $data = M("shoplist")->select();
         //dump($data);die;
        foreach ($data as $k=>$val) {
			$price=$val['price'];//dump(//);die;
           // $total += $val['num'] * $price;
        }
        RETURN sprintf("%01.2f", $price);
    }
 
    public function getpriceNum($id) { 
	    $id=safe_replace($id);//过滤
        $price = 0.00;
        $data = M("shoplist")->where("tag='$id'")->select();
        foreach ($data as $k=>$item) {
            $sum += $item['num'];
        }
        return  $sum;
    }

    /* 文档模型频道页 */
	public function detail(){
		if ( !is_login() ) {
		     $this->error( "您还没有登陆",U("User/login") );
		}
		$id= I('get.id');//获取id
		$id =safe_replace($id);//过滤
// 		dump($id);die;
// 		$typeCom=M("order")->where("orderid='$id'")->getField("express");
// 		$typeNu=M("order")->where("orderid='$id'")->getField("express_code");
// 		//dump($typeCom);die;
// 		if (isset($typeCom)&&$typeNu){
// 		    $retData=$this->getkuaidi($typeCom,$typeNu );
// 		}else{
// 			$retData="";
// 		}
// 		$this->assign('kuaidata', $retData);
// 		/* uid调用*/
// 		$Member=D("member");
// 		$uid=$Member->uid();
// 		$order=D("order");
// 		$detail=$order->where("orderid='$id'")->select();

// 		$list=M("shoplist");
// 		foreach($detail as $n=> $val){
// 		    $detail[$n]['id']=$list->where('orderid=\''.$val['id'].'\'')->select();

// 		}
// 		$addressid=$order->where("orderid='$id'")->getField("addressid");
// 		$trans=M("address")->where("id='$addressid'")->select();
// 		$this->assign('translist',$trans);
		$detail = M('baoming')->query("select b.id, b.title,b.starttime,b.deadline,b.price from yershop_document as b,yershop_baoming  as a where b.id=a.goodid and a.id=$id");

 		$this->assign('detaillist',$detail);
		$this->meta_title = '订单详情';
		$this->display();
	}


	public function wuliu(){
		if ( !is_login() ) {
		    $this->error( "您还没有登陆",U("User/login") );
		}
		$id= I('get.orderid','','strip_tags');//获取id
		$id =safe_replace($id);//过滤
		$this->meta_title = '订单'.$id.'物流详情';
		$typeCom=M("order")->where("orderid='$id'")->getField("tool");
		$typeNu=M("order")->where("orderid='$id'")->getField("toolid");
		if (isset($typeCom)&&$typeNu){
		    $retData=$this->getkuaidi($typeCom,$typeNu );
		    addUserLog('查询快递', is_login())  ;
		}
		else{
		  $retData="";
		}
		$this->assign('kuaidata', $retData);		
		$this->display();
	}



    public function getkuaidi($typeCom,$typeNu ){
		$AppKey=C('100KEY');//请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
		$url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$typeCom.'&nu='.$typeNu.'&show=2&muti=1&order=asc';
		//请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
		$powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';
		//优先使用curl模式发送数据
		if (function_exists('curl_init') == 1){
			  $curl = curl_init();
			  curl_setopt ($curl, CURLOPT_URL, $url);
			  curl_setopt ($curl, CURLOPT_HEADER,0);
			  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
			  curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
			  curl_setopt ($curl, CURLOPT_TIMEOUT,5);
			  $get_content = curl_exec($curl);
			  curl_close ($curl);
		 }else{
			  Vendor("Snoopy.Snoopy");
			  $snoopy=new \Vendor\Snoopy\Snoopy();
			  $snoopy->referer = 'http://www.google.com/';//伪装来源
			  $snoopy->fetch($url);
			  $get_content = $snoopy->results;
		}
		return $get_content;
		//print_r($get_content . '<br/>' . $powered);
    }
	public function delorder() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$tag=I('tag');
        $tag=safe_replace($tag);//过滤
		$map["tag"]=array("in",$tag);
		$map["uid"]=is_login();
		$map["status"]=array("gt",2);
		M("order")->where($map)->delete();		
		$data=M("shoplist")->where($map)->delete();	
		if($data) { 
			$this->success('删除成功！');
		}else{
		   $this->error('删除失败！');
		}
	}
	
	/*删除订单*/
	public function delete(){
		
		$id = $_GET['id'];
		$data['if_baoming'] = -1;
		$baoming = M('baoming');
		$res = $baoming->where("id=$id")->save($data);
		if($res){
			$this->success('操作成功！');
		}else{
			$this->error('操作失败！');
		}
	}
	public function del(){
		$id = implode(',',$_POST['id']);
		$data['if_baoming'] = -1;
		$baoming = M('baoming');
		$res = $baoming->where("id in ($id)")->save($data);
		if($res){
			$this->success('操作成功！');
		}else{
			$this->error('操作失败！');
		}
	}
}
