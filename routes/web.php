<?php
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::group(['middleware' => ['administrador']], function () {
    Route::get('anularventa','VentaController@indexanular')->name('anularventa');
    Route::get('anularcobro','CtaCobrarController@indexanular')->name('anularcobro');
    Route::get('anularcompra','CompraController@indexanular')->name('anularcompra');
    Route::post('anular_venta','VentaController@destroy');
    Route::post('anular_cobro','CtaCobrarController@destroy');
    Route::post('anular_compra','CompraController@destroy');
    Route::post('usuario', 'UserController@store');
    Route::delete('usuario/{id}', 'UserController@destroy');
    Route::get('usuario', 'UserController@index')->name('usuario');
    //Stock
    Route::get('excel/articulos_costo/','ArticuloController@export_costo');
    Route::get('resumen','ResumenController@index')->name('resumen');
    Route::get('resumen/datos','ResumenController@resumen');
    //Caja
    Route::get('caja/movimiento/{id}','MovimientoCajaController@informe')->name('caja.informe');
});
    //Articulos
    
    Route::get('inf/articulo', 'ArticuloController@informe')->name('articulo@informe');
    Route::get('articulo', 'ArticuloController@index')->name('articulo');
    Route::get('articulo/cm', 'ArticuloController@cm')->name('articulo.cm');
    Route::get('articulo/cm/{id}', 'ArticuloController@cmupdate')->name('articulo.cmupdate');
    Route::get('articulo/buscar', 'ArticuloController@getArticulo')->name('articulo@buscar');
    Route::get('articulo/ultimo', 'ArticuloController@getUltimo')->name('articulo@ultimo');
    Route::get('articulo/precios/{id}','ArticuloController@getPrecios');
    Route::put('articulo/id/', 'ArticuloController@getById');
    Route::put('articulo', 'ArticuloController@getByCodigo');
    Route::post('articulo', 'ArticuloController@store');
    Route::put('articulo/{id}', 'ArticuloController@update')->name('articulo.update');
    Route::delete('articulo/res/{id}', 'ArticuloController@destroy')->name('articulo.destroy');
    Route::get('articulo/validar/cbarra/{cbarra}','ArticuloController@validarCbarra');
    
    
    
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
    Route::get('venta/facturar/{id}', 'FacturarController@index');
    Route::post('venta/facturar', 'FacturarController@store');
    Route::delete('venta/facturar/{id}', 'FacturarController@destroy');
    
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
    
    //SUCURSAL
    Route::get('sucursal/all', 'SucursalController@All');
    Route::get('sucursal/set', 'SucursalController@set')->name('sucursal.set');
    //STOCK
    Route::get('stock/{id}', 'StockController@show');
    //EMPRESA
    Route::get('empresa','EmpresaController@index')->name('empresa.index');
    Route::post('empresa','EmpresaController@update');
    //CIUDAD
    Route::get('ciudad','CiudadController@index')->name('ciudad.index');
    Route::post('ciudad', 'CiudadController@store');
    Route::post('ciudad/{id}', 'CiudadController@update');
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

Route::get('reffactura', 'ReffacturaController@index')->name('reffactura.index');
Route::get('reffactura/all', 'ReffacturaController@getAll');
Route::post('reffactura', 'ReffacturaController@store');
Route::post('reffactura', 'ReffacturaController@update');

Route::get('unidades', 'UnidadController@index')->name('unidades.index');
Route::get('unidades/create', 'UnidadController@create')->name('unidades.create');
Route::post('unidades', 'UnidadController@store')->name('unidades.store');
Route::get('unidades/{unidad}/edit', 'UnidadController@edit')->name('unidades.edit');
Route::put('unidades/{unidad}', 'UnidadController@update')->name('unidades.update');
Route::delete('unidades/{unidad}', 'UnidadController@destroy')->name('unidades.destroy');




/*

Modificar fecha en informe
url api buscar en venta


*/