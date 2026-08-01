import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import './calendario';

console.log('APP JS CARGADO');

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

// Eliminar
document.addEventListener('submit', function (e) {

    if (!e.target.classList.contains('formulario-eliminar')) {
        return;
    }

    e.preventDefault();

    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no podrá deshacerse.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {
            e.target.submit();
        }

    });

});

// Activar / Inactivar
window.confirmarEstado = function (boton) {

    Swal.fire({
        title: '¿Cambiar estado?',
        text: '¿Deseas cambiar el estado de este registro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {
            boton.closest('form').submit();
        }

    });

    return false;

};