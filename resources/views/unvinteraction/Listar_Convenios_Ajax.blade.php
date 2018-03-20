 @component('themes.bootstrap.elements.portlets.portlet', ['icon' => 'icon-list', 'title' => 'LISTAR CONVENIOS']) 
                @permission(['Add_Convenio'])
                    <div class="col-md-12">
                        <div class="actions">
                            <a id="archivo3" href="javascript:;" class="btn btn-simple btn-success btn-icon create" title="Agregar un convenio"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                @endpermission
                <div class="row">
                    <div class="clearfix"> </div><br><br>
                    <div class="col-md-12">
                        @component('themes.bootstrap.elements.tables.datatables', ['id' => 'Listar_Convenios']) @slot('columns', 
                            [ '#' => ['style' => 'width:20px;'], 
                            'ID', 
                            'Nombre',
                            'Fecha de inicio',
                            'Fecha finalizacion',
                            'Estado',
                            'Sede',
                            'Acciones' => ['style' => 'width:160px;'] ])
                        @endcomponent
                    </div>
                </div>
@endcomponent
<!-- Modal agregar convenio -->
<div class="col-md-12">
    <!-- Modal -->
    <div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-header-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h1><i class="glyphicon glyphicon-thumbs-up"></i> AGREGAR EMPRESA</h1>
                </div>
                <div class="modal-body">

                    {!! Form::open(['url' => '/forms','enctype'=>'multipart/form-data','id'=>'form-Agregar-Convenio']) !!}
                    <div class="form-wizard">
                        {!! Field:: text('CVNO_Nombre',['label'=>'nombre del convenio', 'class'=> 'form-control', 'autofocus', 'maxlength'=>'40','autocomplete'=>'off'],['help' => 'Nombre de convenio','icon'=>'fa fa-line-chart'] ) !!} 
                        
                        {!! Field::date('CVNO_Fecha_Inicio',['label'=>'Fecha Inicio','required', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date'=> "+0d"],['help' => 'Digita tu dirección web.', 'icon' => 'fa fa-calendar']) !!}
                        
                        {!! Field::date('CVNO_Fecha_Fin',['label'=>'Fecha Final','required', 'auto' => 'off', 'data-date-format' => "yyyy-mm-dd", 'data-date-start-date'=> "+0d"],['help' => 'Digita tu dirección web.', 'icon' => 'fa fa-calendar']) !!}
                        
                       
                        {!! Field::select('FK_TBL_Sede_Id',$Sede,[ 'label' => 'Selecciona una sede'])!!}
                       
                        <div class="form-actions">
                            <div class="row">
                                <div class="modal-footer">
                                    {!! Form::submit('Agregar', ['class' => 'btn blue']) !!} {!! Form::button('Cancelar', ['class' => 'btn red', 'data-dismiss' => 'modal' ]) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ComponentsDateTimePickers = function () {
            var handleDatePickers = function () {
                if (jQuery().datepicker) {
                    $('.date-picker').datepicker({
                        rtl: App.isRTL(),
                        orientation: "left",
                        autoclose: true,
                        regional: 'es',
                        closeText: 'Cerrar',
                        prevText: '<Ant',
                        nextText: 'Sig>',
                        currentText: 'Hoy',
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                        weekHeader: 'Sm',
                        dateFormat: 'yyyy-mm-dd',
                        firstDay: 1,
                        yearSuffix: '',
                        startDate: null,
                       
                    });
                }
            }
            return {
                init: function () {
                    handleDatePickers();
                }
            };
        }();
    var ComponentsSelect2 = function() {
        var handleSelect = function() {
            $.fn.select2.defaults.set("theme", "bootstrap");
            $(".pmd-select2").select2({
                width: null,
                placeholder: "Selecccionar",
            });
        }
        return {
            init: function() {
                handleSelect();
            }
        };

    }();
    jQuery(document).ready(function() {
        ComponentsDateTimePickers.init();
        ComponentsSelect2.init();
        var form = $('#form-Agregar-Convenio');
        var wizard = $('#form_wizard_1');
        var rules = {
            CVNO_Nombre: {required: true},
            CVNO_Fecha_Inicio: {required: true},
            CVNO_Fecha_Fin: {required: true},
            FK_TBL_Sede_Id: {required: true}
            
        };
        var crearConvenio = function() {
            return {
                init: function() {
                    var route = '{{ route('registroConvenios.registroConvenios') }}';
                    var type = 'POST';
                    var async = async || false;

                    var formData = new FormData();
                    formData.append('CVNO_Nombre', $('#CVNO_Nombre').val());
                    formData.append('CVNO_Fecha_Inicio', $('#CVNO_Fecha_Inicio').val());
                    formData.append('CVNO_Fecha_Fin', $('#CVNO_Fecha_Fin').val());
                    formData.append('FK_TBL_Sede_Id', $('select[name="FK_TBL_Sede_Id"]').val());
                    $.ajax({
                        url: route,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        type: type,
                        contentType: false,
                        data: formData,
                        processData: false,
                        async: async,
                        success: function(response, xhr, request) {
                            if (request.status === 200 && xhr === 'success') {
                                $('#agregar').modal('hide');
                                $('#form-Agregar-Convenio')[0].reset();
                                table.ajax.reload();
                                UIToastr.init(xhr, response.title, response.message);
                            }
                        },
                        error: function(response, xhr, request) {
                            if (request.status === 422 && xhr === 'success') {
                                UIToastr.init(xhr, response.title, response.message);
                            }
                        }
                    });
                }
            }
        };
        var messages = {};
        FormValidationMd.init(form, rules, false, crearConvenio());


        var table, url;
        table = $('#Listar_Convenios');
        url = "{{ route('listarConvenios.listarConvenios') }}";
        table.DataTable({
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "Todo"]
            ],
            responsive: true,
            colReorder: true,
            processing: true,
            serverSide: true,
            ajax: url,
            searching: true,
            language: {
                "sProcessing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span class="sr-only">Procesando...</span>',
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            columns: [{
                    data: 'DT_Row_Index'
                },
                {
                    data: 'PK_CVNO_Convenio',
                    "visible": true,
                    name: "documento"
                },
                {
                    data: 'CVNO_Nombre',
                    searchable: true
                },
                {
                    data: 'CVNO_Fecha_Inicio',
                    searchable: true
                },
                {
                    data: 'CVNO_Fecha_Fin',
                    searchable: true
                },
                {
                    data: 'convenio_estado.ETAD_Estado',
                    searchable: true
                },
                {
                    data: 'convenio_sede.SEDE_Sede',
                    searchable: true
                },
                {
                    data: 'action',
                    className: '',
                    searchable: false,
                    name: 'action',
                    title: 'Acciones',
                    orderable: false,
                    exportable: false,
                    printable: false,
                    defaultContent: '@permission(['Edit_Convenio'])<a href="#" id="editar" title="Editar Convenio" class="btn btn-simple btn-warning btn-icon edit"><i class="icon-pencil"></i></a>@endpermission @permission(['Ver_dato_con'])<a href="#" id="ver" title="Documentos e informacion del Convenio" class="btn btn-simple btn-success btn-icon editar2"><i class="icon-notebook"></i></a> @endpermission'


                }

            ],
            buttons: [{
                    extend: 'print',
                    className: 'btn btn-circle btn-icon-only btn-default tooltips t-print',
                    text: '<i class="fa fa-print"></i>'
                },
                {
                    extend: 'copy',
                    className: 'btn btn-circle btn-icon-only btn-default tooltips t-copy',
                    text: '<i class="fa fa-files-o"></i>'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-circle btn-icon-only btn-default tooltips t-pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                },
                {
                    extend: 'excel',
                    className: 'btn btn-circle btn-icon-only btn-default tooltips t-excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                },
                {
                    extend: 'csv',
                    className: 'btn btn-circle btn-icon-only btn-default tooltips t-csv',
                    text: '<i class="fa fa-file-text-o"></i>',
                },
                {
                    extend: 'colvis',
                    className: 'btn btn-circle btn-icon-only btn-default tooltips t-colvis',
                    text: '<i class="fa fa-bars"></i>'
                },
                {
                    text: '<i class="fa fa-refresh"></i>',
                    className: 'btn btn-circle btn-icon-only btn-default tooltips t-refresh',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ],
            pageLength: 10,
            dom: "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });

        $("#archivo3").on('click', function(e) {
            e.preventDefault();
            $('#agregar').modal('toggle');
        });

        table = table.DataTable();
        table.on('click', '.edit', function(e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data(),
                route_edit = '/siaaf/public/index.php/interaccion-universitaria/editarConvenios/' + dataTable.PK_CVNO_Convenio;

            $(".content-ajax").load(route_edit);
        });
        table.on('click', '.editar2', function(e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data(),
                route_edit = '/siaaf/public/index.php/interaccion-universitaria/documentosConvenios/' + dataTable.PK_CVNO_Convenio;

            $(".content-ajax").load(route_edit);
        });

    });

</script>
</script>
