<?php

$conn = mysqli_connect("localhost", "root", "", "robotservostatus");
if (!$conn) die("DB connection failed");

// Fetch saved poses
$poseResult = mysqli_query($conn, "SELECT * FROM pose ORDER BY id DESC");
$poses = [];
while ($row = mysqli_fetch_assoc($poseResult)) {
    $poses[] = $row;
}

// Fetch current run pose status
$runQuery = mysqli_query($conn, "SELECT * FROM run");
$runStatus = mysqli_fetch_assoc($runQuery);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Robot Arm Control Panel</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="container">

    <h1>Robot Arm Control Panel</h1>

    <!-- Motor Controls -->
    <div class="controlPanelContainer">
      <h2>Motor Controls</h2>

      <div class="sliders">
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <div class="slider-row">
            <label>Motor <?= $i ?>:</label>
            <input 
              type="range" 
              min="0" 
              max="180" 
              value="90" 
              id="servo<?= $i ?>" 
              oninput="updateValue('servo<?= $i ?>Output', this.value)"
            />
            <span id="servo<?= $i ?>Output" class="slider-value">90</span>
          </div>
        <?php endfor; ?>
      </div>

      <div class="control_btns">
        <button onclick="resetSliders()">Reset</button>
        <button onclick="savePose()">Save Pose</button>
        <button onclick="runPose()">Run</button>
      </div>
    </div>

    <!-- Saved Poses -->
    <div class="statuscontainer">
      <h3>Saved Poses</h3>
      <table id="posesTable">
        <tr>
          <th>#</th>
          <th>Motor 1</th>
          <th>Motor 2</th>
          <th>Motor 3</th>
          <th>Motor 4</th>
          <th>Motor 5</th>
          <th>Motor 6</th>
          <th>Action</th>
        </tr>
        <?php $counter = 1; foreach ($poses as $pose): ?>
          <tr>
            <td><?= $counter++ ?></td>
            <td><?= $pose['servo1'] ?></td>
            <td><?= $pose['servo2'] ?></td>
            <td><?= $pose['servo3'] ?></td>
            <td><?= $pose['servo4'] ?></td>
            <td><?= $pose['servo5'] ?></td>
            <td><?= $pose['servo6'] ?></td>
            <td>
              <button onclick='loadPose(<?= json_encode($pose) ?>)'>Load</button>
              <button onclick="deletePose(<?= $pose['id'] ?>)">Remove</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>

    <!-- Current Run Status -->
    <div class="current-status">
      <h3>Execution Status</h3>
      <p>Current Mode: <strong><?= htmlspecialchars($runStatus['status'] ?? 'Unknown') ?></strong></p>
    </div>

  </div>

  <script src="script.js"></script>

</body>

</html>
