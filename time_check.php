<?php
require_once("DBconnect.php");

echo "<h3>Time Sync Check</h3>";
echo "PHP Current Time: " . date("Y-m-d H:i:s") . "<br>";

$res = mysqli_query($conn, "SELECT NOW() as db_time");
$row = mysqli_fetch_assoc($res);
echo "MySQL Current Time: " . $row['db_time'] . "<br>";

if(date("Y-m-d H:i:s") != $row['db_time']){
    echo "<b style='color:red;'>Timezone Mismatch!</b> This is why your logic is showing extra hours.";
} else {
    echo "<b style='color:green;'>Timezones are synced.</b>";
}
?>