    <tr >
    <?php 
	
		$this_user_id = $sql_array->adv_userid;
	
		if ($SCRIPT_NAME == 'onlinenow.php' || $list_mode == "onlinenow" || $SCRIPT_NAME == 'prgminisearch.php' || $list_mode == "prgminisearch") {
			$ret_user_extra = "ol";
		}	
		
		
		///whether to show online now icon if target is not actualll online....
		$is_minisearch_online = 0;
		$use_custom_offline = 0;
		
		
		if (
			$SCRIPT_NAME == 'prgminisearch.php' || $list_mode == "prgminisearch"
			||
			$SCRIPT_NAME == 'mymatch_list.php' || $list_mode == "mymatch_list"
			||
			$SCRIPT_NAME == 'prghotlist.php' || $list_mode == "prghotlist"
			||
			$SCRIPT_NAME == 'prghotlist_me.php' || $list_mode == "prghotlist_me"
			||
			$list_mode == "whoson_chat"
			) {
			
			$use_custom_offline = 1;
						
			if(
				$sql_array->mem_lastvisit == date("Y-m-d") 
				|| $sql_array->mem_lastvisit == date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")))
			)
			{
					
					$is_minisearch_online = 1;	
			}
			
			
		}
		
		//
		if (
				(
				$list_mode == "mymatch_list"
				||
				$list_mode == "prghotlist"
				||
				$list_mode == "prghotlist_me"
				||
				$list_mode == "whoson_chat"
				)
				&&
				!$sql_array->isOnline
				
			) {
				
				$use_custom_offline = 1;	
							
				$mystring = $parade_string; //--> this one locates in "list_onlinenow_string.php"
				$findme   = $this_user_id;
				$pos = strpos($mystring, $findme);				
				
				if($pos === false){
					//not found this member in parade, do nothing
				}else{
					//turn this member online
					$is_minisearch_online = 1;	
				}
			}
		
		if($is_minisearch_online == 1){					
			$sql_array->online="<span class='online'>".PRGSEARCH_ONLINE."</span>";		
		}
		elseif($use_custom_offline == 1){
			$sql_array->online="<span class='offline'>".PRGSEARCH_OFFLINE."</span>";		
		}
	?>
    <td class='resulthead' width="135">
        <a href='<?=$CONST_LINK_ROOT?>/prgretuser<?php echo $ret_user_extra; ?>.php?userid=<?=$sql_array->adv_userid?>' target="_blank"><?=$sql_array->mem_forename?> (<?=$sql_array->mem_userid?>)</a>
    </td>
    <td  class='resultheadright'  style="padding:0 0 0 5px;">
      
	  <table border="0" width="100%" cellpadding="0" cellspacing="0" >
			<tr >
				<td>
				<?php /*if ($SCRIPT_NAME == 'my_interests.php' || $SCRIPT_NAME == 'interested_in_me.php'){*/
					if (1==0){
				?>
					<div style="float:right;"><input type='checkbox' name=chkDelete[] value=<?=$sql_array->adv_userid?>><?=BUTTON_REMOVE?></div>
				<?php } ?>
					<span class='searchage'>
						<?=$sql_array->age?> <?=SEARCH_AGELOCALITY?> <?=$sql_array->full_address?>
					</span>
				</td>
				<td align="right" valign="middle"  class='resultheadright' >
					<?php
						$flag_to_show = $flag_array[$sql_array->gcn_name];
						if(strlen($flag_to_show)<=0){	
							$flag_to_show = "WORLD.gif";
						}				
					?>
					<img src="skins/blue/images/icons/flags/<?php echo $flag_to_show;?>" width="16" height="11">
				</td>
			</tr>
	  </table>
	  
	  
    </td>
   </tr>
    <tr>
    <td align='center' valign='top' class='image'>
        <table  border='0' cellpadding='0' cellspacing='0'>
          <tr>
            <td class='imageframe' onmouseover="this.style.backgroundColor='#E5D9F1';" onmouseout="this.style.backgroundColor='#FFFFFF';">
            	<a href='<?=$CONST_LINK_ROOT?>/prgretuser<?php echo $ret_user_extra; ?>.php?userid=<?=$sql_array->adv_userid?>' target="_blank">
                	<img border='0' src='<?=$CONST_LINK_ROOT?><?=$sql_array->adv_picture->Path?>' width="70" height="90" alt="<?=GENERAL_FINDOUT_PRE?><?=$sql_array->mem_forename?><?=GENERAL_FINDOUT_POST?>" title="<?=GENERAL_FINDOUT_PRE?><?=$sql_array->mem_forename?><?=GENERAL_FINDOUT_POST?>">
                </a>
            </td>
          </tr>
        </table>
    </td>
    <td valign='top' class='resultbody'>
        
  		<?php //VVVVVVVVVVVVVVVVVVVVVVV adv description box ?>
        
        <?=$sql_array->statustext?>, <?php 
			
			if ($SCRIPT_NAME == 'onlinenow.php' || $list_mode == "onlinenow") {
				echo "<span class='online'>Online Now</span>";
			}else{
				echo $sql_array->online;
			}
			?>
        
        <br>
        <br>
        
        <div style="overflow:hidden; width:<?=$CONST_COMMENT_WIDTH?>px;">
			<?php echo substr(to_UTF8($sql_array->adv_title) . " - " .   to_UTF8($sql_array->adv_comment), 0, 150);
            ?>&#8230;
            <a href='<?=$CONST_LINK_ROOT?>/prgretuser<?php echo $ret_user_extra; ?>.php?userid=<?=$sql_array->adv_userid?>'>
	            <?=GENERAL_FINDOUT?>
            </a>
        </div>

		<?php //^^^^^^^^^^^^^^^^^^^^^ adv description box ?>
        
        <?php // starts working on icons?>
		
        <?php
			//only shows icon if logged in
        	if (isset($Sess_UserId)) { 		
		?>            
            <br>
            
            <?php //-----1. skype icon-----------------
				include("skype.inc.php"); 
			?>
			
			
			<?php 
				// yoes 20181006
				//-----1-2. facebook icon-----------------
				include("facebook.inc.php"); 
			?>
			
			<?php 
				// yoes 20181006
				//-----1-3. Line icon-----------------
				//include("line.inc.php"); 
			?>
            
            
		<?php 
			//VVVVVVV FAV icon, normal and premium is the same, but check if added already-----------------
			if($list_mode != "prghotlist"){
			
			?>
            
            <?php
				
				$this_user_id = $sql_array->adv_userid;			
				$fav_count = getFirstItem("SELECT count(hot_userid) FROM hotlist WHERE hot_userid='$Sess_UserId' and hot_advid ='$this_user_id' limit 0,1");
			?>
            
            <?php 
				if($fav_count == 0){
			
				//showing "add fav"
			?>
            
            <div id="fav_ready<?php echo $sql_array->adv_userid;?>" style="margin:0; padding:0; display: inline;">
            	<a href='#' 
                	onmouseover="doHoverOrHide(1,'add2hot<?php echo $this_user_id;?>');" 
                    onmouseout="doHoverOrHide(0,'add2hot<?php echo $this_user_id;?>');" 
                    onClick="hideAllPopup(); doAddFavorite(<?php echo $sql_array->adv_userid;?>); doFavSuccess(<?php echo $sql_array->adv_userid;?>); return false;"  
                    title="<?=PRGRETUSER_TEXT7?>" > <img id="add2hot<?php echo $this_user_id;?>icon" border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/add2hotlist.gif' align="absmiddle"><img id="add2hot<?php echo $sql_array->adv_userid;?>roll" style="display: none;" border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/add2hotlistroll.gif' align="absmiddle">
</a></div><div id="fav_done<?php echo $sql_array->adv_userid;?>" style="margin:0; padding:0; display: none;"><img  border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/add2hotlist-added.gif' align="absmiddle"></div>&nbsp;             
			 <?php 
				}else{//just show "added image" ?><img  border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/add2hotlist-added.gif' align="absmiddle">&nbsp; 
				<?php }	 ?> 
                
                
 <?php 
 	}
 	//^^^^^^ FAV icon, normal and premium is the same, but check if added already-----------------
	
?>
 
 
 	<?php 
			//VVVVVVVVVVVVVVVVVVVVVVVVVV ------------ REMOVE FAV icon, for fav list only
			if($list_mode == "prghotlist"){
	?>
	<a href='minus4hotlist.php?user_id=<?php echo $Sess_UserId; ?>&target_id=<?=$sql_array->adv_userid?>' title="Remove from Favorites"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/minushotlist.gif' align="absmiddle"></a>
 	
    <?php 
			}
			//^^^^^^^^^^^^------------------------------------------REMOVE FAV icon, ?>
	
	
 	
 
    <?php //VVVVVVV -------------------------mail icon ?><?php
  //check blocked etc
 $isblocked=$db->get_var("SELECT count(*) FROM blockmail WHERE blk_receiverid = $sql_array->adv_userid AND blk_senderid = $Sess_UserId",$link);
 
 	if ($isblocked > 0){
 	
	$sendmail_link = "alert('".str_replace("'", "\\'", PRGRETUSER_TEXT3)."'); return false;"; 
	
	//go to send mail page on "<a>"
	$the_hyper_link = "#";
	
	}else{
	
		//also separate levels 		
		//only populate popups for premium users		
		if ($Sess_Userlevel!="silver"){
			//gold member see popup normally
			$sendmail_link = "hideAllPopup(); setMessageBox('".addslashes($sql_array->adv_userid) ."','". addslashes($sql_array->mem_forename)."', '". addslashes(getFullPhotoPath($sql_array->adv_userid)) ."'); fireMyPopup('mypopup',520,280); return false;";	
			
		}else{
					
			//nothing happen on "onclick"
			$sendmail_link = "";
		}

		//go to send mail page on "<a>"
		$the_hyper_link = "$CONST_LINK_ROOT/sendmail.php?userid=$sql_array->adv_userid&handle=$sql_array->adv_username";

	}       ?>
  <a href="<?php echo $the_hyper_link;?>" 
            	onmouseover="doHoverOrHide(1,'mailme<?php echo $this_user_id;?>');" 
                onmouseout="doHoverOrHide(0,'mailme<?php echo $this_user_id;?>');" 
            	onClick="<?=$sendmail_link?>" 
                title="<?=PRGRETUSER_TEXT5?>">
            <img id="mailme<?php echo $this_user_id;?>icon" src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/mailme.gif' border='0' align="absmiddle"><img id="mailme<?php echo $this_user_id;?>roll" style="display: none;" src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/mailmeroll.gif' border='0' align="absmiddle">&nbsp;
            </a>       
		<?php //^^^^^^ -------------------------mail icon ?>
        
        
        
        <?php //VVVVV --------------------flirt icon ?>     
<?php
                    $advQuery=mysql_query("SELECT * FROM adverts where adv_userid=$Sess_UserId AND adv_approved=1",$link);
                    $advNote=mysql_num_rows($advQuery);
                    if ($CONST_FLIRT=='Y' && $advNote > 0) {

                            //yoes sep 03 2009
                            //make it so the flirt can be use once per day (as oppose to once per target member)
                            $NoteQuery=mysql_query("SELECT * FROM notifications where ntf_senderid=$Sess_UserId and ntf_receiverid=$sql_array->adv_userid and ntf_dateadded='".date("Y-m-d")."'",$link);
                            $hadNote=mysql_num_rows($NoteQuery);
							
                            if ($hadNote > 0) {
                            
								//have sent flirt today
							
							?>
                            
                             <img src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/sent4free.gif' align="absmiddle" border='0' alt="You have already flirted with <?php echo $sql_array->mem_forename;?>. You may only flirt once in 24 hours." title="You have already flirted with <?php echo $sql_array->mem_forename;?>. You may only flirt once in 24 hours.">
                            
                            <?php  
							
							}else {
							
							//have not sent flirt today
							
							//see if have sent sometime before
                             
							 $flirt_image_to_use = $CONST_IMAGE_ROOT . $CONST_IMAGE_LANG . "/flirt5free.gif";
							 $flirt_image_to_use_roll = $CONST_IMAGE_ROOT . $CONST_IMAGE_LANG . "/flirt4freeroll.gif";
							 
							 $has_sent = getFirstItem("select count(*) from notifications 
							 							where ntf_senderid=$Sess_UserId 
														and ntf_receiverid=$sql_array->adv_userid");
							 							 
							 if($has_sent > 0){
							 
							 	//use these images instead
							 	 $flirt_image_to_use = $CONST_IMAGE_ROOT . $CONST_IMAGE_LANG . "/flirt4free-yes.gif";
								 $flirt_image_to_use_roll = $CONST_IMAGE_ROOT . $CONST_IMAGE_LANG . "/flirt4free-yes-roll.gif";
							 }
							 
							?>
                            
                            <a id="flirt<?php echo $this_user_id;?>link" href='#' title="<?=SEND_A_FLIRT_TO?><?php //echo $sql_array->mem_forename;?>"' 
                            
                            onmouseover="doHoverOrHide(1,'flirt<?php echo $this_user_id;?>');" 
			                onmouseout="doHoverOrHide(0,'flirt<?php echo $this_user_id;?>');" 
                            
                            onclick='hideAllPopup(); setFlirtBox("<?php echo $this_user_id;?>", "<?php echo $sql_array->mem_forename;?>", "<?php echo addslashes(getFullPhotoPath($sql_array->adv_userid));?>"); document.getElementById("flirt_send").disabled = false; fireMyPopup("flirt_popup",520,330); return false;'
                            >
                            
                            <img id="flirt<?php echo $this_user_id;?>icon" border='0' src='<?=$flirt_image_to_use?>' align="absmiddle" alt="<?=SEND_A_FLIRT_TO?><?php //echo $sql_array->mem_forename;?>" ><img style="display:none;" id="flirt<?php echo $this_user_id;?>roll" border='0' src='<?=$flirt_image_to_use_roll?>' align="absmiddle" alt="<?=SEND_A_FLIRT_TO?><?php //echo $sql_array->mem_forename;?>" >
                            </a>
                            
                            
                             <img id="flirt_done<?php echo $this_user_id;?>" style="display:none" src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/sent4free.gif' align="absmiddle" border='0' alt="You have already flirted with <?php echo $sql_array->mem_forename;?>. You may only flirt once in 24 hours." title="You have already flirted with <?php echo $sql_array->mem_forename;?>. You may only flirt once in 24 hours.">
                            
                            <?php 
							
                            }
							
							
							
                    } elseif ($CONST_FLIRT=='Y' && $advNote < 1) {
                            
							?>
            
           <a href='#'title='<?php echo $PRGRETUSER_TEXT1;?>'><img src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/block4free.gif' align="absmiddle" border='0'></a>
            
            <?php
							
                    }
            ?>
           
		 <?php //^^^^^^^^ --------------------flirt icon ?>   
           
           
           
           
        <?php 
		
		//VVV --------------------tip a friend  (REPLACED BY SOCIAL NETWORK)
		
		//no longer needed-> replcae this with social invit...
		
		//only do this for 'other members'
		if($Sess_UserId != $this_user_id){
		
		     $status = $network->checkRelations($Sess_UserId,$this_user_id);				 
			 
			 //echo   "status: " .$status.$Sess_UserId.$this_user_id;
			 
			 if ($status == NETWORK_SINGLE_EMPTY) {//network record blank on both left and right
			
			 ?>  
			   
			    
				<a href="#"
				
                onmouseover="doHoverOrHide(1,'network<?php echo $this_user_id;?>');" 
				onmouseout="doHoverOrHide(0,'network<?php echo $this_user_id;?>');" 
				title="Invite <?php echo $sql_array->mem_forename;?> to join your Social Network"
                
                onClick="hideAllPopup(); 
                doAddNetwork('<?php echo $this_user_id;?>'); 
                doNetworkSuccess('<?php echo $this_user_id;?>'); 
                return false;"  
                
                id="network_ready<?php echo $this_user_id;?>"
                
				><img id="network<?php echo $this_user_id;?>icon" src="<?php echo $CONST_IMAGE_ROOT?>/add2social.gif" align="absmiddle" border="0"><img style="display:none;" id="network<?php echo $this_user_id;?>roll" border='0' src='<?=$CONST_IMAGE_ROOT?>/add2social-roll.gif' align="absmiddle" ></a><img id="network_done<?php echo $this_user_id;?>" src="<?php echo $CONST_IMAGE_ROOT?>/social-network-wait.gif" align="absmiddle" border="0" title="You have invited <?php echo $sql_array->mem_forename;?> to your social network" style="display:none;">
			   
			   <?php }elseif ($status == NETWORK_SINGLE_LEFT) { //this session is invited the other party, but other party didn't respond yet?>
			   
			   <img id="network<?php echo $this_user_id;?>icon" src="<?php echo $CONST_IMAGE_ROOT?>/social-network-wait.gif" align="absmiddle" border="0" title="Invitation under review - this member cannot be invited">
			   
			   <?php }elseif ($status == NETWORK_SINGLE_RIGHT) { //this session got invited from other party?>
			   
			   <a href=""
				 onmouseover="doHoverOrHide(1,'network<?php echo $this_user_id;?>');" 
				onmouseout="doHoverOrHide(0,'network<?php echo $this_user_id;?>');" 
                
                onClick="hideAllPopup(); 
                doApproveNetwork('<?php echo $this_user_id;?>'); 
                doApproveNetworkSuccess('<?php echo $this_user_id;?>'); 
                return false;"  
                
                 id="approve_network_ready<?php echo $this_user_id;?>"
                
				title="Join <?php echo $sql_array->mem_forename;?>'s social network"
				><img id="network<?php echo $this_user_id;?>icon" src="<?php echo $CONST_IMAGE_ROOT?>/social-network.gif" align="absmiddle" border="0" ><img style="display:none;" id="network<?php echo $this_user_id;?>roll" border='0' src='<?=$CONST_IMAGE_ROOT?>/social-network-added-roll.gif' align="absmiddle" ></a><a  id="approve_network_done<?php echo $this_user_id;?>" href="http://www.thailovelines.com/network/network_new.php?user_id=<?php echo $this_user_id;?>" style="display:none;"><img  src="<?php echo $CONST_IMAGE_ROOT?>/social-network-added.gif" align="absmiddle" border="0" title="You have joined <?php echo $sql_array->mem_forename;?>'s social network" ></a>
                
               <?php }elseif ($status == NETWORK_SINGLE_DUAL) { //both side are friend?>
			   
                <a href="http://www.thailovelines.com/network/network_new.php?user_id=<?php echo $this_user_id;?>"><img id="network<?php echo $this_user_id;?>icon" src="<?php echo $CONST_IMAGE_ROOT?>/social-network-member.gif" align="absmiddle" border="0" title="<?php echo $sql_array->mem_forename;?> is a member of your Social Network"></a>
               
			   <?php } ?>
           
           
 	<?php
	
 		}//end if($Sess_UserId != $this_user_id){
		
		
  		//^^^^^^^^^^^^^ --------------------tip a friend (REPLACED BY SOCIAL NETWORK)?>  
        
        
        
        
<?php 
//VVVVVVVVVVVVVVVVVVVV---------------------gallery icon...


			
			$count_gal = getFirstItem("select count(mem_id) from gallery, galleryitem
										where galleryitem.Gallery_ID = gallery.Gallery_ID
										and Approved = 'Approved'
										and mem_id = '".$this_user_id."'");
										
			if($count_gal > 0){

?>

				<a href="http://www.thailovelines.com/gallery/gallery_new.php?user_id=<?php echo $this_user_id;?>"
				 onmouseover="doHoverOrHide(1,'gallery<?php echo $this_user_id;?>');" 
				onmouseout="doHoverOrHide(0,'gallery<?php echo $this_user_id;?>');" 
               
                               
                 
				title="View <?php echo $sql_array->mem_forename;?>'s photo galleries"
				><img id="gallery<?php echo $this_user_id;?>icon" src="<?php echo $CONST_IMAGE_ROOT?>/thai-dating-network-gallery.gif" align="absmiddle" border="0" ><img style="display:none;" id="gallery<?php echo $this_user_id;?>roll" border='0' src='<?=$CONST_IMAGE_ROOT?>/thai-dating-network-gallery-roll.gif' align="absmiddle" ></a>

<?php 
			}
//^^^^^^^^^^^^^^^^^^^^---------------------gallery icon...
?>



 
 
<?php //VVVVVVV^ -------------------- RATED ICON? ?>  
<?php 
//this member has rated you?
//yoes aug 26 make sure to reset do add button flag first
				$do_add_button = 0;
				
				$ratedMeQuery=mysql_query("SELECT * FROM ratedby WHERE rtb_raterid='$sql_array->adv_userid' and rtb_userid ='$Sess_UserId' limit 0,1",$link);				
				if (mysql_num_rows($ratedMeQuery) > 0 ) {
					//if this member has rated the current logged in user then show "star" icon
					//also checked if the logged in member has rated back
					$ratedYouQuery=mysql_query("SELECT * FROM ratedby WHERE rtb_raterid='$Sess_UserId' and rtb_userid ='$sql_array->adv_userid' limit 0,1",$link);
					if (mysql_num_rows($ratedYouQuery) < 1 ) {
						//you rate me but i haven;t rate you, add button
						$do_add_button = 1;
					}
				}
				
				if($do_add_button==1){
			?>
				<a href="<?php echo $CONST_LINK_ROOT?>/meetamatch.php?userid=<? echo "$sql_array->adv_userid"; ?>" title='Rate Now'
                
                 onmouseover="doHoverOrHide(1,'rateme<?php echo $this_user_id;?>');" 
	            onmouseout="doHoverOrHide(0,'rateme<?php echo $this_user_id;?>');"
                
                ><img id="rateme<?php echo $this_user_id;?>icon" border='0' src='./skins/blue/images/rate-now.gif' align="absmiddle"><img id="rateme<?php echo $this_user_id;?>roll" style="display:none;" border='0' src='./skins/blue/images/rate-now-roll.gif' align="absmiddle"></a>
			<?php
				}
			?>
 <?php //^^^^^^^^^^^ -------------------- RATED ICON? ?>  
 
 
           
<?php //VVVVV -------------------- MAKE_A_DATE_ICON ?>     
<a 
             onmouseover="doHoverOrHide(1,'makedate<?php echo $this_user_id;?>');" 
	            onmouseout="doHoverOrHide(0,'makedate<?php echo $this_user_id;?>');"
                href="<?php echo $CONST_LINK_ROOT?>/makeadate.php<? echo "?userid=$sql_array->adv_userid"; ?>" title='<?=MAKE_A_DATE_ICON?>'>
                	<img id="makedate<?php echo $this_user_id;?>icon" src='./skins/blue/images/EN/makeadate.gif' width="23" height="30" border='0' align="absmiddle"><img id="makedate<?php echo $this_user_id;?>roll" style="display:none;" src='./skins/blue/images/EN/makeadateroll.gif' width="23" height="30" border='0' align="absmiddle"> </a>
<?php //^^^^^^ -------------------- MAKE_A_DATE_ICON ?>    



<?php
	//---------------------------------- VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV HI Icon
	//only show in some type of list only
	if(
		$list_mode == "prghotlist"
		||
		$list_mode == "prghotlist_me"
		||
		$list_mode == "mymatch_list"
		
	){
?>

	 <?php //show hi or not 
	 
		$last_day_time =  date("Y-m-d H:i:s", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y")) );
		$hi_count = getFirstItem("SELECT count(hi_to) FROM hi_message WHERE hi_from='$Sess_UserId' and hi_to ='$this_user_id' and hi_date > '$last_day_time' limit 0,1");
		
		//echo "SELECT count(hi_to) FROM hi_message WHERE hi_from='$Sess_UserId' and hi_to ='$this_userid' and hi_date > '$last_day_time' limit 0,1";
		
		//echo "hi_count: ". $hi_count;
		
		if($hi_count < 1 ){
	?>
	<span id="hi_ready<?php echo $this_user_id; ?>" ><a href="#" 
    	onMouseOver="doHoverOrHide(1,'hi_icon<?php echo $this_user_id; ?>');"
        onMouseOut="doHoverOrHide(0,'hi_icon<?php echo $this_user_id; ?>');"
        onClick="hideAllPopup(); doAddHi(<?php echo $this_user_id;?>); doHiSuccess(<?php echo $this_user_id;?>); return false;"
     ><img id="hi_icon<?php echo $this_user_id; ?>icon" src="http://www.thailovelines.com/skins/blue/images/hi.gif"  title="Send a Hi message" alt="Send a Hi message"   border="0" align=absmiddle /><img id="hi_icon<?php echo $this_user_id; ?>roll" src="http://www.thailovelines.com/skins/blue/images/hi-roll.gif" title="Send a Hi message" alt="Send a Hi message"   border="0" align=absmiddle style="display: none;" /></a></span><img id="hi_done<?php echo $this_user_id; ?>" style="display:none;" src='http://www.thailovelines.com/skins/blue/images/hi-done.gif'  title="Hi message sent" border='0' align=absmiddle><?php
		}else{
	?>	
		
		<img src='http://www.thailovelines.com/skins/blue/images/hi-done.gif' title="Hi message sent"  alt="Hi message sent"  border='0' align="absmiddle" id="hi_done<?php echo $this_blogid; ?>">
  <?php	
		}
	?> 


<?php
	}
	//---------------------------------- ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ HI Icon
?>



<?php //VVVVV -------------------- IM ?>     	
<?php include_once(CONST_INCLUDE_ROOT.'/messenger/function.php');


				//echo $CONST_IMAGE_ROOT;
				if ($sql_array->isOnline && $Sess_UserId != $sql_array->adv_userid) {
				
					//member is actually online
					//for gold member...
					if ($Sess_Userlevel!="silver" && isset($Sess_UserId)) {
						//gold member see everything normally
						if ($option_manager->GetValue('userplane_im')) {
						
							print('<a href="#" onClick="up_launchWM( \''.$Sess_UserId.'\', \''.$sql_array->adv_userid.'\' ); return false;" ><img border="0" src="'.$CONST_IMAGE_ROOT.$CONST_IMAGE_LANG.'/online_im.gif" align="absmiddle" alt="'.CHAT_NOW_WITH_PRE.$sql_array->mem_forename.CHAT_NOW_WITH_POST.'" title="'.CHAT_NOW_WITH_PRE.$sql_array->mem_forename.CHAT_NOW_WITH_POST.'">nnn</a>');
							
						} elseif ($option_manager->GetValue('userplane_im_free')) {
						
						?>
							<a href="#" onClick="up_launchWM_free('<?=$Sess_UserId?>', '<?=$sql_array->adv_userid?>' ); return false;" 
                            
                             onmouseover="doHoverOrHide(1,'im<?php echo $this_user_id;?>');" 
				            onmouseout="doHoverOrHide(0,'im<?php echo $this_user_id;?>');"
                            
                            >
                            
								<img id='im<?php echo $this_user_id;?>icon' border="0" src="<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/online_im.gif" align="absmiddle" alt="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>" title="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>"><img id='im<?php echo $this_user_id;?>roll' style='display:none;' border="0" src="<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/online_im-rollover-static.gif" align="absmiddle" alt="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>" title="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>">
                               
                                
                            </a>
							
                        <?php
						} elseif (getOnlineImUser ($sql_array->adv_username)=='T') {
							
							?>
                            
							<a href='#' 
                            	onClick="MDM_openWindow(
                                	'<?php echo $CONST_LINK_ROOT?>/messenger/index.php?new_win_launched=TRUE'
                                    ,'IM'
                                    ,'width=146
                                    ,height=298');
                                    
                                    window.open('<?php echo $CONST_LINK_ROOT?>/immenow.php?userid=<?php echo $sql_array->adv_userid;?>&handle=<?php echo $sql_array->adv_username; ?>','','toolbar=no,menubar=no,height=400,width=498,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');
                                        
                                    return false;">
                                    	<img border='0' src='<?php echo $CONST_IMAGE_ROOT;?>/online_im.gif' align='absmiddle' alt="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>" title="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>">
                            </a>
                            
                   			<?php
					
						}
						
						//echo " - ";
						
					}else{
										
						//viewer is premium member and target is not actually online, sending an IM send message....
						include "offline_im_ajax_final.inc.php";
					}
					
				}elseif($is_minisearch_online == 1){
				
					//yoes sep 15 2009
					//else if 'online' due to minisearch's criteria (member login sometime today)
					//display offline mail icons
					include "offline_im_ajax_final.inc.php";
				
				}elseif($SCRIPT_NAME == 'onlinenow.php' || $list_mode == "onlinenow") {
					//else put up another icon for offline email
					//also check user level, on premium will send out offline message
					if ($Sess_Userlevel!="silver") {
						//for premium
						?>
						
                        <a href="#" 
                        
                        onClick="doShowIMSent(<?php echo $this_user_id;?>); fireMyPopup('im_sent_popup',240,340); return false;" 
                            
                             onmouseover="doHoverOrHide(1,'im<?php echo $this_user_id;?>');" 
				            onmouseout="doHoverOrHide(0,'im<?php echo $this_user_id;?>');"
                            
                            ><img id='im<?php echo $this_user_id;?>icon' border="0" src="<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/online_im.gif" align="absmiddle" alt="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>" title="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>"><img id='im<?php echo $this_user_id;?>roll' style='display:none;' border="0" src="<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/online_im-rollover-static.gif" align="absmiddle" alt="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>" title="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>"></a>
						
						
                        <?php
					}else{
						//for standard
						?>
                        <a href="#" 
                        	onclick="hideAllPopup();fireMyPopup('im_popup',300,375);return false;"
                            onmouseover="doHoverOrHide(1,'im<?php echo $this_user_id;?>');" 
				            onmouseout="doHoverOrHide(0,'im<?php echo $this_user_id;?>');"
                            
                            
                            >
                       <img id='im<?php echo $this_user_id;?>icon' border="0" src="<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/online_im.gif" align="absmiddle" alt="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>" title="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>"><img id='im<?php echo $this_user_id;?>roll' style='display:none;' border="0" src="<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/online_im-rollover-static.gif" align="absmiddle" alt="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>" title="<?=CHAT_NOW_WITH_PRE?><?=$sql_array->mem_forename?><?=CHAT_NOW_WITH_POST?>">
                        </a>
                        <?php
						
					}
				}
				?>
        
		<?php //^^^^^ -------------------- IM ?>   
		
	<?php } else {
    
        //these are for members who didn't have session
    
    ?>
            <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT7?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/add2hotlist.gif'></a>&nbsp;
            <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT6?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/addimfriend.gif'></a>&nbsp;
            <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT5?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/mailme.gif'></a>&nbsp;
            <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT4?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/flirt5free.gif'></a>&nbsp;
            <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT2?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/tipfriend.gif' ></a>
	<? } ?>
    
  </td>
  </tr>
  <tr >
    <td colspan='3' align='left' valign='top' class='resultfoot'>&nbsp;</td>
  </tr>
