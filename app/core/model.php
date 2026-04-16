<?php
// ============================================================
//  app/core/Model.php  — BASE MODEL
//  All models extend this. Gives them $this->db (PDO connection).
// ============================================================

class Model {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
}