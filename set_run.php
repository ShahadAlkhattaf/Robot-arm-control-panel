<?php
// set_run.php
$conn = mysqli_connect("localhost", "root", "", "robotservostatus");
if (!$conn) die("Error");

$required = ['servo1','servo2','servo3','servo4','servo5','servo6'];
$data = [];

foreach ($required as $s) {
    $data[$s] = (int)($_POST[$s] ?? 90);
}

$stmt = $conn->prepare("
    UPDATE run SET 
    servo1 = ?, servo2 = ?, servo3 = ?, servo4 = ?, servo5 = ?, servo6 = ?, 
    status = 1
");
$stmt->bind_param("iiiiii", 
    $data['servo1'], $data['servo2'], $data['servo3'], 
    $data['servo4'], $data['servo5'], $data['servo6']
);

$stmt->execute();
$stmt->close();
mysqli_close($conn);
?>