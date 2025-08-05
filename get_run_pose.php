<?php
// get_run_pose.php

header('Content-Type: text/plain');

$conn = mysqli_connect("localhost", "root", "", "robotservostatus");
if (!$conn) {
    die("0,s190,s290,s390,s490,s590,s690");
}

$result = mysqli_query($conn, "SELECT * FROM run LIMIT 1");
$pose = mysqli_fetch_assoc($result);

if ($pose) {
    echo $pose['status'] . "," .
         "s1" . (int)$pose['servo1'] . "," .
         "s2" . (int)$pose['servo2'] . "," .
         "s3" . (int)$pose['servo3'] . "," .
         "s4" . (int)$pose['servo4'] . "," .
         "s5" . (int)$pose['servo5'] . "," .
         "s6" . (int)$pose['servo6'];
} else {
    // If no row exists, return default
    echo "0,s190,s290,s390,s490,s590,s690";
}

mysqli_close($conn);
?>