<!DOCTYPE html>
<html>
<head>
    <title>Quiz Question Paper</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
        }

        .question {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        p {
            margin: 0;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
            display: block;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .options {
            display: inline-block;
            margin-right: 10px;
        }
    </style>
    <script>
        function showSuccessMessage() {
            alert("Quiz submitted successfully!");
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Quiz Question Paper</h1>
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

        // Create the questions table if not exists
        $createQuestionsTable = "CREATE TABLE IF NOT EXISTS questions (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            question VARCHAR(255) NOT NULL,
            option1 VARCHAR(255) NOT NULL,
            option2 VARCHAR(255) NOT NULL,
            option3 VARCHAR(255) NOT NULL,
            option4 VARCHAR(255) NOT NULL,
            correct_answer VARCHAR(255) NOT NULL
        )";
        if ($conn->query($createQuestionsTable) === FALSE) {
            echo "Error creating table: " . $conn->error;
            $conn->close();
            exit();
        }

        // Create the student_answers table if not exists
        $createStudentAnswersTable = "CREATE TABLE IF NOT EXISTS student_answers (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            student_id VARCHAR(255) NOT NULL,
            question_id INT(11) NOT NULL,
            answer VARCHAR(255) NOT NULL,
            FOREIGN KEY (question_id) REFERENCES questions(id)
        )";
        if ($conn->query($createStudentAnswersTable) === FALSE) {
            echo "Error creating table: " . $conn->error;
            $conn->close();
            exit();
        }

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Process the form and display the success message

            // Retrieve the questions from the database
            $questionsQuery = "SELECT id FROM questions";
            $questionsResult = $conn->query($questionsQuery);

            // Prepare the statement to insert student answers into the database
            $stmt = $conn->prepare("INSERT INTO student_answers (student_id, question_id, answer) VALUES (?, ?, ?)");

            // Get the student ID from the form
            $studentId = $_POST['student_id'];

            // Bind parameters and execute the statement for each question
            while ($question = $questionsResult->fetch_assoc()) {
                $questionId = $question['id'];
                $answer = $_POST["answer_$questionId"];

                // Insert the student answer into the "student_answers" table
                $stmt->bind_param("sis", $studentId, $questionId, $answer);
                $stmt->execute();
            }

            header("Location: thankyou.php");
            exit();
        }
        ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" required><br><br>

            <?php
            // Retrieve questions from the database
            $questionsQuery = "SELECT id, question, option1, option2, option3, option4 FROM questions";
            $questionsResult = $conn->query($questionsQuery);

            // Display the questions from the database
            while ($question = $questionsResult->fetch_assoc()) {
                $questionId = $question['id'];
                $questionText = $question['question'];
                $option1 = $question['option1'];
                $option2 = $question['option2'];
                $option3 = $question['option3'];
                $option4 = $question['option4'];

                echo "<div class='question'>";
                echo "<h2>Question $questionId:</h2>";
                echo "<p>$questionText</p>";

                echo "<div class='options'>";
                echo "<input type='radio' id='option_$questionId' name='answer_$questionId' value='option1'>";
                echo "<label for='option_$questionId'>$option1</label><br>";

                echo "<input type='radio' id='option_$questionId' name='answer_$questionId' value='option2'>";
                echo "<label for='option_$questionId'>$option2</label><br>";

                echo "<input type='radio' id='option_$questionId' name='answer_$questionId' value='option3'>";
                echo "<label for='option_$questionId'>$option3</label><br>";

                echo "<input type='radio' id='option_$questionId' name='answer_$questionId' value='option4'>";
                echo "<label for='option_$questionId'>$option4</label><br>";
                echo "</div>";

                echo "</div>";
            }
            ?>

            <div class="options">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
