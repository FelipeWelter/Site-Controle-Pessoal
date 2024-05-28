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
$username = "felipe";
$password = "Welter45@";
$dbname = "controle_acesso";

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
    $nome_completo = $conn->real_escape_string($_POST['nome_completo']);
    $identidade_cpf = $conn->real_escape_string($_POST['identidade_cpf']);
    $hora_entrada = $conn->real_escape_string($_POST['hora_entrada']);
    $hora_saida = !empty($_POST['hora_saida']) ? $conn->real_escape_string($_POST['hora_saida']) : NULL;
    $destino = $conn->real_escape_string($_POST['destino']);
    $observacoes = $conn->real_escape_string($_POST['observacoes']);

    // Inserir dados na tabela
    $sql = "INSERT INTO acesso (nome_completo, identidade_cpf, hora_entrada, hora_saida, destino, observacoes)
            VALUES ('$nome_completo', '$identidade_cpf', '$hora_entrada', " . ($hora_saida ? "'$hora_saida'" : "NULL") . ", '$destino', '$observacoes')";

    if ($conn->query($sql) === TRUE) {
        $message = "Registro inserido com sucesso.";
    } else {
        $message = "Erro ao inserir registro: " . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
}
?>
<!DOCTYPE html> 
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Controle de Acesso</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

h1 {
    text-align: center;
}

form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9f9;
}

label {
    display: block;
    margin: 10px 0 5px;
}

input[type="text"],
input[type="datetime-local"],
textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

    </style>
</head>

<body>
    <h1>Controle de Acesso</h1>
    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="controle_acesso.php" method="post">
        <label for="nome_completo">Nome Completo:</label>
        <input type="text" id="nome_completo" name="nome_completo" required>
        
        <label for="identidade_cpf">Identidade ou CPF:</label>
        <input type="text" id="identidade_cpf" name="identidade_cpf" required>
        
        <label for="hora_entrada">Hora de Entrada:</label>
        <input type="datetime-local" id="hora_entrada" name="hora_entrada" required>
        
        <label for="hora_saida">Hora de Saída:</label>
        <input type="datetime-local" id="hora_saida" name="hora_saida">
        
        <label for="destino">Destino:</label>
        <input type="text" id="destino" name="destino" required>
        
        <label for="observacoes">Observações:</label>
        <textarea id="observacoes" name="observacoes"></textarea>
        
        <input type="submit" value="Registrar">
    </form>

    <h2>Registros</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome Completo</th>
            <th>Identidade/CPF</th>
            <th>Hora de Entrada</th>
            <th>Hora de Saída</th>
            <th>Destino</th>
            <th>Observações</th>
        </tr>
        <?php
        // Conexão com o banco de dados
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica a conexão
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Obter dados da tabela
        $sql = "SELECT * FROM acesso";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nome_completo']}</td>
                        <td>{$row['identidade_cpf']}</td>
                        <td>{$row['hora_entrada']}</td>
                        <td>{$row['hora_saida']}</td>
                        <td>{$row['destino']}</td>
                        <td>{$row['observacoes']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Nenhum registro encontrado</td></tr>";
        }

        // Fecha a conexão
        $conn->close();
        ?>
    </table>
</body>
</html>
