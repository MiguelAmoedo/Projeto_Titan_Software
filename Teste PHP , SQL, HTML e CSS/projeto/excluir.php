<?php
// Inclua o arquivo de conexão com o banco de dados
include 'conexao.php';

// Verifique se o ID da conta a pagar foi fornecido via GET
if (isset($_GET['id'])) {
    $idContaPagar = mysqli_real_escape_string($conn, $_GET['id']);

    // Use instrução preparada para excluir a conta do banco de dados
    $stmt = $conn->prepare("DELETE FROM tbl_conta_pagar WHERE id_conta_pagar = ?");
    $stmt->bind_param("i", $idContaPagar);

    if ($stmt->execute()) {
        echo json_encode(["mensagem" => "Conta excluída com sucesso!"]);
    } else {
        echo json_encode(["erro" => "Erro ao excluir a conta: " . $stmt->error]);
    }

    // Feche a instrução preparada
    $stmt->close();
} else {
    echo json_encode(["erro" => "ID da conta não fornecido"]);
}

// Feche a conexão com o banco de dados
$conn->close();
?>
