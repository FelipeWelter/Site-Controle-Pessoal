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
// Conexão com o banco de dados
$servername = "localhost";
$username = "felipe";
$password = "Welter45@";
$dbname = "felipe";
$conn = new mysqli($servername, $username, $password, $dbname);
// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script>
        function confirmLogout(event) {
            event.preventDefault(); // Impede o envio do formulário
            var confirmed = confirm("Você tem certeza que deseja sair?");
            if (confirmed) {
                event.target.submit();
            }
        }
    </script>
    </head>
    <title>Usuário Logado</title>
    <header>
    <style>
        .logout-btn {
            background-color: #ff4b5c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .logout-btn:hover {
            background-color: #e04050;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .action-btn {
            padding: 5px 10px;
            margin-right: 5px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #4CAF50; /* Green */
        }
        .remove-btn {
            background-color: #f44336; /* Red */
        }
        .lgnin {
            border-radius: 4px;
            color: white;
            background-color: #45a049;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            display: flex;
            justify-content: space-around;
            border-bottom: 1px solid #ccc;
        }
        .lgout {
            border-radius: 4px;
            color: white;
            background-color: #f44336;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            display: flex;
            justify-content: space-around;
            border-bottom: 1px solid #ccc;
        }
        .container {
            text-align: center;
        }

        .toolbar {
            display: flex;
            justify-content: space-around;
            background-color: #f1f1f1;
            padding: 10px;
            font-size: 16px;
            border-bottom: 1px solid #ccc;
        }
        .toolbar a, .toolbar form {
            text-decoration: none;
            color: #000;
        }
        .toolbar a:hover, .toolbar input[type="submit"]:hover {
            color: #007BFF;
        }
        .toolbar .logout-btn {
            background-color: #ff4b5c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .toolbar .logout-btn:hover {
            color: #007BFF;
        }
        </style>
    <body>
    <div class="toolbar">
        <h4>Sistema de Gerenciamento Pessoal</h4><a></a><a></a><a></a>
        <form method="post" action="controle_acesso">
            <a href="controle_acesso.php" class="lgnin">Controle de Acesso</a>
        </form>
        <form method="post" action="register">
            <a href="register.php" class="lgnin">Cadastrar</a>
        </form> 
        <form method="post" action="gerenciarusuarios">
            <a href="gerenciarusuarios.php" class="lgnin">Gerenciar Usuários</a>  
        </form>   
        <form method="post" action="logout.php" onsubmit="confirmLogout(event)">
            <input type="submit" class="lgout" value="Sair">
        </form>
    </div>
        </body>
        <body>
            <div class="container">
                <img src="img/bemvindo.png" alt="Bem Vindo" style="width: 30%; height: auto;">
            </div>
        </body>
    </header>
</html>

<?php
$conn->close();
?>