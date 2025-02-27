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
$username = "root";
$password = "";
$dbname = "felipe";
$conn = new mysqli($servername, $username, $password, $dbname);
// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
// Configurações do banco de dados de acesso de controle
$servername = "localhost";
$username = "root";
$password = "";
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
        <title>Usuário Logado</title>
        <style>
            /* A imagem de fundo ocupa toda a tela */
            .background-image {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1; /* Coloca a imagem de fundo atrás dos outros elementos */
            }
            /* Barra de ferramentas fixada no topo */
            .toolbar {
                position: fixed;
                top: 0;
                left: 0;
                width: 98%;
                background-color: rgba(0, 0, 0, 0.6); /* Fundo semi-transparente para destacar a barra */
                color: white;
                padding: 8px 15px; /* Menos espaço na barra */
                z-index: 10; /* Garante que a barra de ferramentas fique acima da imagem */
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 8px 20px;
            }
            /* Logo na barra de ferramentas */
            .toolbar-logo {
                width: 8%;
                height: auto;
            }
            /* Estilo dos links e botões dentro da barra */
            .lgnin {
                border-radius: 4px;
                color: white;
                background-color: #45a049;
                padding: 8px 16px; /* Reduzi o padding entre os botões */
                border: none;
                cursor: pointer;
                font-size: 14px; /* Diminui o tamanho da fonte para caber melhor */
                text-decoration: none;
                margin-left: 10px; /* Menor margem entre os botões */
                display: inline-block;
            }
            .lgnin:hover {
                text-decoration: underline;
            }
            .lgout {
                border-radius: 4px;
                color: white;
                background-color: #f44336;
                padding: 8px 16px; /* Ajustei o padding */
                border: none;
                cursor: pointer;
                font-size: 14px; /* Diminui o tamanho da fonte */
                text-decoration: none;
                margin-left: 5px; /* Ajustei a margem para colocar o botão mais para a esquerda */
                display: inline-block;
            }
            .lgout:hover {
                background-color: #d32f2f;
            }
            /* Tabelas */
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

            /* Botões de ação */
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
            .container {
                text-align: center;
            }
            /* Ajusta o tamanho da fonte do título */
            .toolbar h4 {
                font-size: 30px; /* Ajuste o tamanho da fonte conforme desejado */
                margin: 0; /* Remove o espaço extra */
            }

            /* Nova tabela abaixo da toolbar*/
            .table-container {
                margin-top: 150px; /* Garante que a tabela fique abaixo da toolbar */
                padding: 20px;
                background-color: rgba(255, 255, 255, 0.8); /* Fundo semitransparente para a tabela */
                position: relative; /* Mantém o conteúdo na mesma posição em relação à tela */
                z-index: 5; /* Garante que fique acima da imagem de fundo */
            }


        </style>
    </head>
    <body>
        <div class="container"> 
            <img src="img/fundo.jpg" alt="imgFundo" class="background-image">
        </div>

        <div class="toolbar">
            <img src="img/53519954.jpeg" alt="Bem Vindo" class="toolbar-logo">
            <h4>Sistema de Gerenciamento Pessoal</h4>
            <a></a><a></a><a></a>

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

        <!-- Nova div para a tabela -->
        <div class="table-container">
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

        </div>
    </body>

</html>
