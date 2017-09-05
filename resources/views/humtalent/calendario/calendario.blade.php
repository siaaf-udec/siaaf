@extends('material.layouts.dashboard')

@section('page-title', 'Documentos Requeridos:')
@push('styles')
<link href="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.print.css') }}" rel="stylesheet" media='print' type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />

@endpush

@section('content')
    @component('themes.bootstrap.elements.portlets.portlet', ['icon' => 'icon-book-open', 'title' => 'Calendario'])
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <!-- Selección de eventos-->
                <div id="external-events">

                    {!! Field::text(
                        'NOTIF_Descripcion',null,
                        ['label'=>'Crear un evento:','class'=> 'form-control','required','placeholder'=>'Nombre del evento...', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date' => "+0d"],
                        ['help' => 'Digite el nombre del evento', 'icon' => 'fa fa-calendar']) !!}

                    <a href="javascript:;" id="event_add" class="btn green"> Añadir </a>

                    <hr/>
                    <div id="event_box" class="margin-bottom-10"></div>
                    <p>

                        <br>
                        <i style="color: lightslategray;font-size: 600%" class="fa fa-remove" id="trash"></i>


                    </p>
                    <hr class="visible-xs" /> </div>
                <!-- Fin selección de eventos-->
            </div>
            <div class="col-md-9 col-sm-12">
                <div id="calendar" class="has-toolbar"> </div>
            </div>
            <div class="col-md-12">
                <!-- Modal -->
                <div class="modal fade" id="modal-update-notify" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            {!! Form::open(['id' => 'form_calendar_notify','method'=>'POST', 'route'=> ['talento.humano.calendario.storeDateNotification']]) !!}
                            <div class="modal-header modal-header-success">
                                <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                                    ×
                                </button>
                                <h1><i class="glyphicon glyphicon-thumbs-up"></i> Fecha de Notificación</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! Field::date(
                                                'NOTIF_Fecha_Notificacion',
                                                ['label' => 'Fecha de notificación :', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date' => "+0d"],
                                                ['help' => 'Digite la fecha de recordatorio.', 'icon' => 'fa fa-calendar']) !!}
                                        {!! Field::hidden ('PK_NOTIF_Id_Notificacion')!!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                {!! Form::submit('Guardar', ['class' => 'btn blue']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <!-- Modal -->
                <div class="modal fade" id="modal-update-Event" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            {!! Form::open(['id' => 'form_calendar_notify','method'=>'POST', 'route'=> ['talento.humano.calendario.storeDateEvent']]) !!}
                            <div class="modal-header modal-header-success">
                                <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                                    ×
                                </button>
                                <h1><i class="glyphicon glyphicon-thumbs-up"></i> Fecha de Notificación</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! Field::date(
                                                'EVNT_Fecha_Notificacion',
                                                ['label' => 'Fecha de notificación :','required', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date' => "+0d"],
                                                ['help' => 'Digite la fecha de recordatorio.', 'icon' => 'fa fa-calendar']) !!}
                                        {!! Field::hidden ('PK_EVNT_IdEvento')!!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                {!! Form::submit('Guardar', ['class' => 'btn blue']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <!-- Modal -->
                <div class="modal fade" id="modal-update-titleNotify" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            {!! Form::open(['id' => 'form_calendar_Updatenotify','method'=>'POST', 'route'=> ['talento.humano.calendario.updateNotification']]) !!}
                            <div class="modal-header modal-header-success">
                                <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                                    ×
                                </button>
                                <h1><i class="glyphicon glyphicon-thumbs-up"></i> Modificar recordatorio</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! Field::textarea(
                                            'NOTIF_Descripcion',null,
                                            ['id'=>'eventDesc','label'=>'Crear un evento:','class'=> 'form-control','required','placeholder'=>'Nombre del evento...', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date' => "+0d"],
                                            ['help' => 'Digite el nombre del evento', 'icon' => 'fa fa-calendar']) !!}
                                        {!! Field::date(
                                                'NOTIF_Fecha_Notificacion',
                                                ['label' => 'Fecha de notificación :', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date' => "+0d"],
                                                ['help' => 'Digite la fecha de recordatorio.', 'icon' => 'fa fa-calendar']) !!}
                                        {!! Field::hidden ('PK_NOTIF_Id_Notificacion')!!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                {!! Form::submit('Guardar', ['class' => 'btn blue']) !!}
                                {!! Form::button('Cancelar', ['class' => 'btn red', 'data-dismiss' => 'modal' ]) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <!-- Modal -->
                <div class="modal fade" id="modal-update-titleEvent" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            {!! Form::open(['id' => 'form_calendar_UpdateEvent','method'=>'POST', 'route'=> ['talento.humano.calendario.updateEvent']]) !!}
                            <div class="modal-header modal-header-success">
                                <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                                    ×
                                </button>
                                <h1><i class="glyphicon glyphicon-thumbs-up"></i> Modificar Evento</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! Field::textarea(
                                            'EVNT_Descripcion',null,
                                            ['label'=>'Crear un evento:','class'=> 'form-control','required','placeholder'=>'Nombre del evento...', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date' => "+0d"],
                                            ['help' => 'Digite el nombre del evento', 'icon' => 'fa fa-calendar']) !!}
                                        {!! Field::text(
                                                'EVNT_Hora',
                                                ['label'=>'Hora:','class' => 'timepicker timepicker-no-seconds', 'data-date-format' => "hh/mm-", 'data-date-start-date' => "+0d", 'required', 'auto' => 'off'],
                                                ['help' => 'Selecciona la hora.', 'icon' => 'fa fa-clock-o']) !!}
                                        {!! Field::date(
                                                'EVNT_Fecha_Notificacion',
                                                ['label' => 'Fecha de notificación :','required', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date' => "+0d"],
                                                ['help' => 'Digite la fecha de notificación del evento .', 'icon' => 'fa fa-calendar']) !!}
                                        {!! Field::hidden ('PK_NOTIF_Id_Notificacion')!!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                {!! Form::submit('Guardar', ['class' => 'btn blue']) !!}
                                {!! Form::button('Cancelar', ['class' => 'btn red', 'data-dismiss' => 'modal' ]) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent
@endsection
@push('plugins')

    <script src="{{ asset('assets/global/plugins/fullcalendar/lib/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/fullcalendar/fullcalendar.js') }}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/fullcalendar/lang-all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/localization/messages_es.js') }}" type="text/javascript"></script>
@endpush
@push('functions')
    <script src="{{ asset('assets/main/scripts/ui-toastr.js') }}" type="text/javascript"></script>
    <script>
        var FormValidationMd = function() {

            var handleValidation = function() {

                var form1 = $('#form_calendar_Updatenotify');
                var form2 = $('#form_calendar_UpdateEvent');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: true,
                    ignore: "",
                    rules: {
                        NOTIF_Descripcion: {
                            required: true
                        }
                    },
                    messages:{
                        NOTIF_Descripcion: {
                            required: "Debe ingresar la descripción de la notificación a modificar."
                        }

                    },

                    invalidHandler: function(event, validator) {
                        success1.hide();
                        error1.show();
                        toastr.options.closeButton = true;
                        toastr.options.showDuration= 1000;
                        toastr.options.hideDuration= 1000;
                        toastr.error('Debe corregir algunos campos','Modificación fallida:')
                        App.scrollTo(error1, -200);
                    },

                    errorPlacement: function(error, element) {
                        if (element.is(':checkbox')) {
                            error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                        } else if (element.is(':radio')) {
                            error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    highlight: function(element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error');
                    },

                    unhighlight: function(element) {
                        $(element)
                            .closest('.form-group').removeClass('has-error');
                    },

                    success: function(label) {
                        label
                            .closest('.form-group').removeClass('has-error');

                    },

                    submitHandler: function(form1) {
                        success1.show();
                        error1.hide();
                        form1.submit();
                    }
                });
                form2.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: true,
                    ignore: "",
                    rules: {
                        EVNT_Descripcion: {
                            required: true
                        },
                        EVNT_Fecha_Notificacion: {
                            required: true
                        }
                    },
                    messages:{
                        EVNT_Descripcion: {
                            required: "Debe ingresar la descripción del evento a modificar"
                        },
                        EVNT_Fecha_Notificacion: {
                            required: "Debe ingresar la fecha del evento a modificar"
                        }

                    },

                    invalidHandler: function(event, validator) {
                        success1.hide();
                        error1.show();
                        toastr.options.closeButton = true;
                        toastr.options.showDuration= 1000;
                        toastr.options.hideDuration= 1000;
                        toastr.error('Debe corregir algunos campos','Modificación fallida:')
                        App.scrollTo(error1, -200);
                    },

                    errorPlacement: function(error, element) {
                        if (element.is(':checkbox')) {
                            error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                        } else if (element.is(':radio')) {
                            error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    highlight: function(element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error');
                    },

                    unhighlight: function(element) {
                        $(element)
                            .closest('.form-group').removeClass('has-error');
                    },

                    success: function(label) {
                        label
                            .closest('.form-group').removeClass('has-error');

                    },

                    submitHandler: function(form2) {
                        success1.show();
                        error1.hide();
                        form2.submit();
                    }
                });
            }

            return {
                init: function() {
                    handleValidation();
                }
            };
        }();
        $(document).ready(function() {
            FormValidationMd.init();
                    @if(Session::has('message'))
            var type = "{{Session::get('alert-type','info')}}"
            switch (type) {
                case 'success':
                    toastr.options.closeButton = true;
                    toastr.success("{{Session::get('message')}}", 'Calendario:');
                    break;
            }
                    @endif

            var currentMousePos = { //variable que guarda la posición del puntero del mause
                    x: -1,
                    y: -1
                };
            jQuery(document).on("mousemove", function (event) { //funcion que es llamada cuando el puntero del mause se mueve dentro del aplicativo
                currentMousePos.x = event.pageX; //se almacenan las cordenadas X Y
                currentMousePos.y = event.pageY;
            });

            var initDrag = function (el) {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim(el.text()),
                    stick: true,// use the element's text as the event title
                    color: '#25C279',
                };
                // store the Event Object in the DOM element so we can get to it later
                el.data('event', eventObject);
                // make the event draggable using jQuery UI
                el.draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            };

            var addEvent = function (title) { //cuando se añade una nueva notificación es llamada esta función para agregar el codigo html correspondiente
                title = title.length === 0 ? "Evento sin titulo" : title; //con el respectivo titulo creado o sin titulo en caso de que no se digite nada
                var html = $('<div class="external-event label label-default ui-draggable ui-draggable-handle"  >' + title + '</div>');
                jQuery('#event_box').append(html);
                initDrag(html);
            };

            $('#external-events div.external-event').each(function () { //se inicializa  el recordatorio arrastrable
                initDrag($(this));

            });


            $('#event_add').unbind('click').click(function () { // y se agrega el recordatorio a guardar
                var title = $('#NOTIF_Descripcion').val();
                addEvent(title);
            });

            $('#calendar').fullCalendar({ //se inicializa el calendario de la libreria full calendar

                events: function (start, end, timezone, callback) { //se realiza una llamada al controlador para traer tanto los eneventos como los redordatorios de la BD
                    var route = "{{ route('talento.humano.calendario.getEvent')}}"; //ruta que dirige al controlador para realizar la consulta a ala BD
                    $.ajax({ //se envian los datos correspondientes mediante ajax
                        url: route,
                        type: 'GET',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                        },
                        success: function (eventos) {//se reciben los eventos enviados desde el controlador
                            var events = JSON.parse(eventos); //se realiza la conversíon para poder ser implementados por la libreria
                            callback(events);//se cargan los datos recibidos en el calendario
                        }
                    });
                },

                eventReceive: function (event) { //esta función es llamada cuando se crea un recordatorio y es arrastardo al calendario
                    var title = event.title; //se gurada el titulo del recordatorio
                    var start = event.start.format("YYYY-MM-DD"); //y la fecha donde fue ubicado
                    var end = event.start.format("YYYY-MM-DD");
                    var route = "{{ route('talento.humano.calendario.storeNotification')}}";//ruta que dirige al controlador para almacenar los datos en la BD
                    $.ajax({
                        url: route,
                        data: 'type=new&title=' + title + '&startdate=' + start + '&endDate=' + end,//se envian los datos del recordatorio para ser almacenados
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        success: function (response) { //una vez guardados se recibe el id del recordatorio guardado
                            $('input[name="PK_NOTIF_Id_Notificacion"]').val(response); //se pone en un formulario para actualizar la fecha de notioficación
                            $('#modal-update-notify').modal('toggle');//se llama una ventana modal que contiene el formulario que recibe la fecha de notificación del recordatorio
                        },
                        error: function (e) {//en caso de que la petición falle se muestra el error por consola
                            console.log(e.responseText);
                        }
                    });
                    $('#calendar').fullCalendar('updateEvent', event);//se realiza la actulización del calendario
                },
                eventResize: function(event){//esta función es llamada cuando el usuario cambiia la fecha final del evento o recordatorio
                    var end   = event.end.format("YYYY-MM-DD"); //se toma la nueva fecha de finalización
                    var id    = event.id;//se guarda el id del evento o recordatorio
                    var eventType= event.type;
                    var route = "{{ route('talento.humano.calendario.updateDateNotification')}}";//ruta que conduce al controlador para actulizar el dato mencionado
                    $.ajax({
                        url: route,
                        data: 'type=endDateUpdate&endDate='+end+'&eventId='+id+'&eventType='+eventType,//se ennvian los datos nuevos
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        success: function(response){

                        },
                        error: function(e){
                            console.log(e.responseText); //en caso de error en la petición se carga el error en consola
                        }
                    });
                    $('#calendar').fullCalendar('updateEvent',event);//se actuliza el calendario
                },
                eventDrop: function(event, delta, revertFunc) { //esta función es llamada cuando es cambiado de fecha un evento o recordatorio
                    var title = event.title;
                    var start = event.start.format();//se guarda la nueva fecha
                    var end = (event.end == null) ? start : event.end.format();//si no tiene una fecha de fin diferente a la de inicio se tomarára la misma del inicio
                    var id    = event.id;//se alamacena el id del evento o recordatorio a actualizar
                    var eventType  =  event.type; //se guarda el tipo ya sea evento o recordatorio
                    var route = "{{ route('talento.humano.calendario.updateDateNotification')}}";//ruta para actualizar los datos
                    $.ajax({
                        url: route,
                        data: 'type=resetDate&startdate='+start+'&endDate='+end+'&eventId='+id+'&eventType='+eventType,//se envian los datos
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        success: function(response){ //se recibe el id y el tipo ya sea  evento o recordatorio
                            if(response.eventType == "Evento"){ //se es evento se muetsra el pop up correspondiente
                                $('input[name="PK_EVNT_IdEvento"]').val(response.id); //se pone en el formulario para actualizar la fecha de notificación
                                $('#modal-update-Event').modal('toggle');// se muestra el formulario en una ventana modal
                            }
                            if(response.eventType == "Recordatorio"){ //si es recordatorio se muestra el formulario correspondiente
                                $('input[name="PK_NOTIF_Id_Notificacion"]').val(response.id);//y se realiza el mismo proceso en caso de que sea evento
                                $('#modal-update-notify').modal('toggle');
                            }

                        },
                        error: function(e){
                            console.log(e.responseText);
                        }
                    });
                },
                eventClick: function (calEvent, jsEvent, view) {//esta funció es llamada cuando se hace clic ya sea sobre la notificación o el evento  paera actualizar datos
                    if (calEvent.type == "Recordatorio") {//si es recordatorio
                        $('input[name="PK_NOTIF_Id_Notificacion"]').val(calEvent.id); //se cargan los datos correspondientes
                        $('#eventDesc').val(calEvent.title);
                        $('input[name="NOTIF_Fecha_Notificacion"]').val(calEvent.notif);
                        $('#modal-update-titleNotify').modal('toggle'); //y se muestran en un formulario en una ventana modal y se realiza la respectiva actualización
                    }
                    if (calEvent.type == "Evento") {//si es evento se realiza de igual forma que en el recordatorio  solo que con un formulario diferente
                        $('input[name="PK_NOTIF_Id_Notificacion"]').val(calEvent.id);
                        $('#EVNT_Descripcion').val(calEvent.title);
                        $('input[name="EVNT_Hora"]').val(calEvent.hora);
                        $('input[name="EVNT_Fecha_Notificacion"]').val(calEvent.notif);
                        $('#modal-update-titleEvent').modal('toggle');
                    }
                },
                eventDragStop: function (event, jsEvent, ui, view) {//esta función es llamada en el momento que se arrastre un evento o recordatorio  para ser eliminado
                    /* var el = element.html();
                     element.html(el+'<div style="text-align:right;" class="closeE"><i style="color: #f9fffd;" class="icon-trash"></i></div>');
                     element.find('.closeE').click(function (e){
                         e.preventDefault();*/
                    if (isElemOverDiv()) { //se llama la función que determina la posición del puntero y si esta en el espacio de eliminación
                        var id = event.id; //se toma el id del evento a eliminar
                        var eventType = event.type;//y el tipo
                        var route = "{{ route('talento.humano.calendario.deleteNotification')}}";//ruta que conduce al controlador para realizar la respectiva eliminación
                        swal({
                                title: "¿Esta seguro?",
                                text: "Esta apunto de eliminar información del calendario!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "De acuerdo",
                                cancelButtonText: "Cancelar",
                                closeOnConfirm: true,
                                closeOnCancel: false
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    $.ajax({
                                        url: route,
                                        data: 'eventId=' + id + '&eventType=' + eventType,//se envian los datos a elimianr
                                        type: 'POST',
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        dataType: 'json',
                                        success: function (response, xhr, request) { //se muestra el mensaje de notificación
                                            if (request.status === 200 && xhr === 'success') {
                                                $('#calendar').fullCalendar('removeEvents', event.id);
                                                UIToastr.init(xhr, response.title, response.message);
                                            }
                                        },
                                        error: function (response, xhr, request) {
                                            if (request.status === 422 && xhr === 'success') {
                                                UIToastr.init(xhr, response.title, response.message);
                                            }
                                        }
                                    });
                                } else {
                                    swal("Cancelado", "No se eliminó ninguna información", "error");
                                }
                            });

                    }
                    // });
                },
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                lang: 'es',
                editable: true,
                droppable: true,
                eventLimit: false,
                eventRender: function (event, element) {
                    element.find('.fc-title').prepend("&nbsp;");
                    element.find('.fc-title').append("<br>" + "<br>");
                    element.tooltip({
                        title: event.title,
                    });
                    element.css('font-size', '0.9em');

                },

            });

            function isElemOverDiv() {//función que determina la posición del puntero del mause
                var trashEl = jQuery('#trash');//ubica el elimento que se señalizao para realizar la eliminación

                var ofs = trashEl.offset();

                var x1 = ofs.left; //se alamcena la ubicación del elemento señalizado
                var x2 = ofs.left + trashEl.outerWidth(true);
                var y1 = ofs.top;
                var y2 = ofs.top + trashEl.outerHeight(true);

                if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
                    currentMousePos.y >= y1 && currentMousePos.y <= y2) {//y si el mause esta ubicado sobre el elemento delimitado
                    return true; //permite la eliminación del evento o recordatorio arrastrado hasta allí
                }
            }
        });

    </script>
@endpush