<?php
// about.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>About | Task Manager</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #343a40;
            padding: 15px 0;
            text-align: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 20px;
            font-size: 18px;
            font-weight: bold;
        }

        nav a:hover {
            color: #ffc107;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            font-size: 28px;
            color: #343a40;
            text-align: center;
            margin-bottom: 20px;
        }

        p, ul {
            font-size: 16px;
            color: #495057;
        }

        ul {
            margin-top: 10px;
            padding-left: 20px;
        }

        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
   <nav>
    <a href="index.php">🏠 Home</a>
    <a href="about.php">📖 About</a>
    <a href="#">📞 Contact</a> 
</nav>


    <div class="container">
        <h2>📖 About This Project</h2>
        <p>This Task Manager project was developed as part of a technical assignment for rtCamp. It includes:</p>
        <ul>
            <li>📌 Adding, deleting, and marking tasks as complete/incomplete</li>
            <li>📧 Email subscription system with verification</li>
            <li>⏰ CRON-based task reminder emails</li>
            <li>🔐 Duplicate prevention and proper file-based storage</li>
        </ul>
        <p>Technologies used: PHP, HTML, CSS, File I/O, CRON jobs (local), and logic handling in <code>functions.php</code>.</p>
    </div>
</body>
</html>
