<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['userid'] !== true) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "felipe";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $username = $_POST["username"];

    $sql = "UPDATE usuarios SET username=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $username, $id);

    if ($stmt->execute()) {
        header("Location: gerenciarusuarios.php");
        exit();
    } else {
        echo "Erro ao atualizar usuário: " . $conn->error;
    }
}

$conn->close();
?>
