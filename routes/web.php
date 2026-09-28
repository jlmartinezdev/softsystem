<?php
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::group(['middleware' => ['administrador']], function () {
    // ADMINISTRACIÓN DE PRIVILEGIOS Y ROLES (Solo Administrador)
    Route::get('permiso', 'PermisoController@index')->name('permiso.index');
    Route::get('permiso/rol/{cod_rol}', 'PermisoController@getPermisosRol')->name('permiso.rol');
    Route::post('permiso', 'PermisoController@store')->name('permiso.store');
    Route::post('permiso/rol', 'PermisoController@guardarRol')->name('permiso.rol.guardar');
    Route::delete('permiso/rol/{cod_rol}', 'PermisoController@eliminarRol')->name('permiso.rol.eliminar');
    Route::post('permiso/copiar', 'PermisoController@copiarPermisos')->name('permiso.copiar');
});

// Auditoría y Anulaciones (Gobernadas por permisos por rol en controladores)
Route::get('anularventa','VentaController@indexanular')->name('anularventa');
Route::get('anularcobro','CtaCobrarController@indexanular')->name('anularcobro');
Route::get('anularcompra','CompraController@indexanular')->name('anularcompra');
Route::post('anular_venta','VentaController@destroy');
Route::post('anular_cobro','CtaCobrarController@destroy');
Route::get('cobros_recientes','CtaCobrarController@getCobrosRecientes');
Route::post('anular_compra','CompraController@destroy');
Route::get('compras_recientes','CompraController@getComprasRecientes');

// Usuarios del Sistema
Route::post('usuario', 'UserController@store');
Route::delete('usuario/{id}', 'UserController@destroy');
Route::get('usuario', 'UserController@index')->name('usuario');

// Informes y Exportaciones
Route::get('excel/articulos_costo/','ArticuloController@export_costo');
Route::get('resumen','ResumenController@index')->name('resumen');
Route::get('resumen/datos','ResumenController@resumen');
Route::post('resumen/enviar-correo','ResumenController@enviarCorreo')->name('resumen.email');
Route::get('caja/movimiento/{id}','MovimientoCajaController@informe')->name('caja.informe');
    //Articulos
    
    Route::get('inf/articulo', 'ArticuloController@informe')->name('articulo@informe');
    Route::get('articulo', 'ArticuloController@index')->name('articulo');
    Route::get('articulo/cm', 'ArticuloController@cm')->name('articulo.cm');
    Route::get('articulo/cm/{id}', 'ArticuloController@cmupdate')->name('articulo.cmupdate');
    Route::get('articulo/buscar', 'ArticuloController@getArticulo')->name('articulo@buscar');
    Route::get('articulo/promo/{id}', 'ArticuloController@promoDetalle')->name('articulo.promo');
    Route::get('articulo/ultimo', 'ArticuloController@getUltimo')->name('articulo@ultimo');
    Route::get('articulo/precios/{id}','ArticuloController@getPrecios');
    Route::put('articulo/id/', 'ArticuloController@getById');
    Route::put('articulo', 'ArticuloController@getByCodigo');
    Route::post('articulo', 'ArticuloController@store');
    Route::put('articulo/{id}', 'ArticuloController@update')->name('articulo.update');
    Route::delete('articulo/res/{id}', 'ArticuloController@destroy')->name('articulo.destroy');
    Route::get('articulo/validar/cbarra/{cbarra}','ArticuloController@validarCbarra');
    Route::post('articulo/capturar', 'ArticuloController@capturarImagen');
    Route::post('articulo/imagen', 'ArticuloController@subirImagen');

    // COMBOS
    Route::get('combo', 'ComboController@index')->name('combo.index');
    Route::post('combo', 'ComboController@store');
    Route::put('combo/{id}', 'ComboController@update');
    Route::delete('combo/{id}', 'ComboController@destroy');
    Route::get('combo/articulos', 'ComboController@buscarArticulos');
    Route::get('combo/activos', 'ComboController@listActivos')->name('combo.activos');
    Route::post('combo/redondear', 'ComboController@redondear');
    Route::get('combo/validar-codigo', 'ComboController@validarCodigo');

    // OFERTAS
    Route::get('oferta', 'OfertaController@index')->name('oferta.index');
    Route::post('oferta', 'OfertaController@store');
    Route::put('oferta/{id}', 'OfertaController@update');
    Route::delete('oferta/{id}', 'OfertaController@destroy');
    Route::get('oferta/articulos', 'OfertaController@buscarArticulos');
    Route::get('oferta/activas', 'OfertaController@activas')->name('oferta.activas');
    Route::get('oferta/validar-codigo', 'OfertaController@validarCodigo');
    

    
    
    
    //STOCK
    Route::delete('stock/{id}', 'StockController@destroy');
    Route::post('stock/{id}', 'StockController@update');
    //INVENTARIO
    Route::get('inventario','StockController@infstock')->name('infstock');
    Route::get('inventario/fecha','ArticuloController@getInventario');
    //VENTA
    Route::get('infventa', 'VentaController@indexInf')->name('infventa');
    Route::get('infventa/fecha', 'VentaController@getVentaByFecha')->name('infventa.fecha');
    Route::get('infventa/cliente', 'VentaController@getVentaByCliente')->name('infventa.cliente');
    Route::post('infventa/chart', 'VentaController@getVentaChart')->name('infventa.chart');
    Route::get('infventa/detalle/{id}', 'VentaController@getDetalle');
    Route::get('venta/cabecera/{id}','VentaController@getCabecera');
    Route::get('infventa/articulo', 'VentaController@getVentaArticulo');
    Route::get('venta', 'VentaController@index')->name('venta');
   
    Route::post('venta', 'VentaController@store');
    Route::get('venta/imprimir', 'VentaController@imprimir')->name('infventa.imprimir');
    Route::get('venta/facturar/{id}', 'FacturarController@index')->name('venta.facturar');
    Route::post('venta/facturar', 'FacturarController@store');
    Route::delete('venta/facturar/{id}', 'FacturarController@destroy');
    
    //PRESUPUESTO
    Route::get('presupuesto', 'PresupuestoController@index')->name('presupuesto.index');
    Route::get('presupuesto/crear', 'PresupuestoController@create')->name('presupuesto.create');
    Route::post('presupuesto', 'PresupuestoController@store')->name('presupuesto.store');
    Route::get('presupuesto/{id}', 'PresupuestoController@show')->name('presupuesto.show');
    Route::put('presupuesto/{id}/estado', 'PresupuestoController@cambiarEstado')->name('presupuesto.estado');
    Route::delete('presupuesto/{id}', 'PresupuestoController@destroy')->name('presupuesto.destroy');
    Route::get('presupuesto/{id}/pdf', 'PresupuestoController@pdf')->name('presupuesto.pdf');
    Route::get('presupuesto/{id}/para-venta', 'PresupuestoController@getParaVenta')->name('presupuesto.paraventa');
    
    //COMPRA
    Route::get('infcompra', 'CompraController@indexInf')->name('infcompra');
    Route::get('infcompra/detalle/{id}', 'CompraController@getDetalle');
    Route::get('compra', 'CompraController@index')->name('compra');
    Route::post('compra', 'CompraController@store');
    Route::get('compra/cabecera/{id}','CompraController@getCabecera');
    Route::get('compra/historial', 'CompraController@getHistorialPrecio');
    Route::get('infcompra/fecha', 'CompraController@getCompraByFecha');
    //PROVEEDOR
    Route::get('proveedor/all', 'ProveedorController@getAll');
    Route::get('proveedor/buscar', 'ProveedorController@buscar');
    Route::get('proveedor', 'ProveedorController@index')->name('proveedor.index');
    Route::post('proveedor', 'ProveedorController@store');
    Route::post('proveedor/{id}', 'ProveedorController@update');
    Route::delete('proveedor/{id}', 'ProveedorController@destroy');

    //CAJA
    Route::get('aperturacierre', 'AperturaController@index')->name('apertura');
    Route::post('aperturaciere/open', 'AperturaController@store')->name('apertura.add');
    Route::post('aperturaciere/cierre', 'AperturaController@update')->name('apertura.close');
    Route::get('cierre/{operacion}', 'AperturaController@indexCierre')->name('cierre');
    Route::get('aperturacierre/{sucursal}', 'AperturaController@getStatu');
    Route::get('movimiento', 'MovimientoCajaController@index')->name('movimiento');
    Route::get('movimiento/{nro_operacion}', 'MovimientoCajaController@getAll');
    Route::post('movimiento', 'MovimientoCajaController@store');

    //COBROS
    Route::get('infctacobrar', 'CtaCobrarController@indexInf')->name('infctacobrar');
    Route::get('ctas_cobrar/buscar', 'CtaCobrarController@getCtaCobrar')->name('ctas_cobrar@buscar');
    Route::post('infctacobrar', 'CtaCobrarController@infToPdf')->name('infctacobrar@pdf');
    Route::get('cobro','CtaCobrarController@index')->name('cobro');
    Route::get('cobro/{id}','CtaCobrarController@getCobroById')->name('cobro.id');
    Route::get('cuotas/{id}','CtaCobrarController@getCuotas');
    Route::post('cobro','CtaCobrarController@store');
    Route::get('infcobro','CtaCobrarController@indexCobrado')->name('infcobro');
    Route::get('infcobro/fecha','CtaCobrarController@getCobroFecha');
    Route::get('infcobro/detalle/{id}','CtaCobrarController@getDetalleCobro');
    //Usuario
    
    
   

    //REFERENCIAL
    
    //CONFIGURACION
    Route::get('ajustes','AjusteController@index')->name('ajuste.index');
    Route::post('ajustes','AjusteController@update');
    Route::post('ajustes/mail/test','AjusteController@testMail')->name('ajuste.mail.test');
    Route::post('ajustes/camara/test','AjusteController@testCamara')->name('ajuste.camara.test');
    
    //SUCURSAL
    Route::get('sucursal/all', 'SucursalController@All');
    Route::get('sucursal/set', 'SucursalController@set')->name('sucursal.set');
    //STOCK
    Route::get('stock/{id}', 'StockController@show');
    //EMPRESA
    Route::get('empresa','EmpresaController@index')->name('empresa.index');
    Route::post('empresa','EmpresaController@update');
    //SIFEN
    Route::get('sifen', 'SifenConfigController@index')->name('sifen.index');
    Route::post('sifen', 'SifenConfigController@update')->name('sifen.update');
    Route::get('sifen/all', 'SifenConfigController@getAll');
    Route::get('sifen/documentos', 'SifenConfigController@getDocumentos')->name('sifen.documentos');
    Route::post('sifen/sync', 'SifenConfigController@sincronizarEmpresa')->name('sifen.sync');
    Route::post('sifen/api/probar', 'SifenConfigController@probarApi')->name('sifen.api.probar');
    Route::post('sifen/api/token', 'SifenConfigController@obtenerTokenApi')->name('sifen.api.token');
    Route::get('sifen/laboratorio', 'SifenLaboratorioController@index')->name('sifen.laboratorio');
    Route::post('sifen/laboratorio/ejecutar', 'SifenLaboratorioController@ejecutar')->name('sifen.laboratorio.ejecutar');
    //CIUDAD
    Route::get('ciudad','CiudadController@index')->name('ciudad.index');
    Route::get('ciudad/all','CiudadController@all')->name('ciudad.all');
    Route::post('ciudad', 'CiudadController@store');
    Route::post('ciudad/{id}', 'CiudadController@update');
    Route::put('ciudad/{id}', 'CiudadController@update');
    Route::delete('ciudad/{id}', 'CiudadController@destroy');
    
    //PDF A IMPRIMIR 
    Route::get('pdf/boletaventa/{id}', 'VentaController@pdfboleta')->name('pfd.boletaventa');
    Route::get('pdf/boletacompra/{id}', 'CompraController@pdfboleta')->name('pdf.boletacompra');
    route::get('pdf/recibo/{id}','VentaController@pdfrecibo')->name('pdf.reciboventa');
    //EXCEL 
    Route::get('excel/articulos/','ArticuloController@export');
    Route::get('excel/articulosprecios/','ArticuloController@exportPrecio');
    Route::get('excel/ctascobrar','CtaCobrarController@exportCtasAll');
    // DOCUMENTO A IMPRIMIR 
    Route::get('documento/recibocobro/{id}','CtaCobrarController@printRecibo');
    Route::get('documento/extractocuenta/{id}','CtaCobrarController@printExtracto');
    Route::get('documento/recibocobro/d/{id}','CtaCobrarController@printReciboD');
    Route::get('/clear-cache', 'AperturaController@comando');
    //TICKET
    Route::get('ticket/factura/{id}', 'FacturarController@ticket');
    Route::get('pdf/kude/{id}', 'FacturarController@kudePdf')->name('pdf.kude');
    Route::get('ticket/venta/{id}','VentaController@ticket');

Route::get('usuario/all', 'UserController@showAll')->name('showalluser');
Route::get('seccion', 'SeccionController@index')->name('seccion.index');
Route::post('seccion', 'SeccionController@store');
Route::post('seccion/{id}', 'SeccionController@update');
Route::delete('seccion/{id}', 'SeccionController@destroy');
Route::get('v1/unidad/all', 'UnidadController@All');
Route::get('seccion/all', 'SeccionController@All');


Route::get('cliente/buscar', 'ClienteController@buscar');
Route::get('cliente', 'ClienteController@index')->name('cliente.index');
Route::delete('cliente/{id}', 'ClienteController@destroy');
Route::post('cliente', 'ClienteController@store');
Route::post('cliente/update', 'ClienteController@update');
Route::post('cliente/foto', 'ClienteController@subirFotoDocumento')->name('cliente.foto.subir');
Route::post('cliente/foto/eliminar', 'ClienteController@eliminarFotoDocumento')->name('cliente.foto.eliminar');

Route::get('reffactura', 'ReffacturaController@index')->name('reffactura.index');
Route::get('reffactura/all', 'ReffacturaController@getAll');
Route::post('reffactura', 'ReffacturaController@store');
Route::post('reffactura', 'ReffacturaController@update');

Route::get('unidades', 'UnidadController@index')->name('unidades.index');
Route::get('unidades/all', 'UnidadController@all');
Route::get('unidades/create', 'UnidadController@create')->name('unidades.create');
Route::post('unidades', 'UnidadController@store')->name('unidades.store');
Route::get('unidades/{unidad}/edit', 'UnidadController@edit')->name('unidades.edit');
Route::put('unidades/{unidad}', 'UnidadController@update')->name('unidades.update');
Route::post('unidades/{unidad}', 'UnidadController@update');
Route::delete('unidades/{unidad}', 'UnidadController@destroy')->name('unidades.destroy');




/*

Modificar fecha en informe
url api buscar en venta


*/