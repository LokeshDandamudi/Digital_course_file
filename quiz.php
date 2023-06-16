<!DOCTYPE html>
<html>
<head>
    <title>Quiz Question Paper</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-top: 0;
        }

        form {
            margin-bottom: 20px;
        }

        .question {
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quiz Question Paper</h1>
        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Process the form and display the success message
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
            $sql = "CREATE TABLE IF NOT EXISTS questions (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                question VARCHAR(255) NOT NULL,
                option1 VARCHAR(255) NOT NULL,
                option2 VARCHAR(255) NOT NULL,
                option3 VARCHAR(255) NOT NULL,
                option4 VARCHAR(255) NOT NULL,
                correct_answer VARCHAR(255) NOT NULL
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error creating table: " . $conn->error;
                $conn->close();
                exit();
            }

            // Prepare the statement to insert questions into the database
            $stmt = $conn->prepare("INSERT INTO questions (question, option1, option2, option3, option4, correct_answer) VALUES (?, ?, ?, ?, ?, ?)");

            // Bind parameters and execute the statement for each question
            for ($i = 1; $i <= 3; $i++) {
                $question = $_POST["q$i"];
                $option1 = $_POST["q{$i}_option1"];
                $option2 = $_POST["q{$i}_option2"];
                $option3 = $_POST["q{$i}_option3"];
                $option4 = $_POST["q{$i}_option4"];
                $correctAnswer = $_POST["q{$i}_correct"];

                $stmt->bind_param("ssssss", $question, $option1, $option2, $option3, $option4, $correctAnswer);
                $stmt->execute();
            }

            echo "<div class='message'>Quiz submitted successfully!</div>";

            // Close the database connection
            $conn->close();
        }
        ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php
            // Number of questions to generate
            $numQuestions = 3;

            for ($i = 1; $i <= $numQuestions; $i++) {
                echo "<div class='question'>";
                echo "<h2>Question $i:</h2>";
                echo "<label for='q$i'>Question:</label>";
                echo "<input type='text' name='q$i' required><br>";

                for ($j = 1; $j <= 4; $j++) {
                    $optionLetter = chr(96 + $j); // 'a', 'b', 'c', 'd'
                    echo "<label for='q{$i}_option$j'>Option $optionLetter:</label>";
                    echo "<input type='text' name='q{$i}_option$j' required><br>";
                }

                echo "<label for='q{$i}_correct'>Correct Answer:</label>";
                echo "<input type='text' name='q{$i}_correct' required><br>";

                echo "</div>";
            }
            ?>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
