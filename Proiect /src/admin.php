<?php
session_start();

require 'connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES["image"])) {
        $name = trim($_POST["name"]);
        $image = $_FILES["image"];

        if (empty($name)) {
            echo "<script> alert('Name is required'); </script>";
        } elseif ($image['error'] == 4) {
            echo "<script> alert('Image does not exist'); </script>";
        } else {
            $fileName = $image["name"];
            $fileSize = $image["size"];
            $tmpName = $image["tmp_name"];

            $validImageExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmpName);

            $validMimeTypes = ['image/jpeg', 'image/png'];

            if (!in_array($imageExtension, $validImageExtension) || !in_array($mime, $validMimeTypes)) {
                echo "<script> alert('Invalid Image Extension or MIME type'); </script>";
            } elseif ($fileSize > 1000000) {
                echo "<script> alert('Image size is too large'); </script>";
            } else {
                $ogname = pathinfo($fileName, PATHINFO_FILENAME);
                $newImageName = $ogname.'.' . $imageExtension;
                move_uploaded_file($tmpName,'./images/' . $newImageName);
                $stmt = $con->prepare("INSERT INTO tb_upload (name, image) VALUES (?, ?)");
                $stmt->bind_param("ss", $name, $newImageName);
                if ($stmt->execute()) {
                    echo "<script>
                        alert('Image Successfully Added');
                        window.location.href = 'index.php';
                    </script>";
                } else {
                    echo "<script> alert('Database error'); </script>";
                }
                $stmt->close();
            }
        }
    } elseif (isset($_FILES["file"])) {
        $name = trim($_POST["name"]);
        $file = $_FILES["file"];

        if (empty($name)) {
            echo "<script>alert('Name is required');</script>";
        } elseif ($file['error'] == 4) {
            echo "<script>alert('File does not exist');</script>";
        } else {
            foreach ($file['tmp_name'] as $key => $tmp_name) {
                $fileName = $file["name"][$key];
                $fileSize = $file["size"][$key];
                $tmpName = $tmp_name;

                $validFileExtensions = ['txt', 'pdf', 'gif', 'cpp'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExtension, $validFileExtensions)) {
                    echo "<script>alert('Invalid File Extension');</script>";
                } elseif ($fileSize > 1000000) {
                    echo "<script>alert('File size is too large');</script>";
                } else {
                    $ogFilename = pathinfo($fileName,PATHINFO_FILENAME);
                    $newFileName = $ogFilename . '.' . $fileExtension;
                    move_uploaded_file($tmpName, './uploads/' . $newFileName);

                    $stmt = $con->prepare("INSERT INTO fl_upload (name, file) VALUES (?, ?)");
                    $stmt->bind_param("ss", $name, $newFileName);
                    if ($stmt->execute()) {
                        echo "<script>
                            alert('File Successfully Added');
                            window.location.href = 'index.php';
                        </script>";
                    } else {
                        echo "<script>alert('Database error');</script>";
                    }
                    $stmt->close();
                }
            }
        }
    }
}
?>

<?php
if (isset($_POST['delete_image'])) {
    $image_id = $_POST['image_id'];

    $stmt = $con->prepare("SELECT image FROM tb_upload WHERE id = ?");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $image_name = $row['image'];

    unlink('images/' . $image_name);

    $stmt = $con->prepare("DELETE FROM tb_upload WHERE id = ?");
    $stmt->bind_param("i", $image_id);
    if ($stmt->execute()) {
        echo "<script>alert('Image deleted successfully');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Failed to delete image');</script>";
    }
} elseif (isset($_POST['delete_file'])) {
    $file_id = $_POST['file_id'];

    $stmt = $con->prepare("SELECT file FROM fl_upload WHERE id = ?");
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $file_name = $row['file'];

    unlink('uploads/' . $file_name);

    $stmt = $con->prepare("DELETE FROM fl_upload WHERE id = ?");
    $stmt->bind_param("i", $file_id);
    if ($stmt->execute()) {
        echo "<script>alert('File deleted successfully');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Failed to delete file');</script>";
    }
}
?>


<!DOCTYPE html>
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

  <title> Feane </title>

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
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="hero_area">
    <div class="bg-box">
      <img src="images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.php">
            <span>
              Feane
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  mx-auto ">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="menu.php">Menu</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
              </li>
            </ul>
            <div class="user_option">
            <div style="color:white">
                <?php 
                  if($username == 'AlinSion')
                    echo 'Mr. AlinSion <a href="admin.php" class="user_link"><img src="images/setting.png" style="width: 15px; height: 15px;"></a>';
                  else{
                    echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                  }
                ?>
              </div>
              <a href="logout.php" class="user_link">
                <i class="fa fa-user" aria-hidden="true"></i>
              </a>
              <a class="cart_link" href="cart.php">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                  <g>
                    <g>
                      <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                   c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                   C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                   c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                   C457.728,97.71,450.56,86.958,439.296,84.91z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                   c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                    </g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                </svg>
              </a>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Fast Food Restaurant
                    </h1>
                    <p>
                      Doloremque, itaque aperiam facilis rerum, commodi, temporibus sapiente ad mollitia laborum quam quisquam esse error unde. Tempora ex doloremque, labore, sunt repellat dolore, iste magni quos nihil ducimus libero ipsam.
                    </p>
                    <div class="btn-box">
                      <a href="" class="btn1">
                        Order Now
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item ">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Fast Food Restaurant
                    </h1>
                    <p>
                      Doloremque, itaque aperiam facilis rerum, commodi, temporibus sapiente ad mollitia laborum quam quisquam esse error unde. Tempora ex doloremque, labore, sunt repellat dolore, iste magni quos nihil ducimus libero ipsam.
                    </p>
                    <div class="btn-box">
                      <a href="" class="btn1">
                        Order Now
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Fast Food Restaurant
                    </h1>
                    <p>
                      Doloremque, itaque aperiam facilis rerum, commodi, temporibus sapiente ad mollitia laborum quam quisquam esse error unde. Tempora ex doloremque, labore, sunt repellat dolore, iste magni quos nihil ducimus libero ipsam.
                    </p>
                    <div class="btn-box">
                      <a href="" class="btn1">
                        Order Now
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <ol class="carousel-indicators">
            <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
            <li data-target="#customCarousel1" data-slide-to="1"></li>
            <li data-target="#customCarousel1" data-slide-to="2"></li>
          </ol>
        </div>
      </div>

    </section>
    <!-- Upload Image -->
  </div>
  <div class="container mt-5">
    <h2>Upload Image</h2>
    <form class="slider_section" method="POST" autocomplete="off" enctype="multipart/form-data">
        <label for="name"> Name: </label>
        <input type="text" name="name" id="name" required value=""> <br>
        <label for="image"> Image: </label>
        <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png" value=""> <br><br>
        <button type="submit" name="button">Submit</button>
    </form>
</div>
  <!-- Delete Image -->
  <div class="container mt-5">
    <h2>Uploaded Images</h2>
    <?php
    $result = $con->query("SELECT * FROM tb_upload");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<img src='images/" . $row['image'] . "' alt='" . $row['name'] . "' width='200'>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='image_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' name='delete_image'>Delete</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "No images uploaded.";
    }
    ?>
  </div>
     <!-- Upload Image -->
<div class="container mt-5">
    <h2>Upload File</h2>
    <form class="slider_section" method="POST" autocomplete="off" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required value=""><br>
        <label for="file">File:</label>
        <input type="file" name="file[]" id="file" accept=".txt, .pdf, .gif, .cpp" multiple><br><br>
        <button type="submit" name="button">Submit</button>
    </form>
</div>
 <!-- Delete File -->
 <div class="container mt-5">
    <h2>Uploaded Files</h2>
    <?php
    $result = $con->query("SELECT * FROM fl_upload");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<img src='uploads/" . $row['file'] . "' alt='" . $row['name'] . "' width='200'>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='file_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' name='delete_file'>Delete</button>";
            echo "File Name: ".$row['file'];
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "No images uploaded.";
    }
    ?>
  </div>

  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>
              Contact Us
            </h4>
            <div class="contact_link_box">
              <a href="">
                <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d678.04824745068!2d27.571201276054822!3d47.17365790520336!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNDfCsDEwJzI1LjIiTiAyN8KwMzQnMjAuNyJF!5e0!3m2!1sen!2sro!4v1715364150462!5m2!1sen!2sro" width="300" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <span>
                  Bulevardul Carol I 11, Iași 700506
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call 0232 201 060
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  matematica@uaic.ro
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <div class="footer_detail">
            <a href="" class="footer-logo">
              Feane
            </a>
            <p>
              Necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with
            </p>
            <div class="footer_social">
              <a href="https://www.facebook.com/petrudecebal.sion">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <button onclick="shareOnFacebook()">Share</button>
              <button onclick="likeOnFacebook()">Like</button>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>
            Opening Hours
          </h4>
          <p>
            Everyday
          </p>
          <p>
            10.00 Am -10.00 Pm
          </p>
        </div>
      </div>
      <div class="footer-info">
        <p>
          &copy; <span id="displayYear"></span> All Rights Reserved By
          <a href="https://html.design/">Free Html Templates</a><br><br>
          &copy; <span id="displayYear"></span> Distributed By
          <a href="https://themewagon.com/" target="_blank">ThemeWagon</a>
        </p>
      </div>
    </div>
  </footer>
  <!-- footer section -->

  <!-- jQery -->
  <script src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js --> 
  <script src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- isotope js -->
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
  <!-- Anti right click -->
  <script src="js/anti_right_click.js"></script>
  <!-- Anti text selector -->
  <script src="js/anti_text_selector.js"></script>
  <!-- Like & Share -->
  <script src="js/LikeShare.js"></script>
  <!-- Delete imag -->
  <script src="js/delete.js"></script>
</body>

</html>