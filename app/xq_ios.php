<html>
	<head>
		<meta charset="utf-8">
		<style>
		*div{
			width:99%;
			margin: auto!important;
			font-size: 25px!important;
		}
		*p,*span{
			font-size: 25px!important;
		}
		img{
			width: 100%;
			height: initial;
		}
		/*@media screen and (min-width: 1201px)*/
		</style>
	</head>
	<body>
		<?php
		include ("database.php");
		$doc_id = htmlspecialchars(trim($_GET['hid']));
		//获取活动ID
		$qres = mysql_query("
		SELECT a.id as hid,a.title,a.place,a.price,a.baoming,c.area,
		DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
		DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,b.path,c.id,c.content
		FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id)
		LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
		WHERE a.id={$doc_id};
		");
		$i = 0;
		while ($row = mysql_fetch_assoc($qres)) {
			$reslist[$i] = $row;
			$i++;
		}
		?>
		<!--<div class="app_div">-->
		<?php	
			print_r($reslist['0']['content']);
		?>
		<!--</div>-->
	
	</body>
</html>
