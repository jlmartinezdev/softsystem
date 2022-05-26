<table>
    <thead>
        <tr>
            <th><strong>Codigo</strong></th>
            <th width="60"><strong>Descripcion</strong></th>
            <th width="30"><strong>Seccion</strong></th>
            <th><strong>Stock</strong></th>
            <th width="20"><strong>Precio Contado</strong></th>
            <th><strong>2 Cuotas</strong></th>
            <th><strong>3 Cuotas</strong></th>
            <th><strong>4 Cuotas</strong></th>
            <th><strong>5 Cuotas</strong></th>
            <th><strong>6 Cuotas</strong></th>
            <th><strong>7 Cuotas</strong></th>
            <th><strong>8 Cuotas</strong></th>
            <th><strong>9 Cuotas</strong></th>
            <th><strong>10 Cuotas</strong></th>
            <th><strong>11 Cuotas</strong></th>
            <th><strong>12 Cuotas</strong></th>
            <th><strong>13 Cuotas</strong></th>
        </tr>
        
    </thead>
    <tbody>
        @foreach($articulos as $articulo)
        <tr>
            <td>{{ $articulo->producto_c_barra }}</td>
            <td>{{ $articulo->producto_nombre }}</td>
            <td>{{ $articulo->present_descripcion }}</td>
            <td>{{ $articulo->cantidad }}</td>
            <td>{{ $articulo->pre_venta1 }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>