<?php
include 'equipamentos.php';
include 'chamados.php';

$db = new PDO('sqlite:' . dirname(__FILE__) . '/sistema.db');
MenuPrincipal();
//ListarEquipamentos($statusLista);
//echo "\e[91m Texto Vermelho \033[0m\n";\
//echo "\e[92m Texto Verde \033[0m\n";
function MenuPrincipal()
{
    echo "Selecione uma opção:\n";
    echo "  1 - Gerenciar Equipamentos\n";
    echo "  2 - Gerenciar Chamados\n";
    echo "  3 - Fechar Programa\n";
    do {
        $opcao = readline("Opção: \n");
        switch ($opcao) {
            case 1:
                MenuEquipamentos();
                break;
            case 2:
                MenuChamados();
                break;
            case 3:
                echo "\e[H\e[J";
                echo "Programa encerrado!\n";
                exit();
                break;
            default:
                echo "\e[91mInforme uma opção válida!\033[0m\n";
                break;
        }
    } while (($opcao > 0 && $opcao < 4) != true);
}
function MenuEquipamentos()
{
    echo "Selecione uma opção do Gerenciador de Equipamentos:\n";
    echo "  1 - Cadastrar Equipamento\n";
    echo "  2 - Listar Equipamentos\n";
    echo "  3 - Editar Equipamento\n";
    echo "  4 - Excluir Equipamento\n";
    echo "  5 - Voltar pro Menu Principal\n";

    do {
        $opcao = readline("Opção: \n");

        switch ($opcao) {
            case 1:
                echo "\e[H\e[J";
                AdicionarEquipamento();
                break;
            case 2:
                echo "\e[H\e[J";
                ListarEquipamentos();
                break;
            case 3:
                echo "\e[H\e[J";
                EditarEquipamento();
                break;
            case 4:
                echo "\e[H\e[J";
                ExcluirEquipamento();
                break;
            case 5:
                echo "\e[H\e[J";
                MenuPrincipal();
                break;
            default:
                echo "\e[91mInforme uma das opções válidas!\033[0m\n";
                break;
        }
    } while (($opcao > 0 && $opcao < 6) != true);
    MenuEquipamentos();
}


function MenuChamados()
{
    echo "Selecione uma opção do Gerenciador de Chamados:\n";
    echo "  1 - Abrir Chamado\n";
    echo "  2 - Listar Chamados\n";
    echo "  3 - Editar Chamado\n";
    echo "  4 - Excluir Chamado\n";
    echo "  5 - Voltar pro Menu Principal\n";

    do {
        $opcao = readline("Opção: \n");

        switch ($opcao) {
            case 1:
                echo "\e[H\e[J";
                AbrirChamado();
                break;
            case 2:
                echo "\e[H\e[J";
                ListarChamados();
                break;
            case 3:
                echo "\e[H\e[J";
                EditarChamado();
                break;
            case 4:
                echo "\e[H\e[J";
                ExcluirChamado();
                break;
            case 5:
                echo "\e[H\e[J";
                MenuPrincipal();
                break;
            default:
                echo "\e[91mInforme uma das opções válidas!\033[0m\n";
                break;
        }
    } while (($opcao > 0 && $opcao < 6) != true);
    MenuChamados();
}
