<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * 進捗登録リマインダーメールコマンド
 *
 * @author naoharu.tazawa
 */
class AchievementRegistrationReminderEmailCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BB0103')
            ->setDescription('進捗登録リマインダーメールバッチ')
            ->setDefinition(array(
                new InputOption('bulk_size', 1000, InputOption::VALUE_REQUIRED, 'バルクサイズ')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BB0103 進捗登録リマインダーメールバッチ 開始----------');

        $achievementRegistrationReminderEmailService = $this->getAchievementRegistrationReminderEmailService();
        $exitCode = $achievementRegistrationReminderEmailService->run($input->getOption('bulk_size'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BB0103 進捗登録リマインダーメールバッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BB0103 進捗登録リマインダーメールバッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
