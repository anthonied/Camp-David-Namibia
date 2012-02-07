<?php
include_once 'session.php';

$_REQUEST['cost'] = ltrim($_REQUEST['cost'], "N$ ");
$_REQUEST['amountpayed'] = ltrim($_REQUEST['amountpayed'], "N$ ");
if($_REQUEST['nonquery'] === 'true')
{
	$id = $_POST['id'];
	$oper = $_POST['oper'];
	
	if($oper === 'edit')
	{
		$database->EditMyEvent($id, $session->userdetail['idusers'], $_REQUEST['amountpayed'], $_REQUEST['payedinfull']);
	}
	elseif ($oper === 'del')
	{
		$database->DeleteEvent($id, $session->userdetail['idusers']);
	}
	elseif ($oper === 'add')
	{
		
	}
}
else {
	$page = $_REQUEST['page']; // get the requested page
	$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
	$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
	$sord = $_REQUEST['sord']; // get the direction
	if(!$sidx) $sidx = 0;

	$wh = "";

	$searchOn = Strip($_REQUEST['_search']);
	if($searchOn=='true') {
		$fld = Strip($_REQUEST['searchField']);
		{
			$fldata = Strip($_REQUEST['searchString']);
			$foper = Strip($_REQUEST['searchOper']);

			//['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']
			$qopers = array(
				  'eq'=>" = ",
				  'ne'=>" <> ",
				  'lt'=>" < ",
				  'le'=>" <= ",
				  'gt'=>" > ",
				  'ge'=>" >= ",
				  'bw'=>" LIKE ",
				  'bn'=>" NOT LIKE ",
				  'in'=>" IN ",
				  'ni'=>" NOT IN ",
				  'ew'=>" LIKE ",
				  'en'=>" NOT LIKE ",
				  'cn'=>" LIKE " ,
				  'nc'=>" NOT LIKE " );
			// costruct where
			$wh .= " AND ".$fld.$qopers[$foper];
			if($foper == 'in' || $foper == 'ni')
			$wh .= " (".ToSql($fld, $foper, $fldata).")";
			else
			$wh .= 	ToSql($fld, $foper, $fldata);
		}
	}

	/*
	 $searchOn = Strip($_REQUEST['_search']);
	 if($searchOn=='true') {
	 $searchstr = Strip($_REQUEST['filters']);
	 $wh= constructWhere($searchstr);
	 //echo $wh;
	 }*/


	$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
	if($totalrows) {$limit = $totalrows;}
$userid = $session->userdetail['idusers'];
	$query = "SELECT COUNT(*) AS count FROM ".TABLE_ATTENDEES." a, ".TABLE_EVENTS." b, ".TABLE_CAMPS." c WHERE (a.users_idusers='".$userid."') AND a.events_idevents=b.idevents AND b.camps_idcamps=c.idcamps". $wh;
	$result = $database->ExecuteQuery($query);
	$rows = mysql_fetch_array($result, MYSQL_ASSOC);
	$count = $rows['count'];

	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}

	if ($page > $total_pages)
	$page=$total_pages;
	$start = $limit*($page - 1); // do not put $limit*($page - 1)
	if ($start<0)
	$start = 0;

	$query = "SELECT a.events_idevents, a.users_idusers, a.amountpayed, a.payedinfull, b.description, b.startdate, b.enddate, b.maxattendance, b.cost, b.status, c.name FROM ".TABLE_ATTENDEES." a, ".TABLE_EVENTS." b, ".TABLE_CAMPS." c WHERE (a.users_idusers='".$userid."') AND a.events_idevents=b.idevents AND b.camps_idcamps=c.idcamps". $wh. " ORDER BY ".$sidx." ".$sord. " LIMIT ".$start." , ".$limit;

	$result = $database->ExecuteQuery($query);

	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	$i=0; $amttot=0; $taxtot=0; $total=0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$response->rows[$i]=$row;
		$i++;
	}

	echo json_encode($response);
}
function Strip($value)
{
	if(get_magic_quotes_gpc() != 0)
	{
		if(is_array($value))
		if ( array_is_associative($value) )
		{
			foreach( $value as $k=>$v)
			$tmp_val[$k] = stripslashes($v);
			$value = $tmp_val;
		}
		else
		for($j = 0; $j < sizeof($value); $j++)
		$value[$j] = stripslashes($value[$j]);
		else
		$value = stripslashes($value);
	}
	return $value;
}
function array_is_associative ($array)
{
	if ( is_array($array) && ! empty($array) )
	{
		for ( $iterator = count($array) - 1; $iterator; $iterator-- )
		{
			if ( ! array_key_exists($iterator, $array) ) { return true; }
		}
		return ! array_key_exists(0, $array);
	}
	return false;
}

function constructWhere($s){
	$qwery = "";
	//['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']
	$qopers = array(
				  'eq'=>" = ",
				  'ne'=>" <> ",
				  'lt'=>" < ",
				  'le'=>" <= ",
				  'gt'=>" > ",
				  'ge'=>" >= ",
				  'bw'=>" LIKE ",
				  'bn'=>" NOT LIKE ",
				  'in'=>" IN ",
				  'ni'=>" NOT IN ",
				  'ew'=>" LIKE ",
				  'en'=>" NOT LIKE ",
				  'cn'=>" LIKE " ,
				  'nc'=>" NOT LIKE " );
	if ($s) {
		$jsona = json_decode($s,true);
		if(is_array($jsona)){
			$gopr = $jsona['groupOp'];
			$rules = $jsona['rules'];
			$i =0;
			foreach($rules as $key=>$val) {
				$field = $val['field'];
				$op = $val['op'];
				$v = $val['data'];
				if($v && $op) {
					$i++;
					// ToSql in this case is absolutley needed
					$v = ToSql($field,$op,$v);
					if ($i == 1) $qwery = " AND ";
					else $qwery .= " " .$gopr." ";
					switch ($op) {
						// in need other thing
						case 'in' :
						case 'ni' :
							$qwery .= $field.$qopers[$op]." (".$v.")";
							break;
						default:
							$qwery .= $field.$qopers[$op].$v;
					}
				}
			}
		}
	}
	return $qwery;
}
function ToSql ($field, $oper, $val) {
	// we need here more advanced checking using the type of the field - i.e. integer, string, float
	switch ($field) {
		default :
			//mysql_real_escape_string is better
			if($oper=='bw' || $oper=='bn') return "'" . addslashes($val) . "%'";
			else if ($oper=='ew' || $oper=='en') return "'%" . addslashes($val) . "'";
			else if ($oper=='cn' || $oper=='nc') return "'%" . addslashes($val) . "%'";
			else return "'" . addslashes($val) . "'";
	}
}
?>