<?php
session_start();
// Configurações do banco de dados
$servername = "localhost";
$username = "felipe";
$password = "Welter45@";
$dbname = "felipe";

// Define cabeçalhos para impedir o cache
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepara a consulta SQL
    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o usuário existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verifica a senha
        if (password_verify($password, $hashed_password)) {
            // Sucesso no login
            $_SESSION['userid'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = true;
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red; text-align: center;'>Senha incorreta!</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Usuário não encontrado!</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Página de Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .login-container {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 25px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 5px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .lgnin{
            border-radius: 4px;
            color: #45a049;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post" action="login.php" autocomplete="off">

        <input type="text" name="fakeusernameremembered" class="hidden">
        <input type="password" name="fakepasswordremembered" class="hidden">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required value="" autocomplete="off"><br><br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required value="" autocomplete="off"><br><br>
        <input type="submit" value="Login">
        </form>

        <br>
        
        <a href="../../index.html"><button class="lgnin">voltar</button></a>
    </div>
</body>
</html>
