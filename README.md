# Robot Arm Control Panel

A web-based control system for managing a robotic arm using PHP, MySQL, HTML/CSS/JavaScript, and Arduino (ESP32). The system allows users to control servo motors via a web interface, save poses, and trigger movements — with an Arduino that reads commands from a local server.

---

## Project Structure

- `controlPanel.php`  
  Main PHP page displaying the control interface with servo sliders, saved poses table, and current execution status.

- `pose_api.php`  
  REST API endpoint for managing saved poses:  
  - GET returns all saved poses in JSON  
  - POST saves a new pose to the database.

- `Delete_pose.php`  
  Deletes a saved pose by its ID and redirects back to the control panel.
    
- `set_run.php`  
  Another endpoint to update the run pose and set status to 1 (alternative implementation).

- `get_run_pose.php`  
  Returns the current pose and execution status in plain text format for the Arduino client.

- `update_status_set_run.php`  
  Updates the current run pose and sets `status=1` to signal the Arduino to execute the pose.

- `script.js`  
  Client-side JavaScript handling slider updates, fetching/saving poses, running poses, deleting poses, and updating UI dynamically.

- `style.css`  
  CSS styles for a clean, responsive, and user-friendly interface.

- Arduino Sketch (`RobotArmHTTPClient.ino`)  
  Connects to Wi-Fi, polls the server for run commands, moves servos accordingly, and updates run status.

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
| servo1 | int(11)      | Current servo 1 angle (default 90) |
| servo2 | int(11)      | Current servo 2 angle (default 90) |
| servo3 | int(11)      | Current servo 3 angle (default 90) |
| servo4 | int(11)      | Current servo 4 angle (default 90) |
| servo5 | int(11)      | Current servo 5 angle (default 90) |
| servo6 | int(11)      | Current servo 6 angle (default 90) |

---

## How It Works

1. **User Interface:**  
   Users adjust servo motor angles using sliders (range 0-180°), save poses, load saved poses, or run a pose to move the robot arm.

2. **Saving a Pose:**  
   The pose data is sent via POST to `pose_api.php` and saved in the `pose` table.

3. **Running a Pose:**  
   When "Run" is clicked, the current servo values are sent to `get_run_pose.php`, which updates the `run` table and sets `status=1`.

4. **Arduino Client:**  
   - Connects to the local Wi-Fi network  
   - Polls `get_run_pose.php` every 2 seconds  
   - When `status=1`, parses servo angles and moves the servos accordingly  
   - After execution, updates `status=0` using a separate API call

5. **Execution Status:**  
   The UI polls the server to update the current execution status (Running or Stopped).

---

## Installation & Setup

1. **Database Setup:**  
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
