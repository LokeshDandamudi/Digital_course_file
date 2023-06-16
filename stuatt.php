<!DOCTYPE html>
<html>
<head>
    <title>Student Responses</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "evaluation_db";

    // Create a connection
    $conn = new mysqli($servername, $username,"", $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve the student responses
    $studentResponsesQuery = "SELECT student_answers.student_id, questions.question, student_answers.answer
                              FROM student_answers
                              INNER JOIN questions ON student_answers.question_id = questions.id";
    $studentResponsesResult = $conn->query($studentResponsesQuery);

    if ($studentResponsesResult->num_rows > 0) {
        echo "<h2>Student Responses</h2>";
        echo "<table>";
        echo "<tr><th>Student ID</th><th>Question</th><th>Answer</th></tr>";

        while ($row = $studentResponsesResult->fetch_assoc()) {
            $studentId = $row["student_id"];
            $question = $row["question"];
            $answer = $row["answer"];

            echo "<tr>";
            echo "<td>$studentId</td>";
            echo "<td>$question</td>";
            echo "<td>$answer</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No student responses found.";
    }

    $conn->close();
    ?>
</body>
</html>
