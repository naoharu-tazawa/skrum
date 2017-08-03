<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * メンバー進捗状況レポートメールコマンド
 *
 * @author naoharu.tazawa
 */
class MemberReportEmailCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BB0104')
            ->setDescription('メンバー進捗状況レポートメールバッチ')
            ->setDefinition(array(
                new InputOption('bulk_size', 1000, InputOption::VALUE_REQUIRED, 'バルクサイズ')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BB0104 メンバー進捗状況レポートメールバッチ 開始----------');

        $memberReportEmailService = $this->getMemberReportEmailService();
        $exitCode = $memberReportEmailService->run($input->getOption('bulk_size'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BB0104 メンバー進捗状況レポートメールバッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BB0104 メンバー進捗状況レポートメールバッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
