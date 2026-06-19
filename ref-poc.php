<?php
// XSS PoC - Educational Purpose Only
// This file intentionally does NOT include XSS prevention for learning purposes
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS PoC - Reflection Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #856404;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .output {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>XSS PoC - Reflection Page</h1>
        
        <div class="warning">
            ⚠️ <strong>Warning:</strong> This page is vulnerable to XSS attacks for educational purposes only. 
            Do not use in production environments.
        </div>
        
        <form method="GET">
            <label for="userInput">Enter your text:</label>
            <input type="text" id="userInput" name="input" placeholder="Try entering HTML or JavaScript...">
            <button type="submit">Submit</button>
        </form>
        
        <?php
            if (isset($_GET['input'])) {
                echo '<div class="output">';
                echo '<h3>Your Input (Reflected):</h3>';
                echo '<p>' . $_GET['input'] . '</p>';
                echo '</div>';
            }
        ?>
    </div>
</body>
</html>