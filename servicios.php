<?php
// Start session
session_start();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once 'includes/connection.php';
    
    // Get form data
    $document_type = $_POST['tipo-doc'];
    $document_number = $_POST['num-doc'];
    $full_name = $_POST['nombre'];
    $phone = $_POST['celular'];
    $email = $_POST['correo'];
    $menu_item = $_POST['menu'];
    $table_number = (!empty($_POST['mesa'])) ? $_POST['mesa'] : NULL;
    $service_type = $_POST['servicio'];
    
    try {
        // Insert reservation into database
        $stmt = $conn->prepare("INSERT INTO reservations (document_type, document_number, full_name, phone, 
                              email, menu_item, table_number, service_type) 
                              VALUES (:document_type, :document_number, :full_name, :phone, 
                              :email, :menu_item, :table_number, :service_type)");
        
        $stmt->bindParam(':document_type', $document_type);
        $stmt->bindParam(':document_number', $document_number);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':menu_item', $menu_item);
        $stmt->bindParam(':table_number', $table_number);
        $stmt->bindParam(':service_type', $service_type);
        
        $stmt->execute();
        
        $success_message = "¡Reserva realizada con éxito!";
    } catch(PDOException $e) {
        $error_message = "Error al procesar la reserva: " . $e->getMessage();
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
    <link href="css/styleServices.css" rel="stylesheet">

    <!-- Icon Fonts (solo FontAwesome si lo estás usando) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Estilos propios -->
    <link href="css/style.css" rel="stylesheet">

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
                    <img src="img/logo restaurante.png" alt="Restaurante Logo" class="logo-img">
                </a>
            </div>
            <a href="index.html" class="btn-join">BACK  <i class="fas fa-arrow-left"></i></a>
        </div>
    </header>


    <!-- Separador OUR IT SERVICES Start -->
    <div class="section-separator2 text-center mb-3">
        <span> MENÚ</span>
        <p class="section-description">En nuestro restaurante, cada plato es una experiencia pensada para despertar tus sentidos. Combinamos ingredientes frescos, técnicas culinarias de alta calidad y un toque creativo para ofrecerte una carta variada, ideal para ocasiones especiales,
            almuerzos ejecutivos o cenas inolvidables. Ya sea que prefieras un menú gourmet, una opción vegetariana o simplemente disfrutar de un buen postre, aquí encontrarás el sabor perfecto para cada momento.</p>
    </div>
    <!-- Separador End -->

    <!-- Menus -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="service-card text-center p-3 h-100">
                        <img class="img-fluid mb-3" src="img/serviciomenu2.avif" alt="Entradas">
                        <h4 class="fw-bold">Entradas</h4>
                        <ul class="heart-list text-start ps-3 mb-0">
                            <li>Bruschettas de tomate y albahaca</li>
                            <li>Carpaccio de res con rúgula y parmesano</li>
                            <li>Crema de champiñones con crujiente de pan</li>
                            <li>Ceviche de camarones con leche de tigre</li>
                            <li>Tabla de quesos y embutidos</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="service-card text-center p-3 h-100">
                        <img class="img-fluid mb-3" src="img/serviviomenu1.avif" alt="Platos Fuertes">
                        <h4 class="fw-bold">Platos Fuertes</h4>
                        <ul class="heart-list text-start ps-3 mb-0">
                            <li>Filete mignon en salsa de vino tinto</li>
                            <li>Salmón grillado con hierbas</li>
                            <li>Pollo al curry con arroz basmati</li>
                            <li>Fettuccine Alfredo con camarones</li>
                            <li>Lasaña de berenjena y tofu</li>
                            <li>Tacos de champiñones y guacamole</li>
                            <li>Lasagna tradicional de carne</li>
                            <li>Salmón grillado con costra de hierbas y arroz jazmín</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="service-card text-center p-3 h-100">
                        <img class="img-fluid mb-3" src="img/serviciomenu3.jpg" alt="Service Image">
                        <h4 class="fw-bold">Postres</h4>
                        <ul class="heart-list text-start ps-3 mb-0">
                            <li>Tiramisú artesanal</li>
                            <li>Crème brûlée</li>
                            <li>Brownie con helado de vainilla</li>
                            <li>Panna cotta con frutos rojos</li>
                            <li>Helados artesanales de la casa</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="service-card text-center p-3 h-100">
                        <img class="img-fluid mb-3" src="img/serviciomenu4.jpeg" alt="Service Image">
                        <h4 class="fw-bold">Bebidas</h4>
                        <ul class="heart-list text-start ps-3 mb-0">
                            <li>Jugos naturales (mango, fresa, maracuyá, naranja)</li>
                            <li>Limonadas saborizadas (coco, hierbabuena, jengibre)</li>
                            <li>Malteadas de frutas o chocolate</li>
                            <li>Cócteles clásicos (Margarita, Mojito, Piña Colada)</li>
                            <li>Café colombiano de especialidad</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN MENU -->
    <!-- Inicio el formulario -->
    <div class="container my-5">

        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-3" style="font-size: 2rem;">¡Visitanos o haz tu pedido!</h3>
            <img src="img/Carrucel1.jpg" style="width: 600px; height: 500px;" alt="Imagen del restaurante" class="img-fluid rounded">
        </div>


        <form class="p-4 border rounded shadow" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tipo-doc" class="form-label">Tipo de Documento</label>
                    <select class="form-select" id="tipo-doc" name="tipo-doc" required>
                        <option value="">Selecciona...</option>
                        <option value="CC">Cédula de Ciudadanía</option>
                        <option value="CE">Cédula de Extranjería</option>
                        <option value="TI">Tarjeta de Identidad</option>
                        <option value="PAS">Pasaporte</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="num-doc" class="form-label">Número de Documento</label>
                    <input type="text" class="form-control" id="num-doc" name="num-doc" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="celular" class="form-label">Número de Celular</label>
                    <input type="tel" class="form-control" id="celular" name="celular" required>
                </div>
                <div class="col-md-6">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
            </div>

            <div class="mb-3">
            <label for="menu" class="form-label">Menú Seleccionado</label>
                <select class="form-control" id="menu" name="menu" required>
                    <option value="">Seleccione un plato...</option>
                    <optgroup label="Entradas">
                        <option value="Bruschettas de tomate y albahaca">Bruschettas de tomate y albahaca</option>
                        <option value="Carpaccio de res con rúgula y parmesano">Carpaccio de res con rúgula y parmesano</option>
                        <option value="Crema de champiñones con crujiente de pan">Crema de champiñones con crujiente de pan</option>
                        <option value="Ceviche de camarones con leche de tigre">Ceviche de camarones con leche de tigre</option>
                        <option value="Tabla de quesos y embutidos">Tabla de quesos y embutidos</option>
                    </optgroup>
                    <optgroup label="Platos Fuertes">
                        <option value="Filete mignon en salsa de vino tinto">Filete mignon en salsa de vino tinto</option>
                        <option value="Salmón grillado con hierbas">Salmón grillado con hierbas</option>
                        <option value="Pollo al curry con arroz basmati">Pollo al curry con arroz basmati</option>
                        <option value="Fettuccine Alfredo con camarones">Fettuccine Alfredo con camarones</option>
                        <option value="Lasaña de berenjena y tofu">Lasaña de berenjena y tofu</option>
                        <option value="Tacos de champiñones y guacamole">Tacos de champiñones y guacamole</option>
                        <option value="Lasagna tradicional de carne">Lasagna tradicional de carne</option>
                        <option value="Salmón grillado con costra de hierbas y arroz jazmín">Salmón grillado con costra de hierbas y arroz jazmín</option>
                    </optgroup>
                    <optgroup label="Postres">
                        <option value="Tiramisú artesanal">Tiramisú artesanal</option>
                        <option value="Crème brûlée">Crème brûlée</option>
                        <option value="Brownie con helado de vainilla">Brownie con helado de vainilla</option>
                        <option value="Panna cotta con frutos rojos">Panna cotta con frutos rojos</option>
                        <option value="Helados artesanales de la casa">Helados artesanales de la casa</option>
                    </optgroup>
                    <optgroup label="Bebidas">
                        <option value="Jugos naturales">Jugos naturales (mango, fresa, maracuyá, naranja)</option>
                        <option value="Limonadas saborizadas">Limonadas saborizadas (coco, hierbabuena, jengibre)</option>
                        <option value="Malteadas de frutas o chocolate">Malteadas de frutas o chocolate</option>
                        <option value="Cócteles clásicos">Cócteles clásicos (Margarita, Mojito, Piña Colada)</option>
                        <option value="Café colombiano de especialidad">Café colombiano de especialidad</option>
                    </optgroup>
                </select>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="mesa" class="form-label">Número de Mesa</label>
                    <input type="number" class="form-control" id="mesa" name="mesa" min="1">
                    <small id="mesa-help" class="form-text text-muted d-none">No requerido para pedidos para llevar</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block">Tipo de Servicio</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input service-type" type="radio" name="servicio" id="en-restaurante" value="restaurante" checked required>
                        <label class="form-check-label" for="en-restaurante">En el restaurante</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input service-type" type="radio" name="servicio" id="para-llevar" value="para-llevar">
                        <label class="form-check-label" for="para-llevar">Para llevar</label>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">Enviar Reserva</button>
            </div>
        </form>
    </div>

    <footer class="container-fluid  footerServices pt-5 mt-5 wow fadeIn">
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

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">¡Reserva Exitosa!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                    <p>Tu reserva ha sido registrada correctamente.</p>
                    <p>¡Esperamos verte pronto en Restaurante Love!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a elementos del DOM
            const serviceTypeInputs = document.querySelectorAll('.service-type');
            const tableInput = document.getElementById('mesa');
            const tableHelp = document.getElementById('mesa-help');
            const form = document.querySelector('form');
            
            // Función para manejar cambios en el tipo de servicio
            function handleServiceTypeChange() {
                if (document.getElementById('para-llevar').checked) {
                    tableInput.required = false;
                    tableInput.value = ''; // Limpiar el campo cuando se selecciona "Para llevar"
                    tableHelp.classList.remove('d-none');
                    tableInput.placeholder = "Opcional";
                } else {
                    tableInput.required = true;
                    tableHelp.classList.add('d-none');
                    tableInput.placeholder = "";
                }
            }
            
            // Agregar event listeners a los radio buttons
            serviceTypeInputs.forEach(input => {
                input.addEventListener('change', handleServiceTypeChange);
            });
            
            // Configurar estado inicial
            handleServiceTypeChange();
            
            <?php if (isset($success_message)): ?>
            // Mostrar modal de éxito si hay un mensaje de éxito
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            <?php endif; ?>
        });
    </script>
</body>

</html>