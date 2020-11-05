<?php

namespace Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    /** @var bool */
    private $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
        if(!empty($this->lances) && $this->lanceDoMesmoUsuario($lance))
        {
            throw new \DomainException("Usuário nao pode dar dois lances seguidos.");
        }

        $totalLancesUsuario = $this->quantidadeLancesPorUsuario($lance->getUsuario());

        if($totalLancesUsuario >= 5)
        {
            throw new \DomainException("Usuário nao pode propor mais de cinco lances por leilão.");
        }

        $this->lances[] = $lance;
    }

    private function lanceDoMesmoUsuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[count($this->lances) - 1];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    private function quantidadeLancesPorUsuario(Usuario $usuario): int
    {
        $totalDeLances = array_reduce(
            $this->lances,
                function(int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                    if ($lanceAtual->getUsuario() == $usuario) {
                        return $totalAcumulado + 1;
                    }
                    return $totalAcumulado;
                },
                0
            );

        return $totalDeLances;
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    public function estaFinalizado(): bool
    {
        return $this->finalizado;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
}
