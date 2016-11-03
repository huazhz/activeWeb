$(function(){
	

          $('#edit').editable({inlineMode: false, alwaysBlank: true,language: "zh_cn"});
//        $.get("../../../View/default/Public/s0_head.html",null,function(data){
//        	var arr=$("head").html();
//        	arr+=data;
//        	$("head").html(arr);
//        	
//        		
//        });
//        	$.get("../../../View/default/Public/s1_header.html",null,function(data){
//        		
//        		$("header").html(data)
//        		
//        	}) 
 				 var img=$("#bjxxwe");
 				 $("input[type='file']").change(function(){
 				 	var reader=new FileReader();
 				 	reader.onload=function(e){
 				 	$(img).prop("src",e.target.result);
 				 		
 				 	}
 				 	reader.readAsDataURL(this.files[0]);
 				 })
 				 var s= document.getElementsByClassName("froala-element");
	for(var i=0;i<s.length;i++)
	{
		 s[i].setAttribute("name","content");
	}
	
	$(".fb_1").click(function(){
		var s=$(".f-basic").eq(1).text();
		$("#content1").val(s)
		
		$.ajax({
			'type':'post',
			'url':"{:U('documentuser/issue')}",
			'data':{content:s},
			'datatype':"json",
			'success':function(data){
				var c=eval("("+data+")");
				 alert("C"); 
				    window.location.href="{:U('documentuser/index')}";
			}

	});
	
          
      });
	});
