<?php
include('../libs/includes/dbconn.php');

// Get today's and yesterday's date
$currentDay = date('j');
$currentMonth = date('m');
$currentYear = date('Y');
$yesterday = date('j', strtotime('yesterday'));
$yesterdayColumn = "day" . $yesterday;
$todayColumn = "day" . $currentDay;

// Auto-update attendance for students who haven't marked today's attendance
$query = "
    UPDATE attendance_details
    SET $todayColumn = COALESCE($yesterdayColumn, 'A'), -- Default to 'A' if yesterday's attendance is empty
        days_present = CASE
            WHEN COALESCE($yesterdayColumn, 'A') = 'P' THEN days_present + 1
            ELSE days_present
        END
    WHERE $todayColumn IS NULL
      AND month = ?
      AND year = ?
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('ii', $currentMonth, $currentYear);
$stmt->execute();
$stmt->close();

echo "Attendance auto-updated for all students successfully.";
?>
