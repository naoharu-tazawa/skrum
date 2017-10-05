<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOneOnOne;
use AppBundle\Entity\TOneOnOneTo;
use AppBundle\Api\ResponseDTO\OneOnOneDTO;

/**
 * 1on1サービスクラス
 *
 * @author naoharu.tazawa
 */
class OneOnOneService extends BaseService
{
    /**
     * 日報/進捗報告送信処理
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return void
     */
    public function submitReport(Auth $auth, array $data)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // 1on1登録
            $tOneOnOne = new TOneOnOne();
            $tOneOnOne->setOneOnOneType($data['oneOnOneType']);
            $tOneOnOne->setSenderUserId($auth->getUserId());
            $tOneOnOne->setTargetDate(DateUtility::transIntoDatetime($data['reportDate']));
            if (!empty($data['okrId'])) $tOneOnOne->setOkrId($data['okrId']);
            $tOneOnOne->setBody($data['body']);
            $tOneOnOne->setNewArrivalDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tOneOnOne);

            // 1on1送信先登録
            if (!empty($data['to'])) {
                foreach ($data['to'] as $value) {
                    $tOneOnOneTo = new TOneOnOneTo();
                    $tOneOnOneTo->setOneOnOne($tOneOnOne);
                    $tOneOnOneTo->setUserId($value['userId']);
                    $this->persist($tOneOnOneTo);
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * フィードバック/ヒアリング送信処理
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return void
     */
    public function submitFeedback(Auth $auth, array $data)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // 1on1登録
            $tOneOnOne = new TOneOnOne();
            $tOneOnOne->setOneOnOneType($data['oneOnOneType']);
            $tOneOnOne->setSenderUserId($auth->getUserId());
            if (!empty($data['dueDate'])) $tOneOnOne->setDueDate(DateUtility::transIntoDatetime($data['dueDate']));
            if (!empty($data['feedbackType'])) $tOneOnOne->setFeedbackType($data['feedbackType']);
            if (!empty($data['okrId'])) $tOneOnOne->setOkrId($data['okrId']);
            if ($data['oneOnOneType'] === DBConstant::ONE_ON_ONE_TYPE_HEARING && empty($data['body'])) {
                // ヒアリングの場合で、かつ本文が空の場合は、自動メッセージを登録
                $mUserRepos = $this->getMUserRepository();
                $mUser = $mUserRepos->find($auth->getUserId());

                $autoMessage = sprintf(
                        $this->getParameter('one_on_one_hearing_auto_message'),
                        $mUser->getLastName() . ' ' . $mUser->getFirstName());
                $tOneOnOne->setBody($autoMessage);
            } else {
                $tOneOnOne->setBody($data['body']);
            }
            $tOneOnOne->setNewArrivalDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tOneOnOne);

            // 1on1送信先登録
            foreach ($data['to'] as $value) {
                $tOneOnOneTo = new TOneOnOneTo();
                $tOneOnOneTo->setOneOnOne($tOneOnOne);
                $tOneOnOneTo->setUserId($value['userId']);
                $this->persist($tOneOnOneTo);
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 面談メモ送信処理
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return void
     */
    public function submitInterviewnote(Auth $auth, array $data)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // 1on1登録
            $tOneOnOne = new TOneOnOne();
            $tOneOnOne->setOneOnOneType($data['oneOnOneType']);
            $tOneOnOne->setSenderUserId($auth->getUserId());
            $tOneOnOne->setIntervieweeUserId($data['intervieweeUserId']);
            $tOneOnOne->setTargetDate(DateUtility::transIntoDatetime($data['interviewDate']));
            $tOneOnOne->setBody($data['body']);
            $tOneOnOne->setNewArrivalDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tOneOnOne);

            // 1on1送信先登録
            if (!empty($data['to'])) {
                foreach ($data['to'] as $value) {
                    $tOneOnOneTo = new TOneOnOneTo();
                    $tOneOnOneTo->setOneOnOne($tOneOnOne);
                    $tOneOnOneTo->setUserId($value['userId']);
                    $this->persist($tOneOnOneTo);
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 1on1返信コメント処理
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param TOneOnOne $tOneOnOne 返信対象1on1エンティティ
     * @return void
     */
    public function submitOneOnOneReply(Auth $auth, array $data, TOneOnOne $tOneOnOne)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // 1on1新着日時更新
            $tOneOnOne->setNewArrivalDatetime(DateUtility::getCurrentDatetime());

            // 1on1リプライ登録
            $tOneOnOneReply = new TOneOnOne();
            $tOneOnOneReply->setOneOnOneType($tOneOnOne->getOneOnOneType());
            $tOneOnOneReply->setSenderUserId($auth->getUserId());
            $tOneOnOneReply->setBody($data['reply']);
            $tOneOnOneReply->setParent($tOneOnOne);
            $this->persist($tOneOnOneReply);

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 1on1新着履歴取得処理
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @param string $startDate 開始日（検索）
     * @param string $endDate 終了日（検索）
     * @param string $before 取得基準日時（検索）
     * @return array
     */
    public function getNewOneOnOnes(Auth $auth, string $keyword = null, string $startDate = null, string $endDate = null, string $before = null): array
    {
        // 検索ワードエスケープ処理
        $escapedKeyword = addslashes($keyword);

        if ($startDate === null) {
            $startDate = DateUtility::getMinDatetimeString();
        }
        if ($endDate === null) {
            $endDate = DateUtility::getMaxDatetimeString();
        } else {
            $yearMonthDay = DateUtility::analyzeDate(strstr($endDate, ' ', true));
            $endDate = DateUtility::getNextDayDatetimeString($yearMonthDay[0], $yearMonthDay[1], $yearMonthDay[2]);
        }
        if ($before === null) {
            $before = DateUtility::getMaxDatetimeString();
        }

        $tOneOnOneRepos = $this->getTOneOnOneRepository();
        $tOneOnOneArray = $tOneOnOneRepos->getNewArrivalOneOnOne($auth, $startDate, $endDate, $before, $escapedKeyword);

        // DTOに詰め替える
        $tOneOnOneToRepos = $this->getTOneOnOneToRepository();
        $oneOnOneDTOArray = array();
        foreach ($tOneOnOneArray as $tOneOnOne) {
            // 新着の本文を取得
            $oneOnOneStream = $tOneOnOneRepos->getOneOnOneStream($tOneOnOne['id']);
            if ($oneOnOneStream[1] !== null) {
                $newArrivalText = $oneOnOneStream[count($oneOnOneStream) - 1]->getBody();
            } else {
                $newArrivalText = $oneOnOneStream[count($oneOnOneStream) - 2]->getBody();
            }
            $partOfNewArrivalText = mb_substr($newArrivalText, 0, 15);

            // fromToNamesを作成
            $fromToNames = $tOneOnOne['last_name'] . $tOneOnOne['first_name'];
            $mUserArray = $tOneOnOneToRepos->getAllToUsers($tOneOnOne['id']);
            foreach ($mUserArray as $mUser) {
                $fromToNames .= ', ' . $mUser->getLastName() . $mUser->getFirstName();
            }

            $oneOnOneDTO = new OneOnOneDTO();
            $oneOnOneDTO->setOneOnOneId($tOneOnOne['id']);
            $oneOnOneDTO->setOneOnOneType($tOneOnOne['one_on_one_type']);
            $oneOnOneDTO->setSenderUserId($tOneOnOne['sender_user_id']);
            $oneOnOneDTO->setFromToNames($fromToNames);
            $oneOnOneDTO->setImageVersion($tOneOnOne['image_version']);
            $oneOnOneDTO->setLastUpdate(DateUtility::transIntoDatetime($tOneOnOne['new_arrival_datetime']));
            $oneOnOneDTO->setPartOfText($partOfNewArrivalText);

            $oneOnOneDTOArray[] = $oneOnOneDTO;
        }

        return $oneOnOneDTOArray;
    }
}
