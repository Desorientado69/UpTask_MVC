<?php 
    namespace Model;

class Project extends ActiveRecord {
    protected static $table = 'projects';
    protected static $columnsDB = ['id','name','url','ownerid'];

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->ownerid = $args['ownerid'] ?? '';
    }
    //Validación de nombre del project al incluirlo el usuario
    public function validateProject(): array {
        if (!$this->name) {
            self::$alerts['error'][] = "Project Name is required";
        }
        return self::$alerts;
    }
}
?>