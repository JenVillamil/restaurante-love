<?php
// Start session
session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../loginAdmin.php");
    exit;
}

// Include database connection
require_once 'connection.php';

// Initialize message variables
$success_message = "";
$error_message = "";

// Process form submission for new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {
    // Get form data
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    
    // Basic validation
    if (empty($full_name) || empty($email) || empty($password) || empty($role)) {
        $error_message = "Todos los campos son obligatorios";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Email inválido";
    } else {
        try {
            // Check if email already exists
            $check = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $check->bindParam(':email', $email);
            $check->execute();
            
            if ($check->rowCount() > 0) {
                $error_message = "Este correo ya está registrado";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (:full_name, :email, :password, :role)");
                $stmt->bindParam(':full_name', $full_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':role', $role);
                $stmt->execute();
                
                $success_message = "Usuario creado exitosamente";
            }
        } catch(PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Process user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = $_GET['delete'];
    
    // Don't allow deleting the current logged in user
    if ($user_id != $_SESSION['user_id']) {
        try {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            
            $success_message = "Usuario eliminado exitosamente";
        } catch(PDOException $e) {
            $error_message = "Error al eliminar: " . $e->getMessage();
        }
    } else {
        $error_message = "No puedes eliminar tu propio usuario";
    }
}

// Fetch all users
try {
    $stmt = $conn->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Error al consultar usuarios: " . $e->getMessage();
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Restaurante Love</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .user-management {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fef3f3;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .user-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .user-table {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #830a0a;
            border-color: #830a0a;
        }
        .btn-primary:hover {
            background-color: #6e0909;
            border-color: #6e0909;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .alert {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #830a0a;
            color: white;
        }
        .actions a {
            margin-right: 5px;
        }
        h2 {
            color: #830a0a;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Header Start -->
    <header class="navbar">
        <div class="container">
            <div class="logo">
                <a href="pagIni.php">
                    <img src="../img/logo restaurante.png" alt="Restaurante Logo" class="logo-img">
                </a>
            </div>
            <div style="display: flex; align-items: center;">
                <span style="margin-right: 20px;">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="pagIni.php" class="btn-join">Panel</a>
                <a href="logout.php" class="btn-join" style="margin-left: 10px;">Salir</a>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <div class="user-management">
        <h2 class="text-center">Gestión de Usuarios</h2>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="user-form">
            <h3>Crear Nuevo Usuario</h3>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label for="full_name">Nombre Completo:</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Rol:</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin">Administrador</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </form>
        </div>
        
        <div class="user-table">
            <h3>Usuarios Registrados</h3>
            <?php if (count($users) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Fecha Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                                    <td><?php echo $user['created_at']; ?></td>
                                    <td class="actions">
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?delete=' . $user['id']; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('¿Estás seguro de eliminar este usuario?');">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Usuario actual</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center">No hay usuarios registrados.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Start -->
    <footer class="container-fluid footerServices pt-5 mt-5 wow fadeIn">
        <div class="container text-center">
            <p class="mb-0">Copyright © 2025. All rights reserved.</p>
        </div>
    </footer>
    <!-- Footer End -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>