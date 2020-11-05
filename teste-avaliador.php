<?php

use Leilao\Model\Lance;
use Leilao\Model\Leilao;
use Leilao\Model\Usuario;

use Leilao\Service\Avaliador;

require 'vendor/autoload.php';

//Princípio "Arrange-Act-Assert"
//Princípio "Given-When-Then"

//settando os dados para o teste
//Arrange / Given
$leilao = new Leilao('Fiat 0km');

$maria = new Usuario('Maria');
$joao = new Usuario('João');

$leilao->recebeLance(new Lance($joao, 2000));
$leilao->recebeLance(new Lance($maria, 2500));

$leiloeiro = new Avaliador();

//Executando o código que deve ser testado
//Act / When
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

//Verificando se a saída é a esperada
//Assert - Then
$valorEsperado = 2500;

if($maiorValor == $valorEsperado)
{
    echo "Teste Ok";
}
else
{
    echo "Teste Falhou";
}