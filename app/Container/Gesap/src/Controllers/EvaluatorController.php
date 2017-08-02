<?php

namespace App\Container\gesap\src\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Container\Users\Src\Interfaces\UserInterface;

use App\Container\gesap\src;
use App\Container\gesap\src\Observaciones;
use App\Container\gesap\src\Encargados;
use App\Container\gesap\src\Check_Observaciones;
use App\Container\gesap\src\Respuesta;

class EvaluatorController extends Controller
{
    private $path='gesap';
    protected $connection = 'gesap';
    public function index(){
        return redirect()->route('anteproyecto.index.listjurado');
    }

    public function jurado(){
        return view($this->path.'.Evaluador.JuradoList');
    }
    
    public function createObsevaciones($id){
        $anteproyectos = DB::table('TBL_Anteproyecto')
                            ->select('PK_NPRY_idMinr008','NPRY_Titulo')
                            ->where('PK_NPRY_idMinr008','=',$id)
                            ->get();
        return view($this->path.'.Evaluador.Observaciones',compact('anteproyectos'));
    }
    public function storeObservaciones(Request $request){
        try{
            /*echo ."<br>";
            echo ."<br>";
            echo $request['observacion']."<br>";
            //echo $request['Min']->getClientOriginalName();
            //echo $request['requerimientos']->getClientOriginalName();
            */
            $jurado = Encargados::select('PK_NPRY_idCargo')
                        ->where('FK_TBL_Anteproyecto_id','=',$request['PK_anteproyecto'])
                        ->where('FK_developer_user_id','=',$request['user'])
                        ->where(function($query){
                            $query->where('NCRD_Cargo', '=', 'Jurado 1')  ;
                            $query->orwhere('NCRD_Cargo', '=', 'Jurado 2');
                        })
                        ->firstOrFail();
            
            
            
            $observacion= new Observaciones();
            $observacion->BVCS_Observacion=$request['observacion'];
            $observacion->FK_TBL_Encargado_id=$jurado->PK_NPRY_idCargo;
            $observacion->save();
            
            $checkobservacion= new Check_Observaciones();
            $checkobservacion->FK_TBL_Observaciones_id=$observacion->PK_BVCS_idObservacion;
            $checkobservacion->save();
            

            if(!empty($request['Min']) || !empty($request['Requerimientos'])){
                $respuesta=new Respuesta();
                if(!empty($request['Min']))
                    $respuesta->RPST_RMin=$request['Min']->getClientOriginalName();
                else
                    $respuesta->RPST_RMin=0;
                if(!empty($request['Requerimientos']))
                    $respuesta->RPST_Requerimientos=$request['Requerimientos']->getClientOriginalName();
                else
                    $respuesta->RPST_Requerimientos=0;
                $respuesta->FK_TBL_Observaciones_id=$observacion->PK_BVCS_idObservacion;
                $respuesta->save();
            }
        
            
            
            
            
            
            return redirect()->route('anteproyecto.index.listjurado');
            

        
        }catch(Exception $e){
            return "Fatal Error =".$e->getMessage();
        }
        
    }
    
    public function createConceptos($id){
        $anteproyectos = DB::table('TBL_Anteproyecto')
                            ->select('PK_NPRY_idMinr008','NPRY_Titulo')
                            ->where('PK_NPRY_idMinr008','=',$id)
                            ->get();
        
        return view($this->path.'.Evaluador.Conceptos',compact('anteproyectos'));
    }
    public function storeConceptos(Request $request, $id){
    }
    
    public function director(){
        return view($this->path.'.Evaluador.DirectorList');
    }
    
    public function create(){
    }

    public function store(Request $request){        
    }

    public function show($id){
        return view($this->path.'.Evaluador.ShowObservation');
    }

    public function edit($id){
        return view($this->path.'.Evaluador.Observaciones');
    }
    public function update(Request $request, $id){
    }

    public function destroy($id){
    }

      public function getSql($query){
        $sql = $query->toSql();
        foreach($query->getBindings() as $binding){
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }
    
    public function ListObservation($id){
        $observaciones=
            DB::table('gesap.tbl_observaciones AS O')
                ->select('PK_BVCS_idObservacion','BVCS_Observacion',
                    DB::raw('IFNULL(('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('tbl_encargados.PK_NPRY_idCargo','=',DB::raw('O.FK_TBL_Encargado_id'))
                        )
                    .'),"error")AS Jurado'),
                    DB::raw('IFNULL(('
                        .$this->getSql(
                            DB::table('gesap.tbl_respuesta')
                                ->select('RPST_RMin')
                                ->where('FK_TBL_Observaciones_id','=',DB::raw('O.PK_BVCS_idObservacion'))
                        )
                    .'),"No existe")AS Rmin'),
                     DB::raw('IFNULL(('
                        .$this->getSql(
                            DB::table('gesap.tbl_respuesta')
                                ->select('RPST_Requerimientos')
                                ->where('FK_TBL_Observaciones_id','=',DB::raw('O.PK_BVCS_idObservacion'))
                            )
                    .'),"No existe")AS Rreq')
                )
                ->where('FK_TBL_Anteproyecto_id','=',$id)
                ->where(function($query){
                        $query->where('NCRD_Cargo', '=', 'Jurado 1')  ;
                            $query->orwhere('NCRD_Cargo', '=', 'Jurado 2');
                        })
                ->join('gesap.tbl_encargados','FK_TBL_Encargado_id','=','PK_NPRY_idCargo');
        return Datatables::of(DB::select($this->getSql($observaciones)))->addIndexColumn()->make(true);
    }
    
    

    
    
    public function ListDirector(){
        $result="NO ASIGNADO";
        $anteproyectos = 
            DB::table('gesap.TBL_Anteproyecto AS A')
                ->join('gesap.TBL_Radicacion AS R',DB::raw('R.FK_TBL_Anteproyecto_id'),'=',DB::raw('A.PK_NPRY_idMinr008'))
                ->join('gesap.tbl_encargados AS E',function($join){
                    $join->on(DB::raw('E.FK_TBL_Anteproyecto_id'),'=',DB::raw('A.PK_NPRY_idMinr008'))
                    ->where('NCRD_Cargo','=',"Director")
                    ->where('FK_developer_user_id','=',1);
                })                       
                
                ->select('A.*','R.RDCN_Min','R.RDCN_Requerimientos',
                    DB::raw('IFNULL(('
                        .$this->getSql(
                                DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                    ->where('NCRD_Cargo','=','Director')
                                    ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                                )
                        .'),"'.$result.'")AS Director'
                    ),
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Director')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS DirectorCedula'
                    ),     
                    DB::raw('IFNULL(('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('NCRD_Cargo','=','Jurado 1')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .'),"'.$result.'")AS Jurado1'
                    ), 
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Jurado 1')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS Jurado1Cedula'
                    ),      
                    DB::raw('IFNULL(('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('NCRD_Cargo','=','Jurado 2')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .'),"'.$result.'")AS Jurado2'
                    ), 
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Jurado 2')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS Jurado2Cedula'
                    ),    
                    DB::raw('IFNULL(('
                        .$this->getSql(
                                DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('NCRD_Cargo','=','Estudiante 1')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .'),"'.$result.'")AS estudiante1'
                    ),  
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Estudiante 1')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS estudiante1Cedula'
                    ), 
                    DB::raw('IFNULL(('
                        .$this->getSql(
                                DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('NCRD_Cargo','=','Estudiante 2')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .'),"'.$result.'")AS estudiante2'
                    ), 
                         
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Estudiante 2')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS estudiante2Cedula'
                    )
                );
        return Datatables::of(DB::select($this->getSql($anteproyectos)))->addIndexColumn()->make(true);
   }
    
    public function ListJurado(){
       
            $result="NO ASIGNADO";
        $anteproyectos = 
            DB::table('gesap.TBL_Anteproyecto AS A')
                ->join('gesap.TBL_Radicacion AS R',DB::raw('R.FK_TBL_Anteproyecto_id'),'=',DB::raw('A.PK_NPRY_idMinr008'))
                ->join('gesap.tbl_encargados AS E',function($join){
                    $join->on(DB::raw('E.FK_TBL_Anteproyecto_id'),'=',DB::raw('A.PK_NPRY_idMinr008'))
                    ->where(function($query){
                      $query->where('E.NCRD_Cargo', '=', "Jurado 1")  ;
                      $query->orwhere('E.NCRD_Cargo', '=', "Jurado 2");
                    })
                    ->where('FK_developer_user_id','=',1);
                })                       
                
                ->select('A.*','R.RDCN_Min','R.RDCN_Requerimientos',
                    DB::raw('IFNULL(('
                        .$this->getSql(
                                DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                    ->where('NCRD_Cargo','=','Director')
                                    ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                                )
                        .'),"'.$result.'")AS Director'
                    ),
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Director')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS DirectorCedula'
                    ),     
                    DB::raw('IFNULL(('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('NCRD_Cargo','=','Jurado 1')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .'),"'.$result.'")AS Jurado1'
                    ), 
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Jurado 1')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS Jurado1Cedula'
                    ),      
                    DB::raw('IFNULL(('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('NCRD_Cargo','=','Jurado 2')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .'),"'.$result.'")AS Jurado2'
                    ), 
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Jurado 2')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS Jurado2Cedula'
                    ),    
                    DB::raw('IFNULL(('
                        .$this->getSql(
                                DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('NCRD_Cargo','=','Estudiante 1')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .'),"'.$result.'")AS estudiante1'
                    ),  
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Estudiante 1')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS estudiante1Cedula'
                    ), 
                    DB::raw('IFNULL(('
                        .$this->getSql(
                                DB::table('gesap.tbl_encargados')
                                    ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                    ->select(DB::raw('concat(name," ",lastname)'))
                                ->where('NCRD_Cargo','=','Estudiante 2')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .'),"'.$result.'")AS estudiante2'
                    ), 
                         
                    DB::raw('('
                        .$this->getSql(
                            DB::table('gesap.tbl_encargados')
                                ->join('developer.users','tbl_encargados.FK_developer_user_id','=','developer.users.id')
                                ->select('FK_developer_user_id')
                                ->where('NCRD_Cargo','=','Estudiante 2')
                                ->where('tbl_encargados.FK_TBL_Anteproyecto_id','=',DB::raw('A.PK_NPRY_idMinr008'))
                            )
                        .')AS estudiante2Cedula'
                    )
                );
        return Datatables::of(DB::select($this->getSql($anteproyectos)))->addIndexColumn()->make(true);
   }

}
