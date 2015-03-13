<?php

include('tableFunctions.php');

$teamInfo = build_table("teamInfo.csv");



echo "<?php\n";
echo "\$teamInfo = ";
var_export($teamInfo);
echo ";\n";

$userInfo = build_acl("playerInfo.csv");
echo "\$userInfo = ";
var_export($userInfo);
echo ";\n";
echo "?>";
?>