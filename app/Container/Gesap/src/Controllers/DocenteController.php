<?php

namespace App\Container\Gesap\src\Controllers;


use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection as Collection;

use Illuminate\Support\Facades\Storage;

use Exception;
use Validator;
use Yajra\DataTables\DataTables;

use Illuminate\Support\Facades\Auth;
use App\Container\Overall\Src\Facades\AjaxResponse;
use Illuminate\Support\Facades\Crypt;

use App\Container\Gesap\src\Anteproyecto;
use App\Container\Gesap\src\Proyecto;
use App\Container\Gesap\src\Actividad;
use App\Container\Gesap\src\Encargados;
use App\Container\Gesap\src\Usuarios;
use App\Container\Gesap\src\Fechas;
use App\Container\Gesap\src\RolesUsuario;
use App\Container\Gesap\src\Desarrolladores;
use App\Container\Gesap\src\Estados;

use App\Container\Gesap\src\Resultados;
use Illuminate\Support\Facades\Mail;
use App\Container\Gesap\src\Funciones;
use App\Container\Gesap\src\NoFunciones;
use App\Container\Gesap\src\RubroPersonal;
use App\Container\Gesap\src\RubroEquipos;
use App\Container\Gesap\src\RubroMaterial;
use App\Container\Gesap\src\RubroTecnologico;
use App\Container\Gesap\src\Cronograma;
use App\Container\Gesap\src\Jurados;
use App\Container\Gesap\src\Mctr008;

use App\Container\Gesap\src\PersonaMct;

use App\Container\Gesap\src\Financiacion;
use App\Container\Gesap\src\ObservacionesMct;
use App\Container\Gesap\src\Solicitud;
use App\Container\Gesap\src\ObservacionesMctJurado;
use App\Container\Gesap\src\Commits;
use App\Container\Users\src\User;
use App\Container\Gesap\src\EstadoAnteproyecto;
use App\Container\Users\src\UsersUdec;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

use App\Container\Users\src\Controllers\UsersUdecController;



class DocenteController extends Controller
{
    private $path = 'gesap.Docente.';
    //funcion que redirecciona a la vista principal Anteproyectos//
    public function index(Request $request)
	{
		
			return view($this->path . 'IndexDocente');
		
    }
    //funcion que redirecciona a la vista de proyectos//
    public function indexProyecto(Request $request)
	{
		
			return view($this->path . 'IndexDocenteProyectos');
		
    }
    //con esta funcion traemos los proyectos asignados como director
    public function AnteproyectoList(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $user = Auth::user();
            $id = $user->identity_no;
           //$anteproyecto=Anteproyecto::where('FK_NPRY_Pre_Director', $id) -> get();
           $anteproyectos = Anteproyecto::where('FK_NPRY_Pre_Director', $id)->where('FK_NPRY_Estado','!=',7)->get();
              
            
           $desarrolladorP = "";
           foreach($anteproyectos as $anteproyecto){

            $estado = $anteproyecto -> relacionEstado -> EST_Estado;
            $anteproyecto->offsetSet('Estado',  $estado );

            $Predirector = $anteproyecto-> relacionPredirectores-> User_Nombre1." ".$anteproyecto-> relacionPredirectores-> User_Apellido1;
            $anteproyecto->offsetSet('Nombre',  $Predirector );
            $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$anteproyecto->PK_NPRY_IdMctr008)->get();
            $i=0;
            if($desarrolladores->IsEmpty()){
                $anteproyecto->offsetSet('Desarrolladores',  'Sin Asignar' );
            }else{
                foreach($desarrolladores as $desarrollador){
                    if($i==0){
                        $desarrolladorP = $desarrolladorP.$desarrollador -> relacionUsuario-> User_Nombre1 ." ".$desarrollador -> relacionUsuario-> User_Apellido1 ;
                        $i=1;
                    }else{
                        $desarrolladorP = $desarrolladorP.", ". $desarrollador -> relacionUsuario-> User_Nombre1 ." ".$desarrollador -> relacionUsuario-> User_Apellido1 ;
                    }
                }
                
                $anteproyecto->offsetSet('Desarrolladores',  $desarrolladorP );
            }
            $desarrolladorP = "";
           }

               return DataTables::of($anteproyectos)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
			   ->make(true);
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    //funcion que carga las solicitudes en una tabla para ser visualizada//
    public function VerSolicitud(Request $request)
    {
        $user = Auth::user();
		$id = $user->identity_no;
        if ($request->ajax() && $request->isMethod('GET')) {

               $Solicitudes=Solicitud::where('FK_User_Codigo',$id)->get();
               
        
               return DataTables::of($Solicitudes)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
               ->make(true);
        

        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    //funcion que elimina las solicitudes hechas realizadas o no ///
    public function EliminarSolicitud(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('DELETE')) {	
            
            
            $solicitud = Solicitud::where('PK_Id_Solicitud',$id)->first();

            if(empty($solicitud)){

                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Los Datos Ya Fueron Eliminados.'
                );
            }else{

                $solicitud -> delete();
            }
            
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Datos eliminados correctamente.'
            );
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );

    }   
    //Lista de anteproyectos para jurados
    public function AnteproyectoListJurado(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $user = Auth::user();
            $id = $user->identity_no;
           $jurado = Jurados::where('FK_User_Codigo',$id)->get(); 
           $i=0;
           $concatenado=[];
           foreach($jurado as $jur){

               
                $anteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008', $jur -> FK_NPRY_IdMctr008)->first();
                $collection = collect([]);
                $collection->put('Codigo',$anteproyecto-> PK_NPRY_IdMctr008);
                $collection->put('Titulo',$anteproyecto-> NPRY_Titulo);
                $collection->put('Estado',$anteproyecto-> relacionEstado-> EST_Estado);
                $collection->put('Descripcion',$anteproyecto-> NPRY_Descripcion);
                $collection->put('Duracion',$anteproyecto-> NPRY_Duracion);
                $collection->put('Fecha_Radicacion',$anteproyecto-> NPRY_FCH_Radicacion);
                $NombreDirector = $anteproyecto-> relacionPredirectores-> User_Nombre1." ".$anteproyecto-> relacionPredirectores-> User_Apellido1;
                $collection->put('Director',$NombreDirector);
                $j=0;
                $desarrolladorP="";
                $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$anteproyecto-> PK_NPRY_IdMctr008)->get();
                if($desarrolladores->IsEmpty()){
                    $collection->put('Desarrolladores',  "Sin Asignar" );
                }else{
                    foreach($desarrolladores as $desarrollador){
                        if($j==0){
                            $desarrolladorP = $desarrolladorP.$desarrollador -> relacionUsuario-> User_Nombre1 ." ".$desarrollador -> relacionUsuario-> User_Apellido1 ;
                            $j=1;
                        }else{
                            $desarrolladorP = $desarrolladorP.", ". $desarrollador -> relacionUsuario-> User_Nombre1 ." ".$desarrollador -> relacionUsuario-> User_Apellido1 ;
                        }
                    }
                    $collection->put('Desarrolladores',  $desarrolladorP );

                }
               

                $concatenado[$i]= $collection;

                $i=$i+1;
           }
          
               return DataTables::of($concatenado)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
			   ->make(true);
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    //funcion para listar los proyectos para el rol docente(Jurado)
    public function ProyectosListRadicados(Request $request, $idx)
    {
        if ($request->isMethod('GET')) {
            $user = Auth::user();
            $id = $user->identity_no;
           $Proyectos = Proyecto::all(); 
           $i=0;
           $concatenado=[];
           if($Proyectos->IsEmpty()){
                $concatenado=[];
           }else{
                
                foreach($Proyectos as $Proyecto){

                    $Jurados = jurados::where('FK_NPRY_IdMctr008', $Proyecto-> FK_NPRY_IdMctr008)->get();

                    foreach($Jurados as $jurado){
                        if($jurado->FK_User_Codigo == $id){
                            $anteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008', $Proyecto-> FK_NPRY_IdMctr008)->first();
                            //$proyectofecha = Proyecto::where('FK_NPRY_IdMctr008', $Proyecto-> FK_NPRY_IdMctr008)->first();
                            $collection = collect([]);
                            $collection->put('Codigo',$anteproyecto-> PK_NPRY_IdMctr008);
                            $collection->put('Titulo',$anteproyecto-> NPRY_Titulo);
                            $collection->put('Descripcion',$anteproyecto-> NPRY_Descripcion);
                            $collection->put('Estado',$Proyecto-> relacionEstado-> EST_Estado );
                            $collection->put('Duracion',$anteproyecto-> NPRY_Duracion." meses");                
                            $collection->put('Fecha_Radicacion',$Proyecto-> PYT_Fecha_Radicacion);
                            $NombreDirector = $anteproyecto-> relacionPredirectores-> User_Nombre1." ".$anteproyecto-> relacionPredirectores-> User_Apellido1;
                            $collection->put('Director',$NombreDirector);
                            $j=0;
                            $desarrolladorP="";
                            $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$Proyecto-> FK_NPRY_IdMctr008)->get();
                            if($desarrolladores->IsEmpty()){
                                $collection->put('Desarrolladores',  'Sin Asignar' );
                            }else{
                                foreach($desarrolladores as $desarrollador){
                                    if($j==0){
                                        $desarrolladorP = $desarrolladorP.$desarrollador -> relacionUsuario-> User_Nombre1 ." ".$desarrollador -> relacionUsuario-> User_Apellido1 ;
                                        $j=1;
                                    }else{
                                        $desarrolladorP = $desarrolladorP.", ". $desarrollador -> relacionUsuario-> User_Nombre1 ." ".$desarrollador -> relacionUsuario-> User_Apellido1 ;
                                    }
                                }
                                $collection->put('Desarrolladores',  $desarrolladorP );

                            }
                        
                            $concatenado[$i]= $collection;

                            $i=$i+1;
                            
                        }
                    }

                    
                }   
            }
          
               return DataTables::of($concatenado)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
			   ->make(true);
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }

    //funcion que trae los proyectos para ser mostrados en el drop-down list de solicitudes de docente//
    public function WidgetProyecto(Request $request)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
            $user = Auth::user();
            $id = $user->identity_no;

            
                $Anteproyectos = Anteproyecto::Where('FK_NPRY_Pre_Director',$id)->get();

                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos consultados correctamente.',
                    $Anteproyectos
                );
            
    
       
    
        }              
    }
    //funcion para crear una nueva solicitud//
    public function SolicitudStore(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            $user = Auth::user();
		$id = $user->identity_no;
         
           // $Anteproyecto = Anteproyecto::where('K_NPRY_IdMctr008', $Desarrollador -> FK_NPRY_IdMctr008)->first();
            Solicitud::create([
                'Sol_Solicitud' => $request['Sol_Solicitud'],
                'Sol_Estado' => "EN ESPERA",
                'FK_NPRY_IdMctr008' => $request['FK_NPRY_IdMctr008'],
                'FK_User_Codigo' =>$id,
            ]);
            return AjaxResponse::success(
                '¡Esta Hecho!',
                'Solicitud Hecha.'
            );
          
        }
    }
    
    //funciones que redirecciona a la vista de ver actividades $id = Id Proyecto//
    public function VerActividadesProyecto(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
            $Anteproyecto = $id;

            return view($this->path .'.Proyecto.Director.VerActividadesProyectoDirector',
            [
                'Anteproyecto' => $Anteproyecto,
            ]);
           
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );  
             
        }              
    }
    //funcionn que carga las actividades para el jurado $id= pk del proyecto //
    public function VerActividadesProyectoJurado(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
            $Anteproyecto = $id;

            return view($this->path .'.Proyecto.Jurado.VerActividadesProyectoJurado',
            [
                'Anteproyecto' => $Anteproyecto,
            ]);
           
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );  
             
        }              
    }
    //funcion que dependiendo la $id= Pk de la actividad redirecciona al formulario correspondiente $idp = pkproyecto//
    public function VerActividadProyecto(Request $request, $id, $idp)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

            $Actividad = Mctr008::where('PK_MCT_IdMctr008', $id)->where('FK_Id_Formato',3)->get();
                    
            $Actividad->offsetSet('Anteproyecto', $idp);

            $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
            $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
            if($commit2 == null)
            {
                $Actividad->offsetSet('Commit', "documents/GESAPPDF.pdf");
                $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
                
            }else{
                $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
                $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);

            }
        
               return view($this->path .'.Proyecto.Director.VerActividadProyecto',
                [
                'datos' => $Actividad,
                ]);
            
               
                 
            }     
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );         
        
    }
    // funcion que retorna a la vista correspondiente dependiendo de la $id = pk de la actividad $idp ) pk anteproyecto//
    public function VerActividadProyectoJurado(Request $request, $id, $idp , $idNum)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

            $Actividad = Mctr008::where('PK_MCT_IdMctr008', $id)->where('FK_Id_Formato',3)->get();
                    
            $Actividad->offsetSet('Anteproyecto', $idp);
            $Actividad->offsetSet('Numero', $idNum);

            $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
            $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
            if($commit2 == null)
            {
                $Actividad->offsetSet('Commit',"documents/GESAPPDF.pdf");
                $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
                
            }else{
                $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
                $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);

            }
            $actividadesAct = Mctr008::where('FK_Id_Formato',3)->get();
            $PrimeraAct = $actividadesAct -> first();
            $Actividad->offsetSet('Primera', $PrimeraAct->PK_MCT_IdMctr008 );
            
            $UltimaAct = $actividadesAct -> last();
            $Actividad->offsetSet('Ultima', $UltimaAct->PK_MCT_IdMctr008 );
            $Actividad->offsetSet('Proyecto', $idp );
        
               return view($this->path .'.Proyecto.Jurado.VerActividadProyectoJurado',
                [
                'datos' => $Actividad,
                ]);
            
               
                 
            }     
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );         
        
    }
    //funcion que carga las actividades del proyecto en cuestion $id//
    public function VerActividadesListProyectoDirector(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

               $Actividades=Mctr008::where('FK_Id_Formato',3)->get();
               $numero = 1 ;
               foreach($Actividades as $Actividad){
                   $Actividad->offsetSet('Numero', $numero);
                   $check = Commits::where('FK_MCT_IdMctr008', $Actividad->PK_MCT_IdMctr008)->where('FK_NPRY_IdMctr008',$id)->first();
                   
                   if($check != null){
                       if($check ->relacionEstado -> CHK_Checlist == "EN CALIFICACIÓN"){
                          $Actividad->offsetSet('Check', 'Sin Aprobar');
                       }else{
                          $Actividad->offsetSet('Check', 'Aprobado');
                       }
                   }else{
                        $Actividad->offsetSet('Check', 'Sin Subir');
                   }
                   $numero = $numero +1 ;
               }
                  
        
               return DataTables::of($Actividades)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
               ->make(true);
        

        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    //funcion que carga las actividades del mct para jurado//
    public function VerActividadesListJ(Request $request)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

               $Actividades=Mctr008::where('FK_Id_Formato',1)->get();
               $numero = 1 ;
               foreach($Actividades as $Actividad){
                   $Actividad->offsetSet('Numero', $numero);
                   $numero = $numero +1 ;
               }
                  
        
               return DataTables::of($Actividades)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
               ->make(true);
        

        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    //con esta funcion traemos los poryectos asignados a los directores
    public function ProyectosList(Request $request, $id)
    {
        if ( $request->isMethod('GET')) {
            $user = Auth::user();
            $id = $user->identity_no;
            $proyectos=Proyecto::where('FK_EST_Id','!=',7)->get();
            $i=0;
            $concatenado=[];
            foreach($proyectos as $proyecto){
                
                $proyectodirectorv = Proyecto::where('FK_NPRY_IdMctr008',$proyecto->FK_NPRY_IdMctr008)->where('FK_NPRY_Director',$id)->first();
                if($proyectodirectorv==null){
                    $collection = collect([]);
                }else{
                    $proyectodirector = Anteproyecto::where('PK_NPRY_IdMctr008',$proyectodirectorv->FK_NPRY_IdMctr008)->first();
                
                    $collection = collect([]);

                    $collection->put('Codigo',$proyectodirector-> PK_NPRY_IdMctr008);
                    
                    $collection->put('Titulo',$proyectodirector-> NPRY_Titulo);
                       
                    $collection->put('Descripcion',$proyectodirector-> NPRY_Descripcion);
                    $collection->put('Duracion',$proyectodirector->  NPRY_Duracion." meses");
                    $collection->put('Fecha_Radicacion',$proyecto->  PYT_Fecha_Radicacion);
                    $collection->put('Director',$proyectodirectorv -> relacionDirectores -> User_Nombre1 );
                    $collection->put('Estado',$proyecto-> relacionEstado-> EST_Estado );

                    $j=0;
                    $desarrolladorP="";
                    $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$proyectodirector-> PK_NPRY_IdMctr008)->get();
                    if($desarrolladores->IsEmpty()){
                        $collection->put('Desarrolladores',  'Sin Asignar' );
                    }else{
                        foreach($desarrolladores as $desarrollador){
                            if($j==0){
                                $desarrolladorP = $desarrolladorP.$desarrollador -> relacionUsuario-> User_Nombre1 ." ".$desarrollador -> relacionUsuario-> User_Apellido1 ;
                                $j=1;
                            }else{
                                $desarrolladorP = $desarrolladorP.", ". $desarrollador -> relacionUsuario-> User_Nombre1 ." ".$desarrollador -> relacionUsuario-> User_Apellido1 ;
                            }
                        }
                        $collection->put('Desarrolladores',  $desarrolladorP );

                    }
                    
          
                           
                    $concatenado[$i]= $collection;
                    $i=$i+1;
    
                }
                
            }

               return DataTables::of($concatenado)
                ->removeColumn('created_at')
			    ->removeColumn('updated_at')
			    
			    ->addIndexColumn()
			   ->make(true);
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    
    //funcion que muestra los desarrolaldores asignados al anteproyecto($id)//
    public function DesarrolladoresList(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

            $Desarrollador = Desarrolladores::where('FK_NPRY_IdMctr008',$id)->get();

            $s=0;

            foreach($Desarrollador as $desarrollo){
                
                $id_user[$s]= $Desarrollador[$s]-> FK_User_Codigo;
            

                $user = Usuarios::where('PK_User_Codigo',$id_user[$s])->first();

                $nombre[$s] = $user -> User_Nombre1;

                $Apellido[$s] = $user -> User_Apellido1;
 
                $desarrollo->offsetSet('Codigo',$id_user[$s]);

                $desarrollo->offsetSet('Nombre',$nombre[$s]);
                
                $desarrollo->offsetSet('Apellido',$Apellido[$s]);             

                $s=$s+1;
               }
         
         
            
              return DataTables::of($Desarrollador)
              ->removeColumn('created_at')
              ->removeColumn('updated_at')
               
              ->addIndexColumn()
              ->make(true);
            }
                return AjaxResponse::fail(
                    '¡Lo sentimos!',
                    'No se pudo completar tu solicitud.'
                );
        
    }
    //funcion que redirecciona a un formulario donde se muestran los datos del anteproyecto($id)//
    public function VerAnteproyecto(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

            $infoAnte = Anteproyecto::where('PK_NPRY_IdMctr008',$id)->get();
            $infoAnteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008',$id)->first();
            
          
            $estado = $infoAnteproyecto -> relacionEstado -> EST_Estado;

            $Nombre = $infoAnteproyecto -> relacionPredirectores-> User_Nombre1;
            
            $Apellido = $infoAnteproyecto -> relacionPredirectores-> User_Apellido1;

            $infoAnte->put('Estado',$estado);
            
            $infoAnte->put('Nombre',$Nombre);
            
            $infoAnte->put('Apellido',$Apellido);

            $datos = $infoAnte;

            

                return view ($this->path .'VerAnteproyectoDocente',
                [
                   
                    'datos' => $datos,
                ]);

                return AjaxResponse::fail(
                    '¡Lo sentimos!',
                    'No se pudo completar tu solicitud.'
                );
        }
    }
    //funcion que redirecciona al formulario donde se muestran los datos del anteproyecto para el jurado ($id = pk anteproyecto)//
    public function VerAnteproyectoJurado(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

            $infoAnte = Anteproyecto::where('PK_NPRY_IdMctr008',$id)->get();
            $infoAnteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008',$id)->first();
            
          
            $estado = $infoAnteproyecto -> relacionEstado -> EST_Estado;

            $Nombre = $infoAnteproyecto -> relacionPredirectores-> User_Nombre1;
            
            $Apellido = $infoAnteproyecto -> relacionPredirectores-> User_Apellido1;

            $infoAnte->put('Estado',$estado);
            
            $infoAnte->put('Nombre',$Nombre);
            
            $infoAnte->put('Apellido',$Apellido);

            $datos = $infoAnte;

            

                return view ($this->path .'.Jurado.VerAnteproyectoJurado',
                [
                   
                    'datos' => $datos,
                ]);

                return AjaxResponse::fail(
                    '¡Lo sentimos!',
                    'No se pudo completar tu solicitud.'
                );
        }
    }
//Función para asignar a los desarrolladores seleccionados como desarrolladores de dicho anteproyecto//
    public function Asignar(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $anteproyecto = Anteproyecto::Where('PK_NPRY_IdMctr008', $id)->first();
            
            $anteproyecto -> FK_NPRY_Estado = 2;
            
            $anteproyecto -> save();

            $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$id)->get();
            foreach($desarrolladores as $desarrollador){
                $data = array(
                    'correo'=>$desarrollador->relacionUsuario->User_Correo,
                    'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                );
    
                Mail::send('gesap.Emails.AsignacionNotificacion',$data, function($message) use ($data){
                    
                    $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
    
                    $message->to($data['correo']);
    
                });
    
            }
            

            $infoAnte = Anteproyecto::where('PK_NPRY_IdMctr008',$id)->get();
            $infoAnteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008',$id)->first();
            
          
            $estado = $infoAnteproyecto -> relacionEstado -> EST_Estado;

            $Nombre = $infoAnteproyecto -> relacionPredirectores-> User_Nombre1;
            
            $Apellido = $infoAnteproyecto -> relacionPredirectores-> User_Apellido1;

            $infoAnte->put('Estado',$estado);
            
            $infoAnte->put('Nombre',$Nombre);
            
            $infoAnte->put('Apellido',$Apellido);

            $datos = $infoAnte;




            return AjaxResponse::Success(
                '¡Esta Hecho!',
                'Proyecto Asignado.'
            );
        }
        

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    //funcion que muestra las actividades del mct para director $id = pk del anteproyecto//
    public function VerActividadesList(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

               $Actividades=Mctr008::where('FK_Id_Formato',1)->get();
               $numero = 1 ;
               foreach($Actividades as $Actividad){
                   $Actividad->offsetSet('Numero', $numero);
                   $check = Commits::where('FK_MCT_IdMctr008', $Actividad->PK_MCT_IdMctr008)->where('FK_NPRY_IdMctr008',$id)->first();
                   
                   if($check != null){
                       if($check ->relacionEstado -> CHK_Checlist == "EN CALIFICACIÓN"){
                          $Actividad->offsetSet('Check', 'Sin Calificar');
                       }else{
                          $Actividad->offsetSet('Check', 'Aprobado');
                       }
                   }else{
                        $Actividad->offsetSet('Check', 'Sin Subir');
                   }
                   
                   $numero = $numero +1 ;
               }
                  
        
               return DataTables::of($Actividades)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
               ->make(true);
        

        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    //esta funcion se utiliza para agregar un comentario a la actividad del anteproyecto///
    public function ComentarioStore(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            $user = Auth::user();
            $id = $user->identity_no;
            $fecha = Carbon::now();
            $fechahoy = $fecha->format('Y-m-d');
            if($request['OBS_Limit'] < $fechahoy){
                $IdError = 422;
                return AjaxResponse::success(
                    '¡Lo sentimos!',
                    'La Fecha No puede ser anterior a la Fecha Actual.',
                     $IdError
                );
            } else{
                     ObservacionesMct::create([
                    'FK_NPRY_IdMctr008' => $request['FK_NPRY_IdMctr008'],
                     'FK_MCT_IdMctr008' => $request['FK_MCT_IdMctr008'],
                     'FK_User_Codigo' => $id,
                     'OBS_Observacion' => $request['OBS_observacion'],
                     'OBS_Limit' => $request['OBS_Limit']

                    ]);
                    $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008', $request['FK_NPRY_IdMctr008'])->get();
                    $commit = Mctr008::where('PK_MCT_IdMctr008',$request['FK_MCT_IdMctr008'])->first();
                    foreach($desarrolladores as $desarrollador){

                        $data = array(
                            'correo'=>$desarrollador->relacionUsuario->User_Correo,
                            'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                            'Actividad'=>$commit->MCT_Actividad,
                            'Fecha'=> $request['OBS_Limit'],
                        );
            
                    
            
                    }
                return AjaxResponse::success(
                    '¡Esta Hecho!',
                    'Comentario Hecho.'
                );
            }
            }              
        
    }
    //funcion para enviar dar calificacion del anteproyecto de grado
    public function CalificarAnte(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            $user = Auth::user();
            $id = $user->identity_no;
            if($request['AVAL_Des'] == 1){
                $Commits = Commits::where('FK_NPRY_IdMctr008',$request['PK_Anteproyecto'])->get();
                foreach($Commits as $commit){
                    $commit ->FK_CHK_CheckList = 2;
                    $commit->save();
                }
                
                $desarrolladores = Desarrolladores::where('Fk_NPRY_IdMctr008',$request['PK_Anteproyecto'])->get();
                foreach($desarrolladores as $desarrollador){
                    $data = array(
                        'correo'=>$desarrollador->relacionUsuario->User_Correo,
                        'Ante'=>"del Anteproyecto : ".$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                        'Mensaje'=>"Su Anteproyecto Ha sido AVALADO",
                        'Comentario'=>$request['AVAL_Coment'],
                    );
        
                    Mail::send('gesap.Emails.DecisionDirector',$data, function($message) use ($data){
                        
                        $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
        
                        $message->to($data['correo']);
        
                    });
                
                }
               }else{

                $desarrolladores = Desarrolladores::where('Fk_NPRY_IdMctr008',$request['PK_Anteproyecto'])->get();
                foreach($desarrolladores as $desarrollador){
                    $data = array(
                        'correo'=>$desarrollador->relacionUsuario->User_Correo,
                        'Ante'=>"del Anteproyecto : ".$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                        'Mensaje'=>"Su Anteproyecto NO Ha sido AVALADO",
                        'Comentario'=>$request['AVAL_Coment'],
                    );
        
                    Mail::send('gesap.Emails.DecisionDirector',$data, function($message) use ($data){
                        
                        $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
        
                        $message->to($data['correo']);
        
                    });
                
                }
            }
            
            return AjaxResponse::success(
                    '¡Esta Hecho!',
                    'Comentarios Enviados.'
            );
       
            }   
                       
        
    }
     //funcion para enviar dar calificacion del proyecto de grado
     public function CalificarPro(Request $request)
     {
         if ($request->ajax() && $request->isMethod('POST')) {
             $user = Auth::user();
             $id = $user->identity_no;
             if($request['AVAL_Des'] == 1){
                 $Commits = Commits::where('FK_NPRY_IdMctr008',$request['PK_Anteproyecto'])->get();
                 foreach($Commits as $commit){
                     $commit ->FK_CHK_CheckList = 2;
                     $commit->save();
                 }
                 
                 $desarrolladores = Desarrolladores::where('Fk_NPRY_IdMctr008',$request['PK_Anteproyecto'])->get();
                 foreach($desarrolladores as $desarrollador){
                     $data = array(
                         'correo'=>$desarrollador->relacionUsuario->User_Correo,
                         'Ante'=>"del Proyecto : ".$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                         'Mensaje'=>"Su Anteproyecto Ha sido AVALADO",
                         'Comentario'=>$request['AVAL_Coment'],
                     );
         
                     Mail::send('gesap.Emails.DecisionDirector',$data, function($message) use ($data){
                         
                         $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
         
                         $message->to($data['correo']);
         
                     });
                 
                 }
                }else{
 
                 $desarrolladores = Desarrolladores::where('Fk_NPRY_IdMctr008',$request['PK_Anteproyecto'])->get();
                 foreach($desarrolladores as $desarrollador){
                     $data = array(
                         'correo'=>$desarrollador->relacionUsuario->User_Correo,
                         'Ante'=>"del Proyecto : ".$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                         'Mensaje'=>"Su Anteproyecto NO Ha sido AVALADO",
                         'Comentario'=>$request['AVAL_Coment'],
                     );
         
                     Mail::send('gesap.Emails.DecisionDirector',$data, function($message) use ($data){
                         
                         $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
         
                         $message->to($data['correo']);
         
                     });
                 
                 }
             }
             
             return AjaxResponse::success(
                     '¡Esta Hecho!',
                     'Comentarios Enviados.'
             );
        
             }   
                        
         
     }
    //funcion para guardar el comentaro del anteproyecto del jurado
    public function ComentarioStoreJurado(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            $user = Auth::user();
            $id = $user->identity_no;
            
            
            $desjurado = Jurados::where('FK_NPRY_IdMctr008',$request['FK_NPRY_IdMctr008'])->where('FK_User_Codigo',$id)->first();
            if($desjurado->JR_Comentario_2 == "inhabilitado"){
                $entrega = 1;
            }else{
                $entrega = 2;
            }
                     ObservacionesMctJurado::create([
                    'FK_NPRY_IdMctr008' => $request['FK_NPRY_IdMctr008'],
                     'FK_MCT_IdMctr008' => $request['FK_MCT_IdMctr008'],
                     'FK_User_Codigo' => $id,
                     'OBS_Observacion' => $request['OBS_observacion'],
                     'OBS_Formato' => $request['OBS_Formato'],
                     'OBS_Entrega' => $entrega,
                     

                    ]);
                return AjaxResponse::success(
                    '¡Esta Hecho!',
                    'Comentario Hecho.'
                );
       
            }   
                       
        
    }
      //funcion para guardar el comentaro del proyecto del jurado
      public function ComentarioStoreJuradoProyecto(Request $request)
      {
          if ($request->ajax() && $request->isMethod('POST')) {
              $user = Auth::user();
              $id = $user->identity_no;
              
              $desjurado = Jurados::where('FK_NPRY_IdMctr008',$request['FK_NPRY_IdMctr008'])->where('FK_User_Codigo',$id)->first();
              if($desjurado->JR_Comentario_Proyecto_2 == "inhabilitado"){
                  $entrega = 1;
              }else{
                  $entrega = 2;
              }
                       ObservacionesMctJurado::create([
                      'FK_NPRY_IdMctr008' => $request['FK_NPRY_IdMctr008'],
                       'FK_MCT_IdMctr008' => $request['FK_MCT_IdMctr008'],
                       'FK_User_Codigo' => $id,
                       'OBS_Observacion' => $request['OBS_observacion'],
                       'OBS_Formato' => $request['OBS_Formato'],
                       'OBS_Entrega' => $entrega,
                       
  
                      ]);
                  return AjaxResponse::success(
                      '¡Esta Hecho!',
                      'Comentario Hecho.'
                  );
         
              }   
                         
          
      }
      //funcion que muestra la decision de los jurados para el anteproyecto($id)//
    public function DesicionJurados(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
            $desicion = Jurados::where('FK_NPRY_IdMctr008',$id)->get();

            foreach($desicion as $des){
                $des-> offsetSet('Jurado',$des->relacionUsuarios->User_Nombre1." ".$des->relacionUsuarios->Apellido1);
                $des-> offsetSet('Estado',$Estado = $des->relacionEstado->EST_Estado);
            }
                     
            return DataTables::of($desicion)
           
            ->addIndexColumn()
            ->make(true);
       
            }
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );              
        
    }
    //funcion que muestra la decision tomada por los jurados para proyecto ($id)//
    public function DesicionJuradosProyecto(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
            $desicion = Jurados::where('FK_NPRY_IdMctr008',$id)->get();
            $proyecto = Proyecto::where('FK_NPRY_IdMctr008',$id)->first();

            foreach($desicion as $des){
               
                $Nombre1 = $des -> relacionUsuarios -> User_Nombre1;
                $Apellido = $des -> relacionUsuarios -> User_Apellido1;
                $space = " ";
                $Nombre = $Nombre1.$space.$Apellido;
                $Estado = $des -> relacionEstadoJurado -> EST_Estado;
                $estadoproyecto = $proyecto->relacionEstado->EST_Estado;
                if($estadoproyecto == "ASIGNADO"){
                    $des-> offsetSet('Estado',"ASIGNADO");

                } else{
                    $des-> offsetSet('Estado',$Estado);

                }
                $des-> offsetSet('Jurado',$Nombre);
                
            }
                     
            return DataTables::of($desicion)
           
            ->addIndexColumn()
            ->make(true);
       
            }
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );              
        
    }
    //funcion que retorna el estado para graficar el dropdawnlist//
    public function listarEstadoJurado(Request $request)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $Estado = EstadoAnteproyecto::Where('PK_EST_Id','>',3)->where('PK_EST_Id',1)->get();
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Datos consultados correctamente.',
                $Estado
            );
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );

    }
   //funcion que guarda la decision de los dos jurados y cambia a su respectivo estado el ANTEPROYECTO
    public function CambiarEstadoJurado(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            $user = Auth::user();
		    $id = $user->identity_no;

            $Jurado = Jurados::where('FK_User_Codigo',$id)->where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->first();
            if($Jurado->JR_Comentario_2 == 'inhabilitado'){
                $Jurado -> FK_NPRY_Estado = $request['FK_NPRY_Estado'];
                $Jurado ->  JR_Comentario =  $request['JR_Comentario'];
            
                $Jurado -> save();
            
            }else{
                $Jurado -> FK_NPRY_Estado = $request['FK_NPRY_Estado'];
                $Jurado ->  JR_Comentario_2 =  $request['JR_Comentario'];
                $Jurado -> save();
            }

            $Jurado = Jurados::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
            $DesiciónJuradoUno=$Jurado[0]->relacionEstado->EST_Estado ;
            $DesiciónJuradoDos=$Jurado[1]->relacionEstado->EST_Estado ;

            $anteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->first();
            
            $fecha = Fechas::where('PK_Id_Radicacion',4)->first();

            if(($DesiciónJuradoUno=="APROBADO")&&($DesiciónJuradoDos=="APROBADO")){
                //aprobado
                $anteproyecto -> FK_NPRY_Estado = 4;
                $anteproyecto -> save();
                $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($desarrolladores as $desarrollador){

                    $data = array(
                        'correo'=>$desarrollador->relacionUsuario->User_Correo,
                        'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                    );
        
                    Mail::send('gesap.Emails.DecisionAnte',$data, function($message) use ($data){
                        
                        $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
        
                        $message->to($data['correo']);
        
                    });
        
                }

                Proyecto::create([
                    'FK_EST_Id' => 2 , 
                    'FK_NPRY_IdMctr008' => $request['PK_NPRY_Id_Mctr008'],
                    'PYT_Fecha_Radicacion' => $fecha->FCH_Radicacion,
                    'FK_NPRY_Director' => $anteproyecto->FK_NPRY_Pre_Director,
                    'NPRY_Pro_Estado' => 1
                ]);
                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos modificados correctamente.'
                );

            }

            if(($DesiciónJuradoUno=="REPROBADO")&&($DesiciónJuradoDos=="REPROBADO")){
                //rechazado
                $anteproyecto -> FK_NPRY_Estado = 5;
                $anteproyecto -> save();
                $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($desarrolladores as $desarrollador){

                    $data = array(
                        'correo'=>$desarrollador->relacionUsuario->User_Correo,
                        'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                    );
        
                    Mail::send('gesap.Emails.DecisionAnte',$data, function($message) use ($data){
                        
                        $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
        
                        $message->to($data['correo']);
        
                    });
        
                }

                
                $desarrolladores= Desarrolladores::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($desarrolladores as $desarrollador){
                    $desarrollador-> Fk_IdEstado = 2;
                    $desarrollador->save();

                }
                
                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos modificados correctamente.'
                );

            }
            if(($DesiciónJuradoUno=="APLAZADO")&&($DesiciónJuradoDos=="APLAZADO")){
                //aplazado
                $anteproyecto -> FK_NPRY_Estado = 6;
                $anteproyecto -> save();
                $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($desarrolladores as $desarrollador){

                    $data = array(
                        'correo'=>$desarrollador->relacionUsuario->User_Correo,
                        'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                    );
        
                    Mail::send('gesap.Emails.DecisionAnte',$data, function($message) use ($data){
                        
                        $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
        
                        $message->to($data['correo']);
        
                    });
        
                }

                $actividades = Commits::Where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get(); 
                foreach($actividades as $actividad){
                    $actividad->FK_CHK_Checklist = 1;
                    $actividad->save();
                }
                $Juradod = Jurados::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($Juradod as $Jura){
                    $Jura ->  JR_Comentario_2 = 'habilitado';
                    $Jura -> save();
                }
                
                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos modificados correctamente.'
                );
            }
            if($DesiciónJuradoUno != $DesiciónJuradoDos){
                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos modificados correctamente.'
                );
            
            }
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    //funcion que toma la decision de los jurados y cambia el estado del PROYECTO
    public function CambiarEstadoJuradoproyecto(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            //variable de sesion
            $user = Auth::user();
            $id = $user->identity_no;

            $Jurado = Jurados::where('FK_User_Codigo',$id)->where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->first();
       
            if($Jurado->JR_Comentario_Proyecto_2 == 'inhabilitado'){
                $Jurado -> FK_NPRY_Estado_Proyecto = $request['FK_NPRY_Estado'];
                $Jurado ->  JR_Comentario_Proyecto =  $request['JR_Comentario_Proyecto'];
            
                $Jurado -> save();
            
            }else{
                $Jurado -> FK_NPRY_Estado_Proyecto = $request['FK_NPRY_Estado'];
                $Jurado ->  JR_Comentario_Proyecto_2 =  $request['JR_Comentario_Proyecto'];
                $Jurado -> save();
            }

            $anteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->first();
            
            $Jurado = Jurados::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
            
            $Proyecto = Proyecto::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->first();
            
            $DesiciónJuradoUno=$Jurado[0]->relacionEstadoJurado ->EST_Estado ;
            $DesiciónJuradoDos=$Jurado[1]->relacionEstadoJurado ->EST_Estado ;

            if(($DesiciónJuradoUno=="APROBADO")&&($DesiciónJuradoDos=="APROBADO")){
                $Proyecto -> FK_EST_Id = 4;
                $Proyecto -> NPRY_Pro_Estado = 2;
                $Proyecto -> save();
                $anteproyecto -> NPRY_Ante_Estado = 2;
                $anteproyecto -> save();
                
                $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($desarrolladores as $desarrollador){

                    $data = array(
                        'correo'=>$desarrollador->relacionUsuario->User_Correo,
                        'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                    );
        
                    Mail::send('gesap.Emails.DecisionAnte',$data, function($message) use ($data){
                        
                        $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
        
                        $message->to($data['correo']);
        
                    });
        
                }

                //aprobado
                
                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos modificados correctamente.'
                );

            }

            if(($DesiciónJuradoUno=="REPROBADO")&&($DesiciónJuradoDos=="REPROBADO")){
                $Proyecto -> FK_EST_Id = 5;
                //rechazado
                $Proyecto -> save();
                $anteproyecto -> FK_NPRY_Estado = 5;
                $anteproyecto -> NPRY_Ante_Estado = 2;
                $anteproyecto -> save();
                
                $desarrolladores= Desarrolladores::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($desarrolladores as $desarrollador){
                    $desarrollador-> Fk_IdEstado = 2;
                    $desarrollador->save();

                }
                foreach($desarrolladores as $desarrollador){

                    $data = array(
                        'correo'=>$desarrollador->relacionUsuario->User_Correo,
                        'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                    );
        
                    Mail::send('gesap.Emails.DecisionAnte',$data, function($message) use ($data){
                        
                        $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
        
                        $message->to($data['correo']);
        
                    });
        
                }

                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos modificados correctamente.'
                );

            }
            if(($DesiciónJuradoUno=="APLAZADO")&&($DesiciónJuradoDos=="APLAZADO")){
                $Proyecto -> FK_EST_Id = 6;
                //aplazado
                 
               
                $Proyecto -> save();
                $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($desarrolladores as $desarrollador){

                    $data = array(
                        'correo'=>$desarrollador->relacionUsuario->User_Correo,
                        'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                    );
        
                    Mail::send('gesap.Emails.DecisionAnte',$data, function($message) use ($data){
                        
                        $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
        
                        $message->to($data['correo']);
        
                    });
        
                }

                $actividades = Commits::Where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->where('CMMT_Formato',3)->get(); 
                foreach($actividades as $actividad){
                    $actividad->FK_CHK_Checklist = 1;
                    $actividad->save();
                }
                $Juradod = Jurados::where('FK_NPRY_IdMctr008',$request['PK_NPRY_Id_Mctr008'])->get();
                foreach($Juradod as $Jura){
                    $Jura ->  JR_Comentario_Proyecto_2 = 'habilitado';
                    $Jura -> save();
                }
               

               
                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos modificados correctamente.'
                );
            }
            if($DesiciónJuradoUno != $DesiciónJuradoDos){
                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Datos modificados correctamente.'
                );
            
            }
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }


    //funcon que carga los comentarios hechos por los jurados en la decision final para anteproyecto//    
    public function ComentariosJu(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
            $ObservacionesJurado = ObservacionesMctJurado::where('FK_NPRY_IdMctr008',$id)->where('OBS_Formato',1)->get();

            foreach($ObservacionesJurado as $observacion){
                $Actividad = $observacion -> relacionActividad -> MCT_Actividad;
                $tipo = $observacion -> relacionActividad;
                $formato = $tipo -> relacionFormato -> MCT_Formato;
                $linea="-";

                $nombreActividad = $Actividad.$linea.$formato;
                $observacion -> offsetSet('Nombre',$observacion->relacionUsuario->User_Nombre1." ".$observacion->relacionUsuario->User_Apellido1);
                $observacion -> offsetSet('Actividad',$nombreActividad);

            }
                     
            return DataTables::of($ObservacionesJurado)
           
            ->addIndexColumn()
            ->make(true);
       
            }
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );              
        
    }
    //funcon que carga los comentarios hechos por los jurados en la decision final proyecto//    
    public function ComentariosJuProyecto(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
            $ObservacionesJurado = ObservacionesMctJurado::where('FK_NPRY_IdMctr008',$id)->where('OBS_Formato',2)->get();

            foreach($ObservacionesJurado as $observacion){
               
                $Nombre1 = $observacion -> relacionUsuario -> User_Nombre1;
                $Apellido = $observacion -> relacionUsuario -> User_Apellido1;
                $space = " ";
                $Nombre = $Nombre1.$space.$Apellido;

                $Actividad = $observacion -> relacionActividad -> MCT_Actividad;
                $tipo = $observacion -> relacionActividad;
                $formato = $tipo -> relacionFormato -> MCT_Formato;
                $linea="-";

                $nombreActividad = $Actividad.$linea.$formato;
                $observacion -> offsetSet('Nombre',$Nombre);
                $observacion -> offsetSet('Actividad',$nombreActividad);

            }
                     
            return DataTables::of($ObservacionesJurado)
           
            ->addIndexColumn()
            ->make(true);
       
            }
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );              
        
    }
    //tomar la decision final del anteproyecto con la id del anteproyecto de grado///
    public function CalificarJurado(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $user = Auth::user();
            $idu = $user->identity_no;
            $Anteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008',$id)->first();
            $Nombre1 = $Anteproyecto -> relacionPredirectores -> User_Nombre1;
            $Apellido = $Anteproyecto -> relacionPredirectores -> User_Apellido1;
            $space = " ";
            
          
            $Nombre = $Nombre1.$space.$Apellido;

            $cadena = " ";
            
            $i=0;


            $Comentarios_Juradof = ObservacionesMctJurado::where('FK_User_Codigo',$idu)->where('FK_NPRY_IdMctr008',$id)->where('OBS_Formato',1)->first();
            $Jurado = Jurados::where('FK_User_Codigo',$idu)->where('FK_NPRY_IdMctr008',$id)->first();
            if($Comentarios_Juradof == null){
               
                $Anteproyecto -> offsetSet('Comentarios_Jurado', "Sin Comentarios En las Actividades");
                
            }else{
                if($Jurado->JR_Comentario_2 != 'inhabilitado' ){
                    if( $Jurado -> JR_Comentario_2 == "habilitado"){
                        $Comentarios_Jurado = ObservacionesMctJurado::where('FK_User_Codigo',$idu)->where('FK_NPRY_IdMctr008',$id)->where('OBS_Formato',1)->where('OBS_Entrega',2)->get();                
                        foreach($Comentarios_Jurado as $Comentario_Jurado){
                            $actividadcomentario = Mctr008::find($Comentario_Jurado->FK_MCT_IdMctr008);
                            if($i==0){
                                $cadena = 'Observaciones de las actividades : '.$Comentario_Jurado->OBS_Observacion.'  Actividad : '.$actividadcomentario ->MCT_Actividad;
                                $i = $i +1 ; 
                            }else{
                                $cadena = $cadena.', '.$Comentario_Jurado->OBS_Observacion.', Actividad : '.$actividadcomentario ->MCT_Actividad;
                            }      
                        }
                        $Anteproyecto -> offsetSet('Comentarios_Jurado', $cadena);
                        
                    }else{
                        
                        $Anteproyecto -> offsetSet('Comentarios_Jurado', $Jurado->JR_Comentario_2);
                    }
                    $Anteproyecto -> offsetSet('N_Radicado', 2);
                }else{
                    if( $Jurado -> JR_Comentario == "Sin Comentarios."){
                        $Comentarios_Jurado = ObservacionesMctJurado::where('FK_User_Codigo',$idu)->where('FK_NPRY_IdMctr008',$id)->where('OBS_Formato',1)->where('OBS_Entrega',1)->get();                
                        foreach($Comentarios_Jurado as $Comentario_Jurado){
                            $actividadcomentario = Mctr008::find($Comentario_Jurado->FK_MCT_IdMctr008);
                            if($i==0){
                                $cadena = 'Observaciones de las actividades : '.$Comentario_Jurado->OBS_Observacion.'  Actividad : '.$actividadcomentario ->MCT_Actividad;
                                $i = $i +1 ; 
                            }else{
                                $cadena = $cadena.', '.$Comentario_Jurado->OBS_Observacion.', Actividad : '.$actividadcomentario ->MCT_Actividad;
                            }      
                        }
                        $Anteproyecto -> offsetSet('Comentarios_Jurado', $cadena);
                        
                    }else{
                        
                        $Anteproyecto -> offsetSet('Comentarios_Jurado', $Jurado->JR_Comentario);
                    }
                    $Anteproyecto -> offsetSet('N_Radicado', 1);
                } 
                
               
                
            }

            $Anteproyecto -> offsetSet('Director', $Nombre);
            $Estado = $Anteproyecto -> relacionEstado -> EST_Estado;
            $Anteproyecto -> offsetSet('Estado', $Estado);

                
            return  view ($this->path .'CalificarAnteproyecto',
            [
               
                'datos' => $Anteproyecto,
            ]);
            return AjaxResponse::success(
                    '¡Esta Hecho!',
                    'Comentario Hecho.'
                );
       
            }              
        
    }
   //tomar la decision final del PROYECTO con la id del anteproyecto,PROYECTO de grado///
 
    public function CalificarProyectoJurado(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $user = Auth::user();
            $idu = $user->identity_no;
            $Anteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008',$id )->first();
            $Nombre1 = $Anteproyecto -> relacionPredirectores -> User_Nombre1;
            $Apellido = $Anteproyecto -> relacionPredirectores -> User_Apellido1;
            $space = " ";
            $Nombre = $Nombre1.$space.$Apellido;
            $cadena = " ";
            
            $i=0;
            $Jurado = Jurados::where('FK_User_Codigo',$idu)->where('FK_NPRY_IdMctr008',$id)->first();
            $Comentarios_Juradof = ObservacionesMctJurado::where('FK_User_Codigo',$idu)->where('FK_NPRY_IdMctr008',$id)->where('OBS_Formato',2)->first();
         
            if($Comentarios_Juradof == null){
               
                $Anteproyecto -> offsetSet('Comentarios_Jurado', "Sin Comentarios En las Actividades");
                
            }else{
                if($Jurado->JR_Comentario_Proyecto_2 != 'inhabilitado' ){
                    if( $Jurado -> JR_Comentario_Proyecto_2 == "habilitado"){
                        $Comentarios_Jurado = ObservacionesMctJurado::where('FK_User_Codigo',$idu)->where('FK_NPRY_IdMctr008',$id)->where('OBS_Formato',2)->where('OBS_Entrega',2)->get(); 
         
                        foreach($Comentarios_Jurado as $Comentario_Jurado){
                            
                            $actividadcomentario = Mctr008::find($Comentario_Jurado->FK_MCT_IdMctr008);
                            if($i==0){
                                $cadena = 'Observaciones de las actividades : '.$Comentario_Jurado->OBS_Observacion.'  Actividad : '.$actividadcomentario ->MCT_Actividad;
                                $i = $i +1 ; 
                            }else{
                                $cadena = $cadena.', '.$Comentario_Jurado->OBS_Observacion.', Actividad : '.$actividadcomentario ->MCT_Actividad;
                            }      
                        }
                        $Anteproyecto -> offsetSet('Comentarios_Jurado', $cadena);
                        
                    }else{
                        
                        $Anteproyecto -> offsetSet('Comentarios_Jurado', $Jurado->JR_Comentario_Proyecto_2);
                    }
                    $Anteproyecto -> offsetSet('N_Radicado', 2);
                    
                }else{
                    if( $Jurado -> JR_Comentario_Proyecto == "Sin Comentarios."){
                        $Comentarios_Jurado = ObservacionesMctJurado::where('FK_User_Codigo',$idu)->where('FK_NPRY_IdMctr008',$id)->where('OBS_Formato',2)->where('OBS_Entrega',1)->get(); 
         
                        foreach($Comentarios_Jurado as $Comentario_Jurado){
                            
                            $actividadcomentario = Mctr008::find($Comentario_Jurado->FK_MCT_IdMctr008);
                            if($i==0){
                                $cadena = 'Observaciones de las actividades : '.$Comentario_Jurado->OBS_Observacion.'  Actividad : '.$actividadcomentario ->MCT_Actividad;
                                $i = $i +1 ; 
                            }else{
                                $cadena = $cadena.', '.$Comentario_Jurado->OBS_Observacion.', Actividad : '.$actividadcomentario ->MCT_Actividad;
                            }      
                        }
                        $Anteproyecto -> offsetSet('Comentarios_Jurado', $cadena);
                        
                    }else{
                        
                        $Anteproyecto -> offsetSet('Comentarios_Jurado', $Jurado->JR_Comentario_Proyecto);
                    }
                    $Anteproyecto -> offsetSet('N_Radicado', 1);
                }

            }

            
            $Anteproyecto -> offsetSet('Director', $Nombre);
            $proyecto = proyecto::where('FK_NPRY_IdMctr008',$id )->first();
            $Estado = $proyecto -> relacionestado -> EST_Estado ;
            $idEstado = $proyecto->FK_EST_Id;
            $Anteproyecto -> offsetSet('IdEstado', $idEstado);
            $Anteproyecto -> offsetSet('Estado', $Estado);

                
            return view ($this->path .'.Proyecto.Jurado.CalificarProyecto',
            [
               
                'datos' => $Anteproyecto,
            ]);
    

                  
                return AjaxResponse::success(
                    '¡Esta Hecho!',
                    'Comentario Hecho.'
                );
       
            }              
        
    }
    //esta funcion se utiliza para avalar alsactividades subidas por el estudiante (Director)///
    public function Avalar(Request $request, $id,$idp)
    {
        if ($request->ajax() && $request->isMethod('GET')) {	
            
        $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
        $anteproyecto = Anteproyecto::where('PK_NPRY_IdMctr008',$idp)->first();
        $limit = $anteproyecto  -> NPRY_FCH_Radicacion;
      
        if($limit >= now()->toDateString()){
            $commit -> FK_CHK_Checklist = 2;
                
            $commit -> save();
            $desarrolladores = Desarrolladores::where('FK_NPRY_IdMctr008',$idp)->get();
            foreach($desarrolladores as $desarrollador){

                $data = array(
                    'correo'=>$desarrollador->relacionUsuario->User_Correo,
                    'Ante'=>$desarrollador->relacionAnteproyecto ->NPRY_Titulo,
                    'Actividad'=>$commit->relacionActividad->MCT_Actividad,
                );
    
                Mail::send('gesap.Emails.AprobarAct',$data, function($message) use ($data){
                    
                    $message->from('no-reply@ucundinamarca.edu.co', 'GESAP');
    
                    $message->to($data['correo']);
    
                });
    
            }
            
            
                return AjaxResponse::success(
                    '¡Bien hecho!',
                    'Actividad Aprobada.'
                );
        }else{
           
            $IdError = 422;
            return AjaxResponse::success(
                '¡Lo sentimos!',
                'La fecha de Radicación ya expiro.',
                $IdError
            );
          
        }
        }
       

    }
    //funcion que carga los comectarios hechos a la actividad($id) y de ese anteproyecto($idp)//
    public function Comentarios(Request $request, $id, $idp)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
                      
                $Comentario = ObservacionesMct::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
               $Comentario_2 = ObservacionesMct::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
                $i=0;
                foreach($Comentario as $coment){
                    $usuarioN = $coment -> relacionUsuario;
                    $Nombre = $usuarioN -> User_Nombre1;
                    $Apellido = $usuarioN -> User_Apellido1;
                    $space = " ";
                    $Nombretotal = $Nombre.$space.$Apellido;
                    $s[$i] = $Nombretotal;
                    $i = $i+1;
                }
                $j=0;
                foreach($Comentario as $comen){
                    $comen->offsetSet('Usuario', $s[$j]);
                    $j=$j+1;
                }
                

                return DataTables::of($Comentario)
                ->removeColumn('created_at')
                ->addIndexColumn()
                ->make(true);
               
        }
   
    }
    //funcion que muestra el cronograma//
    public function Cronograma(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

                $Cronograma = Cronograma::where('FK_NPRY_IdMctr008', $id)->get();
                foreach($Cronograma as $Crono){
                    $inicio = $Crono-> MCT_CRN_Semana_Inicio ;
                    $fin = $Crono-> MCT_CRN_Semana_Fin ;
                    $tab = '-';
                    $fecha =  $inicio.$tab.$fin;
                    $Crono ->offsetSet('Semana',$fecha);

                }

                return DataTables::of($Cronograma)
               ->removeColumn('created_at')
               ->removeColumn('updated_at')
                
               ->addIndexColumn()
               ->make(true);
        }
    }
    //funcion en donde se muestran los comentarios de los jurados a cada actividad($id)y al proyecto($idp)//
    public function ComentariosJurado(Request $request, $id, $idp)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
                      
                $Comentario = ObservacionesMctJurado::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
               $Comentario_2 = ObservacionesMctJurado::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
                $i=0;
                foreach($Comentario as $coment){
                    $usuarioN = $coment -> relacionUsuario;
                    $Nombre = $usuarioN -> User_Nombre1;
                    $Apellido = $usuarioN -> User_Apellido1;
                    $space = " ";
                    $Nombretotal = $Nombre.$space.$Apellido;
                    $s[$i] = $Nombretotal;
                    $i = $i+1;
                }
                $j=0;
                foreach($Comentario as $comen){
                    $comen->offsetSet('Usuario', $s[$j]);
                    $j=$j+1;
                }
                

                return DataTables::of($Comentario)
                //->removeColumn('created_at')
                ->addIndexColumn()
                ->make(true);
               
        }
   
    }
    //funcion que retorna la vista para ver las actividades al docente//
    public function VerActividades(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
                $Anteproyecto = $id;

                return view($this->path .'ActividadesDocente',
                [
                    'Anteproyecto' => $Anteproyecto,
                ]);
               
                return AjaxResponse::fail(
                    '¡Lo sentimos!',
                    'No se pudo completar tu solicitud.'
                );  
                 
            }              
        
    }
    //funcion que redirecciona la vista para que el jurado vea las actividades//
    public function VerActividadesJurado(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
                $Anteproyecto = $id;

                return view($this->path .'.Jurado.ActividadesJurado',
                [
                    'Anteproyecto' => $Anteproyecto,
                ]);
               
                return AjaxResponse::fail(
                    '¡Lo sentimos!',
                    'No se pudo completar tu solicitud.'
                );  
                 
            }              
        
    }
    //funcion para retornar la vista donde se mnuestran las actividades de el mct(requerimientos)//
    public function VerRequerimientosDocente(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
                $Anteproyecto = $id;

                return view($this->path .'RequerimientosDocente',
                [
                    'Anteproyecto' => $Anteproyecto,
                ]);
               
                return AjaxResponse::fail(
                    '¡Lo sentimos!',
                    'No se pudo completar tu solicitud.'
                );  
                 
            }              
        
    }
    //funcion para retornar la vista donde se mnuestran las actividades de el mct(requerimientos) jurado//
    public function VerRequerimientosJurado(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            
                $Anteproyecto = $id;

                return view($this->path .'.Jurado.RequerimientosJurado',
                [
                    'Anteproyecto' => $Anteproyecto,
                ]);
               
                return AjaxResponse::fail(
                    '¡Lo sentimos!',
                    'No se pudo completar tu solicitud.'
                );  
                 
            }              
        
    }
    //funcion para listar los requerimentos en la tabla de requeriemientos del jurado///
    public function VerRequerimientosList(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
                
               $Actividades=Mctr008::where('FK_Id_Formato',2)->get();
               $numero = 1 ;
               foreach($Actividades as $Actividad){
                   $Actividad->offsetSet('Numero', $numero);
                   $check = Commits::where('FK_MCT_IdMctr008', $Actividad->PK_MCT_IdMctr008)->where('FK_NPRY_IdMctr008',$id)->first();
                   
                   if($check != null){
                       if($check ->relacionEstado -> CHK_Checlist == "EN CALIFICACIÓN"){
                          $Actividad->offsetSet('Check', 'Sin Calificar');
                       }else{
                          $Actividad->offsetSet('Check', 'Aprobado');
                       }
                   }else{
                        $Actividad->offsetSet('Check', 'Sin Subir');
                   }
                   $numero = $numero +1 ;
               }
                  
        
               return DataTables::of($Actividades)
               ->removeColumn('created_at')
               ->removeColumn('updated_at')
			   
			   ->addIndexColumn()
               ->make(true);
        

        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }
    
    public function CalificarAnteproyecto(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
      
                 
            }     
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );         
        
    }
    //funcion que carga la tabla resultados//
    public function Resultados(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

                $resultado = Resultados::where('FK_NPRY_IdMctr008', $id)->get();

                return DataTables::of($resultado)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
               ->make(true);
        }
    }
    //funcion que carga la tabla financiacion//
    
    public function Financiacion(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

                $Financiacion = Financiacion::where('FK_NPRY_IdMctr008', $id)->get();

                return DataTables::of($Financiacion)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
               ->make(true);
        }
    }
    //funcion que carga la tabla detalles persona//
    
    public function DetallesPersona(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

                $DPersona = PersonaMct::where('FK_NPRY_IdMctr008', $id)->get();

                return DataTables::of($DPersona)
               ->removeColumn('created_at')
			   ->removeColumn('updated_at')
			    
			   ->addIndexColumn()
               ->make(true);
        }
    }
    //funcion que carga la tabla funciones //
    
    public function Funcion(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

                $Funciones = Funciones::where('FK_NPRY_IdMctr008', $id)->get();
                return DataTables::of($Funciones)
               ->removeColumn('created_at')
               ->removeColumn('updated_at')
                
               ->addIndexColumn()
               ->make(true);
        }
    }
    public function NoFuncion(Request $request,$id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

                $Funciones = NoFunciones::where('FK_NPRY_IdMctr008', $id)->get();
                return DataTables::of($Funciones)
               ->removeColumn('created_at')
               ->removeColumn('updated_at')
                
               ->addIndexColumn()
               ->make(true);
        }
    }
    //funcion quedependiendo la actividad($id) redirecciona asu respectiva vista $idp= pk proyecto//
    
    public function VerRequerimientos(Request $request, $id, $idp)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

            $Actividad = Mctr008::where('PK_MCT_IdMctr008', $id)->where('FK_Id_Formato',2)->get();
                    
            $Actividad->offsetSet('Anteproyecto', $idp);

            $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
            $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
            if($commit2 == null)
            {
                $Actividad->offsetSet('Commit', "Aún NO se ha hecho ningún cambio a este Requerimiento.");
                $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
                
            }else{
                $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
                $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);

            }
            $act = Mctr008::where('PK_MCT_IdMctr008',$id)->first();
            if($act->MCT_Actividad == "Funciones"){
                return view($this->path .'VerRequerimientoFunciones',
                [
                'datos' => $Actividad,
                ]);   
                 
            }    
            if($act->MCT_Actividad == "Requerimientos No Funcionales"){
                return view($this->path .'VerRequerimientoNoFuncional',
                [
                'datos' => $Actividad,
                ]);   
                 
            }     
            return view($this->path .'VerRequerimiento',
            [
            'datos' => $Actividad,
            ]);
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );         
        
    }
    
}
//funcion para retornar la vista del requerimiento seleccionado apra su posterior calificación por parte del jrado(Requerimientos) //
public function RequerimientosJurado(Request $request, $id, $idp,$idNum)
{
    if ($request->ajax() && $request->isMethod('GET')) {

        $Actividad = Mctr008::where('PK_MCT_IdMctr008', $id)->where('FK_Id_Formato',2)->get();
                
        $Actividad->offsetSet('Anteproyecto', $idp);
        $Actividad->offsetSet('Numero', $idNum);
        

        $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
        $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
        if($commit2 == null)
        {
            $Actividad->offsetSet('Commit', "Aún NO se ha hecho ningún cambio a este Requerimiento.");
            $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
            
        }else{
            $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
            $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);

        }
        $act = Mctr008::where('PK_MCT_IdMctr008',$id)->first();
        $actividadesAct = Mctr008::where('FK_Id_Formato',2)->get();
            $PrimeraAct = $actividadesAct -> first();
            $Actividad->offsetSet('Primera', $PrimeraAct->PK_MCT_IdMctr008 );
            
            $UltimaAct = $actividadesAct -> last();
            $Actividad->offsetSet('Ultima', $UltimaAct->PK_MCT_IdMctr008 );
            $Actividad->offsetSet('Proyecto', $idp );
        if($act->MCT_Actividad == "Funciones"){
            return view($this->path .'.Jurado.VerRequerimientoFuncionesJurado',
            [
            'datos' => $Actividad,
            ]);   
             
        }    
        if($act->MCT_Actividad == "Requerimientos No Funcionales"){
            return view($this->path .'.Jurado.VerRequerimientoNoFuncionesJurado',
            [
            'datos' => $Actividad,
            ]);   
             
        }     
        return view($this->path .'.Jurado.VerRequerimientoJurado',
        [
        'datos' => $Actividad,
        ]);
        
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );         
    
}

}
//funcion para retornar la vista del requerimiento seleccionado apra su posterior calificación por parte del jrado(MCT) //
    public function VerActividad(Request $request, $id, $idp)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

            $Actividad = Mctr008::where('PK_MCT_IdMctr008', $id)->where('FK_Id_Formato',1)->get();
                    
            $Actividad->offsetSet('Anteproyecto', $idp);

            $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
            $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
            if($commit2 == null)
            {
                $Actividad->offsetSet('Commit', "Aún NO se ha hecho ningún cambio a esta actividad del MCT.");
                $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
                
            }else{
                $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
                $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);

            }
            $act = Mctr008::where('PK_MCT_IdMctr008',$id)->first();
            if($act->MCT_Actividad == "Cronograma"){
                return view($this->path .'.Director.ActividadCronograma',
                [
                'datos' => $Actividad,
                ]);

            
            }if($act->MCT_Actividad == "Detalles de personas"){
                return view($this->path .'.Director.ActividadDetalles',
                [
                'datos' => $Actividad,
                ]);

            }if($act->MCT_Actividad == "Financiacion"){
                return view($this->path .'.Director.ActividadFinanciacion',
                [
                'datos' => $Actividad,
                ]);

            
            }if($act->MCT_Actividad == "Resultados"){
                return view($this->path .'.Director.ActividadResultados',
                [
                'datos' => $Actividad,
                ]);

            }
            if($act->MCT_Actividad == "Resumen De Rubros"){
                return view($this->path .'.Director.ActividadRubros',
                [
                'datos' => $Actividad,
                ]);

            }
               return view($this->path .'.Director.VerActividadDocente',
                [
                'datos' => $Actividad,
                ]);
            
               
                 
            }     
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );         
        
    }
    //funcion que carga la tabla RubrosTecnologicos//
    
    public function RubroTecnologico(Request $request,$id)
               {
                   if ($request->ajax() && $request->isMethod('GET')) {
           
                           $RubroTecnologico = RubroTecnologico::where('FK_NPRY_IdMctr008', $id)->get();
                          
                           return DataTables::of($RubroTecnologico)
                          ->removeColumn('created_at')
                          ->removeColumn('updated_at')
                           
                          ->addIndexColumn()
                          ->make(true);
                   }
               }

    //funcion que carga la tabla RubrosMateriales//
    public function RubroMaterial(Request $request,$id)
               {
                   if ($request->ajax() && $request->isMethod('GET')) {
           
                           $RubroMaterial = RubroMaterial::where('FK_NPRY_IdMctr008', $id)->get();
                          
                           return DataTables::of($RubroMaterial)
                          ->removeColumn('created_at')
                          ->removeColumn('updated_at')
                           
                          ->addIndexColumn()
                          ->make(true);
                   }
               }

    //funcion que carga la tabla RubrosEquipos//
 public function RubroEquipos(Request $request,$id)
               {
                   if ($request->ajax() && $request->isMethod('GET')) {
           
                           $RubroEquipos = RubroEquipos::where('FK_NPRY_IdMctr008', $id)->get();
                          
                           return DataTables::of($RubroEquipos)
                          ->removeColumn('created_at')
                          ->removeColumn('updated_at')
                           
                          ->addIndexColumn()
                          ->make(true);
                   }
               }	

    //funcion que carga la tabla Rubrospersona//
   public function RubroPersonal(Request $request,$id)
               {
                   if ($request->ajax() && $request->isMethod('GET')) {
           
                           $RubroPersonal = RubroPersonal::where('FK_NPRY_IdMctr008', $id)->get();
                          
                           return DataTables::of($RubroPersonal)
                          ->removeColumn('created_at')
                          ->removeColumn('updated_at')
                           
                          ->addIndexColumn()
                          ->make(true);
                   }
               }

    ///funcion que redirecciona a las actividades para calificar como jurado//
    public function navegacionActividades(Request $request, $id, $idp,$idn)
            {
        if ($request->ajax() && $request->isMethod('GET')) {
            if($idn == 0){
                $id = $id - 1;   
            }else{
                $id = $id + 1;
            }
           
            $Numero = $id ;
            $Actividades = Mctr008::where('FK_Id_Formato',1)->get();
            $id = $Actividades[$id]->PK_MCT_IdMctr008;                  
            $Actividad = Mctr008::where('PK_MCT_IdMctr008',$id)->where('FK_Id_Formato',1)->get();
            $Actividad->offsetSet('Anteproyecto', $idp);
            $Actividad->offsetSet('Numero', $Numero);
            
            $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
            $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
           
            if($commit2 == null){
                  $Actividad->offsetSet('Commit', "Aún NO se ha hecho ningún cambio a esta actividad del MCT.");
                  $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
                           
            }else{
                  $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
                  $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);
           
            }
                       $act = Mctr008::where('PK_MCT_IdMctr008',$id)->first();
                       $actividadesAct = Mctr008::where('FK_Id_Formato',1)->get();
                       $PrimeraAct = $actividadesAct -> first();
                       $Actividad->offsetSet('Primera', $PrimeraAct->PK_MCT_IdMctr008 );
                       
                       $UltimaAct = $actividadesAct -> last();
                       $Actividad->offsetSet('Ultima', $UltimaAct->PK_MCT_IdMctr008 );
                       $Actividad->offsetSet('Proyecto', $idp );
                       if($act->MCT_Actividad == "Cronograma"){
                           return view($this->path .'.Jurado.ActividadCronogramaJurado',
                           [
                           'datos' => $Actividad,
                           ]);
           
                       
                       }if($act->MCT_Actividad == "Detalles de personas"){
                           return view($this->path .'.Jurado.ActividadDetallesJurado',
                           [
                           'datos' => $Actividad,
                           ]);
           
                       }if($act->MCT_Actividad == "Financiacion"){
                           return view($this->path .'.Jurado.ActividadFinanciacionJurado',
                           [
                           'datos' => $Actividad,
                           ]);
           
                       
                       }if($act->MCT_Actividad == "Resultados"){
                           return view($this->path .'.Jurado.ActividadResultadosJurado',
                           [
                           'datos' => $Actividad,
                           ]);
           
                       }
                       if($act->MCT_Actividad == "Resumen De Rubros"){
                           return view($this->path .'.Jurado.ActividadRubrosJurado',
                           [
                           'datos' => $Actividad,
                           ]);
           
                       }
                          return view($this->path .'.Jurado.VerActividadJurado',
                           [
                           'datos' => $Actividad,
                           ]);
                       
                          
                            
                       }     
                       return AjaxResponse::fail(
                           '¡Lo sentimos!',
                           'No se pudo completar tu solicitud.'
                       );         
                  
    }
    
               
    //funcion que se encarga de navegar entre las actividades para su posterior calificacion $id=pk actividad $idp = pk proyecto y $idn navegacion si es la primer actividad //
    public function navegacionActividadesP(Request $request, $id, $idp,$idn)
            {
        if ($request->ajax() && $request->isMethod('GET')) {
           
            if($idn == 0){
            
            $id = $id - 1;
           }else{
            
            $id = $id + 1;
           }

           $Numero = $id ;
           $Actividades = Mctr008::where('FK_Id_Formato',3)->get();
           $id = $Actividades[$id]->PK_MCT_IdMctr008;                  
           $Actividad = Mctr008::where('PK_MCT_IdMctr008',$id)->where('FK_Id_Formato',3)->get();
           $Actividad->offsetSet('Anteproyecto', $idp);
           $Actividad->offsetSet('Numero', $Numero);

         
           $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
           $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
           if($commit2 == null)
           {
               $Actividad->offsetSet('Commit',"documents/GESAPPDF.pdf");
               $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
               
           }else{
               $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
               $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);

           }
                       $actividadesAct = Mctr008::where('FK_Id_Formato',3)->get();
                       $PrimeraAct = $actividadesAct -> first();
                       $Actividad->offsetSet('Primera', $PrimeraAct->PK_MCT_IdMctr008 );
                       
                       $UltimaAct = $actividadesAct -> last();
                       $Actividad->offsetSet('Ultima', $UltimaAct->PK_MCT_IdMctr008 );
                       $Actividad->offsetSet('Proyecto', $idp );
                       return view($this->path .'.Proyecto.Jurado.VerActividadProyectoJurado',
                       [
                       'datos' => $Actividad,
                       ]);
                   
                      
                        
                   }     
                   return AjaxResponse::fail(
                       '¡Lo sentimos!',
                       'No se pudo completar tu solicitud.'
                   );         
    }
           
                //funcion que se encarga de navegar entre las actividades para su posterior calificacion $id=pk actividad $idp = pk proyecto y $idn navegacion si es la ultima actividad //
   
    public function navegacionActividadesR(Request $request, $id, $idp,$idn)
            {
        if ($request->ajax() && $request->isMethod('GET')) {
            
            if($idn == 0){
            
            $id = $id - 1;
           }else{
            
            $id = $id + 1;
           }
            $Numero = $id ;
            $Actividades = Mctr008::where('FK_Id_Formato',2)->get();
            $id = $Actividades[$id]->PK_MCT_IdMctr008;                  
            $Actividad = Mctr008::where('PK_MCT_IdMctr008',$id)->where('FK_Id_Formato',2)->get();
            $Actividad->offsetSet('Anteproyecto', $idp);
            $Actividad->offsetSet('Numero', $Numero);
            
            $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
            $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
            if($commit2 == null){
                  $Actividad->offsetSet('Commit', "Aún NO se ha hecho ningún cambio a esta actividad del MCT.");
                  $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
                           
            }else{
                  $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
                  $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);
           
            }
                       $act = Mctr008::where('PK_MCT_IdMctr008',$id)->first();
                       $actividadesAct = Mctr008::where('FK_Id_Formato',2)->get();
                       $PrimeraAct = $actividadesAct -> first();
                       $Actividad->offsetSet('Primera', $PrimeraAct->PK_MCT_IdMctr008 );
                       
                       $UltimaAct = $actividadesAct -> last();
                       $Actividad->offsetSet('Ultima', $UltimaAct->PK_MCT_IdMctr008 );
                       $Actividad->offsetSet('Proyecto', $idp );
                       if($act->MCT_Actividad == "Funciones"){
                        return view($this->path .'.Jurado.VerRequerimientoFuncionesJurado',
                        [
                        'datos' => $Actividad,
                        ]);   
                         
                    }     
                    if($act->MCT_Actividad == "Requerimientos No Funcionales"){
                        return view($this->path .'.Jurado.VerRequerimientoNoFuncionesJurado',
                        [
                        'datos' => $Actividad,
                        ]);   
                         
                    }     
                    return view($this->path .'.Jurado.VerRequerimientoJurado',
                    [
                    'datos' => $Actividad,
                    ]);
                    return AjaxResponse::fail(
                        '¡Lo sentimos!',
                        'No se pudo completar tu solicitud.'
                    );    
                }     
    }
        //funcion que depentiendo la actividad $id y el poryecto $idp redirecciona a una vista diferente// 
    public function VerActividadJurado(Request $request, $id, $idp,$idNum)
    {
        if ($request->ajax() && $request->isMethod('GET')) {

            $Actividad = Mctr008::where('PK_MCT_IdMctr008', $id)->where('FK_Id_Formato',1)->get();
                    
            $Actividad->offsetSet('Anteproyecto', $idp);
            $Actividad->offsetSet('Numero', $idNum);

            $commit = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->get();
            $commit2 = Commits::where('FK_NPRY_Idmctr008',$idp)->where('FK_MCT_IdMctr008',$id)->first();
            if($commit2 == null)
            {
                $Actividad->offsetSet('Commit', "Aún NO se ha hecho ningún cambio a esta actividad del MCT.");
                $Actividad->offsetSet('Estado', "Sin Enviar Para Calificar.");
                
            }else{
                $Actividad->offsetSet('Estado', $commit[0] -> relacionEstado -> CHK_Checlist);
                $Actividad->offsetSet('Commit', $commit[0] -> CMMT_Commit);

            }
            $act = Mctr008::where('PK_MCT_IdMctr008',$id)->first();
            $actividadesAct = Mctr008::where('FK_Id_Formato',1)->get();
            $PrimeraAct = $actividadesAct -> first();
            $Actividad->offsetSet('Primera', $PrimeraAct->PK_MCT_IdMctr008 );
            
            $UltimaAct = $actividadesAct -> last();
            $Actividad->offsetSet('Ultima', $UltimaAct->PK_MCT_IdMctr008 );
            $Actividad->offsetSet('Proyecto', $idp );
            if($act->MCT_Actividad == "Cronograma"){
                return view($this->path .'.Jurado.ActividadCronogramaJurado',
                [
                'datos' => $Actividad,
                ]);

            
            }if($act->MCT_Actividad == "Detalles de personas"){
                return view($this->path .'.Jurado.ActividadDetallesJurado',
                [
                'datos' => $Actividad,
                ]);

            }if($act->MCT_Actividad == "Financiacion"){
                return view($this->path .'.Jurado.ActividadFinanciacionJurado',
                [
                'datos' => $Actividad,
                ]);

            
            }if($act->MCT_Actividad == "Resultados"){
                return view($this->path .'.Jurado.ActividadResultadosJurado',
                [
                'datos' => $Actividad,
                ]);

            }
            if($act->MCT_Actividad == "Resumen De Rubros"){
                return view($this->path .'.Jurado.ActividadRubrosJurado',
                [
                'datos' => $Actividad,
                ]);

            }
               return view($this->path .'.Jurado.VerActividadJurado',
                [
                'datos' => $Actividad,
                ]);
            
               
                 
            }     
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );         
       
    }

}
