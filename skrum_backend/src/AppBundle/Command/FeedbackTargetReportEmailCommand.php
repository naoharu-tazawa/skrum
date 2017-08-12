<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * フィードバック対象者報告メールコマンド
 *
 * @author naoharu.tazawa
 */
class FeedbackTargetReportEmailCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BB0106')
            ->setDescription('フィードバック対象者報告メールバッチ')
            ->setDefinition(array(
                new InputOption('bulk_size', null, InputOption::VALUE_REQUIRED, 'バルクサイズ')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BB0106 フィードバック対象者報告メールバッチ 開始----------');

        $feedbackTargetReportEmailService = $this->getFeedbackTargetReportEmailService();
        $exitCode = $feedbackTargetReportEmailService->run($input->getOption('bulk_size'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BB0106 フィードバック対象者報告メールバッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BB0106 フィードバック対象者報告メールバッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
