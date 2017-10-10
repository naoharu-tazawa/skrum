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
     * S3バケット名（開発環境）
     */
    const S3_BUCKET_DEV = 'skrumdev';

    /**
     * S3バケット名（テスト環境）
     */
    const S3_BUCKET_TEST = 'skrumstg';

    /**
     * S3バケット名（本番環境）
     */
    const S3_BUCKET_PROD = 'skrum';

    /**
     * APIログ出力ファイルパス（アプリケーション）
     */
    const LOG_FILE_PATH_APPLICATION = __DIR__ . '/../../../app/logs/application.log';

    /**
     * APIログ出力ファイルパス（アラート）
     */
    const LOG_FILE_PATH_ALERT = __DIR__ . '/../../../app/logs/alert.log';

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
     * ロール（一般ユーザ）（CSV用）
     */
    const ROLE_NORMAL = '1';

    /**
     * ロール（管理者ユーザ）（CSV用）
     */
    const ROLE_ADMIN = '2';

    /**
     * ロール（スーパー管理者ユーザ）（CSV用）
     */
    const ROLE_SUPERADMIN = '3';

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

    /**
     * 1on1種別（日報）
     */
    const ONE_ON_ONE_TYPE_DAILY_REPORT = '日報';

    /**
     * 1on1種別ラベル（進捗報告）
     */
    const ONE_ON_ONE_TYPE_LABEL_PROGRESS_REPORT = '進捗報告';

    /**
     * 1on1種別（フィードバック）
     */
    const ONE_ON_ONE_TYPE_LABEL_FEEDBACK = 'フィードバック';

    /**
     * 1on1種別（ヒアリング）
     */
    const ONE_ON_ONE_TYPE_LABEL_HEARING = 'ヒアリング';

    /**
     * 1on1種別（面談メモ）
     */
    const ONE_ON_ONE_TYPE_LABEL_INTERVIEW_NOTE = '面談メモ';
}
