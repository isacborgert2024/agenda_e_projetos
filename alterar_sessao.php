<?php
session_start();

if (isset($_POST['nivel_usuario'])) {
    $nivel = (int) $_POST['nivel_usuario'];

    // Apenas aceita valores 1, 3 e 5
    if (in_array($nivel, [1, 3, 5])) {
        $_SESSION['nivel_usuario_real'] = 5;
        $_SESSION['nivel_usuario'] = $nivel;
       // echo "Nível atualizado para $nivel";
    } else {
        echo "Valor inválido";
    }
}
?>
