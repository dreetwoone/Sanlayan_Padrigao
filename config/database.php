<?php
class Database {
    private const HOST    = 'localhost';
    private const DB_NAME = 'profit_db';
    private const USER    = 'root';
    private const PASS    = '';
    private const CHARSET = 'utf8mb4';

    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host='.self::HOST.';dbname='.self::DB_NAME.';charset='.self::CHARSET;
            try {
                self::$instance = new PDO($dsn, self::USER, self::PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die("<div style='font-family:sans-serif;background:#1a1a1a;color:#ff4f4f;
                    padding:30px;border-radius:10px;margin:40px auto;max-width:500px;border:1px solid #ff4f4f'>
                    <h3>Database Connection Failed</h3>
                    <p><strong>Error:</strong> ".htmlspecialchars($e->getMessage())."</p>
                    <p style='color:#7a7a7a;font-size:13px'>
                        1. Make sure XAMPP MySQL is running<br>
                        2. Make sure you created the <strong>profit_db</strong> database<br>
                        3. Check config/Database.php settings
                    </p></div>");
            }
        }
        return self::$instance;
    }
}