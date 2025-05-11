<?php
// Start session
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header(" loginAdmin.php");
    exit;
}

// Get user information
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RESTAURANTE LOVE</title>

    <!-- Template Stylesheet -->
    <link href="../css/stylePagIni.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <div class="header">
            <div class="header-logo">
                <img src="../img/logo restaurante.png" alt="RESTAURANTE Logo">
            </div>

            <div class="header-user-info">
                <i class="fa fa-2x fa-solid fa-user-tie"></i>
                <div class="user-details">
                    <h5><?php echo htmlspecialchars($user_name); ?></h5>
                    <div><span>Cargo:</span> <span class="status"><?php echo ucfirst(htmlspecialchars($user_role)); ?></span></div>
                </div>
                <div class="action-card">
                    <a href="logout.php" class="btn-join">SALIR</a>
                </div>
            </div>
        </div>

        <div class="welcome-section">
            <img src="../img/administrador.avif" alt="Team" class="welcome-img">
            <div class="welcome-text">
                <h1>RESERVAS DEL RESTAURANTE</h1>
                <p>Your IT platform for a more efficient and secure business. Here you'll find tools to manage your services, technical support, and security recommendations.</p>
            </div>
        </div>

        <div class="section-separator text-center mb-3">
            <span>Active Services</span>
        </div>

        <div class="cards">
            <div class="card">
                <img src="../img/icono1.png" alt="Support Icon">
                <p>Specialized Support</p>
                <span class="status-active">ACTIVE</span>
            </div>
            <div class="card">
                <img src="../img/icono4.png" alt="Security Icon">
                <p>Computer Security</p>
                <span class="status-pending">PENDIENT</span>
            </div>
            <div class="card">
                <img src="../img/icono3.png" alt="Cloud Icon">
                <p>Cloud Solutions</p>
                <span class="status-expired">EXPIRED</span>
            </div>
        </div>

        <div class="section-separator text-center mb-3">
            <span>Quick action buttons</span>
        </div>

        <div class="action-buttons">
            <div class="action-card">
                <img src="../img/solicitud.png" alt="Report Icon">
                <p>Request Report</p>
                <button>Click</button>
            </div>
            <div class="action-card">
                <img src="../img/actualizar.png" alt="Plan Icon">
                <p>Update Plan</p>
                <button>Click</button>
            </div>
            <div class="action-card">
                <img src="../img/download.png" alt="Download Icon">
                <p>Download Reports</p>
                <button>Click</button>
            </div>
        </div>

        <footer>
            <p>Copyright © 2025. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>