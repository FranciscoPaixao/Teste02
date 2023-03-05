<?php
function AbrirChamado()
{
    global $db;

    echo "-- Abertura de Chamado -- \n";
    echo "  Informe o titulo do chamado:\n";
    $titulo = readline();
    echo "  Informe a descrição do chamado:\n";
    $descricao = readline();
    echo "  Informe a data de abertura do chamado (dd/mm/aaaa):\n";
    $data_abertura = readline();
    ListarEquipamentos();
    echo "  Informe o ID do equipamento a ser anexado ao chamado (lista de equipamentos acima):\n";
    $idEquipamento = readline();

    $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $idEquipamento")->fetch(PDO::FETCH_ASSOC);
    if ($equipamento) {
        $data_abertura = date('U', strtotime($data_abertura));
        $salvarDb = $db->prepare("INSERT INTO Chamados (titulo, descricao, idEquipamentos, data_abertura) VALUES (:titulo, :descricao, :idEquipamentos, :data_abertura)");
        $salvarDb->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':idEquipamentos' => $idEquipamento,
            ':data_abertura' => $data_abertura
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
        echo "-- Lista de Chamados --\n";
        foreach ($chamados as $chamado) {
            echo "  ID do Chamado: " . $chamado['idChamados'] . PHP_EOL;
            echo "  Titulo: " . $chamado['titulo'] . PHP_EOL;
            echo "  Descrição: " . $chamado['descricao'] . PHP_EOL;
            $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $chamado[idEquipamentos]")->fetch(PDO::FETCH_ASSOC);
            if ($equipamento) {
                echo "  Equipamento: " . $equipamento['nome'] . PHP_EOL;
                echo "  ID do Equipamento: " . $equipamento['idEquipamentos'] . PHP_EOL;
            } else {
                echo "\e[91m    Equipamento: Equipamento não encontrado!\033[0m\n";
                echo "\e[91m    ID do Equipamento: Equipamento não encontrado!\033[0m\n";
            }
            $data = date('d/m/Y', $chamado['data_abertura']);
            echo "  Data de Abertura: " . $data . PHP_EOL;
            $diferenca = time() - $chamado['data_abertura'];
            $dias = floor($diferenca / 86400);
            echo "  Dias aberto: " . $dias." dias\n";
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
        echo "-- Edição de Chamado -- \n";
        echo "DICA: Deixe o campo em branco caso não deseje alterar o valor atual.\n";
        echo "Informe o ID do chamado que deseja editar:\n";
        do{
            $id = readline();
            if(!is_numeric($id)){
                echo "\e[91m O ID precisa ser númerico!\033[0m\n";
            }
        }while(!is_numeric($id));

        $chamado = $db->query("SELECT * FROM Chamados WHERE idChamados = $id")->fetch(PDO::FETCH_ASSOC);
        if ($chamado) {

            echo "Informe o titulo do chamado:\n";
            $titulo = readline();
            if(empty($titulo)){
                $titulo = $chamado['titulo'];
            }

            echo "Informe a descrição do chamado:\n";
            $descricao = readline();
            if(empty($descricao)){
                $descricao = $chamado['descricao'];
            }

            echo "Informe a data de abertura do chamado (dd/mm/aaaa):\n";
            $data_abertura = readline();
            if(empty($data_abertura)){
                $data_abertura = $chamado['data_abertura'];
            }

            echo "Selecione um dos equipamentos abaixo:\n";
            ListarEquipamentos();
            echo "Informe o ID do equipamento:\n";
            $idEquipamento = readline();
            if(empty($idEquipamento)){
                $idEquipamento = $chamado['idEquipamentos'];
            }

            $equipamento = $db->query("SELECT * FROM Equipamentos WHERE idEquipamentos = $idEquipamento")->fetch(PDO::FETCH_ASSOC);
            if ($equipamento) {
                $data_abertura = date('U', strtotime($data_abertura));
                $salvarDb = $db->prepare("UPDATE Chamados SET titulo = :titulo, descricao = :descricao, idEquipamentos = :idEquipamentos, data_abertura = :data_abertura WHERE idChamados = :id");
                $salvarDb->execute([
                    ':id' => $id,
                    ':titulo' => $titulo,
                    ':descricao' => $descricao,
                    ':idEquipamentos' => $idEquipamento,
                    ':data_abertura' => $data_abertura
                ]);
                echo "----------------------------------------\n";
                echo "\e[92m Chamado editado com sucesso!\033[0m\n";
                echo "----------------------------------------\n";
            } else {
                echo "----------------------------------------\n";
                echo "\e[91m Equipamento não encontrado!\033[0m\n";
                echo "----------------------------------------\n";
            }
        }else{
            echo "----------------------------------------\n";
            echo "\e[91m Chamado não encontrado!\033[0m\n";
            echo "----------------------------------------\n";
        }
    }
}


function ExcluirChamado()
{
    global $db;
    $status = ListarChamados();
    if ($status) {
        echo "-- Exclusão de Chamado -- \n";
        echo "Informe o ID do chamado que deseja excluir:\n";
        do{
            $id = readline();
            if(!is_numeric($id)){
                echo "\e[91m O ID precisa ser númerico!\033[0m\n";
            }
        }while(!is_numeric($id));

        $chamado = $db->query("SELECT * FROM Chamados WHERE idChamados = $id")->fetch(PDO::FETCH_ASSOC);
        if ($chamado) {
            $salvarDb = $db->prepare("DELETE FROM Chamados WHERE idChamados = :id");
            $salvarDb->execute([
                ':id' => $id
            ]);
            echo "----------------------------------------\n";
            echo "\e[92m Chamado excluido com sucesso!\033[0m\n";
            echo "----------------------------------------\n";
        } else {
            echo "----------------------------------------\n";
            echo "\e[91m Chamado não encontrado\033[0m\n";
            echo "----------------------------------------\n";
        }
    }
}
