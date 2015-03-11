<?php

include('tableFunctions.php');

$teamInfo = build_table("teamInfo.csv");

echo "<?php\n";
echo "\$teamInfo = ";
var_export($teamInfo);
echo ";\n";
echo "?>";
?>
