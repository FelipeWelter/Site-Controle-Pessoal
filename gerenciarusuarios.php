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

// Executa a consulta para obter os dados
$sql = "SELECT id, username FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login bem-sucedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        .lgnin {
            border-radius: 4px;
            color: #fff;
            background-color: #45a049;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
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
    </style>
</head>
<body>
    <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Login realizado com sucesso.</p>
    <a href="register.php" class="lgnin">Cadastrar</a>
    <h2>Lista de Usuários</h2>
    <table>
        <tr>
            <th>Nome de Usuário</th>
            <th>Ações</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["username"]) . "</td>
                        <td>
                            <button class='action-btn edit-btn' onclick='editarUsuario(" . htmlspecialchars($row["id"]) . ", \"" . htmlspecialchars($row["username"]) . "\")'>Editar</button>
                            <button class='action-btn remove-btn' onclick='removerUsuario(" . htmlspecialchars($row["id"]) . ")'>Remover</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Nenhum usuário encontrado</td></tr>";
        }
        ?>
    </table>

    <h2>Editar Usuário</h2>
    <form id="editForm" method="post" action="editar_usuario.php" style="display:none;">
        <input type="hidden" id="editUserId" name="id">
        <label for="editUsername">Nome de Usuário:</label>
        <input type="text" id="editUsername" name="username" required>
        <button type="submit" class="action-btn edit-btn">Salvar</button>
    </form>

    <script>
        function removerUsuario(id) {
            if (confirm("Tem certeza de que deseja remover este usuário?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "remover_usuario.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        location.reload();
                    }
                };
                xhr.send("id=" + id);
            }
        }

        function editarUsuario(id, username) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('editUserId').value = id;
            document.getElementById('editUsername').value = username;
        }
    </script>

    <br>
    <form method="post" action="index.php">
        <input type="submit" class="logout-btn" value="Voltar">
    </form>
    <br>
    <form method="post" action="logout.php">
        <input type="submit" class="logout-btn" value="Sair">
    </form>
</body>
</html>

<?php
$conn->close();
?>
