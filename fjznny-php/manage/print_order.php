<?php
require_once("inc.common.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单打印 - <?php echo $config["config_name"];?></title>
<link rel="stylesheet" href="style/style.css" type="text/css">
<link rel="stylesheet" href="style/impromptu.css" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/jquery.impromptu.js"></script>
<script type="text/javascript" src="js/lodopfuncs.js"></script>
<style type="text/css">
html,body { margin:0px;padding:0; height:100%;}  
.logo{text-align:center;}
.title{ font-family:'黑体'; font-size:24px; height:50px; line-height:50px; text-align:center; font-weight:bold; width:100%; margin:auto;}
.table1 td{ font-size:14px; height:30px;}
.table2{border-bottom:1px solid #000;border-right:1px solid #000;border-collapse:collapse;}
.table2 td{ border-left:1px solid #000; border-top:1px solid #000; padding:3px 4px;}
.table2 .listrow td{font-size:14px;}
.table3 td{ font-size:14px;}
</style>
</head>
<body style="padding:20px 30px;">
<?php
//读取数据库原有资料
if(!checkint($tg_periods_order_id)){jsalert('参数传递出错，请重试；','back','window');}
$order=$db->fetch_first("select * from {$dbtablepre}tg_periods_order where tg_periods_order_id='".$tg_periods_order_id."'");
if(!$order){
	jsalert('没有找到相关信息，请重试；','back','window');
}
$school=$db->fetch_first("select * from {$dbtablepre}tg_periods_school where tg_periods_school_id=". (int)$order['tg_periods_school_id']."");
$college=$db->fetch_first("select * from {$dbtablepre}tg_periods_college where tg_periods_college_id=". (int)$order['tg_periods_college_id']."");
$grade=$db->fetch_first("select * from {$dbtablepre}tg_periods_grade where tg_periods_grade_id=". (int)$order['tg_periods_grade_id']."");
$profession=$db->fetch_first("select * from {$dbtablepre}tg_periods_profession where tg_periods_profession_id=". (int)$order['tg_periods_profession_id']."");
$customer=$db->fetch_first("select * from {$dbtablepre}tg_customer where tg_customer_id=". (int)$order['tg_customer_id']."");
?>
<div class="logo"><img src="images/print_logo.png" /></div>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
  	<td width="100">&nbsp;</td>
    <td><div class="title"><?php echo $periods['name'];?>领书缴款单 (<?php echo $school['name'];?> <?php echo $college['name'];?>)</div></td>
    <td width="100" align="right">第<?php echo $number;?>页/共<?php echo $totalpage;?>页</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="30">单号：<?php echo $order['order_number'];?></td>
    <td>学号：<?php echo $customer['studentno'];?></td>
    <td>班级：<?php echo $grade['name'];?></td>
    <td>专业：<?php echo $profession['name'];?></td>
    <td>姓名：<?php echo $customer['truename'];?></td>
    <td>电话：<?php echo $customer['telephone'];?></td>
  </tr>
</table>
<br class="brspace" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="table2">
  <tr>
    <td align="center"><strong>课程名称</strong></td>
    <td align="center"><strong>教材名称</strong></td>
    <td align="center"><strong>主编</strong></td>
    <td align="center" width="32"><strong>版次</strong></td>
    <td align="center"><strong>出版社</strong></td>
    <td align="center"><strong>书号</strong></td>
    <td align="center" width="45"><strong>定价</strong></td>
    <td align="center" width="32"><strong>数量</strong></td>
    </tr>
<?php
$sql2 = '';

if(checkisdate($s_min_date_price)){$sql2 .= " and date_price >= '". $s_min_date_price ."' ";}
if(checkisdate($s_max_date_price)){$sql2 .= " and date_price <= '". $s_max_date_price ."' ";}

$query = "select * from {$dbtablepre}tg_periods_order_product where tg_customer_id=". (int)$order['tg_customer_id'] ." AND tg_periods_order_id=". (int)$order['tg_periods_order_id'] ." $sql2 order by tg_periods_order_product_id asc";
//echo $query;
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,100,$page,'?action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"12\" class=\"table_trbg02\">没有任何商品！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
$price_total = 0;
$price_discount = 0;
$price_discount2 = 0;
while($rs = $db->fetch_array($query)) {
	if(floatval($rs["price"])>0){
		$show_price_estimate='---';
		$show_price = floatval($rs["price"]);
		$price = number_format(floatval($rs["price"]) * ($rs["discount"]/100),2,'.','');
		$price_total += floatval($rs["price"]) * $rs["quantity"];
		$price_discount += $price * $rs["quantity"];
	}else{
		$price_estimate = number_format(floatval($rs["price_estimate"]) * ($rs["discount"]/100),2,'.','');
		$show_price = '---';
		$show_price_estimate = floatval($rs["price_estimate"]);
		$price_total += floatval($rs["price_estimate"]) * $rs["quantity"];
		$price_discount += $price_estimate * $rs["quantity"];
	}
?>
  <tr class="listrow">
    <td align="left" style="font-size:13px;"><?php echo $rs["kecheng"];?></td>
    <td align="left"><?php echo $rs["product_name"];?></td>
    <td align="center"><?php echo $rs["zhubian"];?></td>
    <td align="center"><?php echo $rs["banbie"];?></td>
    <td align="center"><?php echo $rs["chubanshe"];?></td>
    <td align="center"><?php echo $rs["model"];?></td>
    <td align="center"><?php echo $show_price;?></td>
    <td align="center"><?php echo $rs['quantity'];?></td>
    </tr>
<?php
}
$price_difference = number_format($price_discount-floatval($order["price_payment"]),2,'.','');
?>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table3" align="center">
  <tr>
    <td height="40">财务签字(盖章)：</td>
    <td>书库签字(盖章)：</td>
    <td>学生签字(盖章)：</td>
    <td style="width:280px;" align="right">总金额<?php echo $price_total;?>元 &nbsp; 优惠金额<?php echo $price_discount;?>元</td>
  </tr>
  <tr>
    <td height="16">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td style="width:280px;" align="right">已支付<?php echo (float)number_format($order['price_payment'],2,'.','');?>元 &nbsp; 差额：<?php if($price_difference==0){echo '0元';}elseif($price_difference<0){echo '退款'.abs($price_difference).'元';}elseif($price_difference>0){echo '补缴'.abs($price_difference).'元';}else{echo ''.abs($price_difference).'元';}?></td>
  </tr>
</table>
<a href="javascript:void(0);" onclick="window.print();" style="display:none;">打印</a>
</body>
</html>