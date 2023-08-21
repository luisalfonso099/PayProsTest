import {obtenerLugares,cargarLugaresOcupados, sacarVehiculo} from './services/lugares.services.js';
import {agregarVeiculo,verificarMatricula} from './services/veiculos.services.js';
import {registrarMarca,registrarColor,registrarModelo,getDataModelos} from './services/registrosMMC.services.js';



const DOMAPP = document.getElementById('root');

// Función para cargar el contenido de una página en el div #contenido
async function cargarPagina(pagina) {
    DOMAPP.innerHTML = ""; // Limpia el contenido actual
    await fetch('./views/'+ pagina+ ".html")
        .then(response => response.text())
        .then(data => {
            DOMAPP.innerHTML = data;
        
        });
}

cargarPagina("ingresarVeiculo").then(()=>{
    document.getElementById("btnIngresarVehiculo").addEventListener("click", agregarVeiculo)
    document.getElementById("btnVerificarMatricula").addEventListener("click", verificarMatricula)
    getDataModelos();
});

document.getElementById("btnIngresar").addEventListener("click",async ()=> {
    await cargarPagina("ingresarVeiculo");
    await getDataModelos();
    document.getElementById("btnIngresarVehiculo").addEventListener("click", agregarVeiculo);
    document.getElementById("btnVerificarMatricula").addEventListener("click", verificarMatricula);
});

document.getElementById("btnVerLugares").addEventListener("click", async()=> {
    await cargarPagina("edificio");
    obtenerLugares();
});

document.getElementById("btnSalida").addEventListener("click", async()=> {
    await cargarPagina("salidaVehiculos");
    cargarLugaresOcupados();
    document.getElementById('registrarSalidaBTN').addEventListener("click", sacarVehiculo);
});

document.getElementById("btnRegistro").addEventListener("click", async ()=> {
    await cargarPagina("registroMMC");
    document.getElementById("btnRegistrarMarca").addEventListener("click", registrarMarca);
    document.getElementById("btnRegistrarColor").addEventListener("click", registrarColor);
    document.getElementById("btnRegistrarModelo").addEventListener("click", registrarModelo);
});

