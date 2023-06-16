<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "evaluation_db";

$conn = new mysqli($servername, $username, "", $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Calculate total marks and assign grades
$sql = "SELECT student_id, SUM(marks) AS total_marks FROM exams GROUP BY student_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Final Results</h2>";
    echo "<table class='center'>";
    echo "<tr><th>Student ID</th><th>Total Marks</th><th>Grade</th></tr>";

    while ($row = $result->fetch_assoc()) {
        $studentId = $row["student_id"];
        $totalMarks = $row["total_marks"];
        $grade = calculateGrade($totalMarks);

        echo "<tr>";
        echo "<td>$studentId</td>";
        echo "<td>$totalMarks</td>";
        echo "<td>$grade</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No results found";
}

$conn->close();

function calculateGrade($marks) {
    if ($marks >= 90) {
        return "A+";
    } elseif ($marks >= 80) {
        return "A";
    } elseif ($marks >= 70) {
        return "B";
    } elseif ($marks >= 60) {
        return "C";
    } elseif ($marks >= 50) {
        return "D";
    } else {
        return "F";
    }
}
?>

<style>
    h2 {
        text-align: center;
    }

    table.center {
        margin-left: auto;
        margin-right: auto;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }
</style>
