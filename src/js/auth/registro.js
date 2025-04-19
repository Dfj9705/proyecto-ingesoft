import { Dropdown } from "bootstrap";
import { soloNumeros, Toast } from "../funciones";

const formRegistro = document.getElementById('formRegistro')
const btnRegistro = document.getElementById('btnRegistro')
const spanLoader = document.getElementById('spanLoader')

spanLoader.style.display = 'none'

const registrar = async (e) => {
    e.preventDefault();
    spanLoader.style.display = ''
    btnRegistro.disabled = true
    let password = document.getElementById('password');
    let confirmPassword = document.getElementById('password2');


    if (password.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Las contraseñas no coinciden');
    } else {
        confirmPassword.setCustomValidity('');
    }
    formRegistro.classList.add('was-validated')
    if (!formRegistro.checkValidity()) {
        Toast.fire({
            icon: 'info',
            title: "Verifique la información ingresada"
        })
        spanLoader.style.display = 'none'
        btnRegistro.disabled = false
        return
    }

    try {

        const body = new FormData(formRegistro)
        const url = "/registro"
        const config = {
            method: 'POST',
            body
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;
        console.log(data);
        let icon = 'info', text = ''
        let redireccion = false
        if (codigo == 1) {
            icon = "success"
            text = "Espere la redirección"
            formRegistro.reset()
            formRegistro.classList.remove('was-validated')
            redireccion = true
        } else if (codigo == 2) {
            icon = 'warning'
            text = detalle
            console.log(detalle);
        } else if (codigo == 0) {
            icon = 'error'
            console.log(detalle);

        }

        Toast.fire({
            icon,
            title: mensaje,
            text
        })

        if (redireccion) {
            location.href = '/login'
        }
    } catch (error) {
        console.log(error);
    }
    spanLoader.style.display = 'none'
    btnRegistro.disabled = false
}

formRegistro.addEventListener('submit', registrar)
formRegistro.cuenta.addEventListener('input', soloNumeros)
formRegistro.dpi.addEventListener('input', soloNumeros)