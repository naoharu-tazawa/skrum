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
     * グループ種別（部門）
     */
    const GROUP_TYPE_DEPARTMENT = '1';

    /**
     * グループ種別（チーム）
     */
    const GROUP_TYPE_TEAM = '2';

    /**
     * OKR種別（目標）
     */
    const OKR_TYPE_OBJECTIVE = '1';

    /**
     * OKR種別（キーリザルト）
     */
    const OKR_TYPE_KEY_RESULT = '2';

    /**
     * OKRステータス（オープン）
     */
    const OKR_STATUS_OPEN = '1';

    /**
     * OKRステータス（クローズド）
     */
    const OKR_STATUS_CLOSED = '2';

    /**
     * OKR公開設定（全体公開）
     */
    const OKR_DISCLOSURE_TYPE_OVERALL = '1';

    /**
     * OKR公開設定（グループ公開）
     */
    const OKR_DISCLOSURE_TYPE_GROUP = '2';

    /**
     * OKR公開設定（管理者公開）
     */
    const OKR_DISCLOSURE_TYPE_ADMIN = '3';

    /**
     * OKR公開設定（グループ管理者公開）
     */
    const OKR_DISCLOSURE_TYPE_GROUP_ADMIN = '4';

    /**
     * OKR公開設定（本人のみ公開）
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
}
