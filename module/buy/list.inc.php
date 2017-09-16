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
if($kw){
	$condition.=" and title like '%{$kw}%'";
	}
	
$px=$MOD['order'];
if($order==1){
$px="addtime asc";	
}
if($order==2){
$px="addtime desc";	
}
if($order==3){
$px="totime asc";	
}
if($order==4){
$px="totime desc";	
}
if($catid){
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
			$maincat3 = get_maincat_all($jibie, $moduleid); 
		 }else{//选择到二级
            $maincat2 = get_maincat_all($jibie, $moduleid);
            $maincat3 = get_maincat_all($catid, $moduleid);
         }
    }else{
		 $maincat2 = get_maincat_all($catid,$moduleid);
		 $yiji=1;
    }
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

if($items) {

	$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE {$condition} ORDER BY ".$MOD['order']." LIMIT {$offset},{$pagesize}", ($CFG['db_expires'] && $page == 1) ? 'CACHE' : '', $CFG['db_expires']);

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

$showpage = 1;

$datetype = 5;

$seo_file = 'list';

include DT_ROOT.'/include/seo.inc.php';

if($EXT['mobile_enable']) $head_mobile = $EXT['mobile_url'].mobileurl($moduleid, $catid, 0, $page);

$template = $CAT['template'] ? $CAT['template'] : ($MOD['template_list'] ? $MOD['template_list'] : 'list');

include template($template, $module);

?>