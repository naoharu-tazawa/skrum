<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Entity\TPreUser;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TTimeframe;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\Constant;

/**
 * ユーザ設定サービスクラス
 *
 * @author naoharu.tazawa
 */
class UserSettingService extends BaseService
{
    /**
     * ユーザ仮登録
     *
     * @param $emailAddress Eメールアドレス
     * @param $subdomain サブドメイン
     * @param $companyId 会社ID（ユーザ招待の場合のみ）
     * @param $roleAssignmentId ロール割当ID（ユーザ招待の場合のみ）
     * @return boolean 登録結果
     */
    public function preregisterUser($emailAddress, $subdomain, $companyId = null, $roleAssignmentId = null)
    {
        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserRepos = $this->getMUserRepository();
        $result = $mUserRepos->findBy(array('emailAddress' => $emailAddress));
        if (count($result) > 0) {
            throw new DoubleOperationException('Eメールアドレスはすでに登録済みです');
        }

        // URLトークンを生成
        $urltoken = $this->getToken();

        // 仮登録ユーザテーブルに登録
        $tPreUser = new TPreUser();
        $tPreUser->setEmailAddress($emailAddress);
        $tPreUser->setSubdomain($subdomain);
        $tPreUser->setUrltoken($urltoken);
        // 会社IDが存在する場合（ユーザ招待の場合）のみ、会社IDとロール割当IDをセット
        if ($companyId) {
            $tPreUser->setCompanyId($companyId);

            // ロール割当ID存在チェック
            $mRoleAssignmentRepos = $this->getMRoleAssignmentRepository();
            $mRoleAssignmentArray = $mRoleAssignmentRepos->findBy(array(
                                        'roleAssignmentId' => $roleAssignmentId,
                                        'companyId' => $companyId
                                    ));
            if (count($mRoleAssignmentArray) === 0) {
                throw new ApplicationException('ロール割当IDが存在しません');
            }

            $tPreUser->setRoleAssignmentId($roleAssignmentId);
        } else {
            $tPreUser->setInitialUserFlg(DBConstant::FLG_TRUE);
        }

        try {
            $this->persist($tPreUser);
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }

        $url = "https://skrum.jp/signup" . "?tkn=" . $urltoken;

        $message = \Swift_Message::newInstance()
            ->setFrom('skrum@skrum.jp')
            ->setTo($emailAddress)
            ->setSubject('新規ユーザ登録メール送信APIテスト件名')
            ->setBody('メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。' . $url);

        $result = $this->getContainer()->get('mailer')->send($message);

//         $transport = $mailer->getTransport();
//         $spool = $transport->getSpool();
//         $spool->flushQueue($this->getContainer()->get('swiftmailer.transport.real'));

        // メールを送信
        if ($result) {
            return true;
        } else {
            $this->logError("メールの送信に失敗しました");

            return false;
        }
    }

    /**
     * トークン取得
     *
     * @return string トークン
     */
    private function getToken()
    {
        return hash('sha256',uniqid(rand(),1));
    }

    /**
     * 初期設定登録
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $userInfo ユーザ情報連想配列
     * @param array $companyInfo 会社情報連想配列
     * @param array $timeframeInfo タイムフレーム情報連想配列
     * @return void
     */
    public function establishCompany($auth, $userInfo, $companyInfo, $timeframeInfo)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // ユーザ情報を登録
            $mUserRepos = $this->getMUserRepository();
            $mUser = $mUserRepos->findOneBy(array('userId' => $auth->getUserId()));
            $mUser->setLastName($userInfo['lastName']);
            $mUser->setFirstName($userInfo['firstName']);
            $mUser->setPosition($userInfo['position']);
            $mUser->setPhoneNumber($userInfo['phoneNumber']);
            $this->persist($mUser);

            // 会社情報を登録
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->findOneBy(array('companyId' => $auth->getCompanyId()));
            $mCompany->setCompanyName($companyInfo['name']);
            $mCompany->setVision($companyInfo['vision']);
            $mCompany->setMission($companyInfo['mission']);
            $mCompany->setDefaultDisclosureType(DBConstant::OKR_DISCLOSURE_TYPE_OVERALL);
            $this->persist($mCompany);

            // グループマスタに登録されている会社レコードの会社名を変更
            $mGroupRepos = $this->getMGroupRepository();
            $mGroup = $mGroupRepos->findOneBy(array('company' => $auth->getCompanyId(), 'companyFlg' => DBConstant::FLG_TRUE));
            $mGroup->setGroupName($companyInfo['name']);

            // グループツリーテーブルに登録されている会社レコードのグループパス名を変更
            $tGroupTreeRepos = $this->getTGroupTreeRepository();
            $tGroupTreeArray = $tGroupTreeRepos->findBy(array('group' => $mGroup->getGroupId()), array('id' => 'ASC'), 1);
            $tGroupTreeArray[0]->setGroupTreePathName($companyInfo['name'] . '/');

            // タイムフレームを登録
            if ($timeframeInfo['customFlg']) {
                /* カスタム設定の場合 */

                // 開始年月日を生成
                $startYearMonthDate = $timeframeInfo['start']['year'] . '-' . $timeframeInfo['start']['month'] . '-' . $timeframeInfo['start']['date'];
                // 終了年月日を生成
                $endYearMonthDate = $timeframeInfo['end']['year'] . '-' . $timeframeInfo['end']['month'] . '-' . $timeframeInfo['end']['date'];

                $tTimeframe = new TTimeframe();
                $tTimeframe->setCompany($mCompany);
                $tTimeframe->setTimeframeName($timeframeInfo['timeframeName']);
                $tTimeframe->setStartDate(DateUtility::transIntoDatetime($startYearMonthDate));
                $tTimeframe->setEndDate(DateUtility::transIntoDatetime($endYearMonthDate));
                $tTimeframe->setDefaultFlg(DBConstant::FLG_TRUE);
                $this->persist($tTimeframe);
            } else {
                /* 標準タイムフレームを使用する場合 */

                // サイクル種別から作成レコード数と間隔月数を取得
                switch ($timeframeInfo['cycleType']) {
                    case DBConstant::TIMEFRAME_CYCLE_TYPE_MONTH:
                        $cycleType = array('i' => 12, 'months' => 1);
                        break;
                    case DBConstant::TIMEFRAME_CYCLE_TYPE_QUARTER:
                        $cycleType = array('i' => 4, 'months' => 3);
                        break;
                    case DBConstant::TIMEFRAME_CYCLE_TYPE_HALF:
                        $cycleType = array('i' => 2, 'months' => 6);
                        break;
                    default:
                        $cycleType = array('i' => 1, 'months' => 12);
                }

                // サイクル種別に応じた回数ループ
                for ($i = 0; $i < $cycleType['i']; $i++) {
                    // 開始日
                    if ($i == 0) {
                        $startYearMonthDate = $timeframeInfo['start']['year'] . '-' . $timeframeInfo['start']['month'] . '-' . $timeframeInfo['start']['date'];
                    } else {
                        $startYearMonthDate = DateUtility::get1stOfXMonthDateString($startDateArray[0], $startDateArray[1], $cycleType['months']);
                    }

                    // 開始日を分解
                    $startDateArray = DateUtility::analyzeDate($startYearMonthDate);

                    // 終了日
                    $endYearMonthDate = DateUtility::getEndOfXMonthDateString($startDateArray[0], $startDateArray[1], $cycleType['months'] - 1);

                    // 終了日を分解
                    $endDateArray = DateUtility::analyzeDate($endYearMonthDate);

                    // タイムフレーム名を作成
                    $timeframeName = sprintf(
                                        Constant::NORMAL_TIMEFRAME_NAME_FORMAT,
                                        $startDateArray[0],
                                        $startDateArray[1],
                                        $endDateArray[0],
                                        $endDateArray[1]
                                    );

                    $tTimeframe = new TTimeframe();
                    $tTimeframe->setCompany($mCompany);
                    $tTimeframe->setTimeframeName($timeframeName);
                    $tTimeframe->setStartDate(DateUtility::transIntoDatetime($startYearMonthDate));
                    $tTimeframe->setEndDate(DateUtility::transIntoDatetime($endYearMonthDate));
                    if ($i == 0) {
                        $tTimeframe->setDefaultFlg(DBConstant::FLG_TRUE);
                    }
                    $this->persist($tTimeframe);
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
            $this->rollback();
        }
    }

    /**
     * 追加ユーザ初期設定登録
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $userInfo ユーザ情報連想配列
     * @return void
     */
    public function establishUser($auth, $userInfo)
    {
        // ユーザ情報を登録
        $mUserRepos = $this->getMUserRepository();
        $mUser = $mUserRepos->findOneBy(array('userId' => $auth->getUserId()));
        $mUser->setLastName($userInfo['lastName']);
        $mUser->setFirstName($userInfo['firstName']);
        $mUser->setPosition($userInfo['position']);
        $mUser->setPhoneNumber($userInfo['phoneNumber']);

        try {
            $this->persist($mUser);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
