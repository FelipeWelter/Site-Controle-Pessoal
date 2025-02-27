<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['userid'] !== true) {
    header("Location: login.php");
    exit();
}
// Define cabeçalhos para impedir o cache
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "felipe";

// Mensagem de sucesso ou erro
$message = "";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Captura os dados do formulário
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Proteção contra SQL Injection
    $user = $conn->real_escape_string($user);
    $pass = $conn->real_escape_string($pass);

    // Hash da senha
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Insere os dados na tabela users
    $sql = "INSERT INTO usuarios (username, password) VALUES ('$user', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        $message = "Cadastro realizado com sucesso!";
    } else {
        $message = "Erro: " . $sql . "<br>" . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
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
        .container {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container h2 {
            margin-bottom: 25px;
        }
        .container input[type="text"],
        .container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            color: red;
            text-align: center;
        }
        .vltr {
            border-radius: 4px;
            color: #45a049;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .vltr:hover {
            color: #fff;
            background-color: #45a049;
        }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Usuário</h2>
        <form method="post" action="" autocomplete="off">
        <input type="text" name="fakeusernameremembered" class="hidden">
        <input type="password" name="fakepasswordremembered" class="hidden">

            <label for="username">Nome de Usuário:</label>
            <input type="text" id="username" name="username" required autocomplete="off"><br><br>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required autocomplete="off"><br><br>
            <input type="submit" value="Cadastrar">
        </form>
        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <br>
        <a href="index.php" class="vltr">Voltar</a>
    </div>
</body>
</html>
