<?php

include 'conexao.php';

// Verifique se o ID da conta foi fornecido via GET
if (isset($_GET['id'])) {
    $idContaPagar = mysqli_real_escape_string($conn, $_GET['id']);

    // Consulta para obter os detalhes da conta com base no ID, incluindo o campo 'empresa'
    $consulta = "SELECT * FROM tbl_conta_pagar WHERE id_conta_pagar = $idContaPagar";
    $resultado = mysqli_query($conn, $consulta);

    if ($resultado) {
        // Converte o resultado da consulta em um array associativo
        $conta = mysqli_fetch_assoc($resultado);

        // Retorna os dados da conta como resposta em formato JSON
        echo json_encode($conta);
    } else {
        echo json_encode(["erro" => "Erro ao obter dados da conta: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["erro" => "ID da conta não fornecido"]);
}

// Feche a conexão com o banco de dados
$conn->close();
?>
