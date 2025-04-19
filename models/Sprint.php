<?php

namespace Model;

use Model\ActiveRecord;

/**
 * Modelo de la tabla sprints
 */
class Sprint extends ActiveRecord
{
    /** @var string Nombre de la tabla */
    protected static $tabla = 'sprints';

    /** @var string Llave primaria de la tabla */
    protected static $idTabla = 'id';

    /** @var array Columnas de la base de datos */
    protected static $columnasDB = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'proyecto_id'
    ];

    /** @var int|null ID del sprint */
    public $id;

    /** @var string Nombre del sprint */
    public $nombre;

    /** @var string Fecha de inicio del sprint */
    public $fecha_inicio;

    /** @var string Fecha de fin del sprint */
    public $fecha_fin;

    /** @var int ID del proyecto asociado al sprint */
    public $proyecto_id;

    /**
     * Constructor del modelo Sprint
     *
     * @param array $args Valores iniciales del modelo
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->fecha_inicio = $args['fecha_inicio'] ?? null;
        $this->fecha_fin = $args['fecha_fin'] ?? null;
        $this->proyecto_id = $args['proyecto_id'] ?? null;
    }
}
