<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * 目標期限日リマインダーメールコマンド
 *
 * @author naoharu.tazawa
 */
class OkrDeadlineReminderEmailCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BB0101')
            ->setDescription('目標期限日リマインダーメールバッチ')
            ->setDefinition(array(
                new InputOption('bulk_size', null, InputOption::VALUE_REQUIRED, 'バルクサイズ')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BB0101 目標期限日リマインダーメールバッチ 開始----------');

        $okrDeadlineReminderEmailService = $this->getOkrDeadlineReminderEmailService();
        $exitCode = $okrDeadlineReminderEmailService->run($input->getOption('bulk_size'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BB0101 目標期限日リマインダーメールバッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BB0101 目標期限日リマインダーメールバッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
