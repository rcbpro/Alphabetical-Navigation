<?php

if ($_GET['action'] == 'loadImgs'){
	$connection = mysql_connect('localhost', 'root', 'root');
	if ($connection) mysql_select_db('rehand') or die(mysql_error());
	$results = mysql_query("SELECT COUNT(*) AS COUNT_OF_PICS FROM tagging__pictures");
	$COUNT_OF_PICS = mysql_result($results, 0);
	//$results = mysql_query("SELECT * FROM tagging__pictures WHERE userId = 1 AND tagged = 1 AND deletedFlag = 0 LIMIT {$_GET['start']}, {$_GET['limit']}", $connection);
	$results = mysql_query("SELECT * FROM tagging__pictures LIMIT {$_GET['start']}, {$_GET['limit']}", $connection);
	$i = 0;
	$images = array();
	while($row = mysql_fetch_array($results)){
		$images[$i]['pictureId'] = $row['pictureId'];
		$images[$i]['title'] = $row['title'];
		$images[$i]['uploadLImgLocation'] = $row['uploadLImgLocation'];
		$images[$i]['uploadTImgLocation'] = $row['uploadTImgLocation'];		
		$i++;
	}
	//echo $COUNT_OF_PICS;
	if ($COUNT_OF_PICS <= ($_GET['start'] + $_GET['limit'])) $endOfImgLot = true; else $endOfImgLot = false; 
	echo json_encode(array('nextStart' => ($_GET['start'] + $_GET['limit']), 'imageSet' => $images, 'endOfImgLot' => $endOfImgLot));
}