import {showSnackbar} from './snackBar.js';

const PATH = '../backend/routes/lugares_estacionamiento/'

export const obtenerLugares = async () => {
    try {
        const response = await fetch(`${PATH}obtener_lugares.php`);
        const data = await response.json();
        const lugaresOcupadosDiv = document.querySelector("#lugaresOcupadosDiv");

        if (data.length > 0) {
            const lugaresOcupados = data;
            lugaresOcupadosDiv.classList.add("lugares-container");
            let pisoActual = 0;
            lugaresOcupados.forEach((lugar) => {
                pisoActual 

                const lugarDiv = document.createElement("div");
                lugarDiv.classList.add("lugar");
                
                if (lugar.matricula === null) {
                    lugarDiv.classList.add("libre");
                } else {
                    lugarDiv.classList.add("ocupado");
                }
                if (lugar.matricula !== null) {
                    const matriculaInfo = document.createElement("h2");
                    matriculaInfo.textContent = `Matrícula: ${lugar.matricula}`;
                    lugarDiv.appendChild(matriculaInfo);
                }else {
                    const libreTitle = document.createElement("h2");
                    libreTitle.textContent = `Libre`;
                    lugarDiv.appendChild(libreTitle);
                }
                const lugarInfo = document.createElement("p");
                lugarInfo.textContent = `Piso ${lugar.piso}, Lugar ${lugar.numero}`;

                lugarDiv.appendChild(lugarInfo);
                lugaresOcupadosDiv.appendChild(lugarDiv);
                pisoActual = lugar.piso;

            });
        } else {
            lugaresOcupadosDiv.style.display = "none";
            alert("No se pudieron obtener los lugares ocupados.");
        }
    } catch (error) {
        console.error("Error al obtener lugares ocupados:", error);
    }
};

export const cargarLugaresOcupados = async()=> {
    try {
        const response = await fetch(`${PATH}obtener_lugares_ocupados.php`);
        const lugaresOcupados = await response.json();
        const lugaresOcupadosList = document.querySelector("#lugaresOcupadosList");

       
        console.log('sadasdad', lugaresOcupados);
        if(!lugaresOcupados.success && !lugaresOcupados.length){
            showSnackbar('El estacionamiento esta vacio', true);
            return;
        }else{
            console.log('dsd');
            lugaresOcupados.forEach(lugar => {
                const option = document.createElement('option');
                option.value = lugar.lugar_id;
                option.innerHTML  = `Matrícula: <bold>${lugar.matricula}</bold> - Piso: ${lugar.piso} - Lugar: ${lugar.numero}`;
                lugaresOcupadosList.appendChild(option);
            });
        }
       
    } catch (error) {
        console.error('Error al cargar lugares ocupados:', error);
    }
}

export const sacarVehiculo = async () => {
    try {
        let lugarId = document.querySelector("#lugaresOcupadosList");
        const dataBody = new URLSearchParams();
        dataBody.append("lugar_id", Number(lugarId.value));
        const response = await fetch(`${PATH}registrar_salida.php`, {
            method: "POST",
            body: dataBody, 
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        });

        const data = await response.json();

        if (data.success) {
            showSnackbar(data.message, true);
            lugarId.value = '';
        } else {
            showSnackbar(data.message, false);
        }
    } catch (error) {
        console.error("Error al sacar vehículo:", error);
    }
};


