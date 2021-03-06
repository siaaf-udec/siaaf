    <div class="col-md-12">
        @component('themes.bootstrap.elements.portlets.portlet', ['icon' => 'icon-book-open', 'title' => 'Formulario de actualización de datos del personal'])
            @slot('actions', [
           'link_cancel' => [
               'link' => '',
               'icon' => 'fa fa-arrow-left',
           ],
       ])
            <div class="row">
                <div class="col-md-7 col-md-offset-2">
                {!! Form::model ($empleado, ['id'=>'form_empleado_update', 'url' => '/forms'])  !!}

                    <div class="form-body">

                        {!! Field::select('PRSN_Rol',['DOCENTE'=>'DOCENTE', 'ADMINISTRATIVO'=>'ADMINISTRATIVO'], null,['label'=>'Rol del empleado: Selecciona una opción', 'icon'=>'fa fa-user']) !!}


                        {!! Field:: text('PRSN_Nombres',null,['label'=>'Nombre(s)','class'=> 'form-control', 'autofocus', 'maxlength'=>'40','autocomplete'=>'off'],
                                                         ['help' => 'Digite el nombre del empleado.','icon'=>'fa fa-user']) !!}

                        {!! Field:: text('PRSN_Apellidos',null,['label'=>'Apellido(s):', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'40','autocomplete'=>'off'],
                                                         ['help' => 'Digite el apellido del empleado.','icon'=>'fa fa-user'] ) !!}

                        {!! Field:: text('PK_PRSN_Cedula',null,['label'=>'Cédula de ciudadanía:', 'class'=> 'form-control', 'autofocus', 'disabled', 'maxlength'=>'10','autocomplete'=>'off'],
                                                         ['help' => 'Digite la cédula del empleado.','icon'=>'fa fa-credit-card'] ) !!}

                        {!! Field:: email('PRSN_Correo',null,['label'=>'Correo electrónico:', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'60','autocomplete'=>'off'],
                                                         ['help' => 'Digite un correo válido.','icon'=>'fa fa-envelope-open '] ) !!}

                        {!! Field:: text('PRSN_Telefono',null,['label'=>'Teléfono:', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'30','autocomplete'=>'off'],
                                                         ['help' => 'Digite un número de teléfono o celular.','icon'=>'fa fa-phone'] ) !!}

                        {!! Field:: text('PRSN_Direccion',null,['label'=>'Dirección:', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'90','autocomplete'=>'off'],
                                                         ['help' => 'Digite la dirección de residencia.','icon'=>'fa fa-building-o'] ) !!}

                        {!! Field:: text('PRSN_Ciudad',null,['label'=>'Ciudad de residencia:', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'35','autocomplete'=>'off'],
                                                         ['help' => 'Digite la ciudad del empleado.','icon'=>'fa fa-map-o'] ) !!}

                        {!! Field:: text('PRSN_Salario',null,['label'=>'Salario del empleado:', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'40','autocomplete'=>'off'],
                                                        ['help' => 'Digite el salario del empleado.','icon'=>'fa fa-dollar'] ) !!}


                        {!! Field:: text('PRSN_Area',null,['label'=>'Área o programa de trabajo:', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'40','autocomplete'=>'on'],
                                                         ['help' => 'Digite el área o facultad del empleado.','icon'=>'fa fa-group'] ) !!}

                        {!! Field:: text('PRSN_Eps',null,['label'=>'EPS:', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'40','autocomplete'=>'off'],
                                                        ['help' => 'EPS (Es opcional).','icon'=>'fa fa-list-alt'] ) !!}

                        {!! Field:: text('PRSN_Fpensiones',null,['label'=>'Fondo de pensiones:', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'40','autocomplete'=>'off'],
                                                        ['help' => 'Fondo de pensiones (Es opcional).','icon'=>'fa fa-list-alt'] ) !!}

                        {!! Field:: text('PRSN_Caja_Compensacion',null,['label'=>'Caja de compensación:', 'class'=> 'form-control','autofocus', 'maxlength'=>'40','autocomplete'=>'off'],
                                                         ['help' => 'Caja de compensación (Es opcional).','icon'=>'fa fa-list-alt'] ) !!}

                        {!! Field::select('PRSN_Estado_Persona',['NUEVO'=>'NUEVO', 'ANTIGUO'=>'ANTIGUO', 'RETIRADO'=>'RETIRADO'],null,['label'=>'Estado del empleado: Selecciona una opción']) !!}

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12 col-md-offset-0">
                                    <a href="javascript:;" class="btn btn-outline red button-cancel"><i class="fa fa-angle-left"></i>
                                        Cancelar
                                    </a>
                                    {{ Form::submit('Editar', ['class' => 'btn blue']) }}
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
        @endcomponent
    </div>
<!-- Validation, Select y Toastr Scripts -->
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/main/scripts/form-validation-md.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/main/scripts/ui-toastr.js') }}" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function() {

    /*Configuracion de Select*/
    $.fn.select2.defaults.set("theme", "bootstrap");
    $(".pmd-select2").select2({
        placeholder: "Selecccionar",
        allowClear: true,
        width: 'auto',
        escapeMarkup: function (m) {
            return m;
        }
    });

    $('.pmd-select2', form).change(function () {
        form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
    });
    jQuery.validator.addMethod("letters", function(value, element) {
        return this.optional(element) || /^[a-z," " áéíóúñüàè]+$/i.test(value);
    });
    jQuery.validator.addMethod("noSpecialCharacters", function(value, element) {
        return this.optional(element) || /^[-a-z," ",$,0-9,.,#áéíóúñüàè]+$/i.test(value);
    });

    var createUsers = function () {
        return{
            init: function () {
                var route = '{{ route('talento.humano.empleado.update') }}';
                var type = 'POST';
                var async = async || false;

                var formData = new FormData();
                formData.append('PRSN_Nombres', $('input:text[name="PRSN_Nombres"]').val());
                formData.append('PRSN_Apellidos', $('input:text[name="PRSN_Apellidos"]').val());
                formData.append('PRSN_Correo', $('input[name="PRSN_Correo"]').val());
                formData.append('PK_PRSN_Cedula', $('input:text[name="PK_PRSN_Cedula"]').val());
                formData.append('PRSN_Telefono', $('input:text[name="PRSN_Telefono"]').val());
                formData.append('PRSN_Direccion', $('input:text[name="PRSN_Direccion"]').val());
                formData.append('PRSN_Ciudad', $('input:text[name="PRSN_Ciudad"]').val());
                formData.append('PRSN_Area', $('input:text[name="PRSN_Area"]').val());
                formData.append('PRSN_Salario', $('input:text[name="PRSN_Salario"]').val());
                formData.append('PRSN_Rol', $('select[name="PRSN_Rol"]').val());
                formData.append('PRSN_Estado_Persona', $('select[name="PRSN_Estado_Persona"]').val());
                formData.append('PRSN_Eps', $('input:text[name="PRSN_Eps"]').val());
                formData.append('PRSN_Fpensiones', $('input:text[name="PRSN_Fpensiones"]').val());
                formData.append('PRSN_Caja_Compensacion', $('input:text[name="PRSN_Caja_Compensacion"]').val());
                $.ajax({
                    url: route,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
                        if (request.status === 200 && xhr === 'success') {
                            $('#form_empleado_update')[0].reset(); //Limpia formulario
                            UIToastr.init(xhr , response.title , response.message  );
                            App.unblockUI('.portlet-form');
                            var route = '{{ route('talento.humano.empleado.index.ajax') }}';
                            $(".content-ajax").load(route);
                        }
                    },
                    error: function (response, xhr, request) {
                        if (request.status === 422 &&  xhr === 'error') {
                            UIToastr.init(xhr, response.title, response.message);
                        }
                    }
                });
            }
        }
    };
    var form = $('#form_empleado_update');
    var formRules = {
        PRSN_Nombres: {required: true, letters: true},
        PRSN_Apellidos: {required: true ,letters: true},
        PRSN_Correo: {required: true, email: true},
        PK_PRSN_Cedula: {required: true, number: true},
        PRSN_Telefono: {required: true, noSpecialCharacters:true},
        PRSN_Ciudad: {required: true, letters: true},
        PRSN_Area: {required: true, letters: true},
        PRSN_Salario: {required: true , noSpecialCharacters:true},
        PRSN_Rol: {required: true},
        PRSN_Estado_Persona: {required: true},
        PRSN_Eps: {letters: true},
        PRSN_Fpensiones: {letters: true},
        PRSN_Caja_Compensacion: {letters: true},
        PRSN_Direccion: {noSpecialCharacters: true}
    };
    var formMessage = {
        PRSN_Nombres: {letters: 'Solo se pueden ingresar letras'},
        PRSN_Apellidos: {letters: 'Solo se pueden ingresar letras'},
        PRSN_Ciudad: {letters: 'Solo se pueden ingresar letras'},
        PRSN_Area: {letters: 'Solo se pueden ingresar letras'},
        PRSN_Eps: {letters: 'Solo se pueden ingresar letras'},
        PRSN_Fpensiones: {letters: 'Solo se pueden ingresar letras'},
        PRSN_Caja_Compensacion: {letters: 'Solo se pueden ingresar letras'},
        PRSN_Salario: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
        PRSN_Telefono: {noSpecialCharacters: 'Existen caracteres que no son válidos'},
        PRSN_Direccion: {noSpecialCharacters: 'Existen caracteres que no son válidos'}
    };
    FormValidationMd.init(form,formRules,formMessage,createUsers());

    $('.button-cancel').on('click', function (e) {
        e.preventDefault();
        var route = '{{ route('talento.humano.empleado.index.ajax') }}';
        $(".content-ajax").load(route);
    });

   $( "#link_cancel" ).on('click', function () {
       var route = '{{ route('talento.humano.empleado.index.ajax') }}';
       $(".content-ajax").load(route);
   });


});

</script>
