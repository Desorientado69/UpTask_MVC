<?php 
    namespace Model;

class Task extends ActiveRecord {
    protected static $table = 'tasks';
    protected static $columnsDB = ['id','name','state','projectid'];

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->state = $args['state'] ?? 0;
        $this->projectid = $args['projectid'] ?? '';
    }
    //Validación de nombre de task al incluirlo en un project
    public function validateTask(): array {
        if (!$this->name) {
            self::$alerts['error'][] = "Task Name is required";
        }
        return self::$alerts;
    }
}
?>