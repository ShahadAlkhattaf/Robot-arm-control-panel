<?php

// Prevent any accidental whitespace or errors from outputting HTML
ob_start();

header('Content-Type: application/json');

// Database connection
$conn = mysqli_connect("localhost", "root", "", "robotservostatus");
if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    ob_end_flush();
    exit();
}

// GET: Return all saved poses
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = mysqli_query($conn, "SELECT * FROM pose ORDER BY id DESC");
    $poses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $poses[] = $row;
    }
    echo json_encode($poses);
    ob_end_flush();
    exit();
}

// POST: Save new pose
$required = ['servo1','servo2','servo3','servo4','servo5','servo6'];
$data = [];

foreach ($required as $s) {
    if (!isset($_POST[$s]) || !is_numeric($_POST[$s])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid or missing $s"]);
        ob_end_flush();
        exit();
    }
    $data[$s] = (int)$_POST[$s];
}

$stmt = $conn->prepare("INSERT INTO pose (servo1, servo2, servo3, servo4, servo5, servo6) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed: " . mysqli_error($conn)]);
    ob_end_flush();
    exit();
}

$stmt->bind_param("iiiiii", $data['servo1'], $data['servo2'], $data['servo3'], $data['servo4'], $data['servo5'], $data['servo6']);

if ($stmt->execute()) {
    echo json_encode(["success" => "Pose saved!"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Execute failed: " . $stmt->error]);
}

$stmt->close();
mysqli_close($conn);
ob_end_flush(); 
?>