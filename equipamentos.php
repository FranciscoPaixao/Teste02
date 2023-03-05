<?php
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
    echo "Cadastrando equipamento...\n";
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
        echo "Lista de Equipamentos:\n";
        foreach ($equipamentos as $equipamento) {
            echo "  ID: " . $equipamento['idEquipamentos'] . PHP_EOL;
            echo "  Nome: " . $equipamento['nome'] . PHP_EOL;
            echo "  Fabricante: " . $equipamento['fabricante'] . PHP_EOL;
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
        echo "-- Editando Equipamento -- \n";
        echo "\033[0;33mDICA: Deixe o campo em branco para manter o valor atual.\033[0m\n";
        echo "Informe o ID do equipamento que deseja editar:";
        do{
            $id = readline();
            if(!is_numeric($id)){
                echo "\e[91m O ID precisa ser númerico!\033[0m\n";
            }
        }while(!is_numeric($id));

        $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $id")->fetch(PDO::FETCH_ASSOC);
        if ($equipamento) {
            do {
                echo "Informe o nome do equipamento:\n";
                $nome = readline();
                if (strlen($nome) < 6 && !empty($nome)) {
                    echo "\e[91mO nome do equipamento deve ter no mínimo 6 caracteres!\033[0m\n";
                }
            } while ((strlen($nome) > 5) != true || empty($nome));
            
            echo "Informe o preco de aquisicao do equipamento (somente numeros):\n";
            $precoAquisicao = readline();
            if(empty($precoAquisicao)){
                $precoAquisicao = $equipamento['preco_aquisicao'];
            }

            echo "Informe o numero de serie do equipamento:\n";
            $numeroSerie = readline();
            if(empty($numeroSerie)){
                $numeroSerie = $equipamento['numero_serie'];
            }

            echo "Informe a data de fabricação do equipamento (dd/mm/aaaa):\n";
            $dataFabricacao = readline();
            if(empty($dataFabricacao)){
                $dataFabricacao = $equipamento['data_fabricacao'];
            }

            echo "Informe o nome do fabricante do equipamento:\n";
            $fabricante = readline();
            if(empty($fabricante)){
                $fabricante = $equipamento['fabricante'];
            }

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
            echo "\e[92m Equipamento editado com sucesso!\033[0m\n";
            echo "----------------------------------------\n";
        } else {
            echo "----------------------------------------\n";
            echo "\e[91m Equipamento não encontrado!\033[0m\n";
            echo "----------------------------------------\n";
        }
    }
}
function ExcluirEquipamento()
{
    global $db;
    $status = ListarEquipamentos();
    if ($status) {
        echo "-- Exclusão de Equipamento -- \n";
        echo "Informe o ID do equipamento que deseja excluir:";
        do{
            $id = readline();
            if(!is_numeric($id)){
                echo "\e[91m O ID precisa ser númerico!\033[0m\n";
            }
        }while(!is_numeric($id));
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
                    echo "\e[92m Chamados relacionados ao equipamento excluídos com sucesso!\033[0m\n";
                    echo "----------------------------------------\n";
                }
            }
            echo "----------------------------------------\n";
            echo "\e[92m Equipamento excluído com sucesso!\033[0m\n";
            echo "----------------------------------------\n";
        } else {
            echo "----------------------------------------\n";
            echo "\e[91m Equipamento não encontrado!\033[0m\n";
            echo "----------------------------------------\n";
        }
    }
}
