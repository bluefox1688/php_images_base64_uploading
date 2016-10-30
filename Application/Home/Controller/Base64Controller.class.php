<?php
namespace Home\Controller;
use Think\Controller;
class Base64Controller extends Controller {
	
	
	public function imagesupload(){
		
		
		
		
		
		$this->display();
		
	}
	
	function base64imgupload(){
		
		$base64 = I('post.base64','');
		
		$ary = $this -> base64imgsave($base64);
		
		$this -> ajaxReturn($ary);
		
	}
	
	//base64上传的图片储存到服务器本地
	protected function base64imgsave($img){
	
		$ymd = date("Ymd"); //图片路径地址
		
			
		$basedir = 'upload/demo/base64/'.$ymd.'';
		$fullpath = $basedir;
		if(!is_dir($fullpath)){
			mkdir($fullpath,0777,true);
		}
		$types = empty($types)? array('jpg', 'gif', 'png', 'jpeg'):$types;
		
		$img = str_replace(array('_','-'), array('/','+'), $img);
		
		$b64img = substr($img, 0,100);
		
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $b64img, $matches)){
			
		$type = $matches[2];
		if(!in_array($type, $types)){
			return array('status'=>1,'info'=>'图片格式不正确，只支持 jpg、gif、png、jpeg哦！','url'=>'');
		}
		$img = str_replace($matches[1], '', $img);
		$img = base64_decode($img);
		$photo = '/'.md5(date('YmdHis').rand(1000, 9999)).'.'.$type;
		file_put_contents($fullpath.$photo, $img);
			
			$ary['status'] = 1;
			$ary['info'] = '保存图片成功';
			$ary['url'] = $basedir.$photo;
			
			return $ary;
		
		}
		
			$ary['status'] = 1;
			$ary['info'] = '请选择要上传的图片';
			
			return $ary;
		
	
	}
	
	
	
}