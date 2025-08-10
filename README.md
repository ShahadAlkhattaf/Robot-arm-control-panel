# Robot Arm Control Panel

A web-based control system for managing a robotic arm using PHP, MySQL, HTML/CSS/JavaScript. The system allows users to control servo motors via a web interface, save poses, and trigger movements with an Arduino sketch that reads commands from a local server.

---

## Project Structure

- `controlPanel.php`  
  Main PHP page displaying the control interface with servo sliders, saved poses table, and current execution status.

- `pose_api.php`  
  REST API endpoint for managing saved poses.

- `Delete_pose.php`  
  Deletes a saved pose by its ID.

- `get_run_pose.php`  
  Returns the current pose and execution status in plain text format for the Arduino client.

- `set_run.php`  
  Updates the run table to signal the Arduino to execute the pose.

- `update_status.php`  
  Updates the current run pose and sets `status=0`.

- `script.js`  
  Client-side JavaScript handling slider updates, fetching/saving poses, running poses, deleting poses, and updating UI.

- `style.css`  
  CSS styles for a clean, responsive, and user-friendly interface.

- Arduino Sketch (`RobotArmHTTPClient.ino`)  
 Polls the server for run commands, moves servos accordingly, and updates run status.

---

## Database Schema (`robotservostatus`)

### Table: `pose`

| Column | Type      | Description                |
|--------|-----------|----------------------------|
| id     | int(11)   | Primary key, auto-increment |
| servo1 | int(11)   | Servo 1 angle (0–180)      |
| servo2 | int(11)   | Servo 2 angle (0–180)      |
| servo3 | int(11)   | Servo 3 angle (0–180)      |
| servo4 | int(11)   | Servo 4 angle (0–180)      |
| servo5 | int(11)   | Servo 5 angle (0–180)      |
| servo6 | int(11)   | Servo 6 angle (0–180)      |

### Table: `run`

| Column | Type         | Description                        |
|--------|--------------|----------------------------------|
| status | tinyint(1)   | Execution status: 0 = idle, 1 = running |
| servo1 | int(11)      | Current servo 1 angle |
| servo2 | int(11)      | Current servo 2 angle |
| servo3 | int(11)      | Current servo 3 angle |
| servo4 | int(11)      | Current servo 4 angle |
| servo5 | int(11)      | Current servo 5 angle |
| servo6 | int(11)      | Current servo 6 angle |

---


## Database Setup

   Create a MySQL database `robotservostatus` and import the following tables:

   ```sql
   CREATE TABLE pose (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      servo1 INT NOT NULL,
      servo2 INT NOT NULL,
      servo3 INT NOT NULL,
      servo4 INT NOT NULL,
      servo5 INT NOT NULL,
      servo6 INT NOT NULL
   );

   CREATE TABLE run (
      status tinyint(1) NOT NULL,
      servo1 INT NOT NULL,
      servo2 INT NOT NULL,
      servo3 INT NOT NULL,
      servo4 INT NOT NULL,
      servo5 INT NOT NULL,
      servo6 INT NOT NULL
   );
   INSERT INTO run (status, servo1, servo2, servo3, servo4, servo5, servo6) VALUES (0, 90, 90, 90, 90, 90, 90);
```

---

## ScreenShot

<img src="screenshot1" width= 400>
<img src="screenshot2" width= 400>
