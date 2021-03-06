@extends('material.layouts.dashboard') @push('styles')
<!-- Datatables Styles -->
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2material/css/pmd-select2.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/dropzone/basic.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endpush 
@section('title', '| Lista de Sedes') 
@section('page-title', 'Lista de Sedes') 
@section('page-description', 'Sedes registradas')
@section('content')
@component('themes.bootstrap.elements.portlets.portlet', ['icon' => 'icon-list', 'title' => 'LISTAR SEDES'])
<ul class="nav nav-tabs">
    <li class="active">
        <a href="#tab_1_1" data-toggle="tab"> SEDES </a>
    </li>
    <li>
        <a href="#tab_1_2" data-toggle="tab"> SEDES ELIMINADAS </a>
    </li>

</ul>
<div class="tab-content">
    <div class="tab-pane fade active in" id="tab_1_1">
        <div class="col-md-12">
            <div class="actions">
                <a id="abrir" href="javascript:;" class="btn btn-simple btn-success btn-icon create"><i class="fa fa-plus" title="Agregar Sede"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="clearfix"> </div><br><br>
            <div class="col-md-12">
                @component('themes.bootstrap.elements.tables.datatables', ['id' => 'Listar_Convenios'])
                @slot('columns', [ 
                    '#' => ['style' => 'width:20px;'],
                    'Codigo',
                    'Nombre',
                    'Acciones' => ['style' => 'width:160px;'] ]) 
                @endcomponent
            </div>
        </div>
    </div>
    <div class="tab-pane fade " id="tab_1_2">
        <div class="row">
            <div class="clearfix"> </div><br><br><br><br>
            <div class="col-md-12">
                @component('themes.bootstrap.elements.tables.datatables', ['id' => 'Listar_Convenios2'])
                @slot('columns', [ 
                    '#' => ['style' => 'width:20px;'],
                    'Codigo',
                    'Nombre',
                    'Acciones' => ['style' => 'width:160px;'] ]) 
                @endcomponent
            </div>
        </div>
    </div>
</div>
<!-- AGREGAR SEDE -->
<div class="col-md-12">
    <!-- Modal -->
    <div class="modal fade" id="sede" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-header-success">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h1><i class="glyphicon glyphicon-thumbs-up"></i> AGREGAR SEDE</h1>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => '/forms','enctype'=>'multipart/form-data','id'=>'form-Agregar-Sede']) !!}
                    <div class="form-wizard">
                        {!! Field:: text('SEDE_Sede',null,['label'=>'Nombre','class'=> 'form-control', 'autofocus','required', 'maxlength'=>'40','autocomplete'=>'off'],['help' => 'Digita el nombre de la sede.','icon'=>'fa fa-industry']) !!}
                    </div>
                    <div class="modal-footer">
                        {!! Form::submit('Agregar', ['class' => 'btn blue']) !!} {!! Form::button('Cancelar', ['class' => 'btn red', 'data-dismiss' => 'modal' ]) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- FIN MODALS -->
@endcomponent
@endsection



@push('plugins')
<!-- Datatables Plugins -->
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>

<!-- Validation Plugins -->
<script src="{{asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/localization/messages_es.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
<!-- Utoastr Plugins -->
<script src="{{ asset('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
@endpush
<script src="{{ asset('assets/main/scripts/form-validation-md.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/main/scripts/ui-toastr.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/main/scripts/table-datatable.js') }}" type="text/javascript"></script>
@push('functions')
<script>
jQuery(document).ready(function () {
    App.unblockUI('.portlet-form');
    var table, url, columns;
        table = $('#Listar_Convenios');
        url = "{{ route('listarSedes.listarSedes') }}";
        columns = [
            {data: 'DT_Row_Index'},
           {data: 'PK_SEDE_Sede', "visible": true, name:"PK_SEDE_Sede" },
           {data: 'SEDE_Sede', searchable: true, name:"SEDE_Sede"},
           {data: 'action',
            name: 'action',
            title: 'Acciones',
            orderable: false,
            searchable: false,
            exportable: false,
            printable: false,
            className: 'text-center',
            render: null,
            serverSide: false,
            responsivePriority: 2,
            defaultContent: '<a href="#" class="btn btn-simple btn-warning btn-icon editar" title="Editar Empresa"><i class="icon-pencil"></i></a><a href="#" target="_blank" class="btn btn-simple btn-danger btn-icon delete" title="eliminar"><i class="icon-close"></i></a>'
           }
        ];
        dataTableServer.init(table, url, columns);
        table.on('click', '.delete', function(e) {
                e.preventDefault();
                table = $('#Listar_Convenios').DataTable();
				$tr = $(this).closest('tr');
				var o = table.row($tr).data();
				var route = '{{route('eliminarSedes.eliminarSedes')}}/'+o.PK_SEDE_Sede;
				var type = 'DELETE';
				var async = async || false;
				swal({
					title: "¿Esta seguro?",
                    text: "¿Esta seguro de eliminar la sede seleccionada?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "De acuerdo",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
					 function(isConfirm){
					if (isConfirm) {
						$.ajax({
							url: route,
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            cache: false,
                            type: type,
                            contentType: false,
                            processData: false,
                            async: async,
                            success: function (response, xhr, request) {
								if (request.status === 200 && xhr === 'success') {
									table.ajax.reload();
                                    table1 = $('#Listar_Convenios2').DataTable();
                                    table1.ajax.reload();
                                    UIToastr.init(xhr, response.title, response.message);                                    
                                }
                            },
                            error: function (response, xhr, request) {
                                if (request.status === 422 &&  xhr === 'error') {
                                    UIToastr.init(xhr, response.title, response.message);
                                }
                            }
						});
						swal.close();
					} else {
                        swal("Cancelado", "No se eliminó ninguna sede", "error");
                    }
                });
            });
    $("#abrir").on('click', function (e) {
            e.preventDefault();
            $('#sede').modal('toggle');
        });
    table.on('click', '.editar', function (e) {
            table = $('#Listar_Convenios').DataTable();
            e.preventDefault();
            $tr = $(this).closest('tr');
            var dataTable = table.row($tr).data(),
                route_edit = '{{ route('editarSedes.editarSedes') }}'+'/'+dataTable.PK_SEDE_Sede;
     $(".content-ajax").load(route_edit);
        });
    
    $('.portlet-form').attr("id","form_wizard_1");
    var rules = {
            SEDE_Sede: {required: true}
    };
    var form=$('#form-Agregar-Sede');
    var wizard =  $('#form_wizard_1');
    var crearConvenio = function () {
            return{
                init: function () {
                    table = $('#Listar_Convenios').DataTable();
                    var route = '{{ route('resgistrarSedes.resgistrarSedes') }}';
                    var type = 'POST';
                    var async = async || false;
                    var formData = new FormData();
                    formData.append('SEDE_Sede', $('#SEDE_Sede').val());
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
                        $('#sede').modal('hide');
                        $('#form-Agregar-Sede')[0].reset();
                        table.ajax.reload();
                        UIToastr.init(xhr , response.title , response.message  );
                        App.unblockUI('.portlet-form');
                    }
                },
                error: function (response, xhr, request) {
                    if (request.status === 422 &&  xhr === 'error') {
                        UIToastr.init(xhr, response.title, response.message);
                        App.unblockUI('.portlet-form');
                    }
                }
                    });
                }
            }
        };
    var messages = {};
    FormValidationMd.init( form, rules, messages , crearConvenio());
    
    var table, url, columns;
        table = $('#Listar_Convenios2');
        url = "{{ route('listarSedesEliminadas.listarSedesEliminadas') }}";
        columns = [
            {data: 'DT_Row_Index'},
           {data: 'PK_SEDE_Sede', "visible": true, name:"PK_SEDE_Sede" },
           {data: 'SEDE_Sede', searchable: true, name:"SEDE_Sede"},
           {data: 'action',
            name: 'action',
            title: 'Acciones',
            orderable: false,
            searchable: false,
            exportable: false,
            printable: false,
            className: 'text-center',
            render: null,
            serverSide: false,
            responsivePriority: 2,
            defaultContent: '<a href="#" target="_blank" class="btn btn-simple btn-danger btn-icon reset" title="resetear"><i class="icon-plus"></i></a>'
           }
        ];
        dataTableServer.init(table, url, columns);
    
    $('#Listar_Convenios2').on('click', '.reset', function(e) {
                table = $('#Listar_Convenios2').DataTable();
                e.preventDefault();
				$tr = $(this).closest('tr');
				var o =  table.row($tr).data();
				var route = '{{route('resetSedes.resetSedes')}}/'+o.PK_SEDE_Sede;
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
								App.blockUI({target: '.portlet-form', animate: true});
							},
                    success: function (response, xhr, request) {
                        if (request.status === 200 && xhr === 'success') {
                            table.ajax.reload();
                            table1 = $('#Listar_Convenios').DataTable();
                            table1.ajax.reload();
                            UIToastr.init(xhr, response.title, response.message);
                            App.unblockUI('.portlet-form');
                        }
                    },
                    error: function (response, xhr, request) {
                        if (request.status === 422 &&  xhr === 'error') {
                            UIToastr.init(xhr, response.title, response.message);
                            App.unblockUI('.portlet-form');
                        }
                    }
                });
    });
          
});
</script>
@endpush