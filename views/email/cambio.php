<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Restablecer Contraseña</title>
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

            .reset-button {
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

            .reset-button:hover {
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

            <h1>Restablezca tu Contraseña</h1>
            <p>Hola, <?= $nombre ?></p>
            <p>Ha solicitado restablecer su contraseña. Haga clic en el siguiente enlace para continuar con el proceso:
            </p>

            <div class="button-container">
                <a href="<?= $_ENV['HOST'] . '/cambio?t=' . $token ?>" class="reset-button">Restablecer Contraseña</a>
            </div>

            <p>Si no solicitó este cambio, puede ignorar este correo de forma segura.</p>
            <p>Si el botón no funciona, copie este enlace en su navegador: <?= $_ENV['HOST'] . "/cambio?t=" . $token ?>
            </p>

            <div class="footer">
                <p>&copy; 2024 Desarrollo Web. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>

</html>