@permission('ACAD_MANT')
@extends('material.layouts.dashboard')

@push('styles')
    {{--Select2--}}
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2material/css/pmd-select2.css') }}" rel="stylesheet"
          type="text/css"/>
    <!-- MODAL -->
    <link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet"
          type="text/css"/>
    <!-- DATATABLE  -->
    <link href="{{ asset('https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}"
          rel="stylesheet" type="text/css"/>
    {{--toast--}}
    <link href="{{asset('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css"/>
    {{--JQuery datatable and row details--}}
    <link href="{{ asset('assets/main/acadspace/css/rowdetails.css') }}" rel="stylesheet" type="text/css"/>
@endpush


@section('content')
    {{-- BEGIN HTML SAMPLE --}}
    <div class="col-md-12">
        @component('themes.bootstrap.elements.portlets.portlet', ['icon' => 'icon-info', 'title' => 'Gestión Mantenimiento'])
            <div class="clearfix">
            </div>
            <br>
            <br>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="actions">
                        @permission('ACAD_REGISTRAR_MANT')
                        <a class="btn btn-simple btn-success btn-icon create" data-toggle="modal">
                            <i class="fa fa-plus">
                            </i>
                            Registrar Mantenimiento
                        </a>
                        @endpermission
                    </div>
                </div>
            </div>
            <div class="clearfix">
            </div>
            <br>
            <div class="col-md-12">
                @permission('ACAD_CONSULTAR_MANT')
                @component('themes.bootstrap.elements.tables.datatables', ['id' => 'art-table-ajax',
                'class' => 'table table-striped table-bordered table-hover dt-responsive dataTable no-footer dtr-column collapsed'])
                    @slot('columns', [
                    'id_mantenimiento',
                    '',
                    '#',
                    'Placa del Articulo',
                    'Nombre tecnico',
                    'Estado',
                    'Acciones' => ['style' => 'width:70px;']
                    ])
                @endcomponent
                @endpermission
            </div>
            <div class="clearfix">
            </div>
            <div class="row">
                <div class="col-md-12">
                {{-- BEGIN HTML MODAL CREATE --}}
                <!-- responsive -->
                    <div class="modal fade" data-width="760" id="modal-create-mant" tabindex="-1">
                        <div class="modal-header modal-header-success">
                            <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                            </button>
                            <h2 class="modal-title">
                                <i class="glyphicon glyphicon-tv">
                                </i>
                                Registrar Mantenimiento
                            </h2>
                            <h5>
                                <i class="fa fa-warning"></i>    
                                Antes de intentar registrar un mantenimiento agregue por lo menos un artículo y un tipo de mantenimiento.
                            </h5>
                        </div>
                        <div class="modal-body">
                            {!! Form::open(['id' => 'form_mant', 'class' => '', 'url'=>'/forms']) !!}
                            <div class="row">
                                <div class="col-md-12">

                                    {!! Field:: text('nom_tecnico',null,
                                    ['label'=>'Nombre técnico:','class'=> 'form-control', 'autofocus','autocomplete'=>'off'],
                                    ['help' => 'Digite el nombre del técnico','icon'=>'fa fa-user'] ) !!}

                                    {!! Field:: select('Placa del artículo:',$articulos,
                                    ['id' => 'articulos', 'name' => 'articulos'])
                                    !!} 
                                    
                                    {!! Field::select('Tipo de mantenimiento:',$tipos, ['id' => 'mantenimiento',
                                    'name' => 'mantenimiento']) !!}

                                    {!! Field:: textarea('descripcion',null,
                                    ['label'=>'Descripción:','class'=> 'form-control', 'rows'=>'3', 'autofocus','autocomplete'=>'off'],
                                    ['help' => 'Descripción del por qué se realizara el mantenimiento','icon'=>'fa fa-user'] ) !!}

                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            @permission('ACAD_REGISTRAR_MANT')
                            {!! Form::submit('Guardar', ['class' => 'btn blue']) !!}
                            @endpermission
                            {!! Form::button('Cancelar', ['class' => 'btn red', 'data-dismiss' => 'modal' ]) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                    {{-- END HTML MODAL CREATE--}}
                </div>
                {{-- END HTML MODAL CREATE--}}
            </div>
        @endcomponent
    </div>
    {{-- END HTML SAMPLE --}}
@endsection

@push('plugins')
    {{--Selects--}}
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <!-- SCRIPT DATATABLE -->
    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/pages/scripts/table-datatables-responsive.min.js') }}"
            type="text/javascript"></script>
    <!-- SCRIPT MODAL -->
    <script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}"
            type="text/javascript"></script>
    <!-- SCRIPT Validacion Maxlength -->
    <script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"
            type="text/javascript">
    </script>
    <!-- SCRIPT Validacion Personalizadas -->
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/localization/messages_es.js') }}"
            type="text/javascript"></script>
    <!-- SCRIPT MENSAJES TOAST-->
    <script src="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
@endpush


@push('functions')
    <!--HANDLEBAR-->
    <script src="{{ asset('assets/main/acadspace/js/handlebars.js') }}"></script>
    <script src="{{ asset('assets/main/scripts/form-validation-md.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/main/scripts/form-validation-md.js') }}" type="text/javascript"></script>
    <!-- Estandar Mensajes -->
    <script src="{{ asset('assets/main/scripts/ui-toastr.js') }}" type="text/javascript"></script>
    <!-- Estandar Datatable -->
    <script src="{{ asset('assets/main/scripts/table-datatable.js') }}" type="text/javascript"></script>
    {{--ROW DETAILS DESPLEGABLE--}}
    <script id="details-template" type="text/x-handlebars-template">
        <table class="table">
            <tr>
                <td>Descripcion:</td>
                <td>@{{ MANT_Descripcion }}</td>
            </tr>
            <tr>
                <td>Fecha Inicio:</td>
                <td>@{{ MANT_Fecha_Inicio }}</td>
            </tr>
        </table>
    </script>
    <script>
        $(document).ready(function () {
            //inicializar select
            $.fn.select2.defaults.set("theme", "bootstrap");
            $(".pmd-select2").select2({
                placeholder: "Seleccionar",
                allowClear: true,
                width: 'auto',
                escapeMarkup: function (m) {
                    return m;
                }
            });
            var template = Handlebars.compile($("#details-template").html());
            var table, url, columns;
            //Define que tabla cargara los datos
            table = $('#art-table-ajax');
            url = "{{ route('espacios.academicos.mantenimiento.data') }}"; //url para cargar datos
            columns = [
                {data: 'PK_MANT_Id_Registro', name: 'id_mantenimiento', "visible": false},
                {
                    "className": 'details-control',
                    "orderable": false,
                    "searchable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {data: 'DT_Row_Index'},
                {   data: 'id_articulo',
                    name: 'Placa del articulo'

                },
                {   data: 'MANT_Nombre_Tecnico', 
                    name: 'Nombre Tecnico'
                },
                {   data:'estado',
                    name:'Estado'
                },
                {
                    defaultContent: '@permission('ACAD_ELIMINAR_MANT') <div class="btn-group pull-right"><button class="btn green btn-xs btn-outline dropdown-toggle" data-toggle="dropdown">Opciones<i class="fa fa-angle-down"></i></button><ul class="dropdown-menu pull-right"><li><a href="javascript:;" class="edit"><i class="fa fa-edit"></i> Ver/Asignar Diagnostico </a></li><li><a href="javascript:;" class="remove"><i class="fa fa-trash"></i> Eliminar</a></li></ul></div> @endpermission',
                    data: 'action',
                    name: 'action',
                    title: 'Acciones',
                    orderable: false,
                    searchable: false,
                    exportable: false,
                    printable: false,
                    render: null,
                    responsivePriority: 2
                }
            ];
            dataTableServer.init(table, url, columns);
            table = table.DataTable();

            /*ELIMINAR REGISTROS*/
            table.on('click', '.remove', function (e) {
                e.preventDefault();
                $tr = $(this).closest('tr');
                var dataTable = table.row($tr).data();
                var route = '{{ route('espacios.academicos.mantenimiento.destroy') }}' + '/' + dataTable.PK_MANT_Id_Registro;
                var type = 'DELETE';
                var async = async || false;
                $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                            UIToastr.init(xhr, response.title, response.message);
                        }
                    },
                    error: function (response, xhr, request) {
                        if (request.status === 422 && xhr === 'error') {
                            UIToastr.init(xhr, response.title, response.message);
                        }
                    }
                });
            });

            /*Inicio detalles desplegables*/
                $('#art-table-ajax tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                if (row.child.isShown()) {
                // This row is already open - close it
                    tr.removeClass('details');
                    row.child.hide();
                 } else {
                                // Open this row
                     tr.addClass('details');
                     row.child(template(row.data())).show();
                 }
                });
                        /*Fin detalles de solicitud*/

            /*ABRIR MODAL*/
            $(".create").on('click', function (e) {
                e.preventDefault();
                $('#modal-create-mant').modal('toggle');
            });

            /*EDITAR LA CATEGORIA*/
            table.on('click', '.edit', function(e) {
                e.preventDefault();
                $tr = $(this).closest('tr');
                var dataTable = table.row($tr).data(),
                route_edit = '{{ route('espacios.academicos.mantenimiento.editMantenimiento') }}'+'/'+dataTable.PK_MANT_Id_Registro;
                $(".content-ajax").load(route_edit);
             });

            /*CREAR MARCA CON VALIDACIONES*/
            var createPermissions = function () {
                return {
                    init: function () {
                        var route = '{{ route('espacios.academicos.mantenimiento.regisMantenimiento') }}';
                        var type = 'POST';                
                        var async = async || false;

                        var formData = new FormData();
                        formData.append('MANT_Nombre_Tecnico', $('input:text[name="nom_tecnico"]').val());                      
                        formData.append('ART_Codigo', $('select[name="articulos"]').val());
                        formData.append('MANT_Descripcion', $('textarea[name="descripcion"]').val());
                        formData.append('FK_MANT_Id_Tipo', $('select[name="mantenimiento"]').val());


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
                                    table.ajax.reload();
                                    $('#modal-create-mant').modal('hide');
                                    $('#form_mant')[0].reset(); //Limpia formulario
                                    UIToastr.init(xhr, response.title, response.message);
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

            var form_edit = $('#form_mant');
            var rules_edit = {
                nom_tecnico: {required: true, minlength:1, maxlength: 20},
                mantenimiento: {required: true},
                descripcion: {required: true, minlength:1, maxlength: 100}
            };
            FormValidationMd.init(form_edit, rules_edit, false, createPermissions());
        });

    </script>
@endpush
@endpermission