
@component('themes.bootstrap.elements.portlets.portlet', ['icon' => 'icon-frame', 'title' => 'Gestión Tipo Artículo'])
    @slot('actions', [
      'link_cancel' => [
      'link' => '',
      'icon' => 'fa fa-arrow-left',
     ],
    ])
    <div class="row">
        <div class="col-md-12">
            <div class="modal fade" data-width="760" id="modal-create-tipo" tabindex="-1">
                <div class="modal-header modal-header-success">
                    <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                    </button>
                    <h2 class="modal-title">
                        <i class="glyphicon glyphicon-user">
                        </i>
                        Detalles Tipo Artículo
                    </h2>
                </div>
                <div class="modal-body">
                    {!! Form::open(['id' => 'from_art_tipo_create', 'class' => '', 'url' => '/forms']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! Field::text('TPART_Nombre',
                                   ['label' => 'Tipo Artículo:', 'max' => '15', 'min' => '2', 'required', 'auto' => 'off','tabindex'=>'1'],
                                   ['help' => 'Ingrese Tipo artículo ejemplo: Computador, Cable', 'icon' => 'fa fa-info'])
                               !!}
                        </div>
                        <div class="col-md-6">
                            {!! Field::select('TPART_Tiempo',
                                     [
                                2 => 'Asignado',
                                1 => 'Libre'
                             ],
                                ['label' => 'Seleccione una Opcion'])
                             !!}
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-8">
                                {!! Form::submit('CREAR', ['class' => 'btn blue']) !!}
                                {!! Form::button('CANCELAR', ['class' => 'btn red', 'data-dismiss' => 'modal' ]) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                </div>
            </div>
            {{-- END HTML MODAL CREATE--}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="modal fade" data-width="760" id="modal-edit-tipo" tabindex="-1">
                <div class="modal-header modal-header-success">
                    <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                    </button>
                    <h2 class="modal-title">
                        <i class="glyphicon glyphicon-user">
                        </i>
                        Detalles Tipo Artículo
                    </h2>
                </div>
                <div class="modal-body">
                    {!! Form::open(['id' => 'from_art_tipo_edit', 'class' => '', 'url' => '/forms']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! Field::text('TPART_Nombre_Edit',
                                   ['label' => 'Tipo Artículo:', 'max' => '15', 'min' => '2', 'required', 'auto' => 'off','tabindex'=>'1'],
                                   ['help' => 'Ingrese Tipo artículo ejemplo: Computador, Cable', 'icon' => 'fa fa-info'])
                               !!}
                        </div>
                        <div class="col-md-6">
                            {!! Field::select('TPART_Tiempo_Edit',
                                     [
                                2 => 'Asignado',
                                1 => 'Libre'
                             ],
                                ['label' => 'Seleccione una Opcion'])
                             !!}
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-8">
                                {!! Form::submit('MODIFICAR', ['class' => 'btn blue']) !!}
                                {!! Form::button('CANCELAR', ['class' => 'btn red', 'data-dismiss' => 'modal' ]) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="actions">
                <a class="btn btn-outline dark createTipoArticulo" data-toggle="modal">
                    <i class="fa fa-plus">
                    </i>
                    Crear Tipo Artículo
                </a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-md-12">
        @component('themes.bootstrap.elements.tables.datatables', ['id' => 'tipoArt-table-ajax'])
            @slot('columns', [
                '#' => ['style' => 'width:20px;'],
                'Tipo',
                'Cantidad Artículos',
                'Tiempo',
                'Acciones' => ['style' => 'width:90px;']
            ])
        @endcomponent
    </div>
    <div class="clearfix"></div>
@endcomponent
<script>
    var table, url, columns;
    var ComponentsSelect2 = function () {
        return {
            init: function () {
                $.fn.select2.defaults.set("theme", "bootstrap");
                $(".pmd-select2").select2({
                    placeholder: "Selecccionar",
                    allowClear: true,
                    width: 'auto',
                    escapeMarkup: function (m) {
                        return m;
                    }
                });
            }
        }
    }();
    var ComponentsBootstrapMaxlength = function () {
        var handleBootstrapMaxlength = function () {
            $("input[maxlength], textarea[maxlength]").maxlength({
                alwaysShow: true,
                appendToParent: true
            });
        }
        return {
            //main function to initiate the module
            init: function () {
                handleBootstrapMaxlength();
            }
        };
    }();
    $(document).ready(function () {
        var idTipoArticulo;
        ComponentsBootstrapMaxlength.init();
        ComponentsSelect2.init();
        table = $('#tipoArt-table-ajax');
        url ="{{ route('listarTipoArticulos.data') }}";
        columns = [
            {data: 'DT_Row_Index'},
            {data: 'TPART_Nombre' , name: 'Tipo'},
            {data: 'consultar_articulos_count' , name: 'Cantidad Artículos'},
            {data: 'Tiempo' , name: 'Tiempo'},
            {data: 'Acciones', name: 'Acciones'}
        ];
        dataTableServer.init(table, url, columns);
        table = table.DataTable();
        $('.createTipoArticulo').on('click',function(e){
            e.preventDefault();
            swal({
                    title :"INFORMACION",
                    text: "Al crear un nuevo tipo de artículo , tiene la" +
                    " opción de seleccionar un tiempo(item )",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Continuar",
                    cancelButtonText: "Consultar",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $('#modal-create-tipo').modal('toggle');

                    } else {
                        var route = '{{ route('audiovisuales.gestionArticulos.ValidacionesAjax') }}';
                        $(".content-ajax").load(route);
                    }
                });

        });
        table.on('click', '.edit', function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data();
            $('#TPART_Nombre_Edit').val(dataTable.TPART_Nombre);
            if(dataTable.consultar_articulos_count!=0){
                $("#TPART_Nombre_Edit").prop("disabled", true);
            }else{
                $("#TPART_Nombre_Edit").removeAttr('disabled');
            }
            idTipoArticulo = parseInt(dataTable.id);
            $('#modal-edit-tipo').modal('toggle');
        });
        table.on('click', '.remove', function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data();
            swal({
                    title: "Esta Seguro De eliminar?",
                    text: "Este Tipo De Artículo será eliminado!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "si",
                    cancelButtonText: "No",
                    closeOnConfirm: true
                },
                function(isConfirm){
                    if(isConfirm){
                        var route = '{{ route('tipoArticuloEliminarA') }}'+'/'+dataTable.id;
                        var type = 'POST';
                        var async = async || false;
                        $.ajax({
                            url: route,
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            cache: false,
                            type: type,
                            contentType: false,
                            processData: false,
                            async: async,
                            beforeSend: function () {

                            },
                            success: function (response, xhr, request) {
                                if (request.status === 200 && xhr === 'success') {
                                    table.ajax.reload();
                                    UIToastr.init(xhr , response.title , response.message  );
                                }
                            },
                            error: function (response, xhr, request) {
                                if (request.status === 422 &&  xhr === 'success') {
                                    UIToastr.init(xhr, response.title, response.message);
                                }
                            }
                        });
                    }
                });
        });
        var modificarTipo = function () {
            return{
                init: function () {
                    var route = '{{ route('audiovisuales.articulo.modificarTipo') }}';
                    var type = 'POST';
                    var async = async || false;

                    var formData = new FormData();
                    formData.append('id',idTipoArticulo);
                    formData.append('TPART_Nombre', $('input:text[name="TPART_Nombre_Edit"]').val());
                    formData.append('TPART_Tiempo', parseInt($('select[name="TPART_Tiempo_Edit"]').val()));
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
                            swal(
                                "Modificar!",
                                "los artículos que se encuentran en alguna solicitud ," +
                                " se actualizarán en la siguiente solicitud", "success"
                            );
                        },
                        success: function (response, xhr, request) {
                            if (request.status === 200 && xhr === 'success') {
                                $('#modal-edit-tipo').modal('hide');
                                UIToastr.init(xhr , response.title , response.message  );
                                $('#from_art_tipo_edit')[0].reset();
                                table.ajax.reload();
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
        var form_art_tipo_edit = $('#from_art_tipo_edit');
        var rules_art_tipo_create = {
            TPART_Nombre: {
                minlength: 3, required: true, remote: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('tipoArticulo.validar') }}",
                    type: "post"
                }
            },
            TPART_Tiempo: {required: true}

        };
        var messages = {
            TPART_Nombre: {
                remote: 'El Nombre de Tipo de Artículo ya está en uso.'
            }
        };

        FormValidationMd.init(form_art_tipo_edit, rules_art_tipo_create, messages,modificarTipo());
        var createTipoArticulo = function () {
            return {
                init: function () {
                    var route = '{{ route('tipoArticulos.store') }}';
                    var type = 'POST';
                    var async = async || false;
                    var formData = new FormData();
                    formData.append('TPART_Nombre', $('input:text[name="TPART_Nombre"]').val());
                    formData.append('TPART_Tiempo', $('select[name="TPART_Tiempo"]').val());
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

                        },
                        success: function (response, xhr, request) {
                            if (request.status === 200 && xhr === 'success') {
                                $('#modal-create-tipo').modal('hide');
                                $('#from_art_tipo_create')[0].reset(); //Limpia formulario
                                UIToastr.init(xhr, response.title, response.message);
                                table.ajax.reload();
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
        var form_art_tipo_create = $('#from_art_tipo_create');
        var rules_art_tipo_create = {
            TPART_Nombre: {
                minlength: 3, required: true, remote: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('tipoArticulo.validar') }}",
                    type: "post"
                }
            },
            TPART_Tiempo: {required: true}

        };
        var messages = {
            TPART_Nombre: {
                remote: 'El Nombre de Tipo de Articulo ya esta en uso.'
            }
        };

        FormValidationMd.init(form_art_tipo_create, rules_art_tipo_create, messages, createTipoArticulo());
        $("#from_art_tipo_create").validate({
            onkeyup: false
        });
        $('#link_cancel').on('click', function (e) {
            e.preventDefault();
            var route = '{{ route('audiovisuales.gestionArticulos.indexAjax') }}';
            $(".content-ajax").load(route);
        });
        swal("Tipo Artículo!", "Solo podrá ser elminado el " +
            "tipo del artículo , si este no posee mas de una cantidad de artículos");
    });

</script>