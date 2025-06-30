{{-- resources/views/components/floating-notification.blade.php --}}
<div class="floating-notification" id="floating-notification" style="display: none;">
    <div class="notification-card">
        <div class="notification-content">
            <div class="notification-main-row">
                <div class="notification-info">
                    <div class="notification-main">
                        <span class="user-count" id="pending-count">0</span>
                        <span class="notification-text" id="notification-text">
                            usuarios por notificar
                        </span>
                    </div>
                    <div class="notification-subtitle">
                        Invitaciones pendientes de envío
                    </div>
                </div>
            </div>
            <div class="notification-actions">
                <button class="btn-send" id="send-all-btn">
                    <i class="fas fa-paper-plane"></i>
                    Enviar a todos
                </button>
                <button class="btn-clients" id="go-clients-btn">
                    <i class="fas fa-users"></i>
                    Ir a clientes
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.floating-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
    width: 320px;
    max-width: calc(100vw - 40px);
    transform: translateX(100%);
    transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.floating-notification.show {
    transform: translateX(0);
}

.floating-notification.hide {
    transform: translateX(100%);
}

.notification-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    color: white;
    overflow: hidden;
}

.notification-content {
    padding: 12px 16px 8px 16px;
}

.notification-main-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 6px;
}

.notification-info {
    flex: 1;
    min-width: 0;
}

.notification-main {
    display: flex;
    align-items: baseline;
    gap: 6px;
}

.user-count {
    font-size: 1.4rem;
    font-weight: 700;
    color: #fff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    line-height: 1;
}

.notification-text {
    font-size: 0.9rem;
    opacity: 0.95;
    font-weight: 500;
}

.notification-subtitle {
    font-size: 0.75rem;
    opacity: 0.7;
    margin: 0;
    line-height: 1.2;
}

.notification-actions {
    display: flex;
    gap: 6px;
    justify-content: flex-end;
    margin-top: 4px;
}

.btn-send, .btn-clients {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: white;
    border-radius: 5px;
    padding: 4px 8px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 3px;
}

.btn-send:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-1px);
}

.btn-clients:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

/* Loading state */
.btn-send:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
    .floating-notification {
        top: 10px;
        right: 10px;
        left: 10px;
        width: auto;
        transform: translateY(-100%);
    }
    
    .floating-notification.show {
        transform: translateY(0);
    }
    
    .floating-notification.hide {
        transform: translateY(-100%);
    }
    
    .notification-content {
        padding: 10px 14px 6px 14px;
    }
    
    .user-count {
        font-size: 1.2rem;
    }
    
    .notification-text {
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .notification-main-row {
        gap: 8px;
    }
    
    .notification-actions {
        gap: 4px;
    }
    
    .btn-send, .btn-clients {
        padding: 3px 6px;
        font-size: 0.7rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const floatingNotification = document.getElementById('floating-notification');
    const pendingCountElement = document.getElementById('pending-count');
    const notificationTextElement = document.getElementById('notification-text');
    const sendAllBtn = document.getElementById('send-all-btn');
    const goClientsBtn = document.getElementById('go-clients-btn');
    
    let currentPendingCount = 0;
    
    // Función para verificar usuarios pendientes
    function checkPendingNotifications() {
        $.ajax({
            url: "{{ route('users.pending-notifications') }}",
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(data) {
                if (data.success) {
                    const pendingCount = data.pending_count;
                    currentPendingCount = pendingCount;
                    
                    if (pendingCount > 0) {
                        // Actualizar contenido
                        pendingCountElement.textContent = pendingCount;
                        notificationTextElement.textContent = pendingCount === 1 ? 'usuario por notificar' : 'usuarios por notificar';
                        
                        // Mostrar notificación con animación
                        showNotification();
                    } else {
                        // Ocultar notificación
                        hideNotification();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al verificar notificaciones pendientes:', error);
            }
        });
    }
    
    // Función para mostrar la notificación
    function showNotification() {
        floatingNotification.style.display = 'block';
        // Pequeño delay para que el display se aplique antes de la animación
        setTimeout(() => {
            floatingNotification.classList.add('show');
            floatingNotification.classList.remove('hide');
        }, 10);
    }
    
    // Función para ocultar la notificación
    function hideNotification() {
        floatingNotification.classList.add('hide');
        floatingNotification.classList.remove('show');
        // Ocultar completamente después de la animación
        setTimeout(() => {
            floatingNotification.style.display = 'none';
        }, 400);
    }
    
    // Manejar click en "Enviar a todos"
    sendAllBtn.addEventListener('click', function() {
        if (currentPendingCount === 0) return;
        
        // Confirmar acción con SweetAlert
        Swal.fire({
            title: '{{ trans("web.users.confirm_notify_all") }}',
            text: `¿Estás seguro de que deseas enviar notificaciones a ${currentPendingCount} usuarios?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ trans("web.users.yes_notify") }}',
            cancelButtonText: '{{ trans("web.users.cancel") }}',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar estado de carga durante la petición
                Swal.fire({
                    title: '{{ trans("web.users.sending_notifications") }}',
                    text: '{{ trans("web.users.please_wait") }}',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                        
                        // Llamar a la función de envío masivo
                        $.ajax({
                            url: "{{ route('users.notify') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                force: false
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Cerrar el loading y verificar de nuevo para actualizar el contador
                                    Swal.close();
                                    checkPendingNotifications();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '{{ trans("web.users.error") }}',
                                        text: response.message || 'Error al enviar notificaciones'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ trans("web.users.error") }}',
                                    text: 'Error de conexión al enviar notificaciones'
                                });
                            }
                        });
                    }
                });
            }
        });
    });
    
    // Manejar click en "Ir a clientes"
    goClientsBtn.addEventListener('click', function() {
        window.location.href = '{{ route("users") }}';
    });
    
    // Verificar inicialmente
    checkPendingNotifications();
    
    // Verificar cada 2 minutos
    setInterval(checkPendingNotifications, 120000);
    
    // Escuchar eventos personalizados para actualizar cuando se hagan cambios
    document.addEventListener('usersUpdated', function() {
        checkPendingNotifications();
    });
    
    document.addEventListener('notificationsSent', function() {
        checkPendingNotifications();
    });
});
</script>