<div class="modal fade" id="precioArticulo">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
		        <h5 class="modal-title">Precios Credito</h5>
		      </div>
			<div class="modal-body">

                <nav>
					<div class="nav nav-tabs" role="tablist">
						<a class="nav-item nav-link active" data-toggle="tab" role="tab" href="#group1" aria-controls="group1" aria-select="true">PRECIO 2 - 7</a>
						<a class="nav-item nav-link" data-toggle="tab" role="tab" href="#group2" aria-controls="group2" aria-select="false">PRECIO 8 - 13</a>
						<a class="nav-item nav-link" data-toggle="tab" role="tab" href="#group3" aria-controls="group3" aria-select="false">PRECIO 14 - 18</a>
					</div>
				</nav>
                <div class="tab-content">
                    <!--         GRUPO 1  -->
					<div class="tab-pane fade show active" id="group1" role="tabpanel">
                        <div class="row">
                            <div class="col-3">
                                <span class="d-block mb-2">&nbsp;</span>
                                <span class="d-block mb-3 text-right">Margen %</span>
                                <span class="d-block mb-3 text-right">Precio</span>
                                <span class="d-block mb-3 text-right">Cuota</span>
                            </div>
                            <!--  PRECIO 2  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 2</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[0].m" type="number" tabindex="22" v-on:keyup="setPrecio(0);setCuota(0)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="2" v-model="precios[0].p" placeholder="Precio" @change="setMargen(0)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[0].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 3  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 3</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[1].m" type="number" tabindex="23" v-on:keyup="setPrecio(1);setCuota(1)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="3" v-model="precios[1].p" placeholder="Precio" @change="setMargen(1)"></in-number>
                                    
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[1].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 4  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 4</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[2].m" type="number" tabindex="24" v-on:keyup="setPrecio(2);setCuota(2)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="4" v-model="precios[2].p" placeholder="Precio" @change="setMargen(2)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[2].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="col-3">
                                <span class="d-block mb-2">&nbsp;</span>
                                <span class="d-block mb-3 text-right">Margen %</span>
                                <span class="d-block mb-3 text-right">Precio</span>
                                <span class="d-block mb-3 text-right">Cuota</span>
                            </div>
                            <!--  PRECIO 5  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 5</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[3].m" type="number" tabindex="25" v-on:keyup="setPrecio(3);setCuota(3)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="5" v-model="precios[3].p" placeholder="Precio" @change="setMargen(3)"></in-number>
                                </div>
                                <div class="m-2"> 
                                    <in-number  v-model="precios[3].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                             <!--  PRECIO 6  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 6</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[4].m" type="number" tabindex="26" v-on:keyup="setPrecio(4);setCuota(4)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="6" v-model="precios[4].p" placeholder="Precio" @change="setMargen(4)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[4].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 7  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 7</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[5].m" type="number" tabindex="27" v-on:keyup="setPrecio(5);setCuota(5)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="7" v-model="precios[5].p" placeholder="Precio" @change="setMargen(5)"></in-number>
                                   
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[5].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <!--         GRUPO 2  -->
                    <div class="tab-pane fade" id="group2" role="tabpanel">
                        <div class="row">
                            <div class="col-3">
                                <span class="d-block mb-2">&nbsp;</span>
                                <span class="d-block mb-3 text-right">Margen %</span>
                                <span class="d-block mb-3 text-right">Precio</span>
                                <span class="d-block mb-3 text-right">Cuota</span>
                            </div>
                            <!--  PRECIO 8  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 8</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[6].m" type="number" tabindex="28" v-on:keyup="setPrecio(6);setCuota(6)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="8" v-model="precios[6].p" placeholder="Precio" @change="setMargen(6)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[6].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 9  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 9</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[7].m" type="number" tabindex="29" v-on:keyup="setPrecio(7);setCuota(7)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="9" v-model="precios[7].p" placeholder="Precio" @change="setMargen(7)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[7].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 10  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 10</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[8].m" type="number" tabindex="30" v-on:keyup="setPrecio(8);setCuota(8)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="10" v-model="precios[8].p" placeholder="Precio" @change="setMargen(8)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[8].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="col-3">
                                <span class="d-block mb-2">&nbsp;</span>
                                <span class="d-block mb-3 text-right">Margen %</span>
                                <span class="d-block mb-3 text-right">Precio</span>
                                <span class="d-block mb-3 text-right">Cuota</span>
                            </div>
                            <!--  PRECIO 11  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 11</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[9].m" type="number" tabindex="31" v-on:keyup="setPrecio(9);setCuota(9)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="11" v-model="precios[9].p" placeholder="Precio" @change="setMargen(9)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[9].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 12  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 12</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[10].m" type="number" tabindex="32" v-on:keyup="setPrecio(10);setCuota(10)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="12" v-model="precios[10].p" placeholder="Precio" @change="setMargen(10)"></in-number>
                             
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[10].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 13  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 13</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[11].m" type="number" tabindex="33" v-on:keyup="setPrecio(11);setCuota(11)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="13" v-model="precios[11].p" placeholder="Precio" @change="setMargen(11)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[11].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <!--         GRUPO 3  -->
                    <div class="tab-pane fade" id="group3" role="tabpanel">
                        <div class="row">
                            <div class="col-3">
                                <span class="d-block mb-2">&nbsp;</span>
                                <span class="d-block mb-3 text-right">Margen %</span>
                                <span class="d-block mb-3 text-right">Precio</span>
                                <span class="d-block mb-3 text-right">Cuota</span>
                            </div>
                            <!--  PRECIO 14  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 14</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[12].m" type="number" tabindex="34" v-on:keyup="setPrecio(12);setCuota(12)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="14" v-model="precios[12].p" placeholder="Precio" @change="setMargen(12)"></in-number>
                                   
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[12].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 15  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 15</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[13].m" type="number" tabindex="35" v-on:keyup="setPrecio(13);setCuota(13)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="15" v-model="precios[13].p" placeholder="Precio" @change="setMargen(13)"></in-number>
                                   
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[13].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 16  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 16</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[14].m" type="number" tabindex="36" v-on:keyup="setPrecio(14);setCuota(14)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="16" v-model="precios[14].p" placeholder="Precio" @change="setMargen(14)"></in-number>
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[14].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="col-3">
                                <span class="d-block mb-2">&nbsp;</span>
                                <span class="d-block mb-3 text-right">Margen %</span>
                                <span class="d-block mb-3 text-right">Precio</span>
                                <span class="d-block mb-3 text-right">Cuota</span>
                            </div>
                            <!--  PRECIO 17  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 17</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[15].m" type="number" tabindex="37" v-on:keyup="setPrecio(15);setCuota(15)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="17" v-model="precios[15].p" placeholder="Precio" @change="setMargen(15)"></in-number>
                                    
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[15].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                            <!--  PRECIO 18  -->
                            <div class="col-3">
                                <span class="m-2"><strong>PRECIO 18</strong></span>
                                <div class="m-2">
                                    <input class="form-control form-control-sm" v-model="precios[16].m" type="number" tabindex="38" v-on:keyup="setPrecio(16);setCuota(16)" placeholder="Margen">
                                </div>
                                <div class="m-2">
                                    <in-number tabindex="18" v-model="precios[16].p" placeholder="Precio" @change="setMargen(16)"></in-number>
                                    
                                </div>
                                <div class="m-2">
                                    <in-number  v-model="precios[16].c" placeholder="Cuota"></in-number>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <label><input type="checkbox" v-model="chprecio" name="precio"> Redondear Precio</label>
                <label><input type="checkbox" v-model="chcuota" name="cuota"> Redondear Cuota</label>
                <button @click="cerrarPrecios" class="btn btn-success"><span class="fa fa-save"></span> Aceptar</button>
            </div>
        </div>
    </div>
</div>