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

// Create exams table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS exams (
    id INT(30) PRIMARY KEY AUTO_INCREMENT,
    exam_name VARCHAR(200) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    marks INT(30) NOT NULL,
    student_id INT(30) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES student_list(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Exams table created successfully<br>";
} else {
    echo "Error creating exams table: " . $conn->error;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $examName = $_POST["examName"];
    $subject = $_POST["subject"];
    $marks = $_POST["marks"];
    $studentId = $_POST["studentId"];

    // Insert marks into exams table
    $insertSql = "INSERT INTO exams (exam_name, subject, marks, student_id) VALUES ('$examName', '$subject', $marks, $studentId)";

    if ($conn->query($insertSql) === TRUE) {
        echo "Marks entered successfully<br>";
    } else {
        echo "Error entering marks: " . $conn->error;
    }
}

// Fetch students from student_list table
$studentsSql = "SELECT id, firstname, lastname FROM student_list";
$result = $conn->query($studentsSql);

if ($result->num_rows > 0) {
    echo "<h2>Enter Marks</h2>";
    echo "<form method='POST' action=''>";
    echo "<label for='examName'>Exam Name:</label>";
    echo "<input type='text' name='examName' required><br>";
    echo "<label for='subject'>Subject:</label>";
    echo "<input type='text' name='subject' required><br>";
    echo "<label for='marks'>Marks:</label>";
    echo "<input type='number' name='marks' required><br>";
    echo "<label for='studentId'>Select Student:</label>";
    echo "<select name='studentId'>";

    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row["id"] . "'>" . $row["firstname"] . " " . $row["lastname"] . "</option>";
    }

    echo "</select><br>";
    echo "<input type='submit' value='Submit'>";
    echo "</form>";
} else {
    echo "No students found";
}

$conn->close();
?>

<style>
    h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
    }

    form label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }

    form input[type='text'],
    form input[type='number'],
    form select {
        padding: 5px;
        margin-bottom: 15px;
        width: 300px;
    }

    form input[type='submit'] {
        padding: 10px 20px;
        background-color: #4CAF50;
        border: none;
        color: white;
        cursor: pointer;
    }

    form input[type='submit']:hover {
        background-color: #45a049;
    }
</style>
