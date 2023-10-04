<?php
// Inclua o arquivo de conexão com o banco de dados
include 'conexao.php';

// Verifique se o ID da conta a pagar e o novo valor foram fornecidos via GET
if (isset($_GET['id']) && isset($_GET['novoValor'])) {
    $idContaPagar = mysqli_real_escape_string($conn, $_GET['id']);
    $novoValor = mysqli_real_escape_string($conn, $_GET['novoValor']);

    // Use instrução preparada para atualizar o valor da conta no banco de dados
    $stmt = $conn->prepare("UPDATE tbl_conta_pagar SET valor = ? WHERE id_conta_pagar = ?");
    $stmt->bind_param("di", $novoValor, $idContaPagar);

    if ($stmt->execute()) {
        echo json_encode(["mensagem" => "Valor da conta atualizado com sucesso!"]);
    } else {
        echo json_encode(["erro" => "Erro ao atualizar o valor da conta: " . $stmt->error]);
    }

    // Feche a instrução preparada
    $stmt->close();
} else {
    echo json_encode(["erro" => "Parâmetros inválidos"]);
}

// Feche a conexão com o banco de dados
$conn->close();
?>
