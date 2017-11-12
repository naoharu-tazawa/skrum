<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * OKR一括登録コマンド
 *
 * @author naoharu.tazawa
 */
class OkrsBulkRegistrationCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BC0102')
            ->setDescription('OKR一括登録バッチ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BC0102 OKR一括登録バッチ 開始----------');

        $okrsBulkRegistrationService = $this->getOkrsBulkRegistrationService();
        $exitCode = $okrsBulkRegistrationService->run();

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BC0102 OKR一括登録バッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_RETRY) {
            $this->logDebug('----------BC0102 OKR一括登録バッチ リトライ終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BC0102 OKR一括登録バッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
