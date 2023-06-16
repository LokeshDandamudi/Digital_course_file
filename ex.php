<!DOCTYPE html>
<html>
<head>
    <title>Attendance Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            font-size: 36px;
            text-align: center;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
    <script>
        function showSuccessMessage() {
            alert("Attendance marked successfully!");
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Attendance Form</h1>
        <?php
        // Database credentials
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "evaluation_db";

        // Create a new MySQL connection
        $conn = new mysqli($host, $username, $password, $dbname);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Create the 'attendance' table if it doesn't exist
        $createTableQuery = "CREATE TABLE IF NOT EXISTS attendance (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT,
            attendance_date DATE,
            attendance_time TIME,
            status VARCHAR(50)
        )";
        if (!$conn->query($createTableQuery)) {
            die("Error creating table: " . $conn->error);
        }

        // Set the timezone to your desired location
        date_default_timezone_set('Asia/Kolkata');

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Get the date and time of the attendance
            $attendanceDate = date("Y-m-d");
            $attendanceTime = date("H:i:s");

            // Loop through each student and insert their attendance
            foreach ($_POST["students"] as $schoolId => $attendance) {
                $stmt = $conn->prepare("SELECT id FROM student_list WHERE school_id = ?");
                $stmt->bind_param("s", $schoolId);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $studentId = $row["id"];

                $status = $attendance === "present" ? "present" : "absent";
                $stmt = $conn->prepare("INSERT INTO attendance (student_id, attendance_date, attendance_time, status) SELECT id, ?, ?, ? FROM student_list WHERE school_id = ?");
                $stmt->bind_param("ssss", $attendanceDate, $attendanceTime, $status, $schoolId);
                $stmt->execute();
            }

            // Call the JavaScript function to show the success message
            echo "<script>showSuccessMessage();</script>";
        }

        // Get the list of students
        $result = $conn->query("SELECT * FROM student_list");

        // Check if the query was successful
        if (!$result) {
            die("Error executing query: " . $conn->error);
        }

        // Close the database connection
        $conn->close();
        ?>

        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["school_id"]; ?></td>
                            <td><?php echo $row["firstname"] . " " . $row["lastname"]; ?></td>
                            <td><?php echo $row["email"]; ?></td>
                            <td>
                                <label><input type="radio" name="students[<?php echo $row["school_id"]; ?>]" value="present"> Present</label>
                                <label><input type="radio" name="students[<?php echo $row["school_id"]; ?>]" value="absent"> Absent</label>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
