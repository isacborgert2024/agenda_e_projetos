<?php
require 'banco_e_functions_gerais.php';

if (!isset($_GET['id_usuario_tecnico']) || !isset($_GET['data_agenda'])) {
    echo json_encode(['error' => 'Parametros ausentes']);
    exit;
}

$id_tecnico = $_GET['id_usuario_tecnico'];
$data_agenda = $_GET['data_agenda']; // Formato: YYYY-MM-DD
$id_agenda = isset($_GET['id_agenda']) ? $_GET['id_agenda'] : null; // Verifica se o id_agenda foi passado

$sql = "SELECT data_agenda, horas_execucao FROM agenda WHERE id_usuario_tecnico = ? AND DATE(data_agenda) = ?";
if ($id_agenda) {
    $sql .= " AND id != ?";
}

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Erro ao preparar a consulta']);
    exit;
}

if ($id_agenda) {
    $stmt->bind_param("isi", $id_tecnico, $data_agenda, $id_agenda); // Bindando id_agenda quando fornecido
} else {
    $stmt->bind_param("is", $id_tecnico, $data_agenda); // Bind sem id_agenda
}

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Erro ao executar a consulta']);
    exit;
}

$result = $stmt->get_result();

$horarios_ocupados = [];

while ($row = $result->fetch_assoc()) {
    $inicio = strtotime($row['data_agenda']);
    $horas_execucao = $row['horas_execucao'];
    $fim = $inicio + ($horas_execucao * 3600);

    // Criar intervalo de horas ocupadas no formato correto
    for ($hora = $inicio; $hora < $fim; $hora += 3600) {
        $horarios_ocupados[] = date('H:00', $hora); // Correção do formato
    }
}

echo json_encode($horarios_ocupados);

?>
