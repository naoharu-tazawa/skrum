<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;


/**
 * メール送信コマンド
 *
 * @author naoharu.tazawa
 */
class EmailSendingCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BB0108')
            ->setDescription('メール送信バッチ')
            ->setDefinition(array(
                new InputOption('bulk_size', null, InputOption::VALUE_REQUIRED, 'バルクサイズ')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BB0108 メール送信バッチ 開始----------');

        $mailSendingService = $this->getEmailSendingService();
        $exitCode = $mailSendingService->run($input->getOption('bulk_size'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BB0108 メール送信バッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_RETRY) {
            $this->logDebug('----------BB0108 メール送信バッチ リトライ終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BB0108 メール送信バッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
