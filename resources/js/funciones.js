const subFecha= function(startFecha) {
    const fechaInicio = new Date(startFecha).getTime();
    const fechaFin = new Date().getTime(); 
    if (fechaInicio > fechaFin) {
        return 0;
    }
    const diff = fechaFin - fechaInicio;
    return parseInt(diff / (1000 * 60 * 60 * 24));
}
const setMontointeres=function (vencimiento, monto, isSaldo) {
    if (isSaldo==0)
        return 0;
    //Informe de venta misma funcion
    let montoInteres = 0;
    const interes_mora = 100;
    const tmp_vencimiento = subFecha(vencimiento);

    if (interes_mora > 0 && tmp_vencimiento > 5) {
        montoInteres = (monto * interes_mora) / 100;
        montoInteres = montoInteres / 360;
        montoInteres = montoInteres * tmp_vencimiento;
    }
    return parseInt(montoInteres);
}

const formatFecha=function (fecha) {
    const f = fecha.split("-");
    return f[2] + "/" + f[1] + "/" + f[0];
}
const diferenciaFecha=function (fecha_vent, monto_saldo) {
    //2016-07-12
    const dia = subFecha(fecha_vent)
    
    //let diferenciaFecha = 0;
    if (monto_saldo > 0) {
        return dia
    } else {
        return "-"
    }
}

module.exports= { setMontointeres, subFecha, formatFecha, diferenciaFecha};