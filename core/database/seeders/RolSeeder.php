<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\RolPermit;
use App\Models\Permit;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol = new Rol();
        $rol->name = "Administrador";
        $rol->save();

        $rol = new Rol();
        $rol->name = "Recolector";
        $rol->save();

        $rol = new Rol();
        $rol->name = "Secretaria";
        $rol->save();

        $rol = new Rol();
        $rol->name = "Viajero";
        $rol->save();

        $rol = new Rol();
        $rol->name = "Contador";
        $rol->save();

        $rol = new Rol();
        $rol->name = "Encargado Houston";
        $rol->save();

        $rol = new Rol();
        $rol->name = "Encargada Oficinas";
        $rol->save();
        // CATEGORY
        $permitParent = new Permit;
        $permitParent->name = "Categorias";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista categoría";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear categoría";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar categoría";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar categoría";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END CATEGORY

        // OFFICE USA
        $permitParent = new Permit;
        $permitParent->name = "Oficinas USA";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Mostrar";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END OFFICE USA

        // OFFICE GT
        $permitParent = new Permit;
        $permitParent->name = "Oficinas GT";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Mostrar";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END OFFICE GT

        // ACCOUNT
        $permitParent = new Permit;
        $permitParent->name = "Cuentas";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista cuenta";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear cuenta";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar cuenta";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar cuenta";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END ACCOUNT

        // PERSONAL ACCOUNTS
        $permitParent = new Permit;
        $permitParent->name = "Cuentas Personales";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista cuenta personal";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear cuenta personal";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar cuenta personal";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar cuenta personal";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END PERSONAL ACCOUNTS

        // CASH REGISTER
        $permitParent = new Permit;
        $permitParent->name = "Cajas";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista caja";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear caja";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar caja";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar caja";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END CASH REGISTER

        // CHARGE
        $permitParent = new Permit;
        $permitParent->name = "Cobros";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista de cobros";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Reporte";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // CHARGE

        // PRICE USA
        $permitParent = new Permit;
        $permitParent->name = "Precios USA";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista precios USA";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear precios USA";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar precios USA";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar precios USA";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END PRICE USA

        // TRANSACTIONS
        $permitParent = new Permit;
        $permitParent->name = "Transacciones";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista Transacciones";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Movimiento";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Retiro/Ingreso";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END TRANSACTIONS

        // CUSTOMERS
        $permitParent = new Permit;
        $permitParent->name = "Clientes";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Listar clientes";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear cliente";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar cliente";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar cliente";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END CUSTOMERS

        // BAGS
        $permitParent = new Permit;
        $permitParent->name = "Maletas";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Manifiesto";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Descargar manifiesto";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Números";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Estado";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Lista de cobros";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END BAGS

        // ENVIOS
        $permitParent = new Permit;
        $permitParent->name = "Envios a USA";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista envio a USA";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear envio a USA";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar envio a USA";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar envio a USA";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END PRICE USA

        // RECOLLECT
        $permitParent = new Permit;
        $permitParent->name = "Recolección";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista recolección";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear recolección";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar recolección";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Aceptar recolección";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Rechazar recolección";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END RECOLLECT

        // PACKAGES
        $permitParent = new Permit;
        $permitParent->name = "Paquetes";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Listar paquetes";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear paquete";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar paquete";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar paquete";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END PACKAGES

        // PACKAGES UPS
        $permitParent = new Permit;
        $permitParent->name = "Paquetes UPS";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Listar paquetes UPS";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear paquete UPS";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar paquete UPS";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar paquete UPS";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END PACKAGES UPS

        // BALER
        $permitParent = new Permit;
        $permitParent->name = "Empacadoras";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Listar empacadoras";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear empacadora";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar empacadora";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar empacadora";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END BALER

        // USUARIOS
        $permitParent = new Permit;
        $permitParent->name = "Usuarios";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista usuario";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear usuario";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar usuario";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar usuario";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END USUARIOS

        // ROL
        $permitParent = new Permit;
        $permitParent->name = "Rol";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista rol";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Crear rol";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Editar rol";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Elimar rol";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END ROL

        // PERMISO
        $permitParent = new Permit;
        $permitParent->name = "Permiso";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Lista permiso";
        $permit->idParent = $permitParent->id;
        $permit->save();

        $permit = new Permit;
        $permit->name = "Activar/Desactivar permiso";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END PERMISO

        // SETTINGS
        // PERMISO
        $permitParent = new Permit;
        $permitParent->name = "Ajustes";
        $permitParent->save();

        $permit = new Permit;
        $permit->name = "Mostrar";
        $permit->idParent = $permitParent->id;
        $permit->save();
        // END SETTINGS


        $permit = Permit::get();
        for ($i = 1; $i <= count($permit); $i++) {
            $permitsRol = new RolPermit;
            $permitsRol->idRol = 1;
            $permitsRol->idPermit = $i;
            $permitsRol->status = 1;
            $permitsRol->save();

            $permitsRol = new RolPermit;
            $permitsRol->idRol = 2;
            $permitsRol->idPermit = $i;
            $permitsRol->status = 0;
            $permitsRol->save();

            $permitsRol = new RolPermit;
            $permitsRol->idRol = 3;
            $permitsRol->idPermit = $i;
            $permitsRol->status = 0;
            $permitsRol->save();

            $permitsRol = new RolPermit;
            $permitsRol->idRol = 4;
            $permitsRol->idPermit = $i;
            $permitsRol->status = 0;
            $permitsRol->save();

            $permitsRol = new RolPermit;
            $permitsRol->idRol = 5;
            $permitsRol->idPermit = $i;
            $permitsRol->status = 0;
            $permitsRol->save();

            $permitsRol = new RolPermit;
            $permitsRol->idRol = 6;
            $permitsRol->idPermit = $i;
            $permitsRol->status = 0;
            $permitsRol->save();

            $permitsRol = new RolPermit;
            $permitsRol->idRol = 7;
            $permitsRol->idPermit = $i;
            $permitsRol->status = 0;
            $permitsRol->save();
        }
    }
}
