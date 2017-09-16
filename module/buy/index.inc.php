<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if(!check_group($_groupid, $MOD['group_index'])) include load('403.inc');
$typeid = isset($typeid) ? intval($typeid) : 99;
isset($TYPE[$typeid]) or $typeid = 99;
$dtype = $typeid != 99 ? " AND typeid=$typeid" : '';
$maincat = get_maincat($catid ? $CAT['parentid'] : 0, $moduleid);
$seo_file = 'index';
$maincat = get_maincat($child ? $catid : $parentid, $moduleid);
$condition = 'status=3';
if($catid){
$condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
}
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

include DT_ROOT.'/include/seo.inc.php';

if($catid) $seo_title = $seo_catname.$seo_title;

if($typeid != 99) $seo_title = $TYPE[$typeid].$seo_delimiter.$seo_title;

if($page == 1) $head_canonical = $MOD['linkurl'];

$destoon_task = "moduleid=$moduleid&html=index";

if($EXT['mobile_enable']) $head_mobile = $EXT['mobile_url'].mobileurl($moduleid, 0, 0, $page);

include template($MOD['template_index'] ? $MOD['template_index'] : 'index', $module);

?>