<?php
// Start session
session_start();

// Include database connection
require_once 'connection.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: ../loginAdmin.php");
    exit;
}

// Get user information
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Process status update via AJAX
if (isset($_POST['update_status']) && isset($_POST['reservation_id']) && isset($_POST['new_status'])) {
    $id = $_POST['reservation_id'];
    $status = $_POST['new_status'];
    
    $valid_statuses = ['pendiente', 'en_proceso', 'completado'];
    if (in_array($status, $valid_statuses)) {
        try {
            $stmt = $conn->prepare("UPDATE reservations SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode(['success' => true]);
            exit;
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RESTAURANTE LOVE - Panel</title>
    <link href="../css/stylePagIni.css" rel="stylesheet">
    <link href="../css/styleModReservation.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <p>Gestiona todas las reservas y pedidos de manera eficiente. Mantén un control detallado de cada solicitud de los clientes.</p>
            </div>
        </div>

        <?php
        // Check if user is a manager or admin
        if ($_SESSION['user_role'] === 'manager' || $_SESSION['user_role'] === 'admin') {
            // Fetch reservations
            try {
                $stmt = $conn->query("SELECT * FROM reservations ORDER BY created_at DESC");
                $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
                $reservations = [];
            }
        ?>

        <div class="reservations-container">
            <h2 class="reservations-title">Reservas y Pedidos del Restaurante</h2>
            
            <div class="filter-container">
                <div class="row">
                    <div class="col-md-4">
                        <label for="search-input">Buscar:</label>
                        <input type="text" id="search-input" class="form-control" placeholder="Nombre, email, plato...">
                    </div>
                    <div class="col-md-3">
                        <label for="filter-status">Filtrar por estado:</label>
                        <select id="filter-status" class="form-control">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="completado">Completado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter-service">Filtrar por servicio:</label>
                        <select id="filter-service" class="form-control">
                            <option value="">Todos</option>
                            <option value="restaurante">En restaurante</option>
                            <option value="para-llevar">Para llevar</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="reset-filters" class="btn btn-secondary w-100">Resetear</button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="reservations-table">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Menú</th>
                            <th>Mesa</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reservations) > 0): ?>
                            <?php foreach ($reservations as $reservation): ?>
                            <tr class="reservation-row" 
                                data-id="<?php echo $reservation['id']; ?>"
                                data-name="<?php echo htmlspecialchars($reservation['full_name']); ?>"
                                data-status="<?php echo $reservation['status']; ?>"
                                data-service="<?php echo $reservation['service_type']; ?>"
                                data-menu="<?php echo htmlspecialchars($reservation['menu_item']); ?>">
                                <td><?php echo $reservation['id']; ?></td>
                                <td><?php echo htmlspecialchars($reservation['full_name']); ?></td>
                                <td>
                                    <small><?php echo htmlspecialchars($reservation['phone']); ?></small><br>
                                    <small><?php echo htmlspecialchars($reservation['email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($reservation['menu_item']); ?></td>
                                <td><?php echo $reservation['table_number']; ?></td>
                                <td><?php echo ($reservation['service_type'] == 'restaurante') ? 'En local' : 'Para llevar'; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $reservation['status']; ?>">
                                        <?php 
                                        $status_text = '';
                                        switch($reservation['status']) {
                                            case 'pendiente': $status_text = 'Pendiente'; break;
                                            case 'en_proceso': $status_text = 'En Proceso'; break;
                                            case 'completado': $status_text = 'Completado'; break;
                                        }
                                        echo $status_text;
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($reservation['created_at'])); ?></td>
                                <td>
                                    <?php if ($reservation['status'] != 'completado'): ?>
                                        <?php if ($reservation['status'] != 'en_proceso'): ?>
                                        <button class="btn btn-sm btn-primary action-btn update-status" 
                                                data-id="<?php echo $reservation['id']; ?>" 
                                                data-status="en_proceso">
                                            <i class="fas fa-cog"></i> Procesar
                                        </button>
                                        <?php endif; ?>
                                        
                                        <button class="btn btn-sm btn-success action-btn update-status" 
                                                data-id="<?php echo $reservation['id']; ?>" 
                                                data-status="completado">
                                            <i class="fas fa-check"></i> Completar
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-sm btn-info action-btn view-details" 
                                            data-id="<?php echo $reservation['id']; ?>">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No hay reservas disponibles</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-confirm">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="icon-box">
                            <i class="fas fa-question"></i>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Confirmar cambio de estado</h4>
                        <p id="confirm-text">¿Estás seguro de cambiar el estado de esta reserva?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirm-status-change" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Details Modal -->
        <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles de la Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="details-content">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Toast Notifications Container -->
        <div class="toast-container"></div>

        <?php } // End of manager/admin check ?>

        <footer>
            <p>Copyright © 2025. All rights reserved.</p>
        </footer>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Variables for status update
            let selectedReservationId = null;
            let selectedStatus = null;
            
            // Handle confirmation modal
            $('.update-status').click(function() {
                selectedReservationId = $(this).data('id');
                selectedStatus = $(this).data('status');
                
                let statusText = selectedStatus === 'en_proceso' ? 'En proceso' : 'Completado';
                let clientName = $(this).closest('tr').data('name');
                
                $('#confirm-text').text(`¿Estás seguro de cambiar el estado de la reserva de "${clientName}" a "${statusText}"?`);
                
                let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                confirmModal.show();
            });
            
            // Handle status change confirmation
            $('#confirm-status-change').click(function() {
                // Send AJAX request to update status
                $.ajax({
                    url: 'pagIni.php',
                    type: 'POST',
                    data: {
                        update_status: true,
                        reservation_id: selectedReservationId,
                        new_status: selectedStatus
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Hide modal
                            $('#confirmModal').modal('hide');
                            
                            // Update row in the table
                            let $row = $(`tr[data-id="${selectedReservationId}"]`);
                            
                            // Remove old status class and add new one
                            let $statusBadge = $row.find('.status-badge');
                            $statusBadge.removeClass('status-pendiente status-en_proceso status-completado');
                            $statusBadge.addClass(`status-${selectedStatus}`);
                            
                            // Update status text
                            let statusText = '';
                            switch(selectedStatus) {
                                case 'pendiente': statusText = 'Pendiente'; break;
                                case 'en_proceso': statusText = 'En Proceso'; break;
                                case 'completado': statusText = 'Completado'; break;
                            }
                            $statusBadge.text(statusText);
                            
                            // Update row data attribute
                            $row.attr('data-status', selectedStatus);
                            
                            // Update action buttons
                            updateActionButtons($row);
                            
                            // Show success notification
                            showNotification('Estado actualizado correctamente', 'success');
                            
                            // Highlight the row briefly
                            $row.addClass('new-reservation');
                            setTimeout(function() {
                                $row.removeClass('new-reservation');
                            }, 2000);
                        } else {
                            // Show error notification
                            showNotification('Error al actualizar el estado: ' + response.message, 'error');
                        }
                    },
                    error: function() {
                        showNotification('Error de conexión al actualizar el estado', 'error');
                    }
                });
            });
            
            // View reservation details
            $('.view-details').click(function() {
                let id = $(this).data('id');
                let $row = $(`tr[data-id="${id}"]`);
                
                // Get reservation details from row data attributes
                let details = {
                    id: id,
                    name: $row.data('name'),
                    status: $row.data('status'),
                    service: $row.data('service'),
                    menu: $row.data('menu'),
                    phone: $row.find('td:eq(2) small:first').text(),
                    email: $row.find('td:eq(2) small:last').text(),
                    table: $row.find('td:eq(4)').text(),
                    date: $row.find('td:eq(7)').text()
                };
                
                // Build details HTML
                let statusClass = `status-${details.status}`;
                let statusText = '';
                switch(details.status) {
                    case 'pendiente': statusText = 'Pendiente'; break;
                    case 'en_proceso': statusText = 'En Proceso'; break;
                    case 'completado': statusText = 'Completado'; break;
                }
                
                let serviceText = details.service === 'restaurante' ? 'En restaurante' : 'Para llevar';
                
                let detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información del Cliente</h5>
                            <p><strong>Nombre:</strong> ${details.name}</p>
                            <p><strong>Teléfono:</strong> ${details.phone}</p>
                            <p><strong>Email:</strong> ${details.email}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Detalles de la Reserva</h5>
                            <p><strong>Plato:</strong> ${details.menu}</p>
                            <p><strong>Mesa:</strong> ${details.table}</p>
                            <p><strong>Tipo de servicio:</strong> ${serviceText}</p>
                            <p><strong>Estado:</strong> <span class="status-badge ${statusClass}">${statusText}</span></p>
                            <p><strong>Fecha:</strong> ${details.date}</p>
                        </div>
                    </div>
                `;
                
                $('#details-content').html(detailsHtml);
                
                let detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                detailsModal.show();
            });
            
            // Function to update action buttons based on status
            function updateActionButtons($row) {
                let id = $row.data('id');
                let status = $row.data('status');
                let $actionCell = $row.find('td:last');
                
                // Clear existing buttons
                $actionCell.empty();
                
                // Only show action buttons if the reservation is not completed
                if (status !== 'completado') {
                    // Add "Procesar" button if not in that status
                    if (status !== 'en_proceso') {
                        let $processBtn = $(`<button class="btn btn-sm btn-primary action-btn update-status" data-id="${id}" data-status="en_proceso">
                                            <i class="fas fa-cog"></i> Procesar
                                        </button>`);
                        $actionCell.append($processBtn);
                        
                        // Add event handler
                        $processBtn.click(function() {
                            selectedReservationId = $(this).data('id');
                            selectedStatus = $(this).data('status');
                            
                            let statusText = 'En proceso';
                            let clientName = $row.data('name');
                            
                            $('#confirm-text').text(`¿Estás seguro de cambiar el estado de la reserva de "${clientName}" a "${statusText}"?`);
                            
                            let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                            confirmModal.show();
                        });
                    }
                    
                    // Add "Completar" button for all non-completed statuses
                    let $completeBtn = $(`<button class="btn btn-sm btn-success action-btn update-status" data-id="${id}" data-status="completado">
                                        <i class="fas fa-check"></i> Completar
                                    </button>`);
                    $actionCell.append($completeBtn);
                    
                    // Add event handler
                    $completeBtn.click(function() {
                        selectedReservationId = $(this).data('id');
                        selectedStatus = $(this).data('status');
                        
                        let statusText = 'Completado';
                        let clientName = $row.data('name');
                        
                        $('#confirm-text').text(`¿Estás seguro de cambiar el estado de la reserva de "${clientName}" a "${statusText}"?`);
                        
                        let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                        confirmModal.show();
                    });
                }
                
                // Always add view details button
                let $viewBtn = $(`<button class="btn btn-sm btn-info action-btn view-details" data-id="${id}">
                                <i class="fas fa-eye"></i> Ver
                                </button>`);
                $actionCell.append($viewBtn);
                
                // Add event handler for view button
                $viewBtn.click(function() {
                    // View details logic here (unchanged)
                    // ...
                });
            }
            
            // Function to show notification toast
            function showNotification(message, type) {
                let bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
                let icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                
                let $toast = $(`
                    <div class="notification-toast ${bgClass} text-white">
                        <i class="fas ${icon} me-2"></i> ${message}
                    </div>
                `);
                
                $('.toast-container').append($toast);
                
                // Show the toast with animation
                setTimeout(() => {
                    $toast.addClass('show');
                }, 100);
                
                // Hide after 3 seconds
                setTimeout(() => {
                    $toast.removeClass('show');
                    setTimeout(() => {
                        $toast.remove();
                    }, 500);
                }, 3000);
            }
            
            // Search and filter functionality
            $('#search-input').on('keyup', function() {
                filterTable();
            });
            
            $('#filter-status, #filter-service').on('change', function() {
                filterTable();
            });
            
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-status, #filter-service').val('');
                filterTable();
            });
            
            function filterTable() {
                let searchText = $('#search-input').val().toLowerCase();
                let statusFilter = $('#filter-status').val();
                let serviceFilter = $('#filter-service').val();
                
                $('.reservation-row').each(function() {
                    let $row = $(this);
                    let rowData = {
                        name: $row.data('name').toLowerCase(),
                        status: $row.data('status'),
                        service: $row.data('service'),
                        menu: $row.data('menu').toLowerCase(),
                        email: $row.find('td:eq(2) small:last').text().toLowerCase()
                    };
                    
                    let matchesSearch = !searchText || 
                                       rowData.name.includes(searchText) || 
                                       rowData.menu.includes(searchText) || 
                                       rowData.email.includes(searchText);
                                       
                    let matchesStatus = !statusFilter || rowData.status === statusFilter;
                    let matchesService = !serviceFilter || rowData.service === serviceFilter;
                    
                    if (matchesSearch && matchesStatus && matchesService) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                });
            }
            
            // Check for new reservations every 30 seconds
            setInterval(checkForNewReservations, 30000);
            
            function checkForNewReservations() {
                // This would typically be an AJAX call to get new reservations
                // For demonstration, we'll simulate a new reservation after 1 minute
                /*
                setTimeout(function() {
                    // Add a new row to the table (in real implementation, this would come from server)
                    // And highlight it with the new-reservation class
                }, 60000);
                */
            }
        });
    </script>
</body>
</html>