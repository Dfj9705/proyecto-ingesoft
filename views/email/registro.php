<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Validación de Correo Electrónico</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f4f8;
                margin: 0;
                padding: 0;
                color: #333;
            }

            .container {
                width: 100%;
                padding: 20px;
                background-color: #ffffff;
                border-radius: 10px;
                max-width: 600px;
                margin: 40px auto;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-top: 5px solid #004080;
                border-bottom: 2px solid #004080;
                border-left: 2px solid #004080;
                border-right: 2px solid #004080;
                /* Línea superior azul */
            }

            .logo-container {
                text-align: center;
                margin-bottom: 20px;
            }

            .logo {
                width: 120px;
                height: auto;
            }

            h1 {
                font-size: 26px;
                color: #004080;
                /* Azul principal */
                text-align: center;
                margin-bottom: 20px;
            }

            p {
                font-size: 16px;
                color: #555555;
                line-height: 1.5;
            }

            .button-container {
                text-align: center;
                margin: 30px 0;
            }

            .validate-button {
                background-color: #004080;
                /* Azul principal */
                color: white;
                padding: 12px 30px;
                font-size: 18px;
                text-decoration: none;
                border-radius: 5px;
                display: inline-block;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .validate-button:hover {
                background-color: #003366;
                /* Un azul más oscuro para hover */
            }

            .footer {
                text-align: center;
                font-size: 12px;
                color: #888888;
                margin-top: 20px;
            }

            .footer a {
                color: #004080;
                /* Azul principal */
                text-decoration: none;
            }

            .footer a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <!-- Logo de la compañía -->
            <div class="logo-container">
                <img src="cid:escudo" alt="Logo Institucional" class="logo">
            </div>

            <h1>Confirma tu Dirección de Correo Electrónico</h1>
            <p>Hola, <?= $nombre ?></p>
            <p>Gracias por registrarse. Por favor, confirme su dirección de correo electrónico haciendo clic en el botón
                de abajo:</p>

            <div class="button-container">
                <a href="<?= $_ENV['HOST'] . "/verificar?t=" . $token ?>" class="validate-button">Validar Correo
                    Electrónico</a>
            </div>

            <p>Si no ha solicitado este correo, puede ignorarlo de forma segura.</p>
            <p>Si el botón no funciona, copie este enlace en su navegador:
                <?= $_ENV['HOST'] . "/verificar?t=" . $token ?>
            </p>

            <div class="footer">
                <p>&copy; 2024 Desarrollo Web. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>