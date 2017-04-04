<?php

namespace AppBundle\Utils;

/**
 * 定数定義クラス
 *
 * @author naoharu.tazawa
 */
class Constant
{
    /**
     * ログ出力ファイルパス（アプリケーション）
     */
    const LOG_FILE_PATH_APPLICATION = '../app/logs/application.log';

    /**
     * ログ出力ファイルパス（アラート）
     */
    const LOG_FILE_PATH_ALERT = '../app/logs/alert.log';

    /**
     * 標準タイムフレーム名
     */
    const NORMAL_TIMEFRAME_NAME_FORMAT = '%s/%s - %s/%s';
}
