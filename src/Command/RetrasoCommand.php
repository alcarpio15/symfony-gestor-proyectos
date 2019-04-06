<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\SolEstadoUpdater;

class RetrasoCommand extends Command
{
    protected static $defaultName = 'gestor:retrasos';
    private $statusUpd;

    public function __construct(SolEstadoUpdater $updater)
    {
        $this->statusUpd = $updater;

        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('gestor:retrasos')
            ->setDescription('Comando para ser ejecutado ejecutado con un cronjob en determinados intervalos de tiempo.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->statusUpd->marcarTareasRetrasadas();
        // ...
    }
}