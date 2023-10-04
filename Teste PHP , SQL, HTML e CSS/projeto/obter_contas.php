<?php
include 'conexao.php';

if (isset($_GET['empresa'])) {
    $nomeEmpresa = mysqli_real_escape_string($conn, $_GET['empresa']);

    // Consulta SQL para filtrar contas com base no nome da empresa
    $sql = "SELECT * FROM tbl_conta_pagar
            WHERE id_empresa IN (SELECT id_empresa FROM tbl_empresa WHERE nome_empresa LIKE '%$nomeEmpresa%')";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $contas = array();

        while ($row = $result->fetch_assoc()) {
            $contas[] = $row;
        }

        echo json_encode(["dados" => $contas]);
    } else {
        echo json_encode(["dados" => []]);
    }
} else {
    echo json_encode(["erro" => "Parâmetros inválidos"]);
}

$conn->close();
?>
