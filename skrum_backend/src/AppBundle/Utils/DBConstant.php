<?php

namespace AppBundle\Utils;

/**
 * データベース定数定義クラス
 *
 * @author naoharu.tazawa
 */
class DBConstant
{
    /**
     * フラグ（TRUE）
     */
    const FLG_TRUE = 1;

    /**
     * フラグ（FALSE）
     */
    const FLG_FALSE = 0;

    /**
     * グループ種別（部門）
     */
    const GROUP_TYPE_DEPARTMENT = '1';

    /**
     * グループ種別（チーム）
     */
    const GROUP_TYPE_TEAM = '2';

    /**
     * グループ種別（会社）
     */
    const GROUP_TYPE_COMPANY = '3';

    /**
     * OKR種別（目標）
     */
    const OKR_TYPE_OBJECTIVE = '1';

    /**
     * OKR種別（キーリザルト）
     */
    const OKR_TYPE_KEY_RESULT = '2';

    /**
     * OKR種別（ルートノード）
     */
    const OKR_TYPE_ROOT_NODE = '3';

    /**
     * OKRオーナー種別（ユーザ）
     */
    const OKR_OWNER_TYPE_USER = '1';

    /**
     * OKRオーナー種別（グループ）
     */
    const OKR_OWNER_TYPE_GROUP = '2';

    /**
     * OKRオーナー種別（会社）
     */
    const OKR_OWNER_TYPE_COMPANY = '3';

    /**
     * OKRオーナー種別（ルートノード）
     */
    const OKR_OWNER_TYPE_ROOT = '4';

    /**
     * OKRステータス（オープン）
     */
    const OKR_STATUS_OPEN = '1';

    /**
     * OKRステータス（クローズド）
     */
    const OKR_STATUS_CLOSED = '2';

    /**
     * OKR公開種別（全体公開）
     */
    const OKR_DISCLOSURE_TYPE_OVERALL = '1';

    /**
     * OKR公開種別（グループ公開）
     */
    const OKR_DISCLOSURE_TYPE_GROUP = '2';

    /**
     * OKR公開種別（管理者公開）
     */
    const OKR_DISCLOSURE_TYPE_ADMIN = '3';

    /**
     * OKR公開種別（グループ管理者公開）
     */
    const OKR_DISCLOSURE_TYPE_GROUP_ADMIN = '4';

    /**
     * OKR公開種別（本人のみ公開）
     */
    const OKR_DISCLOSURE_TYPE_SELF = '5';

    /**
     * OKR操作種別（作成）
     */
    const OKR_OPERATION_TYPE_GENERATE = '1';

    /**
     * OKR操作種別（紐付け）
     */
    const OKR_OPERATION_TYPE_ALIGN = '2';

    /**
     * OKR操作種別（紐付け変更）
     */
    const OKR_OPERATION_TYPE_ALIGN_CHANGE = '3';

    /**
     * OKR操作種別（オーナー変更）
     */
    const OKR_OPERATION_TYPE_OWNER_CHANGE = '4';

    /**
     * OKR操作種別（進捗登録）
     */
    const OKR_OPERATION_TYPE_ACHIEVEMENT = '5';

    /**
     * OKR操作種別（クローン）
     */
    const OKR_OPERATION_TYPE_CLONE = '6';

    /**
     * OKR操作種別（複製）
     */
    const OKR_OPERATION_TYPE_COPY = '7';

    /**
     * OKR操作種別（クローズ）
     */
    const OKR_OPERATION_TYPE_CLOSE = '8';

    /**
     * OKR操作種別（オープン）
     */
    const OKR_OPERATION_TYPE_OPEN = '9';

    /**
     * 投稿者種別（ユーザ）
     */
    const POSTER_TYPE_USER = '1';

    /**
     * 投稿者種別（グループ）
     */
    const POSTER_TYPE_GROUP = '2';

    /**
     * 投稿者種別（会社）
     */
    const POSTER_TYPE_COMPANY = '3';

    /**
     * ロールID（一般ユーザ（スタンダードプラン））
     */
    const ROLE_ID_NORMAL_STANDARD = 'A001';

    /**
     * ロールID（管理者ユーザ（スタンダードプラン））
     */
    const ROLE_ID_ADMIN_STANDARD = 'A002';

    /**
     * ロールID（スーパー管理者ユーザ（スタンダードプラン））
     */
    const ROLE_ID_SUPERADMIN_STANDARD = 'A003';

    /**
     * ロールレベル（一般ユーザ）
     */
    const ROLE_LEVEL_NORMAL = 1;

    /**
     * ロールレベル（管理者ユーザ）
     */
    const ROLE_LEVEL_ADMIN = 4;

    /**
     * ロールレベル（スーパー管理者ユーザ）
     */
    const ROLE_LEVEL_SUPERADMIN = 7;

    /**
     * ロール表示名（一般ユーザ）
     */
    const ROLE_DISPLAY_NAME_NORMAL = '一般ユーザ';

    /**
     * ロール表示名（管理者ユーザ）
     */
    const ROLE_DISPLAY_NAME_ADMIN = '管理者ユーザ';

    /**
     * ロール表示名（スーパー管理者ユーザ）
     */
    const ROLE_DISPLAY_NAME_SUPERADMIN = 'スーパー管理者ユーザ';

    /**
     * プランID（お試しプラン）
     */
    const PLAN_ID_TRIAL_PLAN = 1;

    /**
     * プランID（スタンダードプラン）
     */
    const PLAN_ID_STANDARD_PLAN = 2;

    /**
     * 価格種別（会社単位）
     */
    const PRICE_TYPE_COMPANY = '1';

    /**
     * 価格種別（人数単位）
     */
    const PRICE_TYPE_EMPLOYEES = '2';

    /**
     * タイムフレームサイクル種別（１ヶ月毎）
     */
    const TIMEFRAME_CYCLE_TYPE_MONTH = '1';

    /**
     * タイムフレームサイクル種別（４半期毎）
     */
    const TIMEFRAME_CYCLE_TYPE_QUARTER = '2';

    /**
     * タイムフレームサイクル種別（半年毎）
     */
    const TIMEFRAME_CYCLE_TYPE_HALF = '3';

    /**
     * タイムフレームサイクル種別（１年毎）
     */
    const TIMEFRAME_CYCLE_TYPE_YEAR = '4';

    /**
     * メール通知設定オフ
     */
    const MAIL_OFF = '0';

    /**
     * メール通知設定オン（達成率通知メール）
     */
    const MAIL_OKR_ACHIEVEMENT = '1';

    /**
     * メール通知設定オン（投稿通知メール）
     */
    const MAIL_OKR_TIMELINE = '1';

    /**
     * メール通知設定オン（進捗登録リマインドメール）
     */
    const MAIL_OKR_REMINDER = '1';

    /**
     * メール通知設定オン（メンバー進捗状況レポートメール）
     */
    const MAIL_REPORT_MEMBER_ACHIEVEMENT = '1';

    /**
     * メール通知設定オン（グループ進捗状況レポートメール）
     */
    const MAIL_REPORT_GROUP_ACHIEVEMENT = '1';

    /**
     * メール通知設定オン（フィードバック対象者通知メール）
     */
    const MAIL_REPORT_FEEDBACK_TARGET = '1';

    /**
     * メール通知設定オン（サービスお知らせメール）
     */
    const MAIL_SERVICE_NOTIFICATION = '1';
}
