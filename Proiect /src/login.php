<?php
session_start();

if (empty($_SESSION['captcha'])) {
    $_SESSION['captcha'] = rand(9999, 1000);
}

$servername = "mysql_db";
$username = "root";
$password = "toor";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $captcha = $_POST['captcha'];

    if ($_SESSION['captcha'] == $captcha) {
        $sql = "SELECT id, username, password FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if (password_verify($pass, $row['password'])) {
                $_SESSION['userid'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                unset($_SESSION['captcha']);
                
                header("Location: index.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that username.";
        }
    } else {
        echo "Invalid CAPTCHA.";
    }

    $_SESSION['captcha'] = rand(9999, 1000);
}

$conn->close();
?>

<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Feane Login</title>
    <style>
        .captcha {
            width: 50%;
            background: orange;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/responsive.css" rel="stylesheet" />
</head>
<body>
    <form name="form" method="post" action="login.php">
        <section class="book_section layout_padding">
            <div class="container">
                <div class="heading_container">
                    <h2>Enter Login Details</h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form_container">
                            <div>
                                <input type="text" class="form-control" placeholder="username" name="username" required />
                            </div>
                            <div>
                                <input type="password" class="form-control" placeholder="password" name="password" required />
                            </div>
                            <div>
                                <h5>Captcha Code</h5>
                                <div class="captcha"><?php echo $_SESSION['captcha']; ?></div>
                                <input type="text" class="form-control" id="captcha" placeholder="captcha" name="captcha" required />
                            </div>
                            <div class="btn_box">
                                <button type="submit" name="submit" value="Submit"> <h5>Submit</h5> </button>
                                <a href="register.php" class="btn"> <h5>Register</h5> </a>
                            </div>
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
