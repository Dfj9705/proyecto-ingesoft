<?php

namespace Model;

use Model\ActiveRecord;

/**
 * Modelo de la tabla usuarios
 */
class Usuario extends ActiveRecord
{
    /**
     * Nombre de la tabla
     * @var string
     */
    protected static $tabla = 'usuarios';

    /**
     * Llave primaria de la tabla
     * @var string
     */
    protected static $idTabla = 'id';

    /**
     * Columnas de la base de datos
     * @var array
     */
    protected static $columnasDB = [
        'nombre',
        'email',
        'password',
        'rol_id',
        'token',
        'verificado',
        'creado_en'
    ];

    /** @var int|null ID del usuario */
    public $id;

    /** @var string Nombre del usuario */
    public $nombre;

    /** @var string Correo electrónico del usuario */
    public $email;

    /** @var string Contraseña (hash) */
    public $password;

    /** @var int ID del rol asignado (relación con tabla roles) */
    public $rol_id;

    /** @var string Token de verificación o autenticación */
    public $token;

    /** @var int 0 = no verificado, 1 = verificado */
    public $verificado;

    /** @var string Fecha de creación del registro */
    public $creado_en;

    /**
     * Constructor del modelo Usuario
     *
     * @param array $args Valores iniciales del modelo
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->rol_id = $args['rol_id'] ?? 2;
        $this->token = $args['token'] ?? '';
        $this->verificado = $args['verificado'] ?? 0;
        $this->creado_en = $args['creado_en'] ?? date('Y-m-d H:i:s');
    }

    /**
     * Verifica si un correo electrónico ya existe en la tabla de usuarios.
     *
     * @param string $email Correo electrónico a verificar.
     * @return bool true si el correo existe, false en caso contrario.
     *
     */
    public static function existeEmail($email): bool
    {
        $email = trim($email);
        $conteo = self::countWhere(
            [
                ['email', $email, '=']
            ]
        );



        return $conteo > 0;
    }
}
