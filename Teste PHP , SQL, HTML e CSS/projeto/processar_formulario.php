<?php

include 'conexao.php';

// Verifique se os dados foram recebidos via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empresa = mysqli_real_escape_string($conn, $_POST["empresa"]);
    $data = mysqli_real_escape_string($conn, $_POST["data"]);
    $valor = mysqli_real_escape_string($conn, $_POST["valor"]);

    //  UUID() para gerar uma chave primária única
    $id_conta_pagar = mysqli_query($conn, "SELECT UUID()")->fetch_row()[0];

    // Validar e sanitizar os dados, por exemplo, verificar se a data é válida, etc.

    //  prepared statements para inserir dados de forma segura
    $stmt = $conn->prepare("INSERT INTO tbl_conta_pagar (id_conta_pagar, id_empresa, data_pagar, valor) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $id_conta_pagar, $empresa, $data, $valor);

    if ($stmt->execute()) {
        echo "Dados inseridos com sucesso!";
    } else {
        echo "Erro ao inserir dados: " . $stmt->error;
    }

    // Fechar a conexão com o banco de dados no final do arquivo
    $stmt->close();
    $conn->close();
}


?>
