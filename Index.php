<?php
session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Página Inicial</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#0f172a,#1e3a8a);
}

.container{

    width:100%;
    max-width:500px;

    padding:35px;

    border-radius:25px;

    background:rgba(255,255,255,0.05);

    border:1px solid rgba(255,255,255,0.1);

    backdrop-filter:blur(12px);

    box-shadow:0 10px 30px rgba(0,0,0,0.3);

}

.icone{
    text-align:center;
    font-size:70px;
    margin-bottom:15px;
}

h1{
    color:white;
    text-align:center;
    margin-bottom:10px;
}

.subtitulo{
    color:rgba(255,255,255,0.8);
    text-align:center;
    margin-bottom:25px;
}

.menu{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.menu a{

    text-decoration:none;

    color:white;

    font-size:18px;

    font-weight:bold;

    padding:15px;

    border-radius:12px;

    background:linear-gradient(90deg,#2563eb,#06b6d4);

    transition:0.3s;

    text-align:center;

}

.menu a:hover{
    transform:scale(1.02);
}

.sair{
    background:linear-gradient(90deg,#dc2626,#ef4444) !important;
}

</style>

</head>
<body>

<div class="container">

    <div class="icone">👤</div>

    <h1>Bem-vindo!</h1>

    <p class="subtitulo">
        Escolha uma opção abaixo
    </p>

    <div class="menu">

        <a href="perfil.php">
            👤 Perfil
        </a>

        <a href="editarperfil.php">
            ✏️ Editar Perfil
        </a>

        <a href="visualizar.php">
            🔍 Visualizar Usuários
        </a>

        <a href="pesquisa.php">
            🔎 Filtro de Pesquisa
        </a>

        <a href="mudarconta.php">
            🔄 Mudar Conta
        </a>

        <a href="logout.php" class="sair">
            🚪 Sair
        </a>

    </div>

</div>

</body>
</html>
