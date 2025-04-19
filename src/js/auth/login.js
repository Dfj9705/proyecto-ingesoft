const { Toast } = require("../funciones");

const formLogin = document.getElementById('formLogin')
const btnLogin = document.getElementById('btnLogin')
const spanLoader = document.getElementById('spanLoader')

spanLoader.style.display = 'none'
btnLogin.disabled = false

const login = async (e) => {
    e.preventDefault();
    spanLoader.style.display = ''
    btnLogin.disabled = true

    formLogin.classList.add('was-validated')
    if (!formLogin.checkValidity()) {
        Toast.fire({
            icon: 'info',
            title: "Verifique la información ingresada"
        })
        spanLoader.style.display = 'none'
        btnLogin.disabled = false
        return
    }

    try {

        const body = new FormData(formLogin)
        const url = "/login"
        const config = {
            method: 'POST',
            body
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;

        console.log(data);


        let icon = "info";
        let text = ''
        let redireccion = false
        switch (codigo) {
            case 1:
                icon = "success"
                text = "Espere la redirección"
                console.log(data);
                formLogin.reset()
                formLogin.classList.remove('was-validated')

                redireccion = true
                break;
            case 2:
                icon = "warning"
                text = detalle
                console.log(data);

                break;
            case 0:

                icon = "error"
                console.log(detalle);
                break;

        }

        Toast.fire({
            icon,
            title: mensaje,
            text
        })

        if (redireccion) {
            location.href = '/'
        }

    } catch (error) {
        console.log(error);
    }

    spanLoader.style.display = 'none'
    btnLogin.disabled = false
}

formLogin.addEventListener('submit', login)