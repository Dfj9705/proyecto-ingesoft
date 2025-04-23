<?php

namespace Model;

use Model\ActiveRecord;

/**
 * Modelo que representa la asignación de usuarios a proyectos.
 */
class ProyectoPersona extends ActiveRecord
{
    /** @var string Nombre de la tabla en la base de datos */
    protected static $tabla = 'proyecto_persona';

    /** @var string Clave primaria de la tabla */
    protected static $idTabla = 'id';

    /** @var array Columnas presentes en la base de datos */
    protected static $columnasDB = [
        'proyecto_id',
        'usuario_id',
        'rol_asignado',
        'fecha_asignacion'
    ];

    /** @var int|null ID de la relación (autoincremental) */
    public $id;

    /** @var int ID del proyecto asociado */
    public $proyecto_id;

    /** @var int ID del usuario asignado al proyecto */
    public $usuario_id;

    /** @var string|null Rol asignado dentro del proyecto (opcional) */
    public $rol_asignado;

    /** @var string Fecha de asignación del usuario al proyecto */
    public $fecha_asignacion;

    /**
     * Constructor del modelo ProyectoPersona
     *
     * @param array $args Arreglo asociativo con los valores iniciales del modelo
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto_id = $args['proyecto_id'] ?? null;
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->rol_asignado = $args['rol_asignado'] ?? null;
        $this->fecha_asignacion = $args['fecha_asignacion'] ?? date('Y-m-d');
    }
}