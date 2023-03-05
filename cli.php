<?php
$db = new PDO('sqlite:' . dirname(__FILE__) . '/sistema.db');
MenuPrincipal();
//echo "\e[91m Texto Vermelho \033[0m\n";\
//echo "\e[92m Texto Verde \033[0m\n";
function MenuPrincipal()
{
    echo "Selecione uma opção:\n";
    echo "  1 - Gerenciar Equipamentos\n";
    echo "  2 - Gerenciar Chamados\n";
    echo "  3 - Fechar Programa\n";
    echo "Opção: ";
    do {
        $opcao = readline();
        switch ($opcao) {
            case 1:
                MenuEquipamentos();
                break;
            case 2:
                MenuChamados();
                break;
            case 3:
                echo "Programa encerrado!\n";
                exit();
                break;
            default:
                echo "\e[91mInforme uma opção válida!\033[0m\n";
                echo "Opção: ";
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
    echo "Opção: ";

    do {
        $opcao = readline();

        switch ($opcao) {
            case 1:
                AdicionarEquipamento();
                break;
            case 2:
                echo "Lista de Equipamentos:\n";
                ListarEquipamentos();
                break;
            case 3:
                EditarEquipamento();
                break;
            case 4:
                ExcluirEquipamento();
                break;
            case 5:
                MenuPrincipal();
                break;
            default:
                echo "\e[91mInforme uma opção válida!\033[0m\n";
                echo "Opção: ";
                break;
        }
    } while (($opcao > 0 && $opcao < 6) != true);
    MenuEquipamentos();
}
function AdicionarEquipamento()
{
    global $db;
    echo "Cadastro de Equipamento:\n";
    do {
        echo "  Informe o nome do equipamento:\n";
        $nome = readline();
        if (strlen($nome) < 6) {
            echo "\e[91m    O nome do equipamento deve ter no mínimo 6 caracteres!\033[0m\n";
        }
    } while ((strlen($nome) > 5) != true);
    echo "  Informe o preço de aquisição do equipamento (somente números):\n";
    $precoAquisicao = readline();
    echo "  Informe o número de serie do equipamento:\n";
    $numeroSerie = readline();
    echo "  Informe a data de fabricação do equipamento (dd/mm/aaaa):\n";
    $dataFabricacao = readline();
    echo "  Informe o nome do fabricante do equipamento:\n";
    $fabricante = readline();

    $precoAquisicao = str_replace(",", ".", $precoAquisicao) * 100;
    $dataFabricacao = date('U', strtotime($dataFabricacao));

    $salvarDb = $db->prepare("INSERT INTO Equipamentos (nome, preco_aquisicao, numero_serie, data_fabricacao, fabricante) VALUES (:nome, :preco_aquisicao, :numero_serie, :data_fabricacao, :fabricante)");
    echo "Cadastrando equipamento...";
    $salvarDb->execute([
        ':nome' => $nome,
        ':preco_aquisicao' => $precoAquisicao,
        ':numero_serie' => $numeroSerie,
        ':data_fabricacao' => $dataFabricacao,
        ':fabricante' => $fabricante
    ]);
    echo "----------------------------------------\n";
    echo "\e[92mEquipamento cadastrado com sucesso!\033[0m\n";
    echo "----------------------------------------\n";
}
function ListarEquipamentos()
{
    global $db;
    $status = false;
    $equipamentos = $db->query("SELECT * FROM Equipamentos")->fetchAll(PDO::FETCH_ASSOC);
    if ($equipamentos) {
        $status = 1;
        foreach ($equipamentos as $equipamento) {
            echo "  ID: " . $equipamento['idEquipamentos'] . PHP_EOL;
            echo "  Nome: " . $equipamento['nome'] . PHP_EOL;
            $preco = str_replace('.', ',', $equipamento['preco_aquisicao'] / 100);
            echo "  Preço de Aquisição: " . $preco . PHP_EOL;
            echo "  Número de Série: " . $equipamento['numero_serie'] . PHP_EOL;
            $data = date('d/m/Y', $equipamento['data_fabricacao']);
            echo "  Data de Fabricação: " . $data . PHP_EOL;
            echo "----------------------------------------\n";
        }
    } else {
        echo "\e[91mNão há equipamentos cadastrados no banco de dados!\033[0m\n";
    }
    return $status;
}
function EditarEquipamento()
{
    global $db;
    $status = ListarEquipamentos();
    if ($status) {
        echo "Informe o ID do equipamento que deseja editar:";
        $id = readline();
        $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $id")->fetch(PDO::FETCH_ASSOC);
        if ($equipamento) {
            do {
                echo "Informe o nome do equipamento:\n";
                $nome = readline();
                if (strlen($nome) < 6) {
                    echo "\e[91mO nome do equipamento deve ter no mínimo 6 caracteres!\033[0m\n";
                }
            } while ((strlen($nome) > 5) != true);
            echo "Informe o preco de aquisicao do equipamento (somente numeros):\n";
            $precoAquisicao = readline();
            echo "Informe o numero de serie do equipamento:\n";
            $numeroSerie = readline();
            echo "Informe a data de fabricação do equipamento (dd/mm/aaaa):\n";
            $dataFabricacao = readline();
            echo "Informe o nome do fabricante do equipamento:\n";
            $fabricante = readline();

            $precoAquisicao = str_replace(",", ".", $precoAquisicao) * 100;
            $dataFabricacao = date('U', strtotime($dataFabricacao));

            $salvarDb = $db->prepare("UPDATE Equipamentos SET nome = :nome, preco_aquisicao = :preco_aquisicao, numero_serie = :numero_serie, data_fabricacao = :data_fabricacao, fabricante = :fabricante WHERE idEquipamentos = :id");
            $salvarDb->execute([
                ':id' => $id,
                ':nome' => $nome,
                ':preco_aquisicao' => $precoAquisicao,
                ':numero_serie' => $numeroSerie,
                ':data_fabricacao' => $dataFabricacao,
                ':fabricante' => $fabricante
            ]);
            echo "----------------------------------------\n";
            echo "\e[92mEquipamento editado com sucesso!\033[0m\n";
            echo "----------------------------------------\n";
        } else {
            echo "----------------------------------------\n";
            echo "\e[91mEquipamento não encontrado!\033[0m\n";
            echo "----------------------------------------\n";
        }
    }
}
function ExcluirEquipamento()
{
    global $db;
    $status = ListarEquipamentos();
    if ($status) {
        echo "Informe o ID do equipamento que deseja excluir:";
        $id = readline();
        $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $id")->fetch(PDO::FETCH_ASSOC);
        if ($equipamento) {
            $consultarDb = $db->prepare("DELETE FROM Equipamentos WHERE idEquipamentos = :id");
            $consultarDb->execute([
                ':id' => $id
            ]);
            $verificarChamdos = $db->query("SELECT * FROM Chamados WHERE idEquipamentos = $id")->fetchAll(PDO::FETCH_ASSOC);
            if ($verificarChamdos) {
                echo "Encontramos chamados relacionados a este equipamento, deseja excluir os chamados relacionados a este equipamento? (S/N)\n";
                $opcao = readline();
                if ($opcao == 'S' || $opcao == 's') {
                    $excluirChamados = $db->prepare("DELETE FROM Chamados WHERE idEquipamentos = :id");
                    $excluirChamados->execute([
                        ':id' => $id
                    ]);
                    echo "----------------------------------------\n";
                    echo "\e[92mChamados relacionados ao equipamento excluídos com sucesso!\033[0m\n";
                    echo "----------------------------------------\n";
                }
            }
            echo "----------------------------------------\n";
            echo "\e[92mEquipamento excluído com sucesso!\033[0m\n";
            echo "----------------------------------------\n";
        } else {
            echo "----------------------------------------\n";
            echo "\e[91mEquipamento não encontrado!\033[0m\n";
            echo "----------------------------------------\n";
        }
    }
}

function MenuChamados()
{
    echo "Selecione uma opção do Gerenciador de Chamados:\n";
    echo "  1 - Abrir Chamado\n";
    echo "  2 - Listar Chamados\n";
    echo "  3 - Editar Chamado\n";
    echo "  4 - Excluir Chamado\n";
    echo "  5 - Voltar pro Menu Principal\n";
    echo "Opção:\n";

    do {
        $opcao = readline();

        switch ($opcao) {
            case 1:
                AbrirChamado();
                break;
            case 2:
                echo "Lista de Chamados:\n";
                ListarChamados();
                break;
            case 3:
                EditarChamado();
                break;
            case 4:
                ExcluirChamado();
                break;
            case 5:
                MenuPrincipal();
                break;
            default:
                echo "Opção Inválida";
                echo "Informe uma opção válida!";
                break;
        }
    } while ($opcao > 0 && $opcao < 6);
    MenuChamados();
}

function AbrirChamado()
{
    global $db;

    echo "Abrindo Chamado...\n";
    echo "  Informe o titulo do chamado:\n";
    $titulo = readline();
    echo "  Informe a descrição do chamado:\n";
    $descricao = readline();
    echo "  Informe a data de abertura do chamado (dd/mm/aaaa):\n";
    $dataAbertura = readline();
    echo "  Selecione um dos equipamentos abaixo:\n";
    ListarEquipamentos();
    echo "  Informe o ID do equipamento selecionado:\n";
    $idEquipamento = readline();

    $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $idEquipamento")->fetch(PDO::FETCH_ASSOC);
    if ($equipamento) {
        $dataAbertura = date('U', strtotime($dataAbertura));
        $salvarDb = $db->prepare("INSERT INTO Chamados (titulo, descricao, idEquipamentos, data_abertura) VALUES (:titulo, :descricao, :idEquipamentos, :data_abertura)");
        $salvarDb->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':idEquipamentos' => $idEquipamento,
            ':data_abertura' => $dataAbertura
        ]);
        echo "\e[92mChamado aberto com sucesso!\033[0m\n";
        echo "----------------------------------------\n";
    } else {
        echo "\e[91mEquipamento não encontrado!\033[0m\n";
    }
}
function ListarChamados()
{
    global $db;
    $status = false;
    $chamados = $db->query("SELECT * FROM Chamados")->fetchAll(PDO::FETCH_ASSOC);
    if ($chamados) {
        $status = true;
        foreach ($chamados as $chamado) {
            echo "  ID: " . $chamado['idChamados'] . PHP_EOL;
            echo "  Titulo: " . $chamado['titulo'] . PHP_EOL;
            $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $chamado[idEquipamentos]")->fetch(PDO::FETCH_ASSOC);
            if ($equipamento) {
                echo "  Equipamento: " . $equipamento['nome'] . PHP_EOL;
                echo "  ID do Equipamento: " . $equipamento['idEquipamentos'] . PHP_EOL;
            } else {
                echo "\e[91m    Equipamento: Equipamento não encontrado!\033[0m\n";
                echo "\e[91m    ID do Equipamento: Equipamento não encontrado!\033[0m\n";
            }
            $data = date('d/m/Y', $chamado['dataAbertura']);
            echo "  Data de Abertura: " . $data . PHP_EOL;
            $dataAtual = date('U');
            $diferenca = $dataAtual - $chamado['dataAbertura'];
            $dias = floor($diferenca / (60 * 60 * 24));
            echo "  Dias aberto: " . $dias . PHP_EOL;
            echo "----------------------------------------\n";
        }
    } else {
        echo "\e[91m Nenhum chamado encontrado no banco de dados!\e[0m\n";
    }
    return $status;
}
function EditarChamado()
{
    global $db;
    $status = ListarChamados();
    if ($status) {
        echo "Informe o ID do chamado que deseja editar:\n";
        $id = readline();
        $chamado = $db->query("SELECT * FROM Chamados WHERE idChamados = $id")->fetch(PDO::FETCH_ASSOC);
        if ($chamado) {
            echo "Informe o titulo do chamado:\n";
            $titulo = readline();
            echo "Informe a descrição do chamado:\n";
            $descricao = readline();
            echo "Informe a data de abertura do chamado (dd/mm/aaaa):\n";
            $dataAbertura = readline();
            echo "Selecione um dos equipamentos abaixo:\n";
            ListarEquipamentos();
            echo "Informe o ID do equipamento selecionado:\n";
            $idEquipamento = readline();

            $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $idEquipamento")->fetch(PDO::FETCH_ASSOC);
            if ($equipamento) {
                $dataAbertura = date('U', strtotime($dataAbertura));
                $salvarDb = $db->prepare("UPDATE Chamados SET titulo = :titulo, descricao = :descricao, idEquipamento = :idEquipamento, dataAbertura = :dataAbertura WHERE idChamados = :id");
                $salvarDb->execute([
                    ':id' => $id,
                    ':titulo' => $titulo,
                    ':descricao' => $descricao,
                    ':idEquipamento' => $idEquipamento,
                    ':dataAbertura' => $dataAbertura
                ]);
                echo "Chamado editado com sucesso!\n";
                echo "----------------------------------------\n";
            } else {
                echo "Equipamento não encontrado";
            }
        }
    }
}


function ExcluirChamado()
{
    global $db;
    $status = ListarChamados();
    if ($status) {
        echo "Informe o ID do chamado que deseja excluir:\n";
        $id = readline();
        $chamado = $db->query("SELECT * FROM Chamados WHERE idChamados = $id")->fetch(PDO::FETCH_ASSOC);
        if ($chamado) {
            $salvarDb = $db->prepare("DELETE FROM Chamados WHERE idChamados = :id");
            $salvarDb->execute([
                ':id' => $id
            ]);
            echo "\e[92m Chamado excluido com sucesso!\033[0m\n";
            echo "----------------------------------------\n";
        } else {
            echo "\e[91m Chamado não encontrado\033[0m\n";
        }
    }
}
