<?php
session_start();
include "conexao.php";

$mensagem = "";

function limpar($dado){
    return htmlspecialchars(trim($dado));
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = limpar($_POST['email']);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $mensagem = "Email inválido!";
    } else {

        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if($resultado->num_rows > 0){

            // SALVA EMAIL NA SESSÃO
            $_SESSION['email_recuperacao'] = $email;

            // REDIRECIONA
            header("Location: resetarsenha.php");
            exit;

        } else {
            $mensagem = "Email não encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Recuperar Senha</title>
</head>

<style>
/* RESET */
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

/* FUNDO */
body{
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #0f172a, #1e3a8a);
}

/* FORM */
form{
    width: 100%;
    max-width: 380px;
    padding: 30px;
    border: 2px solid rgba(255,255,255,0.2);
    border-radius: 18px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);

    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* TÍTULO */
h2{
    color: white;
    text-align: center;
    margin-bottom: 10px;
}

/* INPUT */
input{
    width: 100%;
    padding: 15px;
    border-radius: 12px;
    border: 2px solid rgba(255,255,255,0.35);
    background: rgba(255,255,255,0.08);
    color: white;
    font-size: 16px;
    outline: none;
}

input::placeholder{
    color: rgba(255,255,255,0.8);
}

/* BOTÃO */
button{
    padding: 15px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(90deg, #22d3ee, #06b6d4);
    color: white;
    font-size: 20px;
    cursor: pointer;
}

/* MENSAGEM */
.mensagem{
    color: white;
    text-align: center;
}
</style>

<body>

<form method="POST">

    <h2>Recuperar Senha</h2>

    <?php if($mensagem) echo "<div class='mensagem'>$mensagem</div>"; ?>

    <input type="email" name="email" placeholder="Digite seu email" required>

    <button type="submit">Recuperar</button>

</form>
<p>Resetar senha<a href="resetarsenha.php">Resetar senha</a></p>

</body>
</html>