// Update the displayed value of a slider
function updateValue(outputId, value) {
    const output = document.getElementById(outputId);
    if (output) output.textContent = value;
}

// Reset all sliders to 90 degrees
function resetSliders() {
    for (let i = 1; i <= 6; i++) {
        const slider = document.getElementById(`servo${i}`);
        const output = document.getElementById(`servo${i}Output`);
        slider.value = 90;
        if (output) output.textContent = 90;
    }
}

// Load saved poses from the server
function loadPoses() {
    fetch('pose_api.php')
        .then(res => {
            if (!res.ok) throw new Error('Network error');
            return res.json();
        })
        .then(data => {
            const table = document.getElementById('posesTable');
            // Remove all data rows (keep header)
            while (table.rows.length > 1) {
                table.deleteRow(1);
            }

            if (!data || data.length === 0) {
                const row = table.insertRow();
                const cell = row.insertCell();
                cell.colSpan = 8;
                cell.style.textAlign = 'center';
                cell.textContent = 'No poses saved yet.';
                return;
            }

            data.forEach((pose, index) => {
                const row = table.insertRow();
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${pose.servo1}</td>
                    <td>${pose.servo2}</td>
                    <td>${pose.servo3}</td>
                    <td>${pose.servo4}</td>
                    <td>${pose.servo5}</td>
                    <td>${pose.servo6}</td>
                    <td>
                        <button class="load-btn" onclick='loadPose(${JSON.stringify(pose)})'>Load</button>
                        <button class="delete-btn" onclick="deletePose(${pose.id})">Remove</button>
                    </td>
                `;
            });
        })
        .catch(err => {
            console.error('Failed to load poses:', err);
            alert('Could not load saved poses.');
        });
}

// Load current run
function loadRunPose() {
    fetch('get_run_pose.php')
        .then(res => res.text())
        .then(data => {
            const parts = data.trim().split(',');
            if (parts.length !== 7) return;

            const status = parts[0];
            const servos = {};
            for (let i = 1; i <= 6; i++) {
                const val = parts[i].match(/\d+/);
                servos[`servo${i}`] = val ? val[0] : 90;
            }

            const modeText = document.querySelector('.current-status strong');
            if (modeText) {
                modeText.textContent = status === '1' ? 'Running' : 'Stopped';
            }
        })
        .catch(err => {
            console.error('Failed to load run pose:', err);
        });
}

// Save current slider values as a new pose
function savePose() {
    const data = new FormData();
    for (let i = 1; i <= 6; i++) {
        const value = document.getElementById(`servo${i}`).value;
        data.append(`servo${i}`, value);
    }

    fetch('pose_api.php', {
        method: 'POST',
        body: data
    })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                alert('Pose saved successfully!');
                loadPoses();
            } else {
                alert('Error: ' + result.error);
            }
        })
        .catch(err => {
            alert('Save failed: ' + err.message);
        });
}

// Run current pose: send to run table with status = 1
function runPose() {
    const data = new FormData();
    for (let i = 1; i <= 6; i++) {
        const value = document.getElementById(`servo${i}`).value;
        data.append(`servo${i}`, value);
    }

    fetch('update_status.php', {
        method: 'POST',
        body: data
    })
    .then(() => {
        alert('Pose sent to robot!');
        loadRunPose(); // Refresh status
    })
    .catch(err => {
        alert('Run failed: ' + err.message);
    });
}

// Stop robot: set status = 0
function stopRobot() {
    fetch('update_status.php')
        .then(() => {
            alert('Robot stopped.');
            loadRunPose();
        })
        .catch(err => {
            alert('Stop failed: ' + err.message);
        });
}

// Load a saved pose into sliders
function loadPose(pose) {
    for (let i = 1; i <= 6; i++) {
        const slider = document.getElementById(`servo${i}`);
        const output = document.getElementById(`servo${i}Output`);
        if (slider && output) {
            slider.value = pose[`servo${i}`];
            output.textContent = pose[`servo${i}`];
        }
    }
}

// Delete a pose by ID
function deletePose(id) {
    if (confirm('Delete this pose?')) {
        fetch(`delete_pose.php?id=${id}`, {
            method: 'GET'
        })
        .then(() => {
            alert('Pose deleted.');
            loadPoses(); // Refresh list
        })
        .catch(err => {
            alert('Delete failed: ' + err.message);
        });
    }
}

// On page load
document.addEventListener('DOMContentLoaded', () => {
    loadPoses();
    loadRunPose();
    // Auto-refresh
    setInterval(loadPoses, 30000);      // Every 30 seconds
    setInterval(loadRunPose, 10000);    // Every 10 seconds

});
