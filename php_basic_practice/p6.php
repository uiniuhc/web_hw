<!DOCTYPE html>
<html>
<head>
<title>home work 6</title>
<style>
form {width:380px;margin:auto;}
#queryTable{padding:0px 5px 0px 15px;border:1px solid black;}
input{padding:5px;}
.leg_table, .leg_table td,.leg_table th,
.com_table, .com_table td,.com_table th,
.bil_table, .bil_table td,.bil_table th,
.ame_table, .ame_table td,.ame_table th
{
	margin:auto;border:1px solid black;
	border-collapse:collapse;
}
#tdimg{
text-align:center;
margin:auto;
}
img{margin:auto;}
</style>
<script>
function validateForm(){
	var sel=document.getElementById("selCongDB");
	var cong=sel.options[sel.selectedIndex].value;
	var keyword=document.getElementById("text_query").value;
	if(cong!="" && keyword!=""){
		return true;
	}
	else{
		var notice="Please enter the following missing infomation: ";
		if(cong=="" && keyword==""){notice+="Congress database, keyword";}
		else if(cong==""){notice+="Congress database";}
		else{
			notice+="keyword";
		}
		alert(notice);
	}
	return false;
}
function resetKeywordLabel(){
	document.getElementById("keyword_label").firstChild.nodeValue="Keyword*";
	document.getElementById("query_result").innerHTML="";
	return true;
}
function setKeywordLabel(){
	var sel=document.getElementById("selCongDB");
	var cong=sel.options[sel.selectedIndex].value;
	var label=document.getElementById("keyword_label").firstChild;
	switch(sel.selectedIndex){
		case 0:label.nodeValue="Keyword*";break;
		case 1:label.nodeValue="State/Representative*";break;
		case 2:label.nodeValue="Committee ID*";break;
		case 3:label.nodeValue="Bill ID*";break;
		case 4:label.nodeValue="Amendment ID*";break;
	}
}
//show the detail of a bill

function showBill(){
	var bil_t=document.getElementById('bill_det');
	bil_t.style.display='block';
	document.getElementById('query_result').style.display='none';	
}
function getLegDetail(str){
	//alert('details of '+str);
	document.getElementById('useDetail').value=str;
//submit the form
	document.getElementById('subid').click();
	
}
</script>
</head>
<body>
<div name="divQuery">
<?php //echo 'php is runing'; //echo print_r($_POST);
//echo htmlspecialchars($_POST['keyword']);
$states = array( 'ALABAMA'=>'AL', 'ALASKA'=>'AK', 'ARIZONA'=>'AZ', 'ARKANSAS'=>'AR', 
		'CALIFORNIA'=>'CA', 'COLORADO'=>'CO', 'CONNECTICUT'=>'CT', 
		'DELAWARE'=>'DE', 'FLORIDA'=>'FL', 'GEORGIA'=>'GA', 
		'HAWAII'=>'HI', 'IDAHO'=>'ID', 'ILLINOIS'=>'IL', 
		'INDIANA'=>'IN', 'IOWA'=>'IA', 'KANSAS'=>'KS', 'KENTUCKY'=>'KY', 
		'LOUISIANA'=>'LA', 'MAINE'=>'ME', 'MARYLAND'=>'MD', 
		'MASSACHUSETTS'=>'MA', 'MICHIGAN'=>'MI', 'MINNESOTA'=>'MN', 
		'MISSISSIPPI'=>'MS', 'MISSOURI'=>'MO', 'MONTANA'=>'MT', 
		'NEBRASKA'=>'NE', 'NEVADA'=>'NV', 'NEW HAMPSHIRE'=>'NH', 
		'NEW JERSEY'=>'NJ', 'NEW MEXICO'=>'NM', 'NEW YORK'=>'NY', 
		'NORTH CAROLINA'=>'NC', 'NORTH DAKOTA'=>'ND', 'OHIO'=>'OH', 
		'OKLAHOMA'=>'OK', 'OREGON'=>'OR', 'PENNSYLVANIA'=>'PA', 
		'RHODE ISLAND'=>'RI', 'SOUTH CAROLINA'=>'SC', 'SOUTH DAKOTA'=>'SD', 
		'TENNESSEE'=>'TN', 'TEXAS'=>'TX', 'UTAH'=>'UT', 
		'VERMONT'=>'VT', 'VIRGINIA'=>'VA', 
		'WASHINGTON'=>'WA', 'WEST VIRGINIA'=>'WV', 
		'WISCONSIN'=>'WI', 'WYOMING'=>'WY' );
$states_abbr= array('AL'=>'ALABAMA','AK'=>'ALASKA','AS'=>'AMERICAN SAMOA',
		'AZ'=>'ARIZONA','AR'=>'ARKANSAS','CA'=>'CALIFORNIA',
		'CO'=>'COLORADO','CT'=>'CONNECTICUT','DE'=>'DELAWARE',
		'DC'=>'DISTRICT OF COLUMBIA','FM'=>'FEDERATED STATES OF MICRONESIA','FL'=>'FLORIDA',
		'GA'=>'GEORGIA','GU'=>'GUAM GU','HI'=>'HAWAII','ID'=>'IDAHO',
		'IL'=>'ILLINOIS','IN'=>'INDIANA','IA'=>'IOWA','KS'=>'KANSAS','KY'=>'KENTUCKY',
		'LA'=>'LOUISIANA','ME'=>'MAINE','MH'=>'MARSHALL ISLANDS','MD'=>'MARYLAND',
		'MA'=>'MASSACHUSETTS',	'MI'=>'MICHIGAN','MN'=>'MINNESOTA',
		'MS'=>'MISSISSIPPI','MO'=>'MISSOURI','MT'=>'MONTANA','NE'=>'NEBRASKA',
		'NV'=>'NEVADA','NH'=>'NEW HAMPSHIRE','NJ'=>'NEW JERSEY','NM'=>'NEW MEXICO','NY'=>'NEW YORK',
		'NC'=>'NORTH CAROLINA','ND'=>'NORTH DAKOTA','MP'=>'NORTHERN MARIANA ISLANDS',
		'OH'=>'OHIO','OK'=>'OKLAHOMA','OR'=>'OREGON','PW'=>'PALAU',
		'PA'=>'PENNSYLVANIA','PR'=>'PUERTO RICO','RI'=>'RHODE ISLAND',
		'SC'=>'SOUTH CAROLINA','SD'=>'SOUTH DAKOTA','TN'=>'TENNESSEE',
		'TX'=>'TEXAS','UT'=>'UTAH','VT'=>'VERMONT','VI'=>'VIRGIN ISLANDS',
		'VA'=>'VIRGINIA','WA'=>'WASHINGTON','WV'=>'WEST VIRGINIA',
		'WI'=>'WISCONSIN','WY'=>'WYOMING','AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',		     'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
		'AP'=>'ARMED FORCES PACIFIC'
		);
function isStateName($str){
	global $states,$states_abbr;
	$str=strtoupper($str);
//	echo "<script>alert('$str'  );</script>";
	if(isset($states[$str]) || isset($states_abbr[$str])){
//echo "it's a state name: $str<br>";
	return true;}
	else return false;
}
function convertStateName($str){
	global $states,$states_abbr;
	$str=strtoupper($str);
	if(isset($states[$str]))return $states[$str];
	return $str;	
}

$json=null;
$url='http://congress.api.sunlightfoundation.com/';
if(isset($_POST['submm']) && $_POST['detail']==''){
	ini_set("allow_url_fopen",1);
		if($_POST['congDB']=='Legislators'){
		$url.='legislators'.'?chamber='.$_POST['cham'];
		$url.='&';
		if(isStateName($_POST['keyword'])){
		$url.='state='.convertStateName($_POST['keyword']);
		}else{$url.='query='.urlencode($_POST['keyword']);}
	}
	else if($_POST['congDB']=='Committees'){
		$url.='committees'.'?chamber='.$_POST['cham'];
		$url.='&';
		$url.='committee_id='.urlencode($_POST['keyword']);
	}

	else if($_POST['congDB']=='Bills'){
		$url.='bills'.'?chamber='.$_POST['cham'];
		$url.='&';
		$url.='bill_id='.urlencode($_POST['keyword']);
	}

	else if($_POST['congDB']=='Amendments'){
		$url.='amendments'.'?chamber='.$_POST['cham'];
		$url.='&';
		$url.='amendment_id='.urlencode($_POST['keyword']);
	}
	$url.='&apikey=b992491ea98f4b649d50d38aa31d1cf4';
//	echo '<br>'.$url.'<br>';
	//query data
	$json=file_get_contents($url);
	
//	echo '<br>'.$json;	
}

if(isset($_POST['submm']) && $_POST['detail']!=''){
	$url.='legislators'.'?bioguide_id='.$_POST['detail'];
	$url.='&apikey=b992491ea98f4b649d50d38aa31d1cf4';
	$json=file_get_contents($url);

}

?>

<form name="searchForm" id='myForm' method="post" action="" onsubmit="return validateForm()" onreset="return resetKeywordLabel()">
<h2>Congress Information Search</h2>
<div id="queryTable">
<table style="margin:auto;">
<tr>
<td>Congress Database</td>
<td>
<select id="selCongDB"  name="congDB" onchange="setKeywordLabel()" >
<option value="" <?php if( !isset($_POST["submm"])) echo "selected"; ?>>Select your option</option>
<option value="Legislators" <?php if( isset($_POST["submm"]) && $_POST["congDB"]=="Legislators")echo "selected";?>>Legislators</option>
<option value="Committees"  <?php if( isset($_POST["submm"]) && $_POST["congDB"]=="Committees")echo "selected";?> >Committees</option>
<option value="Bills"  <?php if( isset($_POST["submm"]) && $_POST["congDB"]=="Bills")echo "selected";?> >Bills</option>
<option value="Amendments"  <?php if( isset($_POST["submm"]) && $_POST["congDB"]=="Amendments")echo "selected";?> >Amendments</option>
</select>
</td>
</tr>
<tr>
<td>Chamber</td>
<td>
<input type="radio" value="senate" name="cham" id="radio_senate" <?php if( !isset($_POST["submm"])||$_POST["cham"]=="senate")echo "checked"; ?>/>
<label for="radio_senate">Senate</label>
<input type="radio" value="house" name="cham" id="radio_house"   <?php if( isset($_POST["submm"])&&$_POST["cham"]=="house")echo "checked"; ?> />
<label for="radio_house">House</label>
</td>
</tr>
<tr>
<td id="keyword_label"> <?php if(isset($_POST["submm"])) echo '<script> setKeywordLabel(); </script>';else echo'Keyword*';?>
</td>
<td><input type="text" id="text_query" name="keyword" size=25 placeholder="please input the keyword" 
<?php if( isset($_POST["submm"])){echo "value='$_POST[keyword]'";} ?> /></td>
</tr>
<tr>
<td></td>
<td>
<input type="submit" value="Search" name="submm" id="subid">
<input type="reset" value="Clear" >
</td>
</tr>


</table>
<input type="hidden" id="useDetail" name="detail" value="">
<input type="hidden" id="lastQuery" name="last" <?php if( isset($_POST["submm"])){echo "value='$_POST[congDB]'";}
else{echo "value=''";}
 ?>>
<p style="padding:0px 30px 0px 60px;margin:5px 0px 10px;"><a href="http://sunlightfoundation.com/" target="_blank">Powered By Sunlight Foundation</a>
</p>
</div>
</form>
</div>
<br>
<div id="query_result">
<?php 
//showing details?
if(isset($_POST["submm"])){
$res=json_decode($json,true);
//echo print_r($res);
if($res['count']==0 ){
//empty situation
echo "<br><div style='width:500px; margin: auto;'><b>The API returned zero results for the request.</b></div><br>";
}else{
if($_POST["detail"]==""){
//display normally
	if($_POST['congDB']=='Legislators'){
		echo '<table class=\'leg_table\'><tr><th>Name</th><th>State</th>\
		<th>Chamber</th><th>View Dtails</th></tr>';
		foreach($res['results'] as $p){
			echo '<tr>';
			echo'<td>';
			echo $p['first_name'].' ';
			echo $p['last_name'];
			echo'</td>';
			echo'<td>';
			echo $p['state_name'];
			echo'</td>';
			echo'<td>';
			echo $p['chamber'];
			echo'</td>';
			echo'<td>';
			echo '<a href='.'\'javascript:getLegDetail("'.$p['bioguide_id'].'")\'>details</a>';
			echo'</td>';
			echo'</tr>';
		}
		echo '</table>';
	}
	if($_POST['congDB']=='Committees'){
		echo '<table class=\'com_table\'><tr><th>Committe ID</th>\
		<th>Committee Name</th><th>Chamber</th></tr>';
		foreach($res['results'] as $p){
			echo '<tr>';
			echo'<td>';
			echo $p['committee_id'].' ';
			echo'</td>';
			echo'<td>';
			echo $p['name'];
			echo'</td>';
			echo'<td>';
			echo $p['chamber'];
			echo'</td>';
			echo'</tr>';
		}
		echo '</table>';
	}

	if($_POST['congDB']=='Bills'){
		echo '<table class=\'bil_table\'><tr><th>Bill ID</th><th>Short Title</th>\
		<th>Chamber</th><th>Dtails</th></tr>';
		foreach($res['results'] as $p){
			echo '<tr>';
			echo'<td>';
			echo $p['bill_id'];
			echo'</td>';
			echo'<td>';
			echo $p['short_title'];
			echo'</td>';
			echo'<td>';
			echo $p['chamber'];
			echo'</td>';
			echo'<td>';
			echo '<a href=\'javascript:showBill();\'>View Detail</a>';
			echo'</td>';
			echo'</tr>';
		}
		echo '</table>';
	}

	if($_POST['congDB']=='Amendments'){
		echo '<table class=\'ame_table\'><tr><th>Amendment ID</th><th>Amendment Type</th>\
		<th>Chamber</th><th>Introduce on</th></tr>';
		foreach($res['results'] as $p){
			echo '<tr>';
			echo'<td>';
			echo $p['amendment_id'];
			echo'</td>';
			echo'<td>';
			echo $p['amendment_type'];
			echo'</td>';
			echo'<td>';
			echo $p['chamber'];
			echo'</td>';
			echo'<td>';
			echo $p['introduced_on'];
			echo'</td>';
			echo'</tr>';
		}
		echo '</table>';	
	}
}
}

}

?>
<div>
</div>
</div>
<div id="query_detail">
<?php 
if(isset($_POST["submm"])){
//for the situation of legislators
//show a detail directly
if($_POST['congDB']=='Legislators' && $_POST['detail']!=''){
//now the json's data is all about one detail

$bio_det=$res['results'][0];
//echo print_r($res);
echo '<table id=\'bill_det\' style=\'width:500px;padding: 10px 20px;margin:auto;border:1px solid black;\'>';
echo '<tr><td id=tdimg colspan=2 ><img src=\''.'http://theunitedstates.io/images/congress/225x275/'.
$bio_det['bioguide_id'].'.jpg\' width=225px height=275px;></td></tr>';
echo '<tr>';
echo '<td>Full Name</td>';
echo '<td>'.$bio_det['title'].' '.$bio_det['first_name'].' '.$bio_det['last_name'].'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Terms Ends on</td>';
echo '<td>'.$bio_det['term_end'].'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Website</td>';
echo '<td><a href=\''.$bio_det['website'].'\'>'.$bio_det['website'].'</a></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Office</td>';
echo '<td>'.$bio_det['office'].'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Facebook</td>';
echo '<td>'.'<a href=\'https://www.facebook.com/'.$bio_det['facebook_id'].
'\'>'.$bio_det['first_name'].' '.$bio_det['last_name'].'</a></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Twitter</td>';
echo '<td>'.'<a href=\'https://twitter.com/'.$bio_det['twitter_id'].
'\'>'.$bio_det['first_name'].' '.$bio_det['last_name'].'</a></td>';
echo '</tr>';
echo '</table>';
}
//fot the situation of bill
//show a hidden detail page, which trigerred by javascript
if($_POST['congDB']=='Bills'){
//simply out put the detail with a hidden label,
//then use js to open it if is needed
	$bil_d=$res['results'][0];
	echo '<table id=\'bill_det\' style=\'display:none;width:500px;padding: 10px 20px;margin:auto;border:1px solid black;\'>';
	echo '<tr>';
	echo '<td>Bill ID</td>';
	echo '<td>'.$bil_d['bill_id'].'</td>';
	echo '</tr>';
	echo '<td>Bill TITLE</td>';
	echo '<td>'.$bil_d['short_title'].'</td>';
	echo '<tr>';
	echo '<td>Sponser</td>';
	echo '<td>'.$bil_d['sponsor']['title'].' '.
	$bil_d['sponsor']['first_name'].' '.$bil_d['sponsor']['last_name'].'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Introduced On</td>';
	echo '<td>'.$bil_d['introduced_on'].'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Last action with date</td>';
	echo '<td>'.$bil_d['last_version']['version_name'].' '.
	$bil_d['last_action_at'].'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Bill URL</td>';
	echo '<td>'.'<a href=\''.$bil_d['last_version']['urls']['pdf'].'\' target=\'_blank\'> '.$bil_d['short_title'].'</a></td>';
	echo '</tr>';
	echo '</table>';
}
}
?>
</div>
</body>
</html>
