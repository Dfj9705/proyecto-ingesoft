<?php

namespace Model;

use Model\ActiveRecord;

/**
 * Modelo de la tabla proyectos
 */
class Proyecto extends ActiveRecord
{
    /** @var string Nombre de la tabla */
    protected static $tabla = 'proyectos';

    /** @var string Llave primaria de la tabla */
    protected static $idTabla = 'id';

    /** @var array Columnas de la base de datos */
    protected static $columnasDB = [
        'nombre',
        'descripcion',
        'creado_por',
        'creado_en'
    ];

    /** @var int|null ID del proyecto */
    public $id;

    /** @var string Nombre del proyecto */
    public $nombre;

    /** @var string Descripción del proyecto */
    public $descripcion;

    /** @var int ID del usuario que creó el proyecto */
    public $creado_por;

    /** @var string Fecha de creación del proyecto */
    public $creado_en;

    /**
     * Constructor del modelo Proyecto
     *
     * @param array $args Valores iniciales del modelo
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->creado_por = $args['creado_por'] ?? null;
        $this->creado_en = $args['creado_en'] ?? date('Y-m-d H:i:s');
    }
}
