<?php

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid ID");
}

$conn = mysqli_connect("localhost", "root", "", "robotservostatus");
$stmt = $conn->prepare("DELETE FROM pose WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
mysqli_close($conn);

header("Location: controlPanel.php");

?>
