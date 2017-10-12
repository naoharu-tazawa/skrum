<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * サービスお知らせメールコマンド
 *
 * @author naoharu.tazawa
 */
class ServiceNotificationEmailCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BB0107')
            ->setDescription('サービスお知らせメールバッチ')
            ->setDefinition(array(
                new InputOption('bulk_size', null, InputOption::VALUE_REQUIRED, 'バルクサイズ')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BB0107 サービスお知らせメールバッチ 開始----------');

        $serviceNotificationEmailService = $this->getServiceNotificationEmailService();
        $exitCode = $serviceNotificationEmailService->run($input->getOption('bulk_size'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BB0107 サービスお知らせメールバッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BB0107 サービスお知らせメールバッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
