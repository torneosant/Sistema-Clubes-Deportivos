import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

import esLocale from '@fullcalendar/core/locales/es';

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) return;


    /*
    |--------------------------------------------------------------------------
    | AÑO DE TRABAJO
    |--------------------------------------------------------------------------
    */

    const anioTrabajo =
        Number(
            window.anioTrabajo ||
            new Date().getFullYear()
        );


    /*
    |--------------------------------------------------------------------------
    | FECHA INICIAL
    |--------------------------------------------------------------------------
    |
    | Si el año de trabajo es el año actual:
    | → abre en el mes actual.
    |
    | Si estamos trabajando otro año:
    | → abre en enero de ese año.
    |
    */

    const hoy = new Date();

    let fechaInicial;


    if (
        anioTrabajo === hoy.getFullYear()
    ) {

        fechaInicial =
            hoy.toISOString().split('T')[0];

    } else {

        fechaInicial =
            `${anioTrabajo}-01-01`;

    }


    /*
    |--------------------------------------------------------------------------
    | CALENDARIO
    |--------------------------------------------------------------------------
    */

    const calendar = new Calendar(
        calendarEl,
        {

            plugins: [
                dayGridPlugin,
                timeGridPlugin,
                interactionPlugin
            ],


            locale: esLocale,


            initialView:
                'dayGridMonth',


            initialDate:
                fechaInicial,


            headerToolbar: {

                left:
                    'prev,next today',

                center:
                    'title',

                right:
                    'dayGridMonth,timeGridWeek,timeGridDay'

            },


            buttonText: {

                today:
                    'Hoy',

                month:
                    'Mes',

                week:
                    'Semana',

                day:
                    'Día'

            },


            height:
                'auto',


            events:
                window.eventosCalendario,


            /*
            |--------------------------------------------------------------------------
            | CLIC EN EVENTO
            |--------------------------------------------------------------------------
            */

            eventClick(info) {

                if (
                    info.event.url
                ) {

                    window.location.href =
                        info.event.url;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | INFORMACIÓN DEL EVENTO
            |--------------------------------------------------------------------------
            */

            eventDidMount(info) {

                const datos =
                    info.event.extendedProps;

                let texto = '';


                if (datos.tipo) {

                    texto +=
                        datos.tipo +
                        '\n\n';

                }


                if (datos.equipo) {

                    texto +=
                        'Equipo: ' +
                        datos.equipo +
                        '\n';

                }


                if (datos.categoria) {

                    texto +=
                        'Categoría: ' +
                        datos.categoria +
                        '\n';

                }


                if (datos.entrenador) {

                    texto +=
                        'Entrenador: ' +
                        datos.entrenador +
                        '\n';

                }


                if (datos.rival) {

                    texto +=
                        'Rival: ' +
                        datos.rival +
                        '\n';

                }


                if (datos.competencia) {

                    texto +=
                        'Competencia: ' +
                        datos.competencia +
                        '\n';

                }


                if (datos.hora) {

                    texto +=
                        'Hora: ' +
                        datos.hora +
                        '\n';

                }


                if (datos.lugar) {

                    texto +=
                        'Lugar: ' +
                        datos.lugar +
                        '\n';

                }


                if (datos.estado) {

                    texto +=
                        'Estado: ' +
                        datos.estado;

                }


                info.el.title =
                    texto;

            }

        }
    );


    calendar.render();

});