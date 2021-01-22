<!--
  By DotKom @ 2021
-->

<html>
<body>
 
<?php
function post($url,$data) { 
	$process = curl_init();
	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_HEADER => false,
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => TRUE,
		CURLOPT_POST => TRUE,
		CURLOPT_BINARYTRANSFER => TRUE
	);
	curl_setopt_array($process, $options);
	$return = curl_exec($process); 
	curl_close($process); 
	return $return; 
}

$startdate=date("Y-m-d");
$enddate=date("Y-m-d");
$workers=10000;
$ip="192.168.133.207";

$data[]="sdate={$startdate}";
$data[]="edate={$enddate}";
$data[]="period=1";
for ($i=1;$i<$workers;$i++) {
        $data[]="uid={$i}";
}

$result = post("http://{$ip}/form/Download", implode("&",$data));

//Processing data.
//$result = str_replace(array("\t"), ' + ', $result);
//echo $result;die();
$row=explode("\r\n", $result);
foreach($row as $data) {
	$col = array_filter(explode("\t",$data), fn($value) => !is_null($value) && $value !== '');
	if ($col) { 
		$absen[] = $col;
	}
}

//display data
?>
<table border="1" cellspacing="0" cellpadding="5">
	<tr valign="top">
		<th>PIN</th>
		<th>Name</th>
		<th>DateTime</th>
		<th>Verified</th>
		<th>IN/OUT</th>
	</tr>
	<?php	foreach($absen as $val) { ?>
	<tr>
		<td><?=$val[0];?></td>
		<td><?=$val[1];?></td>
		<td><?=$val[2];?></td>
		<td><?=$val[3];?></td>
		<td><?=$val[4];?></td>
	</tr>
<?php } ?>
</table>

</body>
</html>
