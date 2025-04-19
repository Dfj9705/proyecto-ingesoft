<?php

function debuguear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html)
{
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa que el usuario este autenticado
function isAuth()
{
    if (!isset($_SESSION['auth'])) {
        header('Location: /login');
    }
}
function isVerified()
{

    if ($_SESSION['user']->verificado == 0) {
        header('Location: /no-verificado');
    }
}
function isNotVerified()
{

    if ($_SESSION['user']->verificado == 1) {
        header('Location: /');
    }

}
function isAuthApi()
{
    getHeadersApi();
    if (!isset($_SESSION['auth'])) {
        echo json_encode([
            "mensaje" => "No esta autenticado",

            "codigo" => 4,
        ]);
        exit;
    }
}

function isNotAuth()
{
    if (isset($_SESSION['auth'])) {
        header('Location: /');
    }
}

function isNotAuthApi()
{
    getHeadersApi();
    if (isset($_SESSION['auth'])) {
        echo json_encode([
            "mensaje" => "Ya esta autenticado",

            "codigo" => 4,
        ]);
        exit;
    }
}


function hasPermission(array $permisos)
{

    $comprobaciones = [];
    foreach ($permisos as $permiso) {

        $comprobaciones[] = $_SESSION['user']->rol != $permiso ? false : true;
    }

    if (array_search(true, $comprobaciones) !== false) {
    } else {
        header('Location: /');
    }
}

function hasPermissionApi(array $permisos)
{
    getHeadersApi();
    $comprobaciones = [];
    foreach ($permisos as $permiso) {

        $comprobaciones[] = $_SESSION['user']->rol != $permiso ? false : true;
    }

    if (array_search(true, $comprobaciones) !== false) {
    } else {
        echo json_encode([
            "mensaje" => "No tiene permisos",

            "codigo" => 4,
        ]);
        exit;
    }
}

function getHeadersApi()
{
    return header("Content-type:application/json; charset=utf-8");
}

function asset($ruta)
{

    $prefijo = $_ENV['HOST'] == 'localhost' ? "/" . $_ENV['APP_NAME'] : '';
    return $prefijo . "/public/" . $ruta;
}

function sanitizar($datos)
{

    $datos_sanitizados = array();

    foreach ($datos as $campo => $valor) {
        if (is_string($valor)) {
            $valor = trim($valor);
            $valor = stripslashes($valor);
            $valor = htmlspecialchars($valor);
        }

        $datos_sanitizados[$campo] = $valor;
    }
    return $datos_sanitizados;
}

function generarIdentificadorUnico()
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $identificador = '';
    for ($i = 0; $i < 8; $i++) {
        $identificador .= $caracteres[mt_rand(0, strlen($caracteres) - 1)];
    }
    return $identificador;
}

function route($url = "/")
{
    return "/" . $_ENV['APP_NAME'] . $url;
}

function valorEnLetras($x)
{
    if ($x < 0) {
        $signo = "menos ";
    } else {
        $signo = "";
    }
    $x = abs($x);
    $C1 = $x;

    $G6 = floor($x / (1000000));  // 7 y mas

    $E7 = floor($x / (100000));
    $G7 = $E7 - $G6 * 10;   // 6

    $E8 = floor($x / 1000);
    $G8 = $E8 - $E7 * 100;   // 5 y 4

    $E9 = floor($x / 100);
    $G9 = $E9 - $E8 * 10;  //  3

    $E10 = floor($x);
    $G10 = $E10 - $E9 * 100;  // 2 y 1


    $G11 = round(($x - $E10) * 100, 0);  // Decimales
    //////////////////////

    $H6 = unidades($G6);

    if ($G7 == 1 and $G8 == 0) {
        $H7 = "Cien ";
    } else {
        $H7 = decenas($G7);
    }

    $H8 = unidades($G8);

    if ($G9 == 1 and $G10 == 0) {
        $H9 = "Cien ";
    } else {
        $H9 = decenas($G9);
    }

    $H10 = unidades($G10);

    if ($G11 < 10) {
        $H11 = "0" . $G11;
    } else {
        $H11 = $G11;
    }

    /////////////////////////////
    if ($G6 == 0) {
        $I6 = " ";
    } elseif ($G6 == 1) {
        $I6 = "Millón ";
    } else {
        $I6 = "Millones ";
    }

    if ($G8 == 0 and $G7 == 0) {
        $I8 = " ";
    } else {
        $I8 = "Mil ";
    }

    $I10 = "Quetzales con ";
    $I11 = "/100 centavos ";

    $C3 = $signo . $H6 . $I6 . $H7 . $H8 . $I8 . $H9 . $H10 . $I10 . $H11 . $I11;

    return $C3; //Retornar el resultado

}

function unidades($u)
{
    if ($u == 0) {
        $ru = " ";
    } elseif ($u == 1) {
        $ru = "Un ";
    } elseif ($u == 2) {
        $ru = "Dos ";
    } elseif ($u == 3) {
        $ru = "Tres ";
    } elseif ($u == 4) {
        $ru = "Cuatro ";
    } elseif ($u == 5) {
        $ru = "Cinco ";
    } elseif ($u == 6) {
        $ru = "Seis ";
    } elseif ($u == 7) {
        $ru = "Siete ";
    } elseif ($u == 8) {
        $ru = "Ocho ";
    } elseif ($u == 9) {
        $ru = "Nueve ";
    } elseif ($u == 10) {
        $ru = "Diez ";
    } elseif ($u == 11) {
        $ru = "Once ";
    } elseif ($u == 12) {
        $ru = "Doce ";
    } elseif ($u == 13) {
        $ru = "Trece ";
    } elseif ($u == 14) {
        $ru = "Catorce ";
    } elseif ($u == 15) {
        $ru = "Quince ";
    } elseif ($u == 16) {
        $ru = "Dieciseis ";
    } elseif ($u == 17) {
        $ru = "Decisiete ";
    } elseif ($u == 18) {
        $ru = "Dieciocho ";
    } elseif ($u == 19) {
        $ru = "Diecinueve ";
    } elseif ($u == 20) {
        $ru = "Veinte ";
    } elseif ($u == 21) {
        $ru = "Veintiun ";
    } elseif ($u == 22) {
        $ru = "Veintidos ";
    } elseif ($u == 23) {
        $ru = "Veintitres ";
    } elseif ($u == 24) {
        $ru = "Veinticuatro ";
    } elseif ($u == 25) {
        $ru = "Veinticinco ";
    } elseif ($u == 26) {
        $ru = "Veintiseis ";
    } elseif ($u == 27) {
        $ru = "Veintisiente ";
    } elseif ($u == 28) {
        $ru = "Veintiocho ";
    } elseif ($u == 29) {
        $ru = "Veintinueve ";
    } elseif ($u == 30) {
        $ru = "Treinta ";
    } elseif ($u == 31) {
        $ru = "Treinta y un ";
    } elseif ($u == 32) {
        $ru = "Treinta y dos ";
    } elseif ($u == 33) {
        $ru = "Treinta y tres ";
    } elseif ($u == 34) {
        $ru = "Treinta y cuatro ";
    } elseif ($u == 35) {
        $ru = "Treinta y cinco ";
    } elseif ($u == 36) {
        $ru = "Treinta y seis ";
    } elseif ($u == 37) {
        $ru = "Treinta y siete ";
    } elseif ($u == 38) {
        $ru = "Treinta y ocho ";
    } elseif ($u == 39) {
        $ru = "Treinta y nueve ";
    } elseif ($u == 40) {
        $ru = "Cuarenta ";
    } elseif ($u == 41) {
        $ru = "Cuarenta y un ";
    } elseif ($u == 42) {
        $ru = "Cuarenta y dos ";
    } elseif ($u == 43) {
        $ru = "Cuarenta y tres ";
    } elseif ($u == 44) {
        $ru = "Cuarenta y cuatro ";
    } elseif ($u == 45) {
        $ru = "Cuarenta y cinco ";
    } elseif ($u == 46) {
        $ru = "Cuarenta y seis ";
    } elseif ($u == 47) {
        $ru = "Cuarenta y siete ";
    } elseif ($u == 48) {
        $ru = "Cuarenta y ocho ";
    } elseif ($u == 49) {
        $ru = "Cuarenta y nueve ";
    } elseif ($u == 50) {
        $ru = "Cincuenta ";
    } elseif ($u == 51) {
        $ru = "Cincuenta y un ";
    } elseif ($u == 52) {
        $ru = "Cincuenta y dos ";
    } elseif ($u == 53) {
        $ru = "Cincuenta y tres ";
    } elseif ($u == 54) {
        $ru = "Cincuenta y cuatro ";
    } elseif ($u == 55) {
        $ru = "Cincuenta y cinco ";
    } elseif ($u == 56) {
        $ru = "Cincuenta y seis ";
    } elseif ($u == 57) {
        $ru = "Cincuenta y siete ";
    } elseif ($u == 58) {
        $ru = "Cincuenta y ocho ";
    } elseif ($u == 59) {
        $ru = "Cincuenta y nueve ";
    } elseif ($u == 60) {
        $ru = "Sesenta ";
    } elseif ($u == 61) {
        $ru = "Sesenta y un ";
    } elseif ($u == 62) {
        $ru = "Sesenta y dos ";
    } elseif ($u == 63) {
        $ru = "Sesenta y tres ";
    } elseif ($u == 64) {
        $ru = "Sesenta y cuatro ";
    } elseif ($u == 65) {
        $ru = "Sesenta y cinco ";
    } elseif ($u == 66) {
        $ru = "Sesenta y seis ";
    } elseif ($u == 67) {
        $ru = "Sesenta y siete ";
    } elseif ($u == 68) {
        $ru = "Sesenta y ocho ";
    } elseif ($u == 69) {
        $ru = "Sesenta y nueve ";
    } elseif ($u == 70) {
        $ru = "Setenta ";
    } elseif ($u == 71) {
        $ru = "Setenta y un ";
    } elseif ($u == 72) {
        $ru = "Setenta y dos ";
    } elseif ($u == 73) {
        $ru = "Setenta y tres ";
    } elseif ($u == 74) {
        $ru = "Setenta y cuatro ";
    } elseif ($u == 75) {
        $ru = "Setenta y cinco ";
    } elseif ($u == 76) {
        $ru = "Setenta y seis ";
    } elseif ($u == 77) {
        $ru = "Setenta y siete ";
    } elseif ($u == 78) {
        $ru = "Setenta y ocho ";
    } elseif ($u == 79) {
        $ru = "Setenta y nueve ";
    } elseif ($u == 80) {
        $ru = "Ochenta ";
    } elseif ($u == 81) {
        $ru = "Ochenta y un ";
    } elseif ($u == 82) {
        $ru = "Ochenta y dos ";
    } elseif ($u == 83) {
        $ru = "Ochenta y tres ";
    } elseif ($u == 84) {
        $ru = "Ochenta y cuatro ";
    } elseif ($u == 85) {
        $ru = "Ochenta y cinco ";
    } elseif ($u == 86) {
        $ru = "Ochenta y seis ";
    } elseif ($u == 87) {
        $ru = "Ochenta y siete ";
    } elseif ($u == 88) {
        $ru = "Ochenta y ocho ";
    } elseif ($u == 89) {
        $ru = "Ochenta y nueve ";
    } elseif ($u == 90) {
        $ru = "Noventa ";
    } elseif ($u == 91) {
        $ru = "Noventa y un ";
    } elseif ($u == 92) {
        $ru = "Noventa y dos ";
    } elseif ($u == 93) {
        $ru = "Noventa y tres ";
    } elseif ($u == 94) {
        $ru = "Noventa y cuatro ";
    } elseif ($u == 95) {
        $ru = "Noventa y cinco ";
    } elseif ($u == 96) {
        $ru = "Noventa y seis ";
    } elseif ($u == 97) {
        $ru = "Noventa y siete ";
    } elseif ($u == 98) {
        $ru = "Noventa y ocho ";
    } else {
        $ru = "Noventa y nueve ";
    }
    return $ru; //Retornar el resultado
}

function decenas($d)
{
    if ($d == 0) {
        $rd = "";
    } elseif ($d == 1) {
        $rd = "Ciento ";
    } elseif ($d == 2) {
        $rd = "Doscientos ";
    } elseif ($d == 3) {
        $rd = "Trescientos ";
    } elseif ($d == 4) {
        $rd = "Cuatrocientos ";
    } elseif ($d == 5) {
        $rd = "Quinientos ";
    } elseif ($d == 6) {
        $rd = "Seiscientos ";
    } elseif ($d == 7) {
        $rd = "Setecientos ";
    } elseif ($d == 8) {
        $rd = "Ochocientos ";
    } else {
        $rd = "Novecientos ";
    }
    return $rd; //Retornar el resultado
}

function enmascararCorreo($correo)
{

    list($usuario, $dominio) = explode('@', $correo);

    $primeraLetra = substr($usuario, 0, 2);

    $enmascaradoUsuario = $primeraLetra . str_repeat('*', strlen($usuario) - 2);

    $partesDominio = explode('.', $dominio);
    $dominioEnmascarado = substr($partesDominio[0], 0, 2) . str_repeat('*', strlen($partesDominio[0]) - 2) . '.' . $partesDominio[1];

    return $enmascaradoUsuario . '@' . $dominioEnmascarado;
}