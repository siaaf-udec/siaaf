<div class="col-md-12">
    @component('themes.bootstrap.elements.portlets.portlet', ['icon' => 'icon-book-open', 'title' => 'Acta de inicio'])
        @slot('actions', [
              'link_cancel' => [
                  'link' => '',
                  'icon' => 'fa fa-arrow-left',
                               ],
               ])
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="alert alert-info">
                       <h4><strong>CMMI:</strong></h4>Este espacio está dedicado al envio de correos informativos para los
                        usuarios que aún tienen su vehículo dentro de las instalaciones sobre el cierre del parqueadero.
                </div>
            </div>
            <div class="col-md-10 col-md-offset-1">
                <div class="alert alert-info">
                       <h4><strong>SCRUM:</strong></h4>Roles Scrum que son necesarios para este proceso: <strong>Stakeholder= </strong>{{ $equipoScrum[2]['CE_Nombre_Persona'] }}, <strong>Product Owner= </strong>{{ $equipoScrum[1]['CE_Nombre_Persona'] }} y <strong>Scrum Master= </strong>{{ $equipoScrum[0]['CE_Nombre_Persona'] }}.
                </div>
            </div>
            <div class="col-md-10 col-md-offset-1">
                {!! Form::model ([[$idProceso],[$equipoScrum]],['id'=>'form_update_proyecto', 'url' => '/forms']) !!}
                    <div class="form-body">
                        <div class="row">
                        <h3>Informacion del proceso</h3><br>
                            <div class="col-md-6">

                                {!! Field:: hidden ('FK_CP_Id_Usuario', Auth::user()->id)!!}

                                {!! Field:: hidden ('PK_CP_Id_Proyecto', null)!!}

                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Numero de acta:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-circle'] ) !!}

                                {!! Field::date('CP_Fecha_Inicio',['label' => 'Fecha',  'class'=> 'form-control','auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date' => "+0d"],['help' => 'Digite la fecha de inicio del proyecto', 'icon' => 'fa fa-calendar']) !!}

                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Nombre de proyecto:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off',],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-text-o'] ) !!}

                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Tipo de proyecto:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-text-o'] ) !!}

                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Objetivos:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-text-o'] ) !!}
                            
                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Compromiso de obligatorio cumplimiento:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-text-o'] ) !!}

                            </div>
                            <div class="col-md-6">
                            
                                
                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Duracion en meses:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-text-o'] ) !!}

                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Entidades participantes:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-text-o'] ) !!}

                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Interesados:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-text-o'] ) !!}

                                {!! Field:: text('CP_Nombre_Proyecto',null,['label'=>'Fuentes de financiacion:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => 'Digite el nombre del proyecto.','icon'=>'fa fa-file-text-o'] ) !!}

                                                               
                            </div>
                        </div>
                        <div class="row">
                        <h3>Informacion de los roles Scrum</h3><br>
                            <div class="col-md-6">

                                {!! Field:: text('CE_Nombre_1',$equipoScrum[0]['CE_Nombre_Persona'],['label'=>'Scrum Master:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user'] ) !!}

                                {!! Field:: text('CE_Nombre_2',$equipoScrum[1]['CE_Nombre_Persona'],['label'=>'Product Owner:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user'] ) !!}
                                                            
                                {!! Field:: text('CE_Nombre_3',$equipoScrum[2]['CE_Nombre_Persona'],['label'=>'Stakeholder:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user'] ) !!}
                                
                                {!! Field:: text('CE_Nombre_4',$equipoScrum[3]['CE_Nombre_Persona'],['label'=>'Lider del Equipo Scrum:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-users'] ) !!}

                            </div>
                            <div class="col-md-6">
                           
                                {!! Field:: text('CE_Nombre_5',$equipoScrum[4]['CE_Nombre_Persona'],['label'=>'Integrante uno del equipo:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user-o'] ) !!}

                                {!! Field:: text('CE_Nombre_6',$equipoScrum[5]['CE_Nombre_Persona'],['label'=>'Integrante dos del equipo:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user-o'] ) !!}

                                @if(empty($equipoScrum[6]['CE_Nombre_Persona']))
                                    {!! Field:: text('CE_Nombre_7',null,['label'=>'Integrante tres del equipo:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user-o'] ) !!}
                                @else
                                    {!! Field:: text('CE_Nombre_7',$equipoScrum[6]['CE_Nombre_Persona'],['label'=>'Integrante tres del equipo:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user-o'] ) !!}
                                @endif
                                @if(empty($equipoScrum[7]))
                                    {!! Field:: text('CE_Nombre_8',null,['label'=>'Integrante cuatro del equipo:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user-o'] ) !!}  
                                @else
                                    {!! Field:: text('CE_Nombre_8',$equipoScrum[7]['CE_Nombre_Persona'],['label'=>'Integrante cuatro del equipo:', 'class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                                            ['help' => '','icon'=>'fa fa-user-o'] ) !!}                            
                                @endif
                           
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12 col-md-offset-4">
                                @permission('CALIDADPCS_CREATE_PROJECT')<a href="javascript:;"
                                                            class="btn btn-outline red button-cancel"><i
                                            class="fa fa-angle-left"></i>
                                    Cancelar
                                </a>
                                {{ Form::submit('Guardar', ['class' => 'btn blue']) }}
                                
                                @endpermission
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}

            </div>
        </div>

    @endcomponent
</div>

<!-- file script -->
    <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src = "{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type = "text/javascript" > </script>
    <script src="{{ asset('assets/main/scripts/form-validation-md.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/main/scripts/ui-toastr.js') }}" type="text/javascript"></script>
    <script type="text/javascript">

    jQuery(document).ready(function () {

        /*Configuracion de input tipo fecha*/
        $('.datepicker').datepicker({
            //rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            language: 'es',
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'yyyy-mm-dd',
            firstDay: 1,
            showMonthAfterYear: false,
            yearSuffix: ''
        });
        /*FIN Configuracion de input tipo fecha*/
        jQuery.validator.addMethod("letters", function(value, element) {
            return this.optional(element) || /^[a-z," "]+$/i.test(value);
        });
        jQuery.validator.addMethod("noSpecialCharacters", function(value, element) {
            return this.optional(element) || /^[-a-z," ",$,0-9,.,#]+$/i.test(value);
        });
        var editarProyecto = function () {
            return {
                init: function () {
                    var route = '{{ route('calidadpcs.procesosCalidad.storeProceso1') }}';
                    var type = 'POST';
                    var async = async || false;
                    var formData = new FormData();
                    //var FileMoto = document.getElementById("CM_UrlFoto");
              
                    // var FileProp = document.getElementById("CM_UrlPropiedad");
                    // var FileSOAT = document.getElementById("CM_UrlSoat");
                    /*Tabla Usuarios
                    formData.append('PK_CU_Id_Usuario', $('input:hidden[name="PK_CU_Id_Usuario"]').val());
                    formData.append('CU_Cedula', $('input:hidden[name="CU_Cedula"]').val());
                    formData.append('CU_Nombre', $('input:hidden[name="CU_Nombre"]').val());
                    formData.append('CU_Apellido', $('input:hidden[name="CU_Apellido"]').val());
                    formData.append('CU_Telefono', $('input:hidden[name="CU_Telefono"]').val());
                    formData.append('CU_Correo', $('input:hidden[name="CU_Correo"]').val());*/
                    //Tabla Proyectos
                    //formData.append('PK_CP_Id_Proyecto', $('input:hidden[name="PK_CP_Id_Proyecto"]').val());
                    //formData.append('CP_Nombre_Proyecto', $('input:text[name="CP_Nombre_Proyecto"]').val());
                    //formData.append('CP_Fecha_Inicio', $('#CP_Fecha_Inicio').val());
                    //formData.append('CP_Fecha_Final', $('#CP_Fecha_Final').val());
                    //formData.append('PK_CP_Id_Usuario', $('input:hidden[name="PK_CP_Id_Usuario"]').val());
                    //formData.append('FK_CP_Id_Usuario', $('input:hidden[name="FK_CP_Id_Usuario"]').val());
                    //Tabla Equipo Scrum

                    formData.append('FK_CPP_Id_Proceso', $('input:hidden[name="FK_CPP_Id_Proceso"]').val());
                    formData.append('CE_Nombre_1', $('input:text[name="CE_Nombre_1"]').val());
                    formData.append('CE_Nombre_2', $('input:text[name="CE_Nombre_2"]').val());
                    formData.append('CE_Nombre_3', $('input:text[name="CE_Nombre_3"]').val());
                    formData.append('CE_Nombre_4', $('input:text[name="CE_Nombre_4"]').val());

                    //formData.append('CE_Nombre_5', $('input:text[name="CE_Nombre_5"]').val());
                    //formData.append('CE_Nombre_6', $('input:text[name="CE_Nombre_6"]').val());
                    //formData.append('CE_Nombre_7', $('input:text[name="CE_Nombre_7"]').val());
                    //formData.append('CE_Nombre_8', $('input:text[name="CE_Nombre_8"]').val());
                   
                    $.ajax({
                        url: route,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        cache: false,
                        type: type,
                        contentType: false,
                        data: formData,
                        processData: false,
                        async: async,
                        beforeSend: function () {
                            App.blockUI({target: '.portlet-form', animate: true});
                        },
                        success: function (response, xhr, request) {
                            console.log(response);
                            if (request.status === 200 && xhr === 'success') {
                                $('#form_update_proyecto')[0].reset(); //Limpia formulario
                                UIToastr.init(xhr, response.title, response.message);
                                App.unblockUI('.portlet-form');
                                var route = '{{ route('calidadpcs.proyectosCalidad.index.ajax') }}';
                                location.href="{{route('calidadpcs.proyectosCalidad.index')}}";
                                //$(".content-ajax").load(route);
                            }
                        },
                        error: function (response, xhr, request) {
                            if (request.status === 422 && xhr === 'error') {
                                UIToastr.init(xhr, response.title, response.message);
                            }
                        }
                        
                    });
                }
            }
        };
        var form = $('#form_update_proyecto');
        var formRules = {
            //CM_UrlFoto: {required: false, extension: "jpg|png"},
            CP_Nombre_Proyecto: {minlength: 3, maxlength: 50, required: true, noSpecialCharacters:true, letters:true},
            CP_Fecha_Inicio: {required: true, minlength: 3, maxlength: 20},
            CP_Fecha_Final: {required: true, minlength: 3, maxlength: 20},
            CE_Nombre_1: {minlength: 3, maxlength: 50, required: true, noSpecialCharacters:true, letters:true},
            CE_Nombre_2: {minlength: 3, maxlength: 50, required: true, noSpecialCharacters:true, letters:true},
            CE_Nombre_3: {minlength: 3, maxlength: 50, required: true, noSpecialCharacters:true, letters:true},
            CE_Nombre_4: {minlength: 3, maxlength: 50, required: true, noSpecialCharacters:true, letters:true},
            CE_Nombre_5: {minlength: 3, maxlength: 50, required: true, noSpecialCharacters:true, letters:true},
            CE_Nombre_6: {minlength: 3, maxlength: 50, required: true, noSpecialCharacters:true, letters:true},
            CE_Nombre_7: {maxlength: 50, required: false, noSpecialCharacters:true, letters:true},
            CE_Nombre_8: {maxlength: 50, required: false, noSpecialCharacters:true, letters:true},
            // CM_NuSoat: {required: true, minlength: 5, maxlength: 20, noSpecialCharacters:true},
            // CM_FechaSoat: {required: true},
            // CM_UrlPropiedad: {required: false, extension: "jpg|png"},
            // CM_UrlSoat: {required: false, extension: "jpg|png"},
        };
        var formMessage = {
            CP_Nombre_Proyecto: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CP_Nombre_Proyecto: {letters: 'Los numeros no son válidos'},
            CE_Nombre_1: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CE_Nombre_1: {letters: 'Los numeros no son válidos'},
            CE_Nombre_2: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CE_Nombre_2: {letters: 'Los numeros no son válidos'},
            CE_Nombre_3: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CE_Nombre_3: {letters: 'Los numeros no son válidos'},
            CE_Nombre_4: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CE_Nombre_4: {letters: 'Los numeros no son válidos'},
            CE_Nombre_5: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CE_Nombre_5: {letters: 'Los numeros no son válidos'},
            CE_Nombre_6: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CE_Nombre_6: {letters: 'Los numeros no son válidos'},
            CE_Nombre_7: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CE_Nombre_7: {letters: 'Los numeros no son válidos'},
            CE_Nombre_8: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            CE_Nombre_8: {letters: 'Los numeros no son válidos'},
            //CP_fecha_inicio: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            //CP_fecha_final: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
            // CM_NuSoat: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
        };
        FormValidationMd.init(form, formRules, formMessage, editarProyecto());

        $('.button-cancel').on('click', function (e) {
            e.preventDefault();
            var route = '{{ route('calidadpcs.proyectosCalidad.index.ajax') }}';
            location.href="{{route('calidadpcs.proyectosCalidad.index')}}";
            //$(".content-ajax").load(route);
        });

        $("#link_cancel").on('click', function (e) {
            var route = '{{ route('calidadpcs.proyectosCalidad.index.ajax') }}';
            location.href="{{route('calidadpcs.proyectosCalidad.index')}}";
            //$(".content-ajax").load(route);
        });
        /*
         $(".create").on('click', function (e) {
            e.preventDefault();
            var codigo=  $('input:text[name="FK_CP_Codigo"]').val();
            var route = '{{ route('parqueadero.motosCarpark.RegistrarMoto2') }}' + '/' + codigo;
            $(".content-ajax").load(route);
        });*/

    });
</script>