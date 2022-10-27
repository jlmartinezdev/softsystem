<template>
            <div class="table-responsive-sm">
                <table id="tabla" class="table table-striped table-hover table-sm">
                    <thead>
                        <tr class="text-uppercase">
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>Seccion</th>
                            <th class="text-right">Precio</th>
                            <th>Stock</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-family: Arial,Helvetica,sans-serif;">
                        <template v-for="a in articulos">
                            <tr :class="{'text-danger': a.cantidad==0}">
                                <td>@{{ a.producto_c_barra }}</td>
                                <td>@{{ a.producto_nombre }}</td>
                                <td>@{{ a.present_descripcion }}</td>
                                <td class="font-weight-bold text-right">@{{ separador(a.pre_venta1) }}</td>
                                <td class="text-center font-weight-bold">@{{ a.cantidad }}</td>
                                <td>
                                `<div class="btn-group">
                                        <button class="btn btn-link dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="fa fa-bars"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button class='dropdown-item' @click="showEArticulo(a)"><span
                                                    class="fa fa-edit text-primary"></span> Editar</button>
                                            <button class='dropdown-item' @click="verPreciosCredito( a.ARTICULOS_cod, a.producto_costo_compra)"><span
                                                class="fa fa-edit text-primary"></span> Ver Precios Credito</button>
                                            <button class='dropdown-item'
                                                @click="modalDelete( a.ARTICULOS_cod, a.producto_nombre)"><span
                                                    class="fa fa-trash text-primary"></span> Eliminar</button>
                                            <button class='dropdown-item'
                                                @click="showDetalle( a.ARTICULOS_cod,a.producto_nombre )"><span
                                                    class="fa fa-retweet text-primary"></span> Transferir</button>
                                            <button class="dropdown-item" @click="duplicar(a)">
                                                <span class="fa fa-copy text-primary"></span> Duplicar
                                            </button>
                                        </div>
                                    </div>`
                                    
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <v-pagination v-model="currentPage" :page-count="paginacion.ultima_pagina" :classes="bootstrapPaginationClasses"
                :labels="customLabels" @change="onChange">
            </v-pagination>

            <!--div class="d-flex flex-column">
                          <span>{ a.producto_nombre }}</span>
                          <span class="text-muted small">{ a.present_descripcion }}</span>
                        </div -->
                        </template>