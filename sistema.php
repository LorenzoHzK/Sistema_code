<?php

$user_logado = false;
$usuario_atual = "";
$arr = [["admin", "admin"]];
$data_loggin = [];
$data_deslogue = [];
$arr_vendas = [];
$valor_total = 0;



function cad_user(){
    global $arr, $user_logado, $data_loggin, $usuario_atual;
    
    $login = readline ("Escreva o seu login: ");
    $senha = readline ("Escreva a sua senha: ");
    
    foreach ($arr as $dados) {
        if ($dados[0] == $login) {
            echo "Usuário já existe!\n";
            return;
        }
    }

    array_push($arr, [$login, $senha]);
    echo "Usuário cadastrado com sucesso!\n";
    
    $usuario_atual = $login;
    $user_logado = true;
    $data_loggin[$usuario_atual][] = date('d/m/Y H:i:s');
}


function deslogar(){
    global $user_logado, $usuario_atual, $data_deslogue;

    if ($user_logado) {
        $data_deslogue[$usuario_atual][] = date('d/m/Y H:i:s');
        echo "$usuario_atual deslogado com sucesso! \n";
    } else {
        echo "Nenhum usuário está logado.\n";
    }

    $user_logado = false;
    $usuario_atual = "";
}


function verifi_user(){
    global $arr, $user_logado, $usuario_atual, $data_loggin;

    $login = readline("Insira seu login novamente: ");
    $senha = readline("Insira sua senha novamente: ");

    foreach ($arr as $dados) { 
        if ($dados[0] == $login && $dados[1] == $senha) {
            echo "Login realizado com sucesso! \n";
            $usuario_atual = $login;
            $user_logado = true;
            $data_loggin[$usuario_atual][] = date('d/m/Y H:i:s');
            return;
        }
    }        
    echo "Login incorreto \n";
}


function venda(){
    global $arr_vendas, $usuario_atual, $valor_total;

    if (!$usuario_atual) {
        echo "Nenhum usuário logado.\n";
        return;
    }

    $nome_produto = readline("Nome do produto: ");
    $valor_produto = (float) readline("Valor da venda R$ ");
    $data = date('d/m/Y H:i:s');

    if (empty($nome_produto) || empty($valor_produto)) {
        echo "Nome do produto ou valor inválido.\n";
        return;
    }

    echo "O produto $nome_produto foi vendido pelo valor de R$ $valor_produto às $data\n\n";
    $arr_vendas[] = "$usuario_atual vendeu $nome_produto por R$ $valor_produto às $data";
    $valor_total += $valor_produto;
    voltar();
}


function logg(){
    global $arr_vendas, $arr, $data_loggin, $data_deslogue;

    echo "Usuários cadastrados:\n";
    print_r($arr);

    echo "|Histórico de logins|\n";
    foreach ($data_loggin as $usuario => $logins) {
        foreach ($logins as $b) {
            echo "O usuário $usuario fez login em $b \n\n";
        }
    }

    echo "|Histórico de logouts|\n";
    foreach ($data_deslogue as $usuario => $deslogues) {
        foreach ($deslogues as $a) {
            echo "O usuário $usuario deslogou em $a \n\n";
        }
    }

    echo "|Histórico de vendas|\n";
    foreach ($arr_vendas as $venda) {
        echo "$venda \n\n";
    }
}


//função para limpar o screen do PHP
function cls()                                                                                                             
    {
        print("\033[2J\033[;H");
    }

//função para voltar 
function voltar(){
    $volta = readline("1 -- Voltar ao menu: \n");
    cls();
    if ($volta == "1"){
        escolha();
    }
    else{
        echo "Opção inválida \n";
        return voltar();
    }
}

function escolha(){
    global $user_logado, $valor_total, $usuario_atual;

    if (!$user_logado) {
        echo "Nenhum usuário logado. Faça login primeiro.\n";
        return;
    }
    echo "*************************************************************************************** \n";
    echo "1 -- Vender \n";
    echo "2 -- Cadastrar novo usuário \n";
    echo "3 -- Verificar log \n";
    echo "4 -- Log-out (sair da conta) \n";
    echo "*************************************************************************************** \n";
    echo "Valor total das vendas: $valor_total \n";
    echo "Usuário logado é $usuario_atual \n\n";

    $num = readline("Escolha uma das alternativas: ");

    cls();
    if ($num == 1) {
        venda();
    } else if ($num == 2) {
        cad_user();
    } else if ($num == 3) {
        logg();
    } else if ($num == 4) {
        deslogar();
    }
}

while (true){
    if ($user_logado){
        escolha();
    } else {
        verifi_user();
    }
}