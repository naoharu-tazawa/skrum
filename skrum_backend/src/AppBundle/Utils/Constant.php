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
     * 主体種別（ユーザ）
     */
    const SUBJECT_TYPE_USER = '1';

    /**
     * 主体種別（グループ）
     */
    const SUBJECT_TYPE_GROUP = '2';

    /**
     * 主体種別（会社）
     */
    const SUBJECT_TYPE_COMPANY = '3';

    /**
     * 標準タイムフレーム名
     */
    const NORMAL_TIMEFRAME_NAME_FORMAT = '%s/%s - %s/%s';

    /**
     * 入れ子区間モデルのルートノードの左値
     */
    const ROOT_NODE_LEFT_VALUE = 0;

    /**
     * 入れ子区間モデルのルートノードの右値
     */
    const ROOT_NODE_RIGHT_VALUE = 1;
}
