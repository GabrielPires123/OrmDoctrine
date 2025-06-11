<?php

use Doctrine\DBAL\Logging\EchoSQLLogger;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use ORM\Doctrine\Entity\Pedido;
use ORM\Doctrine\Entity\Pessoa;
use Helper\EntityManagerCreator;

require_once __DIR__ . '/../vendor/autoload.php';

function Main(): void
{

    do {
        //cls console

        echo "------
        \n1 - Inserir novo pedido
        2 - Listar todos os pedidos
        3 - Deletar pessoa e seus pedidos
        4 - Atualizar dados de uma pessoa
        0 - Sair\n
        -----\n";
        $menu = readline("Escolha uma opção: ");

        switch ($menu)
        {

            case 1:
            {
                try
                {
                    echo "Insert\n";
                    $nomePessoa = readline("Nome: ");
                    $cpfPessoa = readline("CPF: ");
                    $numPedido = readline("Num. Pedido: ");

                    insertPedidoCompleto($nomePessoa,$cpfPessoa, $numPedido);
                    echo "Pedido inserido com sucesso";
                }
                catch (ORMException $e)
                {
                    echo "Erro: ".$e->getMessage();
                }
                break;
            }
        case 2:
        {
            try
            {

                listAll();
            }
            catch (ORMException $e)
            {
                echo "ERRO: ".$e->getMessage();
            }
            break;
        }
            case 3:
        {
            echo "Deletar pessoa";
            try
            {
                $idpessoa = (int) readline("ID da pessoa: ");
                DeletePedido($idpessoa);
            }
            catch (ORMException $e)
            {
                echo "ERRO: ".$e->getMessage();
            }
            break;
        }

        case 4:
        {
            echo "Atualizar pessoa";
            try
            {
                $idPessoa = (int) readline("");
                upgradePessoa($idPessoa);
            }
            catch (ORMException $e)
            {
                echo "ERRO: ".$e->getMessage();
            }
            break;
        }
        default:
            echo "Opção inválida!";
    }
    }while($menu!=0);

};
function insertPedidoCompleto(string $nomePessoa, string $cpf, int $numPedido): void
{

    $entityManager = EntityManagerCreator::createEntityManager();
    //cls console
    echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; // ^[H^[J
    try {
        $pessoa = new Pessoa($nomePessoa, $cpf);
        $pedido = new Pedido($numPedido);
        $pedido->setPessoa($pessoa);
        $pessoa->addPedidos($pedido);
        $entityManager->persist($pedido);
        $entityManager->persist($pessoa);
        $entityManager->flush();
    }catch (ORMException $e){
        echo "ERRO: ".$e->getMessage();
    }


}
function listAll(): void
{

    $entityManager = EntityManagerCreator::createEntityManager();

    //cls console
    echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; // ^[H^[J

    $conn = $entityManager->getRepository(Pedido::class);
    $Pedidos = $conn->findAll();

    if($Pedidos != null){
        echo "Lista de pedidos\n";
        foreach($Pedidos as $pedido){

            $pessoa = $pedido->getPessoa();
            echo "
            \n\nId Pedido:{$pedido->getId()}
            Num. Pedido:{$pedido->getNum()}
            Id Pessoa:{$pessoa->getId()}
            Nome Pessoa:{$pessoa->getNome()}
            CPF Pessoa:{$pessoa->getCpf()}\n\n";
        }
    }
    else{
        echo "ERRO: Nenhum pedido encontrado\n";
    }
}

//try-catch
/**
 * @throws OptimisticLockException
 * @throws ORMException
 */

function DeletePedido($idPessoa): void
 {
     $entityManager = EntityManagerCreator::createEntityManager();
     $conn = $entityManager->find(Pessoa::class,$idPessoa);

     //cls console
     echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; // ^[H^[J
     try {
         $entityManager->remove($conn);
         $entityManager->flush();
     }catch (ORMException $e){
         echo "ERRO: ".$e->getMessage();
     }

 }

 function upgradePessoa($idPessoa):void{
    //cls console
     echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; // ^[H^[J
     $entityManager = EntityManagerCreator::createEntityManager();

     $conn = $entityManager->find(Pessoa::class,$idPessoa);
     if($conn != null){
         $pessoaNome = readline("Nome: ");
         $pessoaCpf = readline("CPF: ");
         $conn->setNome($pessoaNome);
         $conn->setCpf($pessoaCpf);

         $entityManager->flush();
     }
 }

 Main();