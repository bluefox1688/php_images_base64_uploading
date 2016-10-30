---
title: php图片处理之图片转为base64格式上传
date: 2016-10-26
tags: 
-	php
-	thinkphp
-	base64

---
我们在开发系统时，处理图片上传是不可避免的，使用thinkphp的肯定很熟悉`import("@.ORG.UploadFile");`的上传方式。

今天我们来讲一个使用html5 base64上传图片的方法。

其实就是用到html5 FileReader的接口，既然是html5的，所支持的浏览器我就不多说啦，老生常谈的问题了，远离IE，珍惜生命。
先扔个demo出来给大伙体验体验哈。

http://t.lanchenglv.com/lan/index.php/Base64/imagesupload

PS：主要给大伙体验的，别当网盘储存图片哈，定期自动删除图片的。
可以大概的讲一下思路，其实也挺简单。选择了图片之后，js会先把已选的图片转化为base64格式，然后通过ajax上传到服务器端，服务器端再转化为图片，进行储存的一个过程。
咱们先看看前端的代码。

**html部分**

``` stylus

<input type="file" id="imagesfile">

```
**js部分**

``` stylus

$("#imagesfile").change(function (){
					
	  var file = this.files[0];
      
	 //用size属性判断文件大小不能超过5M ，前端直接判断的好处，免去服务器的压力。
      if( file.size > 5*1024*1024 ){ 
   			alert( "你上传的文件太大了！" ) 
	  }
     
     //好东西来了
	  var reader=new FileReader();
	    reader.onload = function(){
	    	
	        // 通过 reader.result 来访问生成的 base64 DataURL
	        var base64 = reader.result;
			
            //打印到控制台，按F12查看
            console.log(base64);
            
            //上传图片
            base64_uploading(base64);
            
		}
	     reader.readAsDataURL(file);
				
});

//AJAX上传base64
function base64_uploading(base64Data){
	$.ajax({
	      type: 'POST',
	      url: "上传接口路径",
	      data: { 
	        'base64': base64Data
	      },
	      dataType: 'json',
	      timeout: 50000,
	      success: function(data){
				
				console.log(data);
				
	      },
	      complete:function() {},
	      error: function(xhr, type){
	     		alert('上传超时啦，再试试');
	     		
	      }
	 });

}


```
其实前端的代码也并不复杂，主要是使用了`new FileReader();`的接口来转化图片，`new FileReader();`还有其他的接口，想了解更多的接口使用的童鞋，自行谷歌搜索`new FileReader();`。
接下来，那就是服务器端的代码了，上面的demo，是用thinkphp为框架编写的，但其他框架也基本通用的。

``` stylus
	function base64imgsave($img){
		
        //文件夹日期
		$ymd = date("Ymd");
		
		 //图片路径地址	
		$basedir = 'upload/base64/'.$ymd.'';
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
		
			$ary['status'] = 0;
			$ary['info'] = '请选择要上传的图片';
			
			return $ary;
		
	
	}
```
以上就是PHP代码，原理也很简单，拿到接口上传的base64，然后再转为图片再储存。

建了一个github库，需要源码体验的童鞋可以clone来体验体验。
https://github.com/bluefox1688/php_images_base64_uploading

使用的是thinkphp 3.2，无需数据库，PHP环境直接运行即可。
php目录路径为:

``` stylus
‪Application\Home\Controller\Base64Controller.class.php
```
html目录路径为：

``` stylus
Application\Home\View\Base64\imagesupload.html
```







