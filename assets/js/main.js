let reportes = [];

function agregarReporte(){

    let categoria =
        document.getElementById("categoria").value;

    let descripcion =
        document.getElementById("descripcion").value;

    if(categoria === "" || descripcion === ""){

        alert("Complete todos los campos");

        return;
    }

    let reporte = {

        categoria: categoria,
        descripcion: descripcion,
        estado: "Pendiente"

    };

    reportes.push(reporte);

    mostrarReportes();

    document.getElementById("categoria").value = "";
    document.getElementById("descripcion").value = "";
}

function mostrarReportes(){

    let lista =
        document.getElementById("listaReportes");

    lista.innerHTML = "";

    reportes.forEach(function(reporte){

        lista.innerHTML += `

            <div class="reporte">

                <h3>${reporte.categoria}</h3>

                <p>${reporte.descripcion}</p>

                <span class="estado">
                    ${reporte.estado}
                </span>

            </div>

        `;
    });
}
