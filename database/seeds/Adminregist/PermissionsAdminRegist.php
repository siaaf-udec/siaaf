<?php

use Illuminate\Database\Seeder;
/*
 * Modelos
 */
use App\Container\Permissions\src\Permission;
use app\Container\Permissions\src\Role;

class PermissionsAdminRegist extends Seeder
{

    public function run()
    {
        $roleAdmin = Role::where('name' , 'Adminis_AdminRegist')->get(['id'])->first();
        $roleIngre = Role::where('name' , 'Registro_AdminRegist')->get(['id'])->first();
        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_MODULE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso completo a la modulo de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);
        $permissionAdminRegist->roles()->attach($roleIngre);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_CHART';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso completo a las graficas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_REPORT';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso completo a los reportes de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_REPORT_DATE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso al reporte por fechas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_REPORT_NOVE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso al reporte por novedades de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_REPORT_REGIST';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso al reporte general de ingreso de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_PREFRE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso a las preguntas frecuentes de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);
        $permissionAdminRegist->roles()->attach($roleIngre);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_ADPREG';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso a la administración de las preguntas y respuestas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_REINGRE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso al registro de ingreso de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);
        $permissionAdminRegist->roles()->attach($roleIngre);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_HISNOV';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso al historial de ingresos de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_USER';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso al administración de usuarios.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_USER_CREATE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso a la inscripción de usuarios.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);
        $permissionAdminRegist->roles()->attach($roleIngre);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_PRE_CREATE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso para crear preguntas y respuestas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_PRE_DELETE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso para eliminar preguntas y respuestas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_PRE_UPDATE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso para actualizar preguntas y respuestas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_ADNOV';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso a la administración de novedades de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_NOV_CREATE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso para crear novedades de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_NOV_DELETE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso para eliminar novedades de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_NOV_UPDATE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso para actualizar novedades de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_ADSU';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso a la administración de preguntas sugeridas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_SU_CREATE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso para crear preguntas sugeridas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);
        $permissionAdminRegist->roles()->attach($roleIngre);

        //Inicio De Permisos Generales De Los Roles
        $permissionAdminRegist = new Permission;
        $permissionAdminRegist->name = 'ADMINREGIST_SU_DELETE';
        $permissionAdminRegist->display_name = 'Admisiones y registro';
        $permissionAdminRegist->description = 'Acceso para eliminar preguntas sugeridas de admisiones y registro.';
        $permissionAdminRegist->module_id = 11;
        $permissionAdminRegist->save();
        $permissionAdminRegist->roles()->attach($roleAdmin);
    }
}