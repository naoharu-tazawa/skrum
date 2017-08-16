<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * ユーザ一括追加登録コマンド
 *
 * @author naoharu.tazawa
 */
class AdditionalUsersBulkRegistrationCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BC0101')
            ->setDescription('ユーザ一括追加登録バッチ')
            ->setDefinition(array(
                    new InputOption('mail_sending', null, InputOption::VALUE_OPTIONAL, 'メール送信設定(送信する→1、送信しない→0)')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BC0101 ユーザ一括追加登録バッチ 開始----------');

        $additionalUsersBulkRegistrationService = $this->getAdditionalUsersBulkRegistrationService();
        $exitCode = $additionalUsersBulkRegistrationService->run($input->getOption('mail_sending'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BC0101 ユーザ一括追加登録バッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_RETRY) {
            $this->logDebug('----------BC0101 ユーザ一括追加登録バッチ リトライ終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BC0101 ユーザ一括追加登録バッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
