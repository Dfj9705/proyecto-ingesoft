<?php

namespace Model;

use Model\ActiveRecord;

/**
 * Modelo de la tabla tareas
 */
class Tarea extends ActiveRecord
{
    /** @var string Nombre de la tabla */
    protected static $tabla = 'tareas';

    /** @var string Llave primaria de la tabla */
    protected static $idTabla = 'id';

    /** @var array Columnas de la base de datos */
    protected static $columnasDB = [
        'titulo',
        'descripcion',
        'estado',
        'prioridad',
        'asignado_a',
        'creado_por',
        'sprint_id',
        'epica_id',
        'proyecto_id',
        'creado_en'
    ];

    /** @var int|null ID de la tarea */
    public $id;

    /** @var string Título de la tarea */
    public $titulo;

    /** @var string Descripción de la tarea */
    public $descripcion;

    /** @var string Estado de la tarea (pendiente, en_progreso, completado, etc.) */
    public $estado;

    /** @var string Nivel de prioridad (baja, media, alta) */
    public $prioridad;

    /** @var int ID del usuario asignado */
    public $asignado_a;

    /** @var int ID del usuario que creó la tarea */
    public $creado_por;

    /** @var int ID del sprint asociado */
    public $sprint_id;

    /** @var int ID de la épica asociada */
    public $epica_id;

    /** @var int ID del proyecto asociado */
    public $proyecto_id;

    /** @var string Fecha de creación de la tarea */
    public $creado_en;

    /**
     * Constructor del modelo Tarea
     *
     * @param array $args Valores iniciales del modelo
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->estado = $args['estado'] ?? 'pendiente';
        $this->prioridad = $args['prioridad'] ?? 'media';
        $this->asignado_a = $args['asignado_a'] ?? null;
        $this->creado_por = $args['creado_por'] ?? null;
        $this->sprint_id = $args['sprint_id'] ?? null;
        $this->epica_id = $args['epica_id'] ?? null;
        $this->proyecto_id = $args['proyecto_id'] ?? null;
        $this->creado_en = $args['creado_en'] ?? date('Y-m-d H:i:s');
    }
}
