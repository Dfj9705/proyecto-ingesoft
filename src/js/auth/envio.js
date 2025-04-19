const { Toast } = require("../funciones")

const formEnvio = document.getElementById('formEnvio')
const btnEnvio = document.getElementById('btnEnvio')
const spanLoader = document.getElementById('spanLoader')

spanLoader.style.display = 'none'
btnEnvio.disabled = false

const envio = async (e) => {
    e.preventDefault();
    spanLoader.style.display = ''
    btnEnvio.disabled = true


    formEnvio.classList.add('was-validated')
    if (!formEnvio.checkValidity()) {
        Toast.fire({
            icon: 'info',
            title: "Debe llenar todos los campos"
        })
        spanLoader.style.display = 'none'
        btnEnvio.disabled = false
        return
    }



    try {

        const body = new FormData(formEnvio)
        const url = "/envio"
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
        let redireccion = false;
        switch (codigo) {
            case 1:
                icon = "success"
                console.log(data);
                formEnvio.reset()
                redireccion = true;
                formEnvio.classList.remove('was-validated')

                text = 'Esta ventana se cerrará'
                break;
            case 2:
                icon = "warning"
                console.log(data);
                redireccion = false;

                break;
            case 0:
                icon = "error"
                console.log(detalle);
                redireccion = false;
                break;

        }

        Toast.fire({
            icon,
            title: mensaje,
            text
        }).then(() => {
            if (redireccion) {
                location.href = '/'
            }
        }
        )


    } catch (error) {
        console.log(error);
    }

    spanLoader.style.display = 'none'
    btnEnvio.disabled = false
}

formEnvio.addEventListener('submit', envio)