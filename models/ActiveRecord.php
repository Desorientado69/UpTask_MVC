<?php
    namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $table = '';
    protected static $columnsDB = [];

    // Alertas y Mensajes
    protected static $alerts = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database): void {
        self::$db = $database;
    }
    //Guarda una alerta o mensaje
    public static function setAlert($type, $message) : void{
        static::$alerts[$type][] = $message;
    }
    // Devuelve las alertas y mensajes
    public static function getAlerts(): array {
        return static::$alerts;
    }
    // Validación
    public function validate(): array {
        static::$alerts = [];
        return static::$alerts;
    }

    // Registros - CRUD
    public function save() {
        $result = '';
        if(!is_null($this->id)) {
            // actualizar
            $result = $this->update();
        } else {
            // Creando un nuevo registro
            $result = $this->create();
        }
        return $result;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$table;
        $result = self::querySQL($query);
        return $result;
    }

    // Busca un registro por su id
    public static function find($id): object {
        $query = "SELECT * FROM " . static::$table  ." WHERE id = ${id}";
        $result = self::querySQL($query);
        return array_shift( $result ) ;
    }

    // Obtener Registro
    public static function get($limit): object {
        $query = "SELECT * FROM " . static::$table . " LIMIT ${limit}";
        $result = self::querySQL($query);
        return array_shift( $result ) ;
    }

    // Búsqueda Where con Columna. El primer elemento unicamente
    public static function where($column, $value) {
        $query = "SELECT * FROM " . static::$table . " WHERE ${column}='${value}'";
        $result = self::querySQL($query);
        return array_shift( $result ) ;
    }
    // Búsqueda Where. Todos los que coincidan con la consultacon Columna 
    public static function belongsTo($column, $value) {
        $query = "SELECT * FROM " . static::$table . " WHERE ${column}='${value}'";
        $result = self::querySQL($query);
        return  $result;
    }

    // SQL para Consultas Avanzadas.
    public static function SQL($consulta) {
        $query = $consulta;
        $result = self::querySQL($query);
        return $result;
    }

    // Crea un nuevo registro
    public function create(): array {
        // Sanitizar los datos
        $attributes = $this->sanitizeAttributes();
        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$table . " (";
        $query .= join(',', array_keys($attributes));
        $query .= ") VALUES ('"; 
        $query .= join("','", array_values($attributes));
        $query .= "')";
        // Resultado de la consulta
        $result = self::$db->query($query);
        return [
            'result' =>  $result,
            'id' => self::$db->insert_id
        ];
    }

    public function update(): bool {
        // Sanitizar los datos
        $attributes = $this->sanitizeAttributes();
        // Iterar para ir agregando cada campo de la BD
        $values = [];
        foreach($attributes as $key => $value) {
            $values[] = "{$key}='{$value}'"; //Formato para la query update Array con key ='value' en cada posición
        }
        $query = "UPDATE " . static::$table ." SET ";
        $query .=  join(',', $values ); //saca todos los valores del array seguidos separados por ','
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 
        $result = self::$db->query($query);
        return $result;
    }

    // Eliminar un registro - Toma el ID de Active Record
    public function delete(): bool{
        $query = "DELETE FROM "  . static::$table . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $result = self::$db->query($query);
        return $result;
    }

    public static function querySQL($query): array {
        // Consultar la base de datos
        $result = self::$db->query($query);
        // Iterar los resultados
        $array = [];
        while($record = $result->fetch_assoc()) {
            $array[] = static::createObjet($record);
        }
        // Liberar la memoria
        $result->free();
        // Retornar los resultados
        return $array;
    }

    protected static function createObjet($record): object {
        $object = new static;
        foreach($record as $key => $value ) {
            if(property_exists( $object, $key  )) {
                $object->$key = $value;
            }
        }
        return $object;
    }

    // Identificar y unir los atributos de la BD
    public function attributes(): array {

        $attributes = [];
        foreach(static::$columnsDB as $column) {
            if($column === 'id') continue;
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    public function sanitizeAttributes(): array {
        $attributes = $this->attributes();
        $sanitized = [];
        foreach($attributes as $key => $value ) {
            $sanitized[$key] = self::$db->escape_string($value);
        }
        return $sanitized;
    }

    public function syncUp($args=[]): void { 
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
?>