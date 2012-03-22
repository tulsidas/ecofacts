<?php
function executeWS($c, $i) {
   $ret = "";
   if ($i == "") {
      $ret = file_get_contents('http://api.worldbank.org/countries/' .$c. '?format=json');
   }
   else {
      $ret = file_get_contents('http://api.worldbank.org/countries/' .$c. '/indicators/' .$i. '?&date=2000:2011&format=json&per_page=9999');
   }
   
   // guardo en la DB
   mysql_query("insert into cache (country, indicator, result) values('" .$c. "', '" .$i. "', '" .$ret. "')");
   
   return $ret;
}

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'appsforclimate';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);

$country = mysql_real_escape_string($_GET['c']);
$indicator = isset($_GET['i']) ? mysql_real_escape_string($_GET['i']) : "";

$max_diff = 1296000; // 15 dias

mysql_select_db($dbname);

$query = "select result, ts from cache where country = '" . $country . "' and indicator = '" . $indicator. "'";

$result = mysql_query($query);

if ($result && mysql_num_rows($result) == 1) {
   $result_row = mysql_fetch_assoc($result);
   
   $diff = time() - strtotime($result_row['ts']);
   if ($diff > $max_diff) {
      // borro
      mysql_query("delete from cache where country = '" .$country. "' and indicator = '" .$indicator. "'");

      $json = executeWS($country, $indicator);
   }
   else {
      $json = $result_row['result'];
   }
}
else {
   $json = executeWS($country, $indicator);
}

mysql_close($conn);

echo $json;

?>
