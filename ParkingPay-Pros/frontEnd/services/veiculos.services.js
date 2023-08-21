import {showSnackbar} from './snackBar.js';

const PATH = '../backend/routes/vehiculos/';

const validarIngresoVehiculo =(marca, modelo, color, matricula)=> {
    const campos = [
        { valor: marca, campo: 'marca' },
        { valor: modelo, campo: 'modelo' },
        { valor: color, campo: 'color' },
        { valor: matricula, campo: 'matrícula' }
    ];

    for (const { valor, campo } of campos) {
        if (valor.length === 0) {
            showSnackbar(`Deves seleccionar un ${campo} para ingresar`, false);
            return false;
        }
    }

    return true;
};

const validarCampo =(node,campo)=> {
    const valor = node.value.trim();

    if (valor.length === 0) {
        showSnackbar(`El campo ${campo} no puede estar vacío`, false);
        return false;
    }

    if (valor.length < 3) {
        showSnackbar(`El campo ${campo} debe tener al menos tres caracteres`, false);
        return false;
    }

    if (valor.length > 10 && campo =='matricula') {
        showSnackbar(`La matricula no puede tener mas de 10 caracteres`, false);
        return false;
    }

    return true;
};

export const verificarMatricula = async () => {
    try {
        const marcaSelect = document.querySelector("#marca");
        const modeloSelect = document.querySelector("#modelo");
        const colorSelect = document.querySelector("#color");
        const matricula = document.querySelector("#matricula");
        const datosVehiculoDiv = document.querySelector("#datosVehiculo");
        const verificarMatriculaBtn = document.querySelector("#btnVerificarMatricula");
        const response = await fetch(`${PATH}verificar_matricula.php?matricula=${matricula.value}`);


        if(!validarCampo(matricula,'matricula'))return;
    
        const data = await response.json();

        if (data.existe_en_entradas) {
            showSnackbar(`Ya tenemos un vehículo con esa matrícula en el edificio.`, false);
            return;
        }
        if (data.id) {
            // Cargar los datos del vehículo en los campos de selección
            marcaSelect.value = data.marca_id;
            modeloSelect.value = data.modelo_id;
            colorSelect.value = data.color_id;
    
            // Mostrar los campos de selección y ocultar el botón de verificación
            datosVehiculoDiv.style.display = "block";
            verificarMatriculaBtn.style.display = "none";
        } else {
            datosVehiculoDiv.style.display = "block";
            verificarMatriculaBtn.style.display = "none";
        }
    } catch (error) {
        console.error("Error al verificar matrícula:", error);
    }
    
};

export const agregarVeiculo = async () => {
    try {
        let marca = document.querySelector("#marca");
        let modelo = document.querySelector("#modelo");
        let color = document.querySelector("#color");
        let matricula = document.querySelector("#matricula");

        if (!validarIngresoVehiculo(marca.value, modelo.value, color.value, matricula.value)) return;

        const formData = new FormData();
        formData.append("marca", Number(marca.value));
        formData.append("modelo", Number(modelo.value));
        formData.append("color", Number(color.value));
        formData.append("matricula", matricula.value);

        const response = await fetch(`${PATH}ingresar_vehiculo.php`, {
            method: "POST",
            body: formData,
        });
        if (!response.ok) {
            throw new Error(`Error en la petición: ${response.status} - ${response.statusText}`);
        }
        const data = await response.json();
        if (data.success) {
            showSnackbar(data.message, true);
            marca.value = '';
            modelo.value = '';
            color.value = '';
            matricula.value = '';
        } else {
            showSnackbar(data.message, false);
        }
    } catch (error) {
        console.error("Error:", error);
    }
};


