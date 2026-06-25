<?php
session_start();
include "conexao.php";

$mensagem = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $usuario = null;

    // EMAIL
    if(filter_var($login, FILTER_VALIDATE_EMAIL)){

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $login);

    }

    // TELEFONE
    elseif(preg_match('/^[0-9]{10,11}$/', preg_replace('/[^0-9]/', '', $login))){

        $login = preg_replace('/[^0-9]/', '', $login);

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE celular = ?");
        $stmt->bind_param("s", $login);

    }

    // CPF
    else{

        $stmt = $conn->prepare("SELECT * FROM usuarios");

    }

    $stmt->execute();

    $resultado = $stmt->get_result();

    while($row = $resultado->fetch_assoc()){

        $cpfValido = false;

        if(!filter_var($login, FILTER_VALIDATE_EMAIL)){

            $cpfLimpo = preg_replace('/[^0-9]/', '', $login);

            if(password_verify($cpfLimpo, $row['cpf'])){
                $cpfValido = true;
            }
        }

        if(
            (
                $row['email'] == $login ||
                $row['celular'] == $login ||
                $cpfValido
            )
            &&
            password_verify($senha, $row['senha'])
        ){

            $usuario = $row;
            break;

        }

    }

    if($usuario){

        $_SESSION['usuario_id'] = $usuario['id'];

        header("Location: index.php");
        exit();

    } else {

        $mensagem = "Login inválido!";

    }

}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>

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

    border:1px solid rgba(255,255,255,0.2);

    padding:30px;

    border-radius:20px;

    backdrop-filter:blur(10px);

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

    cursor:pointer;

}

p{
    color:white;
    text-align:center;
    margin-top:15px;
}

a{
    color:#22d3ee;
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

<h2>Login</h2>

<?php
if($mensagem){
    echo "<div class='mensagem'>$mensagem</div>";
}
?>

<form method="POST">

<input type="text" name="login" placeholder="Email, CPF ou Telefone" required>

<input type="password" name="senha" placeholder="Senha" required>

<button type="submit">
Entrar
</button>

</form>

<p>
<a href="cadastro.php">Cadastrar</a>
</p>

<p>
<a href="recuperarsenha.php">Recuperar senha</a>
</p>

</div>

</body>
</html>