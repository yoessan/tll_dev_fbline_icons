
<div id='fav_popup' name='fav_popup' style='position: absolute; width: 300px; height: 375px; display: none; background: #ddd; '>
            <?php include "./myemail6_fav_popup.php";?>
        </div>

 <div id='im_popup' name='im_popup' style='position: absolute; width: 300px; height: 375px; display: none; background: #ddd; '>
            <?php include "./myemail6_im_popup.php";?>
        </div>
        <div id='hi_popup' name='hi_popup' style='position: absolute; width: 300px; height: 375px; display: none; background: #ddd; '>
       	 <?php include "./myemail6_hi_popup.php";?>
        </div>
        
        <div id='flirt_sent' name='flirt_sent' style='position: absolute; width: 520px; height: 280px; display: none; background: #ddd; '>
			<?php include "./myemail6_flirt_sent.php";?>
        </div>


<div id='mypopup' name='mypopup' style='position: absolute; width: 520px; height: 280px; display: none; background: #ddd; '>
		<?php include "./myemail6_email-centre.php";?>
  </div>
  
  <div id='mymail_sent' name='mymail_sent' style='position: absolute; width: 520px; height: 280px; display: none; background: #ddd; '>
			<?php include "./myemail6_email-sent.php";?>
        </div>
        
<div id='network_invite' name='network_invite' style='position: absolute; width: 300px; height: 375px; display: none; background: #ddd; '>
			<?php include "./myemail6_network-invited.php";?>
        </div>        

<div id='tip_done' name='tip_done' style='position: absolute; width: 520px; height: 310px;  background: #ddd; display:none; '>
<?php include "./myemail6_tip-done.php";?>
</div>

        
<div id='tip_popup' name='tip_popup' style='position: absolute; width: 520px; height: 360px;  background: #ddd; display: none; '>
<?php include "./myemail6-tip-popup.php";?>
</div>

<div id='im_sent_popup' name='im_sent_popup' style='position: absolute; width: 240px; height: 290px;  background: #ddd; display: none;  '>
<?php include "./myemail6_im_sent.php";?>
</div>
              
<?php include "myemail6-flirt-popup_flirt.php";?>
      
<script>
	
	function doShowIMSent(to_who) {
	
		makePOSTRequest("http://www.thailovelines.com/ajax_tll/doIMSent.php","userid="+to_who);
		
	}
</script>