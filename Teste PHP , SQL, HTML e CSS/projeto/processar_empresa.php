<?php

include 'conexao.php';

// Verifique se os dados foram recebidos via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize e validar o nome da empresa
    $nome = mysqli_real_escape_string($conn, $_POST["nome"]);

    //  UUID() para gerar uma chave primária única
    $id_empresa = mysqli_query($conn, "SELECT UUID()")->fetch_row()[0];

    //  prepared statements para inserir dados de forma segura
    $stmt = $conn->prepare("INSERT INTO tbl_empresa (id_empresa, nome) VALUES (?, ?)");
    $stmt->bind_param("ss", $id_empresa, $nome);

    if ($stmt->execute()) {
        echo "Empresa cadastrada com sucesso!";
    } else {
        echo "Erro ao cadastrar empresa: " . $stmt->error;
    }

    // Fechar a conexão com o banco de dados no final do arquivo
    $stmt->close();
    $conn->close();
}
?>
