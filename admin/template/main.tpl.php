<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div id="tips_update" style="display:none;">
<div class="tt">更新提示</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td><div style="padding:20px 30px 20px 20px;" title="当前版本V<?php echo DT_VERSION; ?> 更新时间<?php echo DT_RELEASE;?>"><img src="admin/image/tips_update.gif" width="32" height="32" align="absmiddle"/>&nbsp;&nbsp; <span class="f_red">您的当前软件版本有新的更新，请注意升级</span>&nbsp;&nbsp;&nbsp;&nbsp;最新版本：V<span id="last_v"><?php echo DT_VERSION; ?></span>&nbsp;&nbsp;更新时间：<span id="last_r"><?php echo DT_RELEASE; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="?file=cloud&action=update" class="t">[立即检查]</a></div></td>
</tr>
</table>
</div>
<div class="tt"><span class="f_r">IP:<?php echo $user['loginip']; ?> <?php echo ip2area($user['loginip']);?>&nbsp;&nbsp;</span>欢迎管理员，<?php echo $_username;?></div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">管理级别</td>
<td width="30%">&nbsp;<?php echo $_admin == 1 ? ($CFG['founderid'] == $_userid ? '网站创始人' : '超级管理员') : ($_aid ? '<span class="f_blue">'.$AREA[$_aid]['areaname'].'站</span>管理员' : '普通管理员'); ?></td>
<td class="tl">登录次数</td>
<td width="30%">&nbsp;<?php echo $user['logintimes']; ?> 次</td>
</tr>
<tr>
<td class="tl">站内信件</td>
<td>&nbsp;<a href="<?php echo $MODULE[2]['linkurl'].'message.php';?>" target="_blank">收件箱[<?php echo $_message ? '<strong class="f_red">'.$_message.'</strong>' : $_message;?>]</a></td>
<td class="tl">登录时间</td>
<td>&nbsp;<?php echo timetodate($user['logintime'], 5); ?> </td>
</tr>
<tr>
<td class="tl">账户余额</td>
<td>&nbsp;<?php echo $_money; ?></td>
<td class="tl">会员<?php echo $DT['credit_name'];?></td>
<td>&nbsp;<?php echo $_credit; ?> </td>
</tr>
<?php if($_admin == 1) { ?>
<form action="?">
<tr>
<td class="tl">后台搜索</td>
<td colspan="2">
<input type="hidden" name="file" value="search"/>
<input type="text" style="width:98%;color:#444444;" name="kw" value="请输入关键词" onfocus="if(this.value=='请输入关键词')this.value='';"/></td>
<td>&nbsp;<input type="submit" name="submit" value="搜 索" class="btn"/>
</td>
</tr>
</form>
<?php } ?>
<form method="post" action="?">
<tr>
<td class="tl">工作便笺</td>
<td colspan="2">
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<textarea name="note" style="width:98%;height:50px;overflow:visible;color:#444444;"><?php echo $note;?></textarea></td>
<td>&nbsp;<input type="submit" name="submit" value="保 存" class="btn"/>
</td>
</tr>
</form>
</table>
<?php if($_founder) {?>
<div id="destoon"></div>
<div class="tt">系统信息</div>
<table cellpadding="2" cellspacing="1" class="tb">

<tr>
<td class="tl">服务器时间</td>
<td>&nbsp;<?php echo timetodate($DT_TIME, 'Y-m-d H:i:s l');?></td>
</tr>
<tr>
<td class="tl">服务器信息</td>
<td>&nbsp;<?php echo PHP_OS.'&nbsp;'.$_SERVER["SERVER_SOFTWARE"];?> [<?php echo gethostbyname($_SERVER['SERVER_NAME']);?>:<?php echo $_SERVER["SERVER_PORT"];?>] <a href="?file=doctor" class="t">[系统体检]</a></td>
</tr>
<tr>
<td class="tl">数据库版本</td>
<td>&nbsp;MySQL <?php echo $db->version();?></td>
</tr>
<tr>
<td class="tl">站点路径</td>
<td>&nbsp;<?php echo DT_ROOT;?></td>
</tr>
</table>



<script type="text/javascript" src="<?php echo $notice_url;?>"></script>
<script type="text/javascript">
$(function(){
	var destoon_release = <?php echo DT_RELEASE;?>;
	var destoon_version = <?php echo DT_VERSION;?>;
	if(typeof destoon_lastrelease != 'undefined') {
		var lastrelease = parseInt(destoon_lastrelease.replace('-', '').replace('-', ''));
		if(destoon_lastversion == destoon_version && destoon_release < lastrelease) {
			$('#last_v').html(destoon_lastversion);
			$('#last_r').html(destoon_lastrelease);
			$('#tips_update').slideDown(600);
		}
	}
});
</script>
<?php } ?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>