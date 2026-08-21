import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

import esLocale from '@fullcalendar/core/locales/es';

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) return;

    const anio = window.anioTrabajo || new Date().getFullYear();

    const calendar = new Calendar(calendarEl, {

        plugins: [
            dayGridPlugin,
            timeGridPlugin,
            interactionPlugin
        ],

        locale: esLocale,

        initialView: 'dayGridMonth',

        initialDate: `${anio}-01-01`,

        headerToolbar: {

            left: 'prev,next today',

            center: 'title',

            right: 'dayGridMonth,timeGridWeek,timeGridDay'

        },

        buttonText: {

            today: 'Hoy',

            month: 'Mes',

            week: 'Semana',

            day: 'Día'

        },

        height: 'auto',

        events: window.eventosCalendario,

        eventClick(info) {

            if (info.event.url) {

                window.location.href = info.event.url;

            }

        },

        eventDidMount(info) {

            let datos = info.event.extendedProps;

            let texto = '';

            if (datos.tipo) {

                texto += datos.tipo + '\n\n';

            }

            if (datos.equipo) {

                texto += 'Equipo: ' + datos.equipo + '\n';

            }

            if (datos.categoria) {

                texto += 'Categoría: ' + datos.categoria + '\n';

            }

            if (datos.entrenador) {

                texto += 'Entrenador: ' + datos.entrenador + '\n';

            }

            if (datos.rival) {

                texto += 'Rival: ' + datos.rival + '\n';

            }

            if (datos.competencia) {

                texto += 'Competencia: ' + datos.competencia + '\n';

            }

            if (datos.hora) {

                texto += 'Hora: ' + datos.hora + '\n';

            }

            if (datos.lugar) {

                texto += 'Lugar: ' + datos.lugar + '\n';

            }

            if (datos.estado) {

                texto += 'Estado: ' + datos.estado;

            }

            info.el.title = texto;

        }

    });

    calendar.render();

});