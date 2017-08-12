<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\DBConstant;

/**
 * 目標進捗率ログ作成コマンド
 *
 * @author naoharu.tazawa
 */
class AchievementRateLogCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('BA0101')
            ->setDescription('目標進捗率ログ作成バッチ')
            ->setDefinition(array(
                new InputOption('bulk_size', null, InputOption::VALUE_REQUIRED, 'バルクサイズ')
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug('----------BA0101 目標進捗率ログ作成バッチ 開始----------');

        $achievementRateLogService = $this->getAchievementRateLogService();
        $exitCode = $achievementRateLogService->run($input->getOption('bulk_size'));

        if ($exitCode === DBConstant::EXIT_CODE_SUCCESS) {
            $this->logDebug('----------BA0101 目標進捗率ログ作成バッチ 正常終了----------');
        } elseif ($exitCode === DBConstant::EXIT_CODE_ERROR) {
            $this->logDebug('----------BA0101 目標進捗率ログ作成バッチ 異常終了----------');
        }

        $output->writeln($exitCode);
    }
}
