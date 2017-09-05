<?php
/**
 * Created by PhpStorm.
 * User: Yeison Gomez
 * Date: 19/06/2017
 * Time: 2:20 PM
 */

namespace App\Container\Humtalent\src\Controllers;

use App\Container\Humtalent\src\Asistent;
use App\Container\Humtalent\src\Induction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Container\Users\Src\Interfaces\UserInterface;
use App\Container\Humtalent\src\Persona;
use App\Container\Humtalent\src\Permission;
use App\Container\Humtalent\src\StatusOfDocument;
use App\Container\Humtalent\src\DocumentacionPersona;
use App\Container\Overall\Src\Facades\AjaxResponse;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadoController extends Controller
{

    protected $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()//muestra todos los empleados registrados
    {
        //$empleados = Persona::all();
        return view('humtalent.empleado.tablasEmpleados');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()//muestra el formulario de registro de un nuevo empleado
    {
        return view('humtalent.empleado.registroEmpleado');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)//se alamacena en la base de datos un nuevo registro del empleado
    {
        Persona::create([
            'PK_PRSN_Cedula'          => $request['PK_PRSN_Cedula' ],
            'PRSN_Rol'                => $request['PRSN_Rol'],
            'PRSN_Nombres'            => $request['PRSN_Nombres'],
            'PRSN_Apellidos'          => $request['PRSN_Apellidos'],
            'PRSN_Telefono'           => $request['PRSN_Telefono'],
            'PRSN_Correo'             => $request['PRSN_Correo'],
            'PRSN_Direccion'          => $request['PRSN_Direccion'],
            'PRSN_Ciudad'             => $request['PRSN_Ciudad'],
            'PRSN_Salario'             => $request['PRSN_Salario'],
            'PRSN_Eps'                => $request['PRSN_Eps'],
            'PRSN_Fpensiones'         => $request['PRSN_Fpensiones'],
            'PRSN_Area'               => $request['PRSN_Area'],
            'PRSN_Caja_Compensacion'  => $request['PRSN_Caja_Compensacion'],
            'PRSN_Estado_Persona'     => $request['PRSN_Estado_Persona'],
        ]);

        $notification=array(
            'message'=>'La información del empleado fue almacenada correctamente.',
            'alert-type'=>'success'
        );
        return back()->with($notification);

    }
    public function importUsers()//se almacena en la base de datos el registro del archivo de excel
    {
        $path = Input::file('import-file')->getRealPath();
        $data = Excel::load($path, function($reader) {
        })->get();
          foreach ($data as $key=>$row) {
              Persona::create([
                  'PK_PRSN_Cedula' => $row->cedula,
                  'PRSN_Rol' => $row->rol,
                  'PRSN_Nombres' => $row->nombres,
                  'PRSN_Apellidos' => $row->apellidos,
                  'PRSN_Telefono' => $row->telefono,
                  'PRSN_Correo' => $row->correo,
                  'PRSN_Direccion' => $row->direccion,
                  'PRSN_Ciudad' => $row->ciudad,
                  'PRSN_Salario' => $row->salario,
                  'PRSN_Eps' => $row->eps,
                  'PRSN_Fpensiones' => $row->fpensiones,
                  'PRSN_Area' => $row->area,
                  'PRSN_Caja_Compensacion' => $row->cajacompensacion,
                  'PRSN_Estado_Persona' => $row->estado,
              ]);
          }
        $notification=array(
            'message'=>'La información del archivo fue almacenada correctamente.',
            'alert-type'=>'success'
        );
        return back()->with($notification);



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //return "en el show";
       // return view('humtalent.empleado.consultaEmpleado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)//presenta el formulario con los datos para editar el regitro de un empleado deseado
    {
        $empleado = Persona::find($id);
        return view('humtalent.empleado.editarEmpleado', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)//re realiza la actualización de los datos
    {
        $empleado= Persona::find($id);
        $empleado->fill($request->all());
        //$empleado-> PRSN_Rol = $request['PRSN_Rol'];
        //$empleado-> PRSN_Estado_Persona = $request['PRSN_Estado_Persona'];
        $empleado->save();
        $notification=array(
            'message'=>'La información del empleado fue modificada correctamente',
            'alert-type'=>'info'
        );
        return back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)//se realiza la eliminación de un registro de empleado en caso de que asi se desee
    {
        if($request->ajax() && $request->isMethod('DELETE')){
            Induction::with('Persona')->where('FK_TBL_Persona_Cedula',$id)->delete();
            StatusOfDocument::with('Persona')->where('FK_TBL_Persona_Cedula',$id)->delete();
            Asistent::with('Persona')->where('FK_TBL_Persona_Cedula',$id)->delete();
            Permission::where('FK_TBL_Persona_Cedula',$id)->delete();
            Persona::destroy($id);
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Datos eliminados correctamente.'
            );
        }else{
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );
        }
    }


}