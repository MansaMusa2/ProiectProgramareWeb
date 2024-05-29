<?php
session_start();

$servername = "mysql_db";
$username = "root";
$password = "toor";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    if (!empty($user) && !empty($pass) && !empty($confirm_pass)) {
        if ($pass === $confirm_pass) {
            $sql = "SELECT id FROM users WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $message = "Username already exists.";
            } else {
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $user, $hashed_pass);
                if ($stmt->execute()) {
                    $message = "Registration successful. You can now <a href='login.php'>login</a>.";
                } else {
                    $message = "Error: " . $stmt->error;
                }
            }
            $stmt->close();
        } else {
            $message = "Passwords do not match.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

$conn->close();
?>

<html>
<head>
     <!-- Basic -->
     <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/favicon.png" type="">

    <title> Feane Register </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- nice select  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
</head>
<body>
    <form name="form" method="post" action="register.php">
        <section class="book_section layout_padding">
            <div class="container">
            <div class="heading_container">
                <h2>
                    Enter Register Details
                </h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                <div class="form_container">
                    <form action="">
                    <div>
                        <input type="text" class="form-control" placeholder="username" name="username" />
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="password"  name="password"/>
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder=" confirm password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="btn_box">
                        <button type="submit" name="submit" value="Submit"> <h5> Submit </h5> </button>
                        <a href="login.php" class="btn"> <h5> Login </h5> </a>
                    </div>
                    </form>
                </div>
                </div>
                <div class="col-md-6">
                <svg width="400" height="400" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="50" rx="80" ry="40" fill="#f4a261" />
                    <circle cx="70" cy="30" r="5" fill="#f1faee" />
                    <circle cx="90" cy="20" r="5" fill="#f1faee" />
                    <circle cx="110" cy="30" r="5" fill="#f1faee" />
                    <circle cx="130" cy="20" r="5" fill="#f1faee" />
                    <circle cx="150" cy="30" r="5" fill="#f1faee" />
                    <path d="M20,90 Q50,70 80,90 T180,90 Q150,70 120,90 T20,90" fill="#2a9d8f" />
                    <rect x="40" y="90" width="120" height="20" rx="10" ry="10" fill="#e63946" />
                    <rect x="30" y="110" width="140" height="20" rx="10" ry="10" fill="#f4a261" />
                    <rect x="20" y="130" width="160" height="30" rx="10" ry="10" fill="#8d4a00" />
                    <ellipse cx="100" cy="180" rx="80" ry="30" fill="#f4a261" />
                </svg>
                </div>
            </div>
            </div>
        </section>
    </form>
</body>
</html>