<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$CAT || $CAT['moduleid'] != $moduleid) include load('404.inc');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($MOD['list_html']) {
	$html_file = listurl($CAT, $page);
	if(is_file(DT_ROOT.'/'.$MOD['moduledir'].'/'.$html_file)) d301($MOD['linkurl'].$html_file);
}
if(!check_group($_groupid, $MOD['group_list']) || !check_group($_groupid, $CAT['group_list'])) include load('403.inc');
$CP = $MOD['cat_property'] && $CAT['property'];
if($MOD['cat_property'] && $CAT['property']) {
	require DT_ROOT.'/include/property.func.php';
	$PPT = property_condition($catid);
}
unset($CAT['moduleid']);
extract($CAT);
$maincat = get_maincat($child ? $catid : $parentid, $moduleid);
$condition = 'status=3';
$condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
//地区
if($areaid){
	$ARE = $AREA[$areaid];
	$condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	}
//高度
if($gaodu){
	$condition.=" AND gaodu=$gaodu ";
}
//米径
if($mijing){
	$condition.=" AND mijing=$mijing ";
}
//地径
if($dijing){
	$condition.=" AND dijing=$dijing ";
}
//冠幅
if($guanfu){
	$condition.=" AND guanfu=$guanfu ";
}
//树形
if($shuxing){
	$condition.=" AND shuxing=$shuxing ";
}
//价格
if($jiage==1){
	$condition.=" AND price>=0 and price<10 ";
}
if($jiage==2){
	$condition.=" AND price>=10 and price<100 ";
}
if($jiage==3){
	$condition.=" AND price>=100 and price<500 ";
}
if($jiage==4){
	$condition.=" AND price>=500 and price<1000 ";
}
if($jiage==5){
	$condition.=" AND price>=1000 and price<10000 ";
}
if($jiage==6){
	$condition.=" AND price>=10000 and price<50000 ";
}
if($jiage==7){
	$condition.=" AND price>=50000 ";
}
if($cityid) {
	$areaid = $cityid;
	$ARE = $AREA[$cityid];
	$condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	$items = $db->count($table, $condition, $CFG['db_expires']);
} else {
	if($page == 1) {
		$items = $db->count($table, $condition, $CFG['db_expires']);
		if($items != $CAT['item']) {
			$CAT['item'] = $items;
			$db->query("UPDATE {$DT_PRE}category SET item=$items WHERE catid=$catid");
		}
	} else {
		$items = $CAT['item'];
	}
}
$pagesize = $MOD['pagesize'];
$offset = ($page-1)*$pagesize;
$pages = listpages($CAT, $items, $page, $pagesize);
$tags = array();
$px=$MOD['order'];
if($order==1){
$px="orders asc";	
}
if($order==2){
$px="orders desc";	
}
if($order==3){
$px="price asc";	
}
if($order==4){
$px="price desc";	
}
if($order==5){
$px="hits asc";	
}
if($order==6){
$px="hits desc";	
}
if($order==7){
$px="addtime asc";	
}
if($order==8){
$px="addtime desc";	
}

if($items) {
	$result = $db->query("SELECT * FROM {$table} WHERE {$condition} ORDER BY ".$px." LIMIT {$offset},{$pagesize}", ($CFG['db_expires'] && $page == 1) ? 'CACHE' : '', $CFG['db_expires']);
	while($r = $db->fetch_array($result)) {
		$r['adddate'] = timetodate($r['addtime'], 5);
		$r['editdate'] = timetodate($r['edittime'], 5);
		if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
		$r['alt'] = $r['title'];
		$r['title'] = set_style($r['title'], $r['style']);
		$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
		$tags[] = $r;
	}
	$db->free_result($result);
}
$diqu="";
$czgd="";
if($tags){
	foreach($tags as $a=>$b){
			if($b['areaid']){
			$diqu.=$b['areaid'].",";
			}
			if($b['gaodu']){
			$czgd.=$b['gaodu'].",";
			}
			if($b['mijing']){
			$czmijing.=$b['mijing'].",";
			}
			if($b['dijing']){
			$czdijing.=$b['dijing'].",";
			}
			if($b['guanfu']){
			$czguanfu.=$b['guanfu'].",";
			}
			if($b['shuxing']){
			$czshuxing.=$b['shuxing'].",";
			}
			if($b['price']){
			$czprice.=$b['price'].",";
			}
		
	 }
	
	$diqua=rtrim($diqu,",");
	if($diqua){$aaa=array_unique(explode(",",$diqua));}
	//高度
	$czgda=rtrim($czgd,",");
	if($czgda){$bbb=array_unique(explode(",",$czgda));}
	//米径
	$czmijinga=rtrim($czmijing,",");
	if($czmijinga){$ccc=array_unique(explode(",",$czmijinga));}
	//地径
	$czdijinga=rtrim($czdijing,",");
	if($czdijinga){$ddd=array_unique(explode(",",$czdijinga));}
	//地径
	$czguanfua=rtrim($czguanfu,",");
	if($czguanfua){$eee=array_unique(explode(",",$czguanfua));}
	//树形
	$czshuxinga=rtrim($czshuxing,",");
	if($czshuxinga){$fff=array_unique(explode(",",$czshuxinga));}
	//价格
	$czpricea=rtrim($czprice,",");
	if($czpricea){$jjj=array_unique(explode(",",$czpricea));}
}


 $result1 = $db->query("SELECT parentid FROM {$db->pre}category WHERE catid=".$catid);
 $r1 = $db->fetch_array($result1);
 $jibie=$r1['parentid'];	
 if($jibie){
	
	//如果选择2或3级
	 $result2= $db->query("SELECT parentid FROM {$db->pre}category WHERE catid=".$jibie);
     $r2 = $db->fetch_array($result2);
		 if($r2['parentid']){//选择到了3级
		    $erji=$r2['parentid'];
		    $maincat2 = get_maincat_all($erji, $moduleid);
			$maincat3 = get_maincat($child ? $catid : $parentid, $moduleid); 
		 }else{//选择到二级
		    $maincat2 = get_maincat_all($jibie, $moduleid);
			$maincat3 = get_maincat_all($catid, $moduleid);
		}
	}else{
	$maincat2 = get_maincat_all($catid,$moduleid);
	$yiji=1;
	
	}


$showpage = 1;
$datetype = 5;
$seo_file = 'list';
include DT_ROOT.'/include/seo.inc.php';
if($EXT['mobile_enable']) $head_mobile = $EXT['mobile_url'].mobileurl($moduleid, $catid, 0, $page);
$template = $CAT['template'] ? $CAT['template'] : 'list';
include template($template, $module);
?>