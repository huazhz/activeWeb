<?php

namespace Admin\Controller;

/**
 * 后台换货订单控制器
  * @author 烟消云散 <1010422715@qq.com>
 */
class ExchangeController extends AdminController {

    /**
     * 订单管理
     * author 烟消云散 <1010422715@qq.com>
     */
    public function index(){
         $status=$_GET['status'];
		 if(isset($_GET['status'])){
		 switch ($status) {
                case '1': 
				     $map['status']=$status;
				     $meta_title="提交换货商品";
                break;
				case '2':
				      $map['status']=$status;
				      $meta_title="同意换货商品";
                  break;
				

			   case '3': 
				     $map['status']=$status;
			         $meta_title="拒绝换货商品";

                  break;
                case '4':                    
				     $map['status']=$status;
                     $meta_title="换货中商品";
					 break;
                case '5':                   
				     $map['status']=$status;
					 $meta_title="完成换货商品";
					 break;
				}
         } 
		
		 else{
		    $status=1;
			
	        $meta_title="换货商品管理";
		 }
         if(isset($_GET['title'])){
		    
            $condition['title']  = array('like', '%'.(string)I('title').'%');
            $data=M('document')->where($condition)->select();
            foreach($data as $k=>$val){
				//获取购物清单数据表产品id，字段id
				$ids=array();
			    array_push($ids,$val['id']);
		     }
           $map['goodid']=array("in",$ids);
        }
        if ( isset($_GET['time-start']) ) {
            $map['update_time'][] = array('egt',strtotime(I('time-start')));
        }
        if ( isset($_GET['time-end']) ) {
            $map['update_time'][] = array('elt',24*60*60 + strtotime(I('time-end')));
        }
        if ( isset($_GET['nickname']) ) {
            $map['uid'] = M('Member')->where(array('nickname'=>I('nickname')))->getField('uid');
        }

	    $this->meta_title = $meta_title;
		$this->assign('status', $status);
        /* 查询条件初始化 */
        $list = $this->lists('Exchange', $map,'id desc');
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
       
        $this->display();
    }

    /**
     * 新增订单
     * @author 烟消云散 <1010422715@qq.com>
     */
    public function add(){
        if(IS_POST){
            $Exchange =D('Exchange');
                if(false !==$Exchange->update()){
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
             
        } else {
            $this->meta_title = '新增'.get_status_title_bymodel($status,'Exchange').'换货商品';
            $this->assign('info',null);
            $this->display();
        }
    }

    /**
     * 编辑订单
     * @author 烟消云散 <1010422715@qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
           
	          $Exchange =D('Exchange');
                if(false !==$Exchange->update()){
                    //记录行为                   
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败'.$id);
              }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Exchange')->find($id);
            $list=M('Exchange')->where("shopid='$id'")->select();
            if(false === $info){
                $this->error('获取订单信息错误');
            }
            $this->assign('list', $list);

			 $this->assign('info', $info);
            $this->meta_title = '编辑订单';
            $this->display();
        }
    }
 /**
     * 同意订单
     * @author 烟消云散 <1010422715@qq.com>
     */
    public function agree($id = 0){
       if(IS_POST){
				$id=$_POST["id"];
				$shopid=$_POST["shopid"];
				//销量减1 库存加1
                $sales= M('document')->where("id='$id'")->setDec('sales');
                $stock= M('document')->where("id='$id'")->setInc('stock');
               /*更新时间*/       
		       $Exchange =D('Exchange');
                if(false !==$Exchange->update()){
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败'.$id);
                }
           
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Exchange')->find($id);
$detail= M('Exchange')->where("id='$id'")->select();
$list=M('shoplist')->where("orderid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
$this->assign('list', $list);
            $this->assign('detail', $detail);
			 $this->assign('info', $info);
			 
            $this->meta_title = '编辑订单';
           $this->display();
        }
    }

   /**
     * 拒绝订单
     * @author 烟消云散 <1010422715@qq.com>
     */
public function refuse($id = 0){
       if(IS_POST){
          
        
            $Exchange =D('Exchange');
                if(false !==$Exchange->update()){
                    //记录行为
                    action_log('update_order', 'order', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败'.$id);
                }
           
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Exchange')->find($id);
$detail= M('Exchange')->where("id='$id'")->select();
$list=M('shoplist')->where("orderid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
$this->assign('list', $list);
            $this->assign('detail', $detail);
			 $this->assign('info', $info);
			 
            $this->meta_title = '拒绝退货订单';
           $this->display();
        }
    }
   /**
     * 编辑订单
     * @author 烟消云散 <1010422715@qq.com>
     */
    public function send($id = 0){
        if(IS_POST){
         
       
             $Exchange =D('Exchange');
                if(false !==$Exchange->update()){
                    //记录行为
                    action_log('update_Exchange', 'Exchange', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败,退货单'.$id);
                }
            
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Exchange')->find($id);

            $shopid=$info["shopid"];
          $orderid=M('shoplist')->where("id='$shopid'")->getField('orderid');  
           $addressid=M('order')->where("id='$orderid'")->getField('addressid');

       $array = array();
            /* 获取数据 */
            $array = M('address')->find($addressid);
            if(false === $info){
                $this->error('获取订单信息错误');
            }
            $this->assign('arr', $array);

			 $this->assign('info', $info);
            $this->meta_title = '编辑订单';
            $this->display();
        }
    }

 /**
     * 同意订单
     * @author 烟消云散 <1010422715@qq.com>
     */
    public function complete($id = 0){
       if(IS_POST){
            $Exchange =D('Exchange');
                if(false !==$Exchange->update()){
                    //记录行为
                    action_log('update_order', 'order', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败'.$id);
                }
           
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Exchange')->find($id);
$detail= M('Exchange')->where("id='$id'")->select();
$list=M('shoplist')->where("orderid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
$this->assign('list', $list);
            $this->assign('detail', $detail);
			 $this->assign('info', $info);
			 
            $this->meta_title = '编辑订单';
           $this->display();
        }
    }

 /**
     * 删除订单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function del(){
       if(IS_POST){
             $ids = I('post.id');
            $order = M("Exchange");
			
            if(is_array($ids)){
                             foreach($ids as $id){
		
                             $order->where("id='$id'")->delete();
						
                }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("Exchange");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }



}