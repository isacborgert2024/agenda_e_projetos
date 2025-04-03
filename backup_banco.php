<?php
ini_set('date.timezone', 'America/Sao_Paulo');


//Agora ele faz backup do banco para ter um backup do banco dentro do gestao-olt para ser salvo no backup do sistema.
// Configurações do banco de dados
$servername = "localhost";
$username = "agenda";
$password = "#htmKJHg786##";
$dbname = "agenda_db";


//jj Caminho onde o backup será salvo
$backupDir = '/var/www/html/agenda/backup'; // Altere para o diretório onde deseja salvar o backup
$timestamp = date('Y-m-d_H-i-s'); // Data e hora para gerar um nome único para o arquivo
$backupFile = escapeshellarg("$backupDir/backup_db_$timestamp.sql");

$command = "MYSQL_PWD=" . escapeshellarg($password) . " mysqldump --user=" . escapeshellarg($username) . 
           " --host=localhost --no-tablespaces " . escapeshellarg($dbname) . " > $backupFile";
/*
$backupFile = escapeshellarg("/var/www/html/TESTE-gestao-olt/backup/gestao_olt_backup_$timestamp.sql");

$command = "MYSQL_PWD=" . escapeshellarg($password) . " mysqldump --user=" . escapeshellarg($username) . 
           " --host=localhost --no-tablespaces " . escapeshellarg($dbname) . " > $backupFile";
*/
// Executa o comando shell para fazer o backup
$output = shell_exec($command);

// Verifica se o comando foi executado com sucesso
if ($output === null) {
    echo "Backup do banco de dados realizado com sucesso em: $backupFile\n";
} else {
    echo "Erro ao realizar o backup: $output\n";
}


$backupDir = "/var/www/html/agenda/backup/";

// Obtém o mês e ano atual
$currentMonth = date('m');
$currentYear = date('Y');

// Calcula o mês e ano anterior
$previousMonth = (int)$currentMonth - 1;
$previousYear = $currentYear;

if ($previousMonth == 0) {
    $previousMonth = 12;
    $previousYear--;
}

$previousMonth = str_pad($previousMonth, 2, '0', STR_PAD_LEFT); // Garante dois dígitos
$pattern = "{$backupDir}backup_db_{$previousYear}-{$previousMonth}-*.sql";

echo "Procurando arquivos com o padrão: $pattern\n";

// Percorre os arquivos do diretório
foreach (glob($pattern) as $file) {
    if (unlink($file)) {
        echo "Removido: $file\n";
    } else {
        echo "Erro ao remover: $file\n";
    }
}

?>
