<?php

namespace App\Container\AdminRegist\Src\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Container\Overall\Src\Facades\AjaxResponse;
use App\Container\AdminRegist\Src\Help;

class HelpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adminregist.help.tablaHelp');
    }

    public function index_ajax(Request $request)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            return view('adminregist.help.ajaxTablaHelp');
        } else {
            return AjaxResponse::fail(
                '¡Lo sentimos!',
                'No se pudo completar tu solicitud.'
            );
        }
    }

    public function data(Request $request)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $help = Help::query();

            return DataTables::of($help)
                ->addColumn('pregunta', function ($help) {
                    return "<textarea readonly class='form-control'>" . $help->pregunta . "</textarea>";
                })
                ->rawColumns(['pregunta'])
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make(true);
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }

    public function create(Request $request)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            return view('adminregist.help.registroHelp');
        }
        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }

    public function store(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {

            Help::create([
                'pregunta' => $request['pregunta'],
                'respuesta' => $request['respuesta'],
            ]);
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Pregunta Agregada correctamente.'
            );
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('DELETE')) {

            $help = Help::where('id', '=', $id)->delete();

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

    public function edit(Request $request, $id)
    {
        if ($request->ajax() && $request->isMethod('GET')) {
            $help = Help::find($id);
            return view('adminregist.help.editHelp',
                [
                    'help' => $help,
                ]);
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );

    }

    public function update(Request $request)
    {
        if ($request->ajax() && $request->isMethod('POST')) {
            $help = Help::find($request['id']);
            $help->pregunta = $request['pregunta'];
            $help->respuesta = $request['respuesta'];
            $help->save();
            return AjaxResponse::success(
                '¡Bien hecho!',
                'Datos modificados correctamente.'
            );
        }

        return AjaxResponse::fail(
            '¡Lo sentimos!',
            'No se pudo completar tu solicitud.'
        );
    }


}