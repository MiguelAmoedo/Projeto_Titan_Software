<?php

include 'conexao.php';

// Verifique se o ID da conta a pagar e o novo status foram fornecidos via GET
if (isset($_GET['id']) && isset($_GET['status'])) {
    $idContaPagar = mysqli_real_escape_string($conn, $_GET['id']);
    $novoStatus = mysqli_real_escape_string($conn, $_GET['status']);

    // Verifique a data de pagamento da conta
    $query = "SELECT data_pagar, valor FROM tbl_conta_pagar WHERE id_conta_pagar = $idContaPagar";
    $resultado = $conn->query($query);

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $dataPagar = strtotime($row['data_pagar']);
        $dataAtual = strtotime(date('Y-m-d'));
        $valor = $row['valor'];

        // Aplicar regras de desconto ou acréscimo com base na data de pagamento
        if ($dataAtual > $dataPagar) {
            // Conta paga após a data de pagamento (acréscimo de 10%)
            $valor *= 1.1;
        } elseif ($dataAtual < $dataPagar) {
            // Conta paga antes da data de pagamento (desconto de 5%)
            $valor *= 0.95;
        }

        // Use instrução preparada para atualizar o status e o valor da conta no banco de dados
        $stmt = $conn->prepare("UPDATE tbl_conta_pagar SET pago = ?, valor = ? WHERE id_conta_pagar = ?");
        $stmt->bind_param("dii", $novoStatus, $valor, $idContaPagar);

        if ($stmt->execute()) {
            echo json_encode(["mensagem" => "Status da conta atualizado com sucesso!"]);
        } else {
            echo json_encode(["erro" => "Erro ao atualizar o status da conta: " . $stmt->error]);
        }

        // Feche a instrução preparada
        $stmt->close();
    } else {
        echo json_encode(["erro" => "Conta não encontrada"]);
    }
} else {
    echo json_encode(["erro" => "Parâmetros inválidos"]);
}

// Feche a conexão com o banco de dados
$conn->close();
?>
