<?php

$is_utf8_page = 1; //this is an utf8 page


include "list_init.php";

$page_name = "whoson.php";

# retrieve the template
$area = 'Member20';

$gender = formGet('gender');
$whos_cond = formGet('whos_cond');

$gender_qry = ($gender) ? " AND adv_sex = '$gender' " : "";
$condition = "AND unix_timestamp(mem_timeout) > unix_timestamp(NOW()) - ".ONLINE_TIMEOUT_PERIOD*60;
$qryphotos = ($whos_cond == "pic") ? " INNER JOIN pictures ON (adv_userid=pic_userid AND pic_default='Y') " : "";

//-----

$count_query="SELECT COUNT(adv_userid)
        FROM adverts
        $qryphotos
        LEFT JOIN members ON (adv_userid=mem_userid)
        WHERE (adv_approved=1)
        AND adv_paused='N' ".$condition.$gender_qry."
        ";
//echo $query;		
$limit = $pager->GetLimit($db->get_var($count_query));
//$limit = $pager->GetLimit(50);


$pager->SetUrl("$CONST_LINK_ROOT/$page_name?gender=".$gender);

//----

	$desired_column = "adv_userid, mem_userid, mem_forename, gcn_name,gst_name,gct_name, adv_sex, adv_title, adv_comment, adv_username, adv_expiredate, adv_createdate, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, unix_timestamp(mem_timeout) AS session_active, mem_timeout, mem_skypeset, mem_skype";




	$query="SELECT $desired_column
		FROM adverts
			$qryphotos
			LEFT JOIN members
				ON (adv_userid=mem_userid)
			LEFT JOIN geo_country
				ON (adv_countryid = gcn_countryid)
			LEFT JOIN geo_state
				ON (adv_stateid = gst_stateid)
			LEFT JOIN geo_city
				ON (adv_cityid = gct_cityid)
		WHERE (adv_approved=1)
			".$condition.$gender_qry."
			AND adv_paused='N'
		
		"; //ORDER BY adv_createdate desc
    
	
	$final_query = "select * from ($query)a ORDER BY adv_userid desc $limit";
	
	//echo $final_query ;
	
	$result=$db->get_results($final_query);
	
	
	
	
?>

<?=$skin->ShowHeader($area)?>

<?php include('./ajax_tll/global_js_flirt.php'); ?>
<?php include "whoson_js.php"; ?>

<?php include "list_js_popup_style_sheet.php";?>

  <table align="center" class="home-unit-online" >
    <tr>
      <td align="right">
          <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>
        
    </tr>
    <tr>
      <td height="60" valign="middle"><?php include($CONST_INCLUDE_ROOT."/whoson_network_menu.inc.php")?></td>
    </tr>
    <tr>
  
    </tr>
    <tr>
      <td height="80" valign="middle" class="pageheader"><?php echo WHOSON_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td>
      <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method="post" action="<?php echo $CONST_LINK_ROOT ?>/<?Php echo $page_name;?>">
          <tr >
            <td colspan="5" align="left">
        <?=WHOSON_CONDITION;?>
        <select name="gender" class="input">
        <option value=""><?=WHOSON_A;?>
        <option value="M" <?if ($gender=="M") {echo " SELECTED";}?>><?=WHOSON_M;?>
        <option value="F" <?if ($gender=="F") {echo " SELECTED";}?>><?=WHOSON_W;?>
        </select>
        <input type="checkbox" name="whos_cond" <?if ($whos_cond=='pic') {echo " CHECKED";}?> value="pic">(<?=WHOSON_PIC;?>)
        <input type='submit' value='<?=WHOSON_VIEW;?>' class='button'>
       <input type='button' value='<?=SECURITY_REPORT;?>' class='button' onClick="window.open('http://www.thailovelines.com/help/security-report.php','','toolbar=no,menubar=no,height=350,width=360,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');return false;">
            </td>
          </tr>
        </form>
          <tr >
            <td colspan="5" align="right">
                <?include "search_pager.php"?>
            </td>
          </tr>
          <?php
# insert the line code here

$total_adverts_on_page = 0;

foreach ($result as $sql_array) {

	$adv->InitByObject($sql_array);
	$adv->SetImage('small');
	$sql_array = $adv;
	include("user_list_flag_ajax_final.inc.php");
	
	$total_adverts_on_page++;
}
?>
	
    

      <tr>
        <td colspan="5" align=right>
            <?include "search_pager.php"?>
        </td>
      </tr>
      
      <?php if($total_adverts_on_page == 1){ ?>
      <tr>
        <td colspan="5">
           <div style="padding-bottom:85px"> <?php include_once "./include_ads/mymatch_ads.php";?>	</div>
        </td>
    </tr>
    <?php } ?>
      
      </table>
      </td>
    </tr>
    
     <?php if($total_adverts_on_page != 1){ ?>
  <tr>
  	<td>
		<?php include_once "./include_ads/mymatch_ads.php";?>	
	</td>
  </tr>
  <?php } ?>
</table>
  </table>
<?=$skin->ShowFooter($area)?>
<?php

	include "list_js_popups.php";
?>
         
<?php include "analytics.php"; ?>
