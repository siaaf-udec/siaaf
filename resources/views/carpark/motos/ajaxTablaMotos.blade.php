<div class="col-md-12">
    @component('themes.bootstrap.elements.portlets.portlet', ['icon' => 'fa fa-tasks', 'title' => 'Vehículos Registrados:'])
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="actions">
                    @permission('PARK_REPORT_MOTO')<a href="javascript:;"
                                                   class="btn btn-simple btn-success btn-icon reports"
                                                   title="Reporte"><i class="glyphicon glyphicon-list-alt"></i>Reporte
                        de Motos</a><br>@endpermission
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-12">
                @component('themes.bootstrap.elements.tables.datatables', ['id' => 'listaMotos'])
                    @slot('columns', [
                        '#',
                        'Placa',
                        'Marca',
                        'Documento Propietario',
                        'Perfil',
                        'Acciones'
                    ])
                @endcomponent
            </div>
        </div>
    @endcomponent
</div>

<script src="{{ asset('assets/main/scripts/ui-toastr.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/main/scripts/table-datatable.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

        var table, url, columns;
        table = $('#listaMotos');
        url = "{{ route('parqueadero.motosCarpark.tablaMotos')}}";
        columns = [
            {data: 'DT_Row_Index'},
            {data: 'CM_Placa', name: 'CM_Placa'},
            {data: 'CM_Marca', name: 'CM_Marca'},
            {data: 'FK_CM_CodigoUser', name: 'FK_CM_CodigoUser'},
            {
                defaultContent: '@permission('PARK_SEE_MOTO')<a href="javascript:;" class="btn btn-success verPerfil"  title="Perfil" ><i class="fa fa-address-card"></i></a>@endpermission',
                data: 'action',
                name: 'Perfil',
                title: 'Perfil',
                orderable: false,
                searchable: false,
                exportable: false,
                printable: false,
                className: 'text-center',
                render: null,
                serverSide: false,
                responsivePriority: 2
            },
            {
                defaultContent: '@permission('PARK_REPORT_MOTO')<a href="javascript:;" class="btn btn-success reporte"  title="Reporte" ><i class="fa fa-table"></i></a>@endpermission @permission('PARK_UPDATE_MOTO') <a href="javascript:;" title="Editar" class="btn btn-primary edit" ><i class="icon-pencil"></i></a>@endpermission @permission('PARK_DELETE_MOTO')<a href="javascript:;" title="Eliminar" class="btn btn-simple btn-danger btn-icon remove"><i class="icon-trash"></i></a>@endpermission',
                data: 'action',
                name: 'action',
                title: 'Acciones',
                orderable: false,
                searchable: false,
                exportable: false,
                printable: false,
                className: 'text-center',
                render: null,
                serverSide: false,
                responsivePriority: 2
            }
        ];
        dataTableServer.init(table, url, columns);
        table = table.DataTable();

        table.on('click', '.remove', function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data();
            var route = '{{ route('parqueadero.motosCarpark.destroy') }}' + '/' + dataTable.PK_CM_IdMoto;
            var type = 'DELETE';
            var async = async || false;
            swal({
                    title: "¿Está seguro?",
                    text: "¿Está seguro de eliminar el usuario seleccionado?",
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
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            cache: false,
                            type: type,
                            contentType: false,
                            processData: false,
                            async: async,
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
                    } else {
                        swal("Cancelado", "No se eliminó ningun usuario", "error");
                    }
                });

        });

        table.on('click', '.verPerfil', function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data(),
                route_edit = '{{ route('parqueadero.motosCarpark.verMoto') }}' + '/' + dataTable.PK_CM_IdMoto;
            $(".content-ajax").load(route_edit);
        });

        table.on('click', '.RegistrarMoto', function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data(),
                route_edit = '{{ route('parqueadero.motosCarpark.RegistrarMoto') }}' + '/' + dataTable.FK_CM_CodigoUser;
            $(".content-ajax").load(route_edit);
        });

        table.on('click', '.edit', function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data(),
                route_edit = '{{ route('parqueadero.motosCarpark.editar') }}' + '/' + dataTable.PK_CM_IdMoto;
            $(".content-ajax").load(route_edit);
        });

        table.on('click', '.reporte', function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data();
            $.ajax({}).done(function () {
                window.open('{{ route('parqueadero.reportesCarpark.reporteMoto') }}' + '/' + dataTable.PK_CM_IdMoto, '_blank');
            });
        });

        $(".reports").on('click', function (e) {
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data();
            $.ajax({}).done(function () {
                window.open('{{ route('parqueadero.reportesCarpark.reporteMotosRegistradas') }}', '_blank');
            });
        });
    });
</script>