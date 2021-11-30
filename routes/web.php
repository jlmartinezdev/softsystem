<?php
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

//Articulos
Route::get('inf/articulo','ArticuloController@informe')->name('articulo@informe');
Route::get('articulo','ArticuloController@index')->name('articulo');
Route::get('articulo/buscar','ArticuloController@getArticulo')->name('articulo@buscar');
Route::get('articulo/ultimo','ArticuloController@getUltimo')->name('articulo@ultimo');
Route::put('articulo','ArticuloController@getByCodigo');
Route::post('articulo/res','ArticuloController@reservarCodigo')->name('articulo@reservarCodigo');
Route::put('articulo/{id}','ArticuloController@update')->name('articulo.update');
Route::delete('articulo/res/{id}','ArticuloController@destroy')->name('articulo.destroy');
//STOCK
Route::delete('stock/{id}','StockController@destroy');
Route::post('stock/{id}','StockController@update');
//VENTA
Route::get('infventa','VentaController@indexInf')->name('infventa');
Route::get('infventa/fecha','VentaController@getVentaByFecha')->name('infventa.fecha');
Route::get('infventa/cliente','VentaController@getVentaByCliente')->name('infventa.cliente');
Route::post('infventa/chart','VentaController@getVentaChart')->name('infventa.chart');
Route::get('infventa/detalle/{id}','VentaController@getDetalle');
Route::get('infventa/articulo','VentaController@getVentaArticulo');
Route::get('venta','VentaController@index')->name('venta');
Route::post('venta','VentaController@store');
Route::get('venta/imprimir','VentaController@imprimir');
//COMPRA
Route::get('infcompra','CompraController@indexInf')->name('infcompra');
Route::get('infcompra/detalle/{id}','CompraController@getDetalle');
Route::get('compra','CompraController@index')->name('compra');
Route::post('compra','CompraController@store');
Route::get('compra/historial','CompraController@getHistorialPrecio');
Route::get('infcompra/fecha','CompraController@getCompraByFecha');
//PROVEEDOR
Route::get('proveedor/all','ProveedorController@getAll');
Route::get('proveedor/buscar','ProveedorController@buscar');

//CAJA
Route::get('aperturacierre','AperturaController@index')->name('apertura');
Route::post('aperturaciere/open','AperturaController@store')->name('apertura.add');
Route::post('aperturaciere/cierre','AperturaController@update')->name('apertura.close');
Route::get('cierre/{operacion}','AperturaController@indexCierre')->name('cierre');
Route::get('aperturacierre/{sucursal}','AperturaController@getStatu');
Route::get('movimiento','MovimientoCajaController@index')->name('movimiento');
Route::get('movimiento/{nro_operacion}','MovimientoCajaController@getAll');
Route::post('movimiento','MovimientoCajaController@store');

//COBROS
Route::get('infctacobrar','CtaCobrarController@indexInf')->name('infctacobrar');
Route::get('ctas_cobrar/buscar','CtaCobrarController@getCtaCobrar')->name('ctas_cobrar@buscar');
Route::post('infctacobrar','CtaCobrarController@infToPdf')->name('infctacobrar@pdf');
//Usuario
Route::get('usuario','UserController@index')->name('usuario');
Route::get('usuario/all','UserController@showAll')->name('showalluser');
Route::post('usuario','UserController@store');
Route::delete('usuario/{id}','UserController@destroy');

//REFERENCIAL
Route::get('v1/unidad/all','UnidadController@All');
Route::get('seccion/all','SeccionController@All');
Route::get('seccion','SeccionController@index')->name('seccion.index');
Route::post('seccion','SeccionController@store');
Route::post('seccion/{id}','SeccionController@update');
Route::delete('seccion/{id}','SeccionController@destroy');
Route::get('reffactura','ReffacturaController@index')->name('reffactura.index');
Route::get('reffactura/all','ReffacturaController@getAll');
Route::post('reffactura','ReffacturaController@store');
Route::post('reffactura','ReffacturaController@update');
//SUCURSAL
Route::get('sucursal/all','SucursalController@All');
Route::get('sucursal/set','SucursalController@set')->name('sucursal.set');
//STOCK
Route::get('stock/{id}','StockController@show');

//cliente
Route::get('cliente/buscar','ClienteController@buscar');
Route::get('cliente','ClienteController@index')->name('cliente.index');
Route::delete('cliente/{id}','ClienteController@destroy');
Route::post('cliente','ClienteController@store');
Route::post('cliente/update','ClienteController@update');
//PDF
Route::get('pdf/boletaventa/{id}','VentaController@pdfboleta')->name('pfd.boletaventa');
Route::get('pdf/boletacompra/{id}','CompraController@pdfboleta')->name('pdf.boletacompra');

Route::get('/clear-cache', 'AperturaController@comando');
//TICKET
Route::get('ticket/factura','VentaController@ticketfactura');

/*
Modificar fecha en informe
url api buscar en venta


*/