<?php

namespace Model;

use Model\ActiveRecord;

/**
 * Modelo de la tabla roles
 */
class Rol extends ActiveRecord
{
    /** @var string Nombre de la tabla */
    protected static $tabla = 'roles';

    /** @var string Llave primaria de la tabla */
    protected static $idTabla = 'id';

    /** @var array Columnas de la base de datos */
    protected static $columnasDB = [
        'nombre',
        'descripcion'
    ];

    /** @var int|null ID del rol */
    public $id;

    /** @var string Nombre del rol (ej. admin, usuario, lider_proyecto) */
    public $nombre;

    /** @var string Descripción del rol */
    public $descripcion;

    /**
     * Constructor del modelo Rol
     *
     * @param array $args Valores iniciales del modelo
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
    }
}
