<?php
/*$servername = "localhost";
$userName = "root";
$passWord = "";
//$dbname = "ngldatabase";
$dbname = "ngldb1";
$port = 3308;

// Connection  <= to MYSQL
$conn = new mysqli($servername, $userName, $passWord, $dbname, $port);*/
?>

<?php
// Load Composer's autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Access environment variables
$servername = $_ENV['DB_HOST'];
$userName = $_ENV['DB_USER'];
$passWord = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];
$port = $_ENV['DB_PORT'];

// Database connection
$conn = new mysqli($servername, $userName, $passWord, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>