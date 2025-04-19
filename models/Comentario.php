<?php

namespace Model;

use Model\ActiveRecord;

/**
 * Modelo de la tabla comentarios
 */
class Comentario extends ActiveRecord
{
    /** @var string Nombre de la tabla */
    protected static $tabla = 'comentarios';

    /** @var string Llave primaria de la tabla */
    protected static $idTabla = 'id';

    /** @var array Columnas de la base de datos */
    protected static $columnasDB = [
        'tarea_id',
        'usuario_id',
        'contenido',
        'creado_en'
    ];

    /** @var int|null ID del comentario */
    public $id;

    /** @var int ID de la tarea relacionada */
    public $tarea_id;

    /** @var int ID del usuario que hizo el comentario */
    public $usuario_id;

    /** @var string Contenido del comentario */
    public $contenido;

    /** @var string Fecha de creación del comentario */
    public $creado_en;

    /**
     * Constructor del modelo Comentario
     *
     * @param array $args Valores iniciales del modelo
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->tarea_id = $args['tarea_id'] ?? null;
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->contenido = $args['contenido'] ?? '';
        $this->creado_en = $args['creado_en'] ?? date('Y-m-d H:i:s');
    }
}
