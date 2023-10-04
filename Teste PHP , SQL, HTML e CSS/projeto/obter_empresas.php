<?php

include 'conexao.php';

// Query para obter todas as empresas e suas contas a pagar
$query = "SELECT e.id_empresa, e.nome as nome_empresa, c.id_conta_pagar, c.valor, c.data_pagar, c.pago 
          FROM tbl_empresa e
          LEFT JOIN tbl_conta_pagar c ON e.id_empresa = c.id_empresa";
$resultado = $conn->query($query);

// Verificar se há resultados e formatar em um array associativo
$dados = [];
if ($resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
        $dados[] = [
            "id_empresa" => $row["id_empresa"],
            "nome_empresa" => $row["nome_empresa"],
            "id_conta_pagar" => $row["id_conta_pagar"],
            "valor" => $row["valor"],
            "data_pagar" => $row["data_pagar"],
            "pago" => $row["pago"]
        ];
    }
}

// Fechar a conexão com o banco de dados
$conn->close();

// Enviar a resposta apenas como JSON
header('Content-Type: application/json');
echo json_encode(["dados" => $dados]);
?>
