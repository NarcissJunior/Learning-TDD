<?php

namespace Leilao\Testes\Service;

use Leilao\Model\Lance;
use Leilao\Model\Leilao;
use Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Usuário nao pode dar dois lances seguidos.");

        $leilao = new Leilao("Variante");
        $ana = new Usuario("Ana");

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));
    }

    public function testLeilaoNaoDeveAceitarMaisDeCincoLancesPorUsuario(Type $var = null)
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Usuário nao pode propor mais de cinco lances por leilão.");

        $leilao = new Leilao("Brasilia amarela");
        $maria = new Usuario("Maria");
        $joao = new Usuario("joao");

        $leilao->recebeLance(new Lance($maria, 1000));
        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 2000));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 3000));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 4000));
        $leilao->recebeLance(new Lance($joao, 4000));
        $leilao->recebeLance(new Lance($maria, 5000));
        $leilao->recebeLance(new Lance($joao, 5000));

        $leilao->recebeLance(new Lance($maria, 6000));
    }

    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(
        int $qtdLances,
        Leilao $leilao,
        array $valores
    ) {
        static::assertCount($qtdLances, $leilao->getLances());

        foreach($valores as $i => $valorEsperado)
        {
            static::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());
        }
    }

    public function geraLances()
    {
        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilaoComDoisLances = new Leilao('Fiat 0km');

        $leilaoComDoisLances->recebeLance(new Lance($joao, 1000));
        $leilaoComDoisLances->recebeLance(new Lance($maria, 2000));

        $leilaoComUmLance = new Leilao('Fusca 0km');
        $leilaoComUmLance->recebeLance(new Lance($maria, 5000));

        return [
            '2-Lances' => [2, $leilaoComDoisLances, [1000, 2000]],
            '1-Lance' => [1, $leilaoComUmLance, [5000]]
        ];
    }
}