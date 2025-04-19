<?php

try {
    $ruta = __DIR__ . '\db.sqlite';

    $db = new PDO("sqlite:$ruta");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        "detalle" => $e->getMessage(),
        "mensaje" => "Error de conexión bd",

        "codigo" => 5,
    ]);
    // header('Location: /');
    // exit;
}
