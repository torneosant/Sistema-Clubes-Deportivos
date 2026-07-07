import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

console.log('APP JS CARGADO');

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

document.addEventListener('submit', function (e) {

    if (!e.target.classList.contains('formulario-eliminar')) {
        return;
    }
     console.log('Intercepté el submit');

    e.preventDefault();

    Swal.fire({
        title: '¿Eliminar jugador?',
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
window.confirmarEliminar = function (boton) {

    Swal.fire({
        title: '¿Eliminar jugador?',
        text: 'Esta acción no podrá deshacerse.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'

    }).then((result) => {

        if (result.isConfirmed) {
            boton.closest('form').submit();
        }

    });

};
window.confirmarEstado = function (boton, activo) {

    Swal.fire({
        title: activo ? '¿Inactivar jugador?' : '¿Activar jugador?',
        text: activo
            ? 'El jugador dejará de estar disponible.'
            : 'El jugador volverá a estar disponible.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: activo ? '#dc2626' : '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: activo ? 'Sí, inactivar' : 'Sí, activar',
        cancelButtonText: 'Cancelar'

    }).then((result) => {

        if (result.isConfirmed) {
            boton.closest('form').submit();
        }

    });

};
