<?php

namespace Model;

use Model\ActiveRecord;

/**
 * Modelo de la tabla epicas
 */
class Epica extends ActiveRecord
{
    /** @var string Nombre de la tabla */
    protected static $tabla = 'epicas';

    /** @var string Llave primaria de la tabla */
    protected static $idTabla = 'id';

    /** @var array Columnas de la base de datos */
    protected static $columnasDB = [
        'titulo',
        'descripcion',
        'proyecto_id',
        'creado_por',
        'creado_en'
    ];

    /** @var int|null ID de la épica */
    public $id;

    /** @var string Título de la épica */
    public $titulo;

    /** @var string Descripción de la épica */
    public $descripcion;

    /** @var int ID del proyecto al que pertenece */
    public $proyecto_id;

    /** @var int ID del usuario que creó la épica */
    public $creado_por;

    /** @var string Fecha de creación de la épica */
    public $creado_en;

    /**
     * Constructor del modelo Epica
     *
     * @param array $args Valores iniciales del modelo
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->proyecto_id = $args['proyecto_id'] ?? null;
        $this->creado_por = $args['creado_por'] ?? null;
        $this->creado_en = $args['creado_en'] ?? date('Y-m-d H:i:s');
    }
}
