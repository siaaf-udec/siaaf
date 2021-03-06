<?php
/**
 * Created by PhpStorm.
 * User: Edwin Clavijo
 * Date: 19/06/2017
 * Time: 2:20 PM
 */

namespace App\Container\Acadspace\src\Controllers;

Use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Container\Acadspace\src\Asistencia;
use App\Container\Acadspace\src\Aulas;
use App\Container\Acadspace\src\Espacios;
use App\Container\Overall\Src\Facades\AjaxResponse;

class AsistenciaController extends Controller
{
    /**
     * Retorna la vista de control estudiante
     * @param Request $request
     * @return \Illuminate\View\View | \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public function asisEst(Request $request)
    {
        if ($request->isMethod('GET')) {
            $espa = new espacios();
            $espacios = $espa->pluck('ESP_Nombre_Espacio', 'PK_ESP_Id_Espacio');
            return view('acadspace.controlEstudiante',
                [
                    'espacios' => $espacios->toArray()
                ]);
        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }

    /**
     * Retorna la vista de control externo
     * @param Request $request
     * @return \Illuminate\View\View | \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public function asisInvitado(Request $request)
    {
        if ($request->isMethod('GET')) {
            $espa = new espacios();
            $espacios = $espa->pluck('ESP_Nombre_Espacio', 'PK_ESP_Id_Espacio');
            return view('acadspace.controlInvitado',
                [
                    'espacios' => $espacios->toArray()
                ]);
        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }

    /**
     * Retorna la vista de control docente
     * @param Request $request
     * @return \Illuminate\View\View | \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public function asisDoc(Request $request)
    {
        if ($request->isMethod('GET')) {
            $espa = new espacios();
            $espacios = $espa->pluck('ESP_Nombre_Espacio', 'PK_ESP_Id_Espacio');
            return view('acadspace.controlDocente',
                [
                    'espacios' => $espacios->toArray()
                ]);
        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }

    /**
     * Recibe el parametro espacio y retorna aulas
     * @param \Illuminate\Http\Request $request
     * @param varchar $espacio
     * @return \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public function cargarSalasAsitencia(Request $request, $espacio)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $aula = Aulas::where('FK_SAL_Id_Espacio', '=', $espacio)
                ->get();
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Datos recibidos correctamente.', $aula
            );
        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }


    /**
     * Verificando que el estudiante exista.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response | \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public function verificarEstudiante(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {

            $validator = Validator::make($request->all(), [
                'codigo' => 'exists:users_udec,number_document'
            ]);
            if (empty($validator->errors()->all())) {
                return response('true');
            } else {
                return response('false');
            }
        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );

    }

    /**
     * Verificando que el docente exista.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response | \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public function verificarDocente(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {

            $validator = Validator::make($request->all(), [
                'codigo' => 'exists:users_udec,number_document'
            ]);
            if (empty($validator->errors()->all())) {
                return response('true');
            } else {
                return response('false');
            }

        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );

    }

    /**
     * Registra ingreso del estudiante
     * @param Request $request
     * @return \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public function regisAsistenciaEst(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            $model = new Asistencia();
            $model->ASIS_Id_Identificacion = $request['ASIS_Id_Identificacion'];
            $model->ASIS_Id_Carrera = $request['ASIS_Id_Carrera'];
            $model->FK_ASIS_Id_Aula = $request['ASIS_Espacio'];
            $model->ASIS_Tipo_Practica = 1;
            $model->FK_ASIS_Id_Espacio = $request['ASIS_Espacio_Academico'];

            $model->save();
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Ingreso registrado correctamente.'
            );

        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }

    /**
     * Registra ingreso del externo
     * @param Request $request
     * @return \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public function regisAsistInv(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {

            $model = new Asistencia();
            $model->ASIS_Id_Identificacion = $request['ASIS_Id_Identificacion'];
            $model->ASIS_Id_Carrera = 0;
            $model->ASIS_Tipo_Practica = 1;
            $model->FK_ASIS_Id_Aula = $request['ASIS_Espacio'];
            $model->FK_ASIS_Id_Espacio = $request['ASIS_Espacio_Academico'];

            $model->save();
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Ingreso registrado correctamente.'
            );
        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );


    }

    /**
     * Registra ingreso del docente
     * @param Request $request
     * @return \App\Container\Overall\Src\Facades\AjaxResponse
     */
    public
    function regisAsistenciaDoc(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {

            $model = new Asistencia();

            $model->ASIS_Id_Identificacion = $request['ASIS_Id_Identificacion'];
            $model->ASIS_Id_Carrera = $request['ASIS_Id_Carrera'];
            $model->ASIS_Nombre_Materia = $request['ASIS_Nombre_Materia'];
            $model->ASIS_Cant_Estudiantes = $request['ASIS_Cant_Estudiantes'];
            $model->ASIS_Tipo_Practica = 2;
            $model->FK_ASIS_Id_Aula = $request['ASIS_Espacio'];
            $model->FK_ASIS_Id_Espacio = $request['ASIS_Espacio_Academico'];

            $model->save();
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Ingreso registrado correctamente.'
            );

        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );

    }

}