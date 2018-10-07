<?php

if(!$premium_test){
	$premium_test = mysql_query("SELECT * FROM adverts Where adv_expiredate >= '" . date("Y-m-d") . "' AND adv_userid=$sql_array->adv_userid limit 0,1") ;		
}

if (mysql_num_rows($premium_test) < 1){

	//echo "...";

}else{

	//echo "..";

	$option_manager =& OptionManager::GetInstance();

	$line_auth=true;
	if ($sql_array->mem_lineset == "HOTLIST" && isset($Sess_UserId)) {
		$line_test=mysql_query("SELECT * FROM hotlist WHERE hot_userid=$sql_array->adv_userid AND hot_advid=$Sess_UserId");
		if (mysql_num_rows($line_test) < 1) $line_auth=false;
	}

	if (trim($sql_array->mem_line) !="" ){

		if(isset($Sess_UserId)){
			
			if ($line_auth && $Sess_Userlevel == 'gold') { ?>
				
				
				<a href="..." title="View <?=$sql_array->mem_forename?>'s Line Profile">
					<img src="./skins/green/images/line_premium.gif" alt="View <?=$sql_array->mem_forename?>'s Line Profile" width="23" height="22" border="0" align=absmiddle />
				</a>

			<?php } elseif ($line_auth == false) {?>
				
				<img src="./skins/green/images/line_blocked.gif" border=0 title="Favorites only can see Line profile" align=absmiddle />

			<?php } elseif ($Sess_Userlevel != 'gold') {?>
				
				<a href="<?=$CONST_LINK_ROOT?>/get_premium.php" title="View <?=$sql_array->mem_forename?>'s Line Profile">
					<img src="./skins/green/images/line_premium.gif" width="23" height="22"  border=0 align=absmiddle title="See <?=$sql_array->mem_forename?>'s Line profile with Premium membership on ThaiLoveLines.com">
				</a>

			<?php }?> 
		
		<?php}else {?>
			
			<a href="<?=$CONST_LINK_ROOT?>/joinreg.php?userid=<?=$sql_array->adv_userid?>" title="View <?=$sql_array->mem_forename?>'s Line Profile">
				<img src="./skins/green/images/line_premium.gif" width="23" height="22"  border=0 / align="absmiddle">
			</a>

		<? }?>
		
	<? }?>
<? }?>