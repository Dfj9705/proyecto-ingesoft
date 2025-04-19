<?php

namespace Model;

use InvalidArgumentException;
use PDO;
use PDOException;

/**
 * Clase ActiveRecord base para todos los modelos del sistema.
 * Implementa operaciones comunes de acceso a base de datos con PDO (compatible con SQLite).
 */
class ActiveRecord
{
    /** @var PDO Instancia de la conexión PDO */
    protected static $db;

    /** @var string Nombre de la tabla */
    protected static $tabla = '';

    /** @var array Lista de columnas en la tabla */
    protected static $columnasDB = [];

    /** @var string Nombre de la columna primaria */
    protected static $idTabla = '';

    /** @var array Alertas de validación o errores */
    protected static $alertas = [];

    /**
     * Establece la conexión a la base de datos.
     * @param PDO $database Instancia de PDO
     */
    public static function setDB($database)
    {
        self::$db = $database;
    }

    /**
     * Retorna la conexión PDO activa.
     * @return PDO
     */
    public static function getDB(): PDO
    {
        return self::$db;
    }

    /**
     * Agrega una alerta para validaciones o errores.
     * @param string $tipo Tipo de alerta (ej. 'error', 'info')
     * @param string $mensaje Mensaje descriptivo
     */
    public static function setAlerta($tipo, $mensaje)
    {
        static::$alertas[$tipo][] = $mensaje;
    }

    /**
     * Obtiene todas las alertas registradas.
     * @return array
     */
    public static function getAlertas()
    {
        return static::$alertas;
    }

    /**
     * Método base para validaciones, sobrescribible en cada modelo.
     * @return array
     */
    public function validar()
    {
        static::$alertas = [];
        return static::$alertas;
    }

    /**
     * Guarda el modelo actual en la base de datos (INSERT o UPDATE).
     * @return mixed
     */
    public function guardar()
    {
        $id = static::$idTabla ?? 'id';
        return !is_null($this->$id) ? $this->actualizar() : $this->crear();
    }

    /**
     * Retorna todos los registros de la tabla como instancias del modelo.
     * @return array
     */
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;
        return self::consultarSQL($query);
    }

    /**
     * Busca un registro por su clave primaria.
     * @param mixed $id Valor de la clave primaria
     * @return object|null Instancia del modelo o null
     */
    public static function find($id)
    {
        $idField = static::$idTabla ?? 'id';
        $query = "SELECT * FROM " . static::$tabla . " WHERE $idField = :id LIMIT 1";
        $stmt = self::$db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registro ? static::crearObjeto($registro) : null;
    }

    /**
     * Inserta un nuevo registro en la base de datos.
     * @return array Resultado con estado y último ID insertado
     */
    public function crear()
    {
        $atributos = $this->atributos();
        $columnas = array_keys($atributos);
        $valores = array_values($atributos);

        $placeholders = implode(', ', array_fill(0, count($valores), '?'));
        $query = "INSERT INTO " . static::$tabla . " (" . implode(', ', $columnas) . ") VALUES ($placeholders)";

        $stmt = self::$db->prepare($query);
        try {
            $stmt->execute($valores);
            $id = self::$db->lastInsertId();
            return ['resultado' => true, 'id' => $id];
        } catch (PDOException $e) {
            return ['resultado' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Actualiza un registro existente en la base de datos.
     * @return array Resultado de la operación
     */
    public function actualizar()
    {
        $atributos = $this->atributos();
        $id = static::$idTabla ?? 'id';

        $set = [];
        foreach ($atributos as $col => $val) {
            $set[] = "$col = ?";
        }

        $query = "UPDATE " . static::$tabla . " SET " . implode(', ', $set) . " WHERE $id = ?";
        $stmt = self::$db->prepare($query);
        $valores = array_values($atributos);
        $valores[] = $this->$id;

        try {
            $stmt->execute($valores);
            return ['resultado' => true];
        } catch (PDOException $e) {
            return ['resultado' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Elimina el registro actual de la base de datos.
     * @return bool
     */
    public function eliminar()
    {
        $id = static::$idTabla ?? 'id';
        $query = "DELETE FROM " . static::$tabla . " WHERE $id = ?";
        $stmt = self::$db->prepare($query);
        return $stmt->execute([$this->$id]);
    }

    /**
     * Retorna los atributos del modelo sin incluir el ID.
     * @return array
     */
    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            $columna = strtolower($columna);
            if ($columna === 'id' || $columna === static::$idTabla || is_null($this->$columna))
                continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    /**
     * Sincroniza los valores de un array con las propiedades del objeto actual.
     * @param array $args
     */
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Ejecuta una consulta SQL y retorna una lista de objetos del modelo.
     * @param string $query Consulta SQL
     * @return array
     */
    public static function consultarSQL($query)
    {
        $stmt = self::$db->query($query);
        $array = [];
        while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $array[] = static::crearObjeto($registro);
        }
        $stmt->closeCursor();
        return $array;
    }

    /**
     * Crea una instancia del modelo a partir de un array de datos.
     * @param array $registro
     * @return object
     */
    protected static function crearObjeto($registro)
    {
        $objeto = new static;
        foreach ($registro as $key => $value) {
            $key = strtolower($key);
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    /**
     * Ejecuta una consulta JOIN con condiciones WHERE y devuelve stdClass.
     * @param array $joins ['tabla_join', 'tabla_base.columna', 'tabla_join.columna', 'TIPO_JOIN']
     * @param array $conditions [['columna', 'valor', 'condicion']]
     * @param string|null $orderBy Campo de ordenamiento opcional
     * @param string $select Columnas a seleccionar
     * @return array Lista de resultados como objetos stdClass
     */
    public static function joinWhere($joins = [], $conditions = [], $orderBy = null, $select = '*')
    {
        $joinClause = '';
        foreach ($joins as $join) {
            if (!is_array($join) || count($join) < 3) {
                throw new InvalidArgumentException('Cada join debe tener al menos 3 elementos: tabla, columna1 y columna2.');
            }
            list($tabla, $col1, $col2, $tipo) = array_pad($join, 4, 'INNER');
            $joinClause .= " $tipo JOIN $tabla ON $col1 = $col2";
        }

        $params = [];
        $whereClause = [];
        foreach ($conditions as $i => $cond) {
            if (!is_array($cond) || count($cond) < 2) {
                throw new InvalidArgumentException('Cada condición debe tener al menos 2 elementos: columna y valor.');
            }
            list($col, $val, $op) = array_pad($cond, 3, '=');
            $param = ":wparam$i";
            $whereClause[] = "$col $op $param";
            $params[$param] = $val;
        }

        $query = "SELECT $select FROM " . static::$tabla . $joinClause;
        if (!empty($whereClause)) {
            $query .= " WHERE " . implode(' AND ', $whereClause);
        }
        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }

        $stmt = self::$db->prepare($query);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = (object) $row;
        }
        $stmt->closeCursor();
        return $data;
    }

    /**
     * Ejecuta una consulta JOIN con condiciones WHERE y devuelve instancias del modelo.
     * @param array $joins ['tabla_join', 'tabla_base.columna', 'tabla_join.columna', 'TIPO_JOIN']
     * @param array $conditions [['columna', 'valor', 'condicion']]
     * @param string|null $orderBy Campo de ordenamiento opcional
     * @param string $select Columnas a seleccionar
     * @return array Lista de resultados como instancias del modelo
     */
    public static function joinWhereObject($joins = [], $conditions = [], $orderBy = null, $select = '*')
    {
        $joinClause = '';
        foreach ($joins as $join) {
            if (!is_array($join) || count($join) < 3) {
                throw new InvalidArgumentException('Cada join debe tener al menos 3 elementos: tabla, columna1 y columna2.');
            }
            list($tabla, $col1, $col2, $tipo) = array_pad($join, 4, 'INNER');
            $joinClause .= " $tipo JOIN $tabla ON $col1 = $col2";
        }

        $params = [];
        $whereClause = [];
        foreach ($conditions as $i => $cond) {
            if (!is_array($cond) || count($cond) < 2) {
                throw new InvalidArgumentException('Cada condición debe tener al menos 2 elementos: columna y valor.');
            }
            list($col, $val, $op) = array_pad($cond, 3, '=');
            $param = ":objparam$i";
            $whereClause[] = "$col $op $param";
            $params[$param] = $val;
        }

        $query = "SELECT $select FROM " . static::$tabla . $joinClause;
        if (!empty($whereClause)) {
            $query .= " WHERE " . implode(' AND ', $whereClause);
        }
        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }

        $stmt = self::$db->prepare($query);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = static::crearObjeto($row);
        }
        $stmt->closeCursor();
        return $data;
    }

    /**
     * Cuenta todos los registros de la tabla.
     * @return int Número total de registros
     */
    public static function count()
    {
        $query = "SELECT COUNT(*) AS total FROM " . static::$tabla;
        $stmt = self::$db->query($query);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }

    /**
     * Cuenta los registros que cumplen ciertas condiciones.
     * @param array $conditions [['columna', 'valor', 'condicion']]
     * @return int Número de registros que cumplen las condiciones
     */
    public static function countWhere($conditions = [])
    {
        $params = [];
        $whereClause = [];

        foreach ($conditions as $i => $cond) {
            if (!is_array($cond) || count($cond) < 2) {
                throw new InvalidArgumentException('Cada condición debe tener al menos 2 elementos: columna y valor.');
            }
            list($col, $val, $op) = array_pad($cond, 3, '=');
            $param = ":countparam$i";
            $whereClause[] = "$col $op $param";
            $params[$param] = $val;
        }

        $query = "SELECT COUNT(*) AS total FROM " . static::$tabla;
        if (!empty($whereClause)) {
            $query .= " WHERE " . implode(' AND ', $whereClause);
        }

        $stmt = self::$db->prepare($query);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }

    /**
     * Obtiene registros paginados de la tabla.
     * @param int $limit Número de registros por página
     * @param int $offset Número de registros a omitir (inicio)
     * @param string|null $orderBy Campo opcional para ordenar los resultados
     * @return array Lista de objetos del modelo paginados
     */
    public static function paginate($limit, $offset, $orderBy = null)
    {
        $query = "SELECT * FROM " . static::$tabla;
        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }
        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = self::$db->prepare($query);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = static::crearObjeto($row);
        }
        $stmt->closeCursor();
        return $data;
    }
}
