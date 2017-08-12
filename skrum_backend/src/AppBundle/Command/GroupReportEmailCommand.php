<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * グループ進捗状況レポートメールコマンド
 *
 * @author naoharu.tazawa
 */
class GroupReportEmailCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BB0105')
            ->setDescription('グループ進捗状況レポートメールバッチ')
            ->setDefinition(array(
                new InputOption('bulk_size', null, InputOption::VALUE_REQUIRED, 'バルクサイズ')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BB0105 グループ進捗状況レポートメールバッチ 開始----------');

        $groupReportEmailService = $this->getGroupReportEmailService();
        $exitCode = $groupReportEmailService->run($input->getOption('bulk_size'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BB0105 グループ進捗状況レポートメールバッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BB0105 グループ進捗状況レポートメールバッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
