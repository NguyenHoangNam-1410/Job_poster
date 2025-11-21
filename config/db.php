<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Database
{
    public $conn;
    private $servername = "localhost";
    private $username = "root";
    private $db_name = "job_poster";
    private $db_password;

    public function __construct()
    {
        $this->servername = $_ENV["DB_HOST"];
        $this->username = $_ENV["DB_USERNAME"];
        $this->db_password = $_ENV['DB_PASSWORD'] ?? '';
        $this->db_name = $_ENV['DB_NAME'] ?? '';
        $this->conn = new mysqli($this->servername, $this->username, $this->db_password, $this->db_name);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}

$db = new Database();
?>