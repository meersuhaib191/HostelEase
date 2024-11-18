<?php
session_start();
include('../includes/dbconn.php');

// Calculate total expenditure and total attendance
$expenditureQuery = "SELECT SUM(amount) AS total_expenditure FROM expenditure";
$expenditureResult = $mysqli->query($expenditureQuery);
$totalExpenditure = $expenditureResult->fetch_assoc()['total_expenditure'];

$attendanceQuery = "SELECT 
    SUM(CASE WHEN day1 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day2 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day3 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day4 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day5 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day6 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day7 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day8 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day9 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day10 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day11 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day12 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day13 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day14 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day15 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day16 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day17 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day18 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day19 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day20 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day21 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day22 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day23 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day24 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day25 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day26 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day27 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day28 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day29 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day30 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN day31 = 'P' THEN 1 ELSE 0 END) AS total_days_present
FROM attendance_details";
$attendanceResult = $mysqli->query($attendanceQuery);
$totalDaysPresent = $attendanceResult->fetch_assoc()['total_days_present'];

$billPerDay = $totalDaysPresent > 0 ? $totalExpenditure / $totalDaysPresent : 0;
$_SESSION['billPerDay'] = $billPerDay;

// Retrieve individual student attendance
$query = "SELECT u.firstName, u.lastName, u.enrollment_no, 
    SUM(CASE WHEN a.day1 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day2 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day3 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day4 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day5 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day6 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day7 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day8 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day9 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day10 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day11 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day12 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day13 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day14 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day15 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day16 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day17 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day18 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day19 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day20 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day21 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day22 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day23 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day24 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day25 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day26 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day27 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day28 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day29 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day30 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day31 = 'P' THEN 1 ELSE 0 END) AS days_present
FROM userregistration u
JOIN attendance_details a ON u.enrollment_no = a.enrollment_no
GROUP BY u.enrollment_no";

$result = $mysqli->query($query);
$students = [];
while ($student = $result->fetch_assoc()) {
    $student['total_bill'] = $student['days_present'] * $billPerDay;
    $students[] = $student;
}

// Store the students and bill details in session
$_SESSION['students'] = $students;
$_SESSION['billPerDay'] = $billPerDay;

// Send JSON response
echo json_encode(['status' => 'success', 'billPerDay' => $billPerDay, 'students' => $students]);
?>
