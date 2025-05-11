<?php
// Start session
session_start();

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: includes/pagIni.php");
    exit;
}

// Initialize error message
$error_message = "";

// Check if form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once 'includes/connection.php';
    
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate inputs
    if(empty($email) || empty($password)) {
        $error_message = "Por favor ingrese correo y contraseña";
    } else {
        // Prepare SQL query
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Check if user exists
        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if(password_verify($password, $user['password'])) {
                // Password is correct, create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: includes/user_management.php");
                    exit;
                } elseif ($user['role'] === 'manager') {
                    header("Location: includes/pagIni.php");
                    exit;
                } else {
                    // For any other role, default to pagIni.php
                    header("Location: includes/pagIni.php");
                    exit;
                }
            } else {
                $error_message = "Correo o contraseña incorrectos";
            }
        } else {
            $error_message = "Correo o contraseña incorrectos";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>RESTAURANTE LOVE</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Icon Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Styles -->
    <link href="css/styleLogin.css" rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" rel="stylesheet">
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="spinner show">
        <div class="loader"></div>
    </div>

    <!-- Spinner End -->
    <header class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.html">
                    <img src="img/logo restaurante.png" alt="RESTAURANTE Logo" class="logo-img">
                </a>
            </div>
        </div>
    </header>
    <!-- Botones -->
    <div class="login-container">
        <img src="img/reservalogin.jpg" alt="Login Illustration" class="login-img">
        <div class="login-form">
            <h3>ADMISITRATIVO RESTAURANTE LOVE</h3>
            
            <?php if(!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="email" name="email" id="email" placeholder="Email address" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <div class="login-buttons">
                    <button type="submit">LOGIN</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="container-fluid footerServices pt-5 mt-5 wow fadeIn">
        <div class="container text-center">
            <p class="mb-0">Copyright © 2025. All rights reserved.</p>
        </div>
    </footer>

    <script>
        window.addEventListener("load", function() {
            const spinner = document.getElementById("spinner");
            if (spinner) {
                spinner.classList.remove("show");
            }
        });
    </script>

    <script src="js/script.js"></script>
</body>
</html>