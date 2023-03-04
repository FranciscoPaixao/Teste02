<?php
echo "Selecione uma opção:";
echo "1 - Gerenciar Equipamentos";
echo "2 - Gerenciar Chamados";
echo "3 - Fechar Programa";
$opcao = readline();
if ($opcao == 1) {
    GerenciarEquipamentos();
} elseif ($opcao == 2) {
    GerenciarChamados();
} elseif ($opcao == 3) {
    echo "Programa Finalizado";
} else {
    echo "Opção Inválida";
}
function GerenciarEquipamentos()
{
    echo "1 - Cadastrar Equipamento";
    echo "2 - Listar Equipamentos";
    echo "3 - Editar Equipamento";
    echo "4 - Excluir Equipamento";
    echo "4 - Voltar";
    $opcao = readline();
    if ($opcao == 1) {
        CadastrarEquipamento();
    } elseif ($opcao == 2) {
        ListarEquipamentos();
    } elseif ($opcao == 3) {
        EditarEquipamento();
    } elseif ($opcao == 4) {
        ExcluirEquipamento();
    } elseif ($opcao == 5) {
        echo "Voltando...";
    } else {
        echo "Opção Inválida";
    }
}
