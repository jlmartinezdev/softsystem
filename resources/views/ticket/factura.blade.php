<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.7">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/ticket.css" rel="stylesheet">
    <title>FACTURA SOFTSYSTEM</title>
</head>

<body>
    <div class="ticket">

        <p class="centrado">
            <strong>{{ $empresa->emp_nombre }}</strong>
            <br>
            {{ $empresa->emp_direccion }}
            <br>
            <strong>
                Celular:{{ $empresa->emp_celular }}
            </strong>
            <br>
            <strong>
                RUC: {{ $empresa->emp_ruc }}
            </strong>
            <br>
            Timbrado: 102020292
            <br>
            Valido desde: 11/02/2021
            <br>
            Valido hasta: 11/02/2023
        </p>
        <p class="centrado">
            <strong> FACTURA CONTADO</strong>
            <br>
            <strong>001-001-0002934</strong>
        </p>
        Fecha: 17/10/2017 02:22 a.m.
        <br>
        Cliente: Juan Perez
        <br>
        C.I. / RUC: 1919191
        <br>
        <table>
            <thead>
                <tr>
                    <th class="cantidad">CANT</th>
                    <th class="producto">PRODUCTO</th>
                    <th class="precio">$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="producto" colspan="3">CHEETOS VERDES 80 G</td>
                </tr>
                <tr>
                    <td class="cantidad">1.00</td>
                    <td class="precio" colspan="2" align="right">$8.000</td>
                </tr>
            </tbody>
        </table>
        <p class="centrado">¡GRACIAS POR SU COMPRA!
            <br>parzibyte.me
        </p>
    </div>
</body>

</html>
