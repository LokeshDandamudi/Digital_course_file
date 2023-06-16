<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
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
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Database credentials
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "evaluation_db";

        // Check if the student is logged in (Replace with your login authentication code)
        $isLoggedIn = true; // Replace with your login authentication logic

        if ($isLoggedIn) {
            // Get the student ID after the login process (replace with your actual login code)
            $studentId = 1; // Replace with the logged-in student's ID

            // Create a new MySQL connection
            $conn = new mysqli($host, $username, "", $dbname);

            // Check if the connection was successful
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve the attendance records of the logged-in student
            $stmt = $conn->prepare("SELECT * FROM attendance WHERE student_id = ?");
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the query was successful
            if (!$result) {
                die("Error executing query: " . $conn->error);
            }

            // Close the database connection
            $conn->close();
            ?>

            <h1>Attendance Report</h1>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["attendance_date"]; ?></td>
                            <td><?php echo $row["attendance_time"]; ?></td>
                            <td><?php echo $row["status"]; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php } else { ?>
            <h1>Login</h1>
            <!-- Your login form HTML code here -->
        <?php } ?>
    </div>
</body>
</html>
