<?php
session_start();
include "conexao.php";

$mensagem = "";

if(!isset($_SESSION['email_recuperacao'])){
    header("Location: recuperarsenha.php");
    exit();
}

$email = $_SESSION['email_recuperacao'];

$stmt = $conn->prepare("
SELECT id FROM usuarios WHERE email = ?
");

$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

$usuario = $resultado->fetch_assoc();

$idUsuario = $usuario['id'];

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $senha = $_POST['senha'];

    if(
        strlen($senha) < 8 ||
        !preg_match('/[A-Z]/', $senha) ||
        !preg_match('/[a-z]/', $senha) ||
        !preg_match('/[0-9]/', $senha) ||
        !preg_match('/[\W]/', $senha)
    ){

        $mensagem = "Senha fraca!";

    } else {

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $update = $conn->prepare("
        UPDATE usuarios
        SET senha = ?
        WHERE id = ?
        ");

        $update->bind_param("si", $senhaHash, $idUsuario);

        if($update->execute()){

            unset($_SESSION['email_recuperacao']);

            $mensagem = "Senha alterada com sucesso!";

        } else {

            $mensagem = "Erro ao alterar senha.";

        }

    }

}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Nova Senha</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#0f172a,#1e3a8a);
}

.formulario{

    width:100%;
    max-width:400px;

    background:rgba(255,255,255,0.05);

    padding:30px;

    border-radius:20px;

}

h2{
    color:white;
    text-align:center;
    margin-bottom:20px;
}

input{

    width:100%;
    padding:15px;
    margin-bottom:15px;

    border:none;
    border-radius:10px;

    background:rgba(255,255,255,0.1);

    color:white;

}

button{

    width:100%;
    padding:15px;

    border:none;
    border-radius:10px;

    background:#06b6d4;

    color:white;
    font-size:18px;

}

.mensagem{

    color:white;
    text-align:center;
    margin-bottom:15px;

}

</style>

</head>
<body>

<div class="formulario">

<h2>Nova Senha</h2>

<?php
if($mensagem){
    echo "<div class='mensagem'>$mensagem</div>";
}
?>

<form method="POST">

<input type="password" name="senha" placeholder="Nova senha" required>

<button type="submit">
Salvar
</button>

</form>

</div>

</body>
</html>