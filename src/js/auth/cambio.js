const { Toast } = require("../funciones")

const formCambio = document.getElementById('formCambio')
const btnCambio = document.getElementById('btnCambio')
const spanLoader = document.getElementById('spanLoader')

spanLoader.style.display = 'none'
btnCambio.disabled = false

const cambio = async (e) => {
    e.preventDefault();
    spanLoader.style.display = ''
    btnCambio.disabled = true

    let password = document.getElementById('password');
    let confirmPassword = document.getElementById('password2');


    if (password.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Las contraseñas no coinciden');
    } else {
        confirmPassword.setCustomValidity('');
    }

    formCambio.classList.add('was-validated')
    if (!formCambio.checkValidity()) {
        Toast.fire({
            icon: 'info',
            title: "Debe llenar todos los campos"
        })
        spanLoader.style.display = 'none'
        btnCambio.disabled = false
        return
    }



    try {

        const body = new FormData(formCambio)
        const url = "/cambio"
        const config = {
            method: 'POST',
            body
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;

        // console.log(data);
        // return

        let icon = "info";
        let text = ''
        redireccion = false
        switch (codigo) {
            case 1:
                icon = "success"
                console.log(data);
                formCambio.reset()
                formCambio.classList.remove('was-validated')
                redireccion = true
                break;
            case 2:
                icon = "warning"
                console.log(data);
                redireccion = false

                break;
            case 0:
                icon = "error"
                console.log(detalle);
                redireccion = false
                break;

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
    btnCambio.disabled = false
}

formCambio.addEventListener('submit', cambio)