<?php

//include('tableFunctions.php');
include('tableFunctionsDB.php');

// Mysql stuff
$servername = "localhost";
$sql_user = "root";
$sql_pass = "madness";
$sql_db = "calcutta_info";
$sql_table_player = "player_info";
$sql_table_info = "team_info";
$sql_table_team = "team_2016";
$sql_year = "2016";

$teamInfo = build_table_db();
$userInfo = build_acl_db();

echo "<?php\n";
echo "\$teamInfo = ";
var_export($teamInfo);
echo ";\n";
echo "\$userInfo = ";
var_export($userInfo);
echo ";\n";

echo "?>";
?>