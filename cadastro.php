<?php
include "conexao.php";

$mensagem = "";

// ===== FUNÇÕES =====

function limpar($dado){
    return htmlspecialchars(trim($dado));
}

function validarCPF($cpf){

    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if(strlen($cpf) != 11) return false;

    if(preg_match('/(\d)\1{10}/', $cpf)) return false;

    for($t = 9; $t < 11; $t++){

        $soma = 0;

        for($i = 0; $i < $t; $i++){

            $soma += $cpf[$i] * (($t + 1) - $i);

        }

        $digito = ((10 * $soma) % 11) % 10;

        if($cpf[$t] != $digito){
            return false;
        }

    }

    return true;

}

function validarTelefone($tel){

    $tel = preg_replace('/[^0-9]/', '', $tel);

    return preg_match('/^[0-9]{10,11}$/', $tel);

}

// ===== PROCESSAMENTO =====

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $login = limpar($_POST['login']);

    $senha = $_POST['senha'];

    $email = null;
    $celular = null;
    $cpf = null;

    // IDENTIFICAÇÃO

    if(filter_var($login, FILTER_VALIDATE_EMAIL)){

        $email = $login;

    }

    elseif(validarCPF($login)){

        $cpf = preg_replace('/[^0-9]/', '', $login);

    }

    elseif(validarTelefone($login)){

        $celular = preg_replace('/[^0-9]/', '', $login);

    }

    else{

        $mensagem = "Entrada inválida!";

    }

    // SENHA FORTE

    if(empty($mensagem)){

        if(

            strlen($senha) < 8 ||

            !preg_match('/[A-Z]/', $senha) ||

            !preg_match('/[a-z]/', $senha) ||

            !preg_match('/[0-9]/', $senha) ||

            !preg_match('/[\W]/', $senha)

        ){

            $mensagem = "Senha fraca!";

        }

    }

    // VERIFICAR DUPLICADO

    if(empty($mensagem)){

        if($email){

            $stmt = $conn->prepare("
            SELECT id FROM usuarios WHERE email = ?
            ");

            $stmt->bind_param("s", $email);

        }

        elseif($celular){

            $stmt = $conn->prepare("
            SELECT id FROM usuarios WHERE celular = ?
            ");

            $stmt->bind_param("s", $celular);

        }

        else{

            $stmt = $conn->prepare("
            SELECT cpf FROM usuarios
            ");

        }

        $stmt->execute();

        $res = $stmt->get_result();

        while($row = $res->fetch_assoc()){

            if($cpf && password_verify($cpf, $row['cpf'])){

                $mensagem = "CPF já cadastrado!";

                break;

            }

            if(isset($row['id'])){

                $mensagem = "Usuário já cadastrado!";

                break;

            }

        }

    }

    // CADASTRAR

    if(empty($mensagem)){

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $cpfHash = $cpf ? password_hash($cpf, PASSWORD_DEFAULT) : null;

        $stmt = $conn->prepare("
        INSERT INTO usuarios
        (email, celular, cpf, senha)
        VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(

            "ssss",

            $email,
            $celular,
            $cpfHash,
            $senhaHash

        );

        if($stmt->execute()){

            header("Location: login.php");
            exit();

        }

        else{

            $mensagem = "Erro ao cadastrar.";

        }

    }

}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cadastro</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{

    height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    background:linear-gradient(135deg,#0f172a,#1e3a8a);

}

/* CARD */

.container{

    width:100%;
    max-width:420px;

    padding:35px;

    border-radius:25px;

    background:rgba(255,255,255,0.05);

    border:1px solid rgba(255,255,255,0.1);

    backdrop-filter:blur(12px);

    box-shadow:0 10px 30px rgba(0,0,0,0.3);

}

/* TÍTULO */

h2{

    color:white;

    text-align:center;

    margin-bottom:25px;

    font-size:32px;

}

/* INPUTS */

input{

    width:100%;

    padding:15px;

    margin-bottom:18px;

    border:none;

    border-radius:12px;

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.1);

    color:white;

    font-size:16px;

    outline:none;

    transition:0.3s;

}

input::placeholder{

    color:rgba(255,255,255,0.7);

}

input:focus{

    border:1px solid #22d3ee;

    box-shadow:0 0 10px rgba(34,211,238,0.5);

}

/* BOTÃO */

button{

    width:100%;

    padding:15px;

    border:none;

    border-radius:12px;

    background:linear-gradient(90deg,#2563eb,#06b6d4);

    color:white;

    font-size:18px;

    font-weight:bold;

    cursor:pointer;

    transition:0.3s;

}

button:hover{

    transform:scale(1.02);

    background:linear-gradient(90deg,#1d4ed8,#0891b2);

}

/* MENSAGEM */

p{

    color:white;

    text-align:center;

    margin-top:15px;

}

a{

    color:#22d3ee;

    text-decoration:none;

    font-weight:bold;

}

a:hover{

    text-decoration:underline;

}

/* ERROS */

.mensagem{

    color:#fca5a5;

    text-align:center;

    margin-bottom:15px;

    font-weight:bold;

}

</style>

</head>
<body>

<div class="container">

<h2>Cadastro</h2>

<?php if($mensagem) echo "<div class='mensagem'>$mensagem</div>"; ?>

<form method="POST">

<input
type="text"
name="login"
placeholder="Email, CPF ou Telefone"
required
>

<input
type="password"
name="senha"
placeholder="Senha"
required
>

<button type="submit">
Cadastrar
</button>

</form>

<p>

Já tem conta?

<a href="login.php">
Login
</a>

</p>

</div>

</body>
</html>