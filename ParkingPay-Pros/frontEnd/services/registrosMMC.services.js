import {showSnackbar} from './snackBar.js';

const PATH = '../backend/routes/registros_mmc/'

const validarModelo = (node,campo)=> {
    const valor = node.value.trim();

    if (valor.length === 0) {
        showSnackbar(`El campo ${campo} no puede estar vacío`, false);
        return false;
    };

    if (valor.length < 3) {
        showSnackbar(`El campo ${campo} debe tener al menos tres caracteres`, false);
        return false;
    };

    return true;
};

export const registrarMarca = async () => {
    try {
        const marca = document.querySelector("#marca_nombre");
        if (!validarModelo(marca, 'Marca')) return;
        const formData = new FormData();
        formData.append("nombre", marca.value);
        formData.append("tipo_registro", 'marca');
        const response = await fetch(`${PATH}registrar.php`, {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            showSnackbar(`Algo salio mal =( ${response.status} - ${response.statusText}`, false);
            throw new Error(`Error en la petición: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        if(data.success){
            marca.innerHTML = ''
            marca.value = ''
            showSnackbar(data.message, data.success)
        }else {
            showSnackbar(data.message, data.success)
        }
    } catch (error) {
        console.error("Error:", error);
    }
};
export const registrarColor = async () => {
    try {
        let color = document.querySelector("#color_nombre");
        if (!validarModelo(color, 'Color')) return;
        const formData = new FormData();
        formData.append("nombre", color.value);
        formData.append("tipo_registro", 'color');
        const response = await fetch(`${PATH}registrar.php`, {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            showSnackbar(`Algo salio mal =( ${response.status} - ${response.statusText}`, false);
            throw new Error(`Error en la petición: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json()
        if(data.success){
            color.innerHTML = ''
            color.value = ''
            showSnackbar(data.message, data.success)
        }else {
            showSnackbar(data.message, data.success)
        }
    } catch (error) {
        console.error("Error:", error);
    }
};

export const registrarModelo = async () => {
    try {
        const modelo = document.querySelector("#modelo_nombre");
        if (!validarModelo(modelo, 'Modelo')) return;
        const formData = new FormData();
        formData.append("nombre", modelo.value);
        formData.append("tipo_registro", 'modelo');
        const response = await fetch(`${PATH}registrar.php`, {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            showSnackbar(`Algo salio mal =( ${response.status} - ${response.statusText}`, false);
            throw new Error(`Error en la petición: ${response.status} - ${response.statusText}`);
        }
        const data = await response.json();
        if(data.success){
            modelo.innerHTML = '';
            modelo.value = '';
            showSnackbar(data.message, data.success)
        }else {
            showSnackbar(data.message, data.success)
        }
    } catch (error) {
        console.error("Error:", error);
    }
};

export const getDataModelos = async () => {
    try {
        const response = await fetch(`${PATH}obtener_registrosMMC.php`);
        const marcaSelct = document.querySelector("#marca");
        const modeloSelct = document.querySelector("#modelo");
        const colorSelct = document.querySelector("#color");

        if (!response.ok) {
            throw new Error(`Error en la petición: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        data.colores.forEach(color => {
            const option = document.createElement('option');
            option.value = color.id;
            option.textContent = color.nombre;
            colorSelct.appendChild(option);
        });
        data.modelos.forEach(modelo => {
            const option = document.createElement('option');
            option.value = modelo.id;
            option.textContent = modelo.nombre;
            modeloSelct.appendChild(option);
        });
        data.marcas.forEach(marca => {
            const option = document.createElement('option');
            option.value = marca.id;
            option.textContent = marca.nombre;
            marcaSelct.appendChild(option);
        });
        // Manejar la respuesta del servidor
    } catch (error) {
        // Manejar errores de la petición
        console.error("Error:", error);
    }
};


