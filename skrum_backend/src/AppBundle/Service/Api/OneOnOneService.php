<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TEmailReservation;
use AppBundle\Entity\TOneOnOne;
use AppBundle\Entity\TOneOnOneTo;
use AppBundle\Api\ResponseDTO\NewOneOnOnesDTO;
use AppBundle\Api\ResponseDTO\OneOnOneDTO;
use AppBundle\Api\ResponseDTO\OneOnOneDialogDTO;
use AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\OneOnOneHeaderDTO;
use AppBundle\Api\ResponseDTO\NestedObject\OneOnOneDefaultDestinationDTO;

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
     * @return OneOnOneDTO
     */
    public function submitReport(Auth $auth, array $data): OneOnOneDTO
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
            $mUserRepos = $this->getMUserRepository();
            $toNames = null;
            if (!empty($data['to'])) {
                foreach ($data['to'] as $key => $value) {
                    $tOneOnOneTo = new TOneOnOneTo();
                    $tOneOnOneTo->setOneOnOne($tOneOnOne);
                    $tOneOnOneTo->setUserId($value['userId']);
                    $this->persist($tOneOnOneTo);

                    // メール送信予約
                    $this->reserveEmails($value['userId'], $auth->getUserId(), $data['oneOnOneType'], $data['body'], $auth->getSubdomain());

                    // $toNamesを生成
                    $mUser = $mUserRepos->find($value['userId']);
                    if ($key === 0) {
                        $toNames .= $mUser->getLastName() . $mUser->getFirstName();
                    } else {
                        $toNames .= ', ' . $mUser->getLastName() . $mUser->getFirstName();
                    }
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // 返却DTOを生成
        $oneOnOneDTO = new OneOnOneDTO();
        $oneOnOneDTO->setOneOnOneId($tOneOnOne->getId());
        $oneOnOneDTO->setOneOnOneType($tOneOnOne->getOneOnOneType());
        $oneOnOneDTO->setSenderUserId($tOneOnOne->getSenderUserId());
        $senderUserEntity = $mUserRepos->find($tOneOnOne->getSenderUserId());
        $oneOnOneDTO->setSenderUserName($senderUserEntity->getLastName() . $senderUserEntity->getFirstName());
        $oneOnOneDTO->setSenderUserImageVersion($senderUserEntity->getImageVersion());
        $oneOnOneDTO->setToNames($toNames);
        $oneOnOneDTO->setLastUpdate($tOneOnOne->getCreatedAt());
        $oneOnOneDTO->setPartOfText(mb_substr($tOneOnOne->getBody(), 0, 200));
        $oneOnOneDTO->setReadFlg(DBConstant::FLG_TRUE);

        return $oneOnOneDTO;
    }

    /**
     * フィードバック/ヒアリング送信処理
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return OneOnOneDTO
     */
    public function submitFeedback(Auth $auth, array $data): OneOnOneDTO
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // 1on1登録
            $mUserRepos = $this->getMUserRepository();
            $tOneOnOne = new TOneOnOne();
            $tOneOnOne->setOneOnOneType($data['oneOnOneType']);
            $tOneOnOne->setSenderUserId($auth->getUserId());
            if (!empty($data['dueDate'])) $tOneOnOne->setDueDate(DateUtility::transIntoDatetime($data['dueDate']));
            if (!empty($data['feedbackType'])) $tOneOnOne->setFeedbackType($data['feedbackType']);
            if (!empty($data['okrId'])) $tOneOnOne->setOkrId($data['okrId']);
            if ($data['oneOnOneType'] === DBConstant::ONE_ON_ONE_TYPE_HEARING && empty($data['body'])) {
                // ヒアリングの場合で、かつ本文が空の場合は、自動メッセージを登録
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
            $toNames = null;
            foreach ($data['to'] as $key => $value) {
                $tOneOnOneTo = new TOneOnOneTo();
                $tOneOnOneTo->setOneOnOne($tOneOnOne);
                $tOneOnOneTo->setUserId($value['userId']);
                $this->persist($tOneOnOneTo);

                // メール送信予約
                $this->reserveEmails($value['userId'], $auth->getUserId(), $data['oneOnOneType'], $data['body'], $auth->getSubdomain());

                // $toNamesを生成
                $mUser = $mUserRepos->find($value['userId']);
                if ($key === 0) {
                    $toNames .= $mUser->getLastName() . $mUser->getFirstName();
                } else {
                    $toNames .= ', ' . $mUser->getLastName() . $mUser->getFirstName();
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // 返却DTOを生成
        $oneOnOneDTO = new OneOnOneDTO();
        $oneOnOneDTO->setOneOnOneId($tOneOnOne->getId());
        $oneOnOneDTO->setOneOnOneType($tOneOnOne->getOneOnOneType());
        $oneOnOneDTO->setSenderUserId($tOneOnOne->getSenderUserId());
        $senderUserEntity = $mUserRepos->find($tOneOnOne->getSenderUserId());
        $oneOnOneDTO->setSenderUserName($senderUserEntity->getLastName() . $senderUserEntity->getFirstName());
        $oneOnOneDTO->setSenderUserImageVersion($senderUserEntity->getImageVersion());
        $oneOnOneDTO->setToNames($toNames);
        $oneOnOneDTO->setLastUpdate($tOneOnOne->getCreatedAt());
        $oneOnOneDTO->setPartOfText(mb_substr($tOneOnOne->getBody(), 0, 200));
        $oneOnOneDTO->setReadFlg(DBConstant::FLG_TRUE);

        return $oneOnOneDTO;
    }

    /**
     * 面談メモ送信処理
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return OneOnOneDTO
     */
    public function submitInterviewnote(Auth $auth, array $data): OneOnOneDTO
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
            $mUserRepos = $this->getMUserRepository();
            $toNames = null;
            if (!empty($data['to'])) {
                foreach ($data['to'] as $key => $value) {
                    $tOneOnOneTo = new TOneOnOneTo();
                    $tOneOnOneTo->setOneOnOne($tOneOnOne);
                    $tOneOnOneTo->setUserId($value['userId']);
                    $this->persist($tOneOnOneTo);

                    // メール送信予約
                    $this->reserveEmails($value['userId'], $auth->getUserId(), $data['oneOnOneType'], $data['body'], $auth->getSubdomain());

                    // $toNamesを生成
                    $mUser = $mUserRepos->find($value['userId']);
                    if ($key === 0) {
                        $toNames .= $mUser->getLastName() . $mUser->getFirstName();
                    } else {
                        $toNames .= ', ' . $mUser->getLastName() . $mUser->getFirstName();
                    }
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // 返却DTOを生成
        $oneOnOneDTO = new OneOnOneDTO();
        $oneOnOneDTO->setOneOnOneId($tOneOnOne->getId());
        $oneOnOneDTO->setOneOnOneType($tOneOnOne->getOneOnOneType());
        $oneOnOneDTO->setSenderUserId($tOneOnOne->getSenderUserId());
        $senderUserEntity = $mUserRepos->find($tOneOnOne->getSenderUserId());
        $oneOnOneDTO->setSenderUserName($senderUserEntity->getLastName() . $senderUserEntity->getFirstName());
        $oneOnOneDTO->setSenderUserImageVersion($senderUserEntity->getImageVersion());
        $oneOnOneDTO->setToNames($toNames);
        $oneOnOneDTO->setLastUpdate($tOneOnOne->getCreatedAt());
        $oneOnOneDTO->setPartOfText(mb_substr($tOneOnOne->getBody(), 0, 200));
        $oneOnOneDTO->setReadFlg(DBConstant::FLG_TRUE);

        return $oneOnOneDTO;
    }

    /**
     * 1on1返信コメント処理
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param TOneOnOne $tOneOnOne 返信対象1on1エンティティ
     * @return OneOnOneDTO
     */
    public function submitOneOnOneReply(Auth $auth, array $data, TOneOnOne $tOneOnOne): OneOnOneDTO
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

            // 送信先の既読フラグをFALSEにする
            $tOneOnOneToRepos = $this->getTOneOnOneToRepository();
            $oneOnOneInfoArray = $tOneOnOneToRepos->getAllToUsers($tOneOnOne->getId());
            foreach ($oneOnOneInfoArray as $key => $oneOnOneInfo) {
                if ($key % 2 === 0) {
                    if ($auth->getUserId() !== $oneOnOneInfo['tOneOnOneTo']->getUserId()) {
                        try {
                            $oneOnOneInfo['tOneOnOneTo']->setReadFlg(DBConstant::FLG_FALSE);
                            $this->flush();
                        } catch (\Exception $e) {
                            throw new SystemException($e->getMessage());
                        }
                    }
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // 返却DTO生成
        $oneOnOneDTO = new OneOnOneDTO();
        $oneOnOneDTO->setOneOnOneId($tOneOnOneReply->getId());
        $oneOnOneDTO->setOneOnOneType($tOneOnOneReply->getOneOnOneType());
        $oneOnOneDTO->setSenderUserId($tOneOnOneReply->getSenderUserId());
        $mUserRepos = $this->getMUserRepository();
        $mUser = $mUserRepos->find($tOneOnOneReply->getSenderUserId());
        $oneOnOneDTO->setSenderUserName($mUser->getLastName() . $mUser->getFirstName());
        $oneOnOneDTO->setSenderUserImageVersion($mUser->getImageVersion());
        $oneOnOneDTO->setLastUpdate($tOneOnOneReply->getUpdatedAt());
        $oneOnOneDTO->setText($tOneOnOneReply->getBody());

        return $oneOnOneDTO;
    }

    /**
     * 1on1投稿通知メール送信予約
     *
     * @param integer $userId 宛先ユーザID
     * @param integer $senderUserId 送信者ユーザID
     * @param string $oneOnOneType 1on1種別
     * @param string $content 投稿内容
     * @param string $subdomain サブドメイン
     * @return void
     */
    private function reserveEmails(int $userId, int $senderUserId, string $oneOnOneType, string $content, string $subdomain)
    {
        $mUserRepos = $this->getMUserRepository();
        $toUserEntity = $mUserRepos->find($userId);
        $senderEntity = $mUserRepos->find($senderUserId);

        // メール本文記載変数
        $data = array();
        $data['userName'] = $toUserEntity->getLastName() . $toUserEntity->getFirstName();
        $data['senderUserName'] = $senderEntity->getLastName() . $senderEntity->getFirstName();
        if ($oneOnOneType === DBConstant::ONE_ON_ONE_TYPE_DAILY_REPORT) {
            $data['oneOnOneTypeLabel'] = Constant::ONE_ON_ONE_TYPE_DAILY_REPORT;
        } elseif ($oneOnOneType === DBConstant::ONE_ON_ONE_TYPE_PROGRESS_MEMO) {
            $data['oneOnOneTypeLabel'] = Constant::ONE_ON_ONE_TYPE_LABEL_PROGRESS_REPORT;
        } elseif ($oneOnOneType === DBConstant::ONE_ON_ONE_TYPE_FEEDBACK) {
            $data['oneOnOneTypeLabel'] = Constant::ONE_ON_ONE_TYPE_LABEL_FEEDBACK;
        } elseif ($oneOnOneType === DBConstant::ONE_ON_ONE_TYPE_HEARING) {
            $data['oneOnOneTypeLabel'] = Constant::ONE_ON_ONE_TYPE_LABEL_HEARING;
        } elseif ($oneOnOneType === DBConstant::ONE_ON_ONE_TYPE_INTERVIEW_NOTE) {
            $data['oneOnOneTypeLabel'] = Constant::ONE_ON_ONE_TYPE_LABEL_INTERVIEW_NOTE;
        }
        $data['content'] = $content;

        // メール配信設定取得
        $tEmailSettingsRepos = $this->getTEmailSettingsRepository();
        $tEmailSettings = $tEmailSettingsRepos->findOneBy(array('userId' => $toUserEntity->getUserId()));

        // メール送信予約テーブルに登録
        if ($toUserEntity->getUserId() !== $senderEntity->getUserId() && $tEmailSettings->getOneOnOne() === DBConstant::EMAIL_ONE_ON_ONE) {
            $tEmailReservation = new TEmailReservation();
            $tEmailReservation->setToEmailAddress($toUserEntity->getEmailAddress());
            $tEmailReservation->setTitle($this->getParameter('one_on_one_notice'));
            $tEmailReservation->setBody($this->renderView('mail/one_on_one_notice.txt.twig', ['data' => $data, 'subdomain' => $subdomain]));
            $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
            $tEmailReservation->setSendingReservationDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tEmailReservation);
        }
    }

    /**
     * 1on1新着履歴取得処理
     *
     * @param Auth $auth 認証情報
     * @param string $keyword 検索ワード
     * @param string $startDate 開始日（検索）
     * @param string $endDate 終了日（検索）
     * @param string $before 取得基準日時
     * @return NewOneOnOnesDTO
     */
    public function getNewOneOnOnes(Auth $auth, string $keyword = null, string $startDate = null, string $endDate = null, string $before = null): NewOneOnOnesDTO
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

        // 検索ワードと取得基準日時がnullの場合、未読フラグ件数を取得
        $unreadFlgCounts = array();
        if ($keyword === null && $before === DateUtility::getMaxDatetimeString()) {
            $unreadFlgCounts['dailyReport'] = $tOneOnOneRepos->getUnreadFlgCount($auth->getUserId(), DBConstant::ONE_ON_ONE_TYPE_DAILY_REPORT);
            $unreadFlgCounts['progressMemo'] = $tOneOnOneRepos->getUnreadFlgCount($auth->getUserId(), DBConstant::ONE_ON_ONE_TYPE_PROGRESS_MEMO);
            $unreadFlgCounts['hearing'] = $tOneOnOneRepos->getUnreadFlgCount($auth->getUserId(), DBConstant::ONE_ON_ONE_TYPE_HEARING);
            $unreadFlgCounts['feedback'] = $tOneOnOneRepos->getUnreadFlgCount($auth->getUserId(), DBConstant::ONE_ON_ONE_TYPE_FEEDBACK);
            $unreadFlgCounts['interviewNote'] = $tOneOnOneRepos->getUnreadFlgCount($auth->getUserId(), DBConstant::ONE_ON_ONE_TYPE_INTERVIEW_NOTE);
        }

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
            $partOfNewArrivalText = mb_substr($newArrivalText, 0, 60);

            // toNamesを作成
            $readFlg = DBConstant::FLG_TRUE;
            $toNames = null;
            $oneOnOneInfoArray = $tOneOnOneToRepos->getAllToUsers($tOneOnOne['id']);
            foreach ($oneOnOneInfoArray as $key => $oneOnOneInfo) {
                if ($key % 2 === 0) {
                    if ($auth->getUserId() === $oneOnOneInfo['tOneOnOneTo']->getUserId()) {
                        $readFlg = $oneOnOneInfo['tOneOnOneTo']->getReadFlg();
                    }
                } else {
                    if ($key === 1) {
                        $toNames .= $oneOnOneInfo['mUser']->getLastName() . $oneOnOneInfo['mUser']->getFirstName();
                    } else {
                        $toNames .= ', ' . $oneOnOneInfo['mUser']->getLastName() . $oneOnOneInfo['mUser']->getFirstName();
                    }
                }
            }

            $oneOnOneDTO = new OneOnOneDTO();
            $oneOnOneDTO->setOneOnOneId($tOneOnOne['id']);
            $oneOnOneDTO->setOneOnOneType($tOneOnOne['one_on_one_type']);
            $oneOnOneDTO->setSenderUserId($tOneOnOne['sender_user_id']);
            $oneOnOneDTO->setSenderUserName($tOneOnOne['last_name'] . $tOneOnOne['first_name']);
            $oneOnOneDTO->setSenderUserImageVersion($tOneOnOne['image_version']);
            $oneOnOneDTO->setToNames($toNames);
            $oneOnOneDTO->setLastUpdate(DateUtility::transIntoDatetime($tOneOnOne['new_arrival_datetime']));
            $oneOnOneDTO->setPartOfText($partOfNewArrivalText);
            $oneOnOneDTO->setReadFlg($readFlg);

            $oneOnOneDTOArray[] = $oneOnOneDTO;
        }

        $newOneOnOnesDTO = new NewOneOnOnesDTO();
        if (!empty($unreadFlgCounts)) $newOneOnOnesDTO->setUnreadFlgCounts($unreadFlgCounts);
        $newOneOnOnesDTO->setData($oneOnOneDTOArray);

        return $newOneOnOnesDTO;
    }

    /**
     * 1on1送受信履歴取得処理
     *
     * @param Auth $auth 認証情報
     * @param string $oneOnOneType 1on1種別
     * @param string $before 取得基準日時
     * @return array
     */
    public function getOneOnOneHistory(Auth $auth, string $oneOnOneType, string $before = null): array
    {
        if ($before === null) {
            $before = DateUtility::getMaxDatetimeString();
        }

        $tOneOnOneRepos = $this->getTOneOnOneRepository();
        $tOneOnOneArray = $tOneOnOneRepos->getOneOnOneHistory($auth, $oneOnOneType, $before);

        // DTOに詰め替える
        $tOneOnOneToRepos = $this->getTOneOnOneToRepository();
        $mUserRepos = $this->getMUserRepository();
        $oneOnOneDTOArray = array();
        foreach ($tOneOnOneArray as $tOneOnOne) {
            // 送受信履歴の本文を取得
            $oneOnOneStream = $tOneOnOneRepos->getOneOnOneStream($tOneOnOne['id']);
            if ($oneOnOneStream[1] !== null) {
                $body = $oneOnOneStream[count($oneOnOneStream) - 1]->getBody();
            } else {
                $body = $oneOnOneStream[count($oneOnOneStream) - 2]->getBody();
            }
            $partOfText = mb_substr($body, 0, 200);

            // toNamesを作成
            $readFlg = DBConstant::FLG_TRUE;
            $toNames = null;
            $oneOnOneInfoArray = $tOneOnOneToRepos->getAllToUsers($tOneOnOne['id']);
            foreach ($oneOnOneInfoArray as $key => $oneOnOneInfo) {
                if ($key % 2 === 0) {
                    if ($auth->getUserId() === $oneOnOneInfo['tOneOnOneTo']->getUserId()) {
                        $readFlg = $oneOnOneInfo['tOneOnOneTo']->getReadFlg();
                    }
                } else {
                    if ($key === 1) {
                        $toNames .= $oneOnOneInfo['mUser']->getLastName() . $oneOnOneInfo['mUser']->getFirstName();
                    } else {
                        $toNames .= ', ' . $oneOnOneInfo['mUser']->getLastName() . $oneOnOneInfo['mUser']->getFirstName();
                    }
                }
            }

            $oneOnOneDTO = new OneOnOneDTO();
            $oneOnOneDTO->setOneOnOneId($tOneOnOne['id']);
            $oneOnOneDTO->setOneOnOneType($tOneOnOne['one_on_one_type']);
            $oneOnOneDTO->setSenderUserId($tOneOnOne['sender_user_id']);
            $oneOnOneDTO->setSenderUserName($tOneOnOne['last_name'] . $tOneOnOne['first_name']);
            $oneOnOneDTO->setSenderUserImageVersion($tOneOnOne['image_version']);
            $oneOnOneDTO->setToNames($toNames);
            if ($tOneOnOne['interviewee_user_id'] !== null) {
                $intervieweeEntity = $mUserRepos->find($tOneOnOne['interviewee_user_id']);
                $oneOnOneDTO->setIntervieweeUserName($intervieweeEntity->getLastName() . $intervieweeEntity->getFirstName());
            }
            $oneOnOneDTO->setLastUpdate(DateUtility::transIntoDatetime($tOneOnOne['created_at']));
            $oneOnOneDTO->setPartOfText($partOfText);
            $oneOnOneDTO->setReadFlg($readFlg);

            $oneOnOneDTOArray[] = $oneOnOneDTO;
        }

        return $oneOnOneDTOArray;
    }

    /**
     * 1on1ダイアログ取得処理
     *
     * @param Auth $auth 認証情報
     * @param integer $oneOnOneId 1on1ID
     * @return OneOnOneDialogDTO
     */
    public function getOneOnOneDialog(Auth $auth, int $oneOnOneId): OneOnOneDialogDTO
    {
        $tOneOnOneRepos = $this->getTOneOnOneRepository();
        $tOneOnOneArray = $tOneOnOneRepos->getOneOnOneStream($oneOnOneId);

        // DTOに詰め替える
        $oneOnOneDialogDTO = new OneOnOneDialogDTO();
        $tOneOnOneToRepos = $this->getTOneOnOneToRepository();
        $mUserRepos = $this->getMUserRepository();
        $oneOnOneDTOArray = array();
        foreach ($tOneOnOneArray as $key1 => $tOneOnOne) {
            // リプライコメントがない場合nullなので処理終了
            if ($tOneOnOne === null) break;

            if ($key1 === 0) {
                $oneOnOneHeaderDTO = new OneOnOneHeaderDTO();
                if ($tOneOnOne->getTargetDate() !== null) {
                    if ($tOneOnOne->getOneOnOneType() === DBConstant::ONE_ON_ONE_TYPE_INTERVIEW_NOTE) {
                        $oneOnOneHeaderDTO->setInterviewDate($tOneOnOne->getTargetDate());
                    } else {
                        $oneOnOneHeaderDTO->setTargetDate($tOneOnOne->getTargetDate());
                    }
                }
                if ($tOneOnOne->getDueDate() !== null) {
                    $oneOnOneHeaderDTO->setDueDate($tOneOnOne->getDueDate());
                }
                if ($tOneOnOne->getFeedbackType() !== null) {
                    $oneOnOneHeaderDTO->setFeedbackType($tOneOnOne->getFeedbackType());
                }
                if ($tOneOnOne->getIntervieweeUserId() !== null) {
                    $intervieweeEntity = $mUserRepos->find($tOneOnOne->getIntervieweeUserId());
                    $oneOnOneHeaderDTO->setIntervieweeUserName($intervieweeEntity->getLastName() . $intervieweeEntity->getFirstName());
                }
                if ($tOneOnOne->getOkrId() !== null) {
                    $tOkrRepos = $this->getTOkrRepository();
                    $tOkr = $tOkrRepos->find($tOneOnOne->getOkrId());
                    $oneOnOneHeaderDTO->setOkrId($tOkr->getOkrId());
                    $oneOnOneHeaderDTO->setOkrName($tOkr->getName());
                }

                $oneOnOneDialogDTO->setHeader($oneOnOneHeaderDTO);
            }

            // toNamesを作成
            $toNames = null;
            if ($key1 === 0) {
                $oneOnOneInfoArray = $tOneOnOneToRepos->getAllToUsers($tOneOnOne->getId());
                foreach ($oneOnOneInfoArray as $key2 => $oneOnOneInfo) {
                    if ($key2 % 2 === 0) {
                        if ($auth->getUserId() === $oneOnOneInfo['tOneOnOneTo']->getUserId()) {
                            try {
                                $oneOnOneInfo['tOneOnOneTo']->setReadFlg(DBConstant::FLG_TRUE);
                                $this->flush();
                            } catch (\Exception $e) {
                                throw new SystemException($e->getMessage());
                            }
                        }
                    } else {
                        if ($key2 === 1) {
                            $toNames .= $oneOnOneInfo['mUser']->getLastName() . $oneOnOneInfo['mUser']->getFirstName();
                        } else {
                            $toNames .= ', ' . $oneOnOneInfo['mUser']->getLastName() . $oneOnOneInfo['mUser']->getFirstName();
                        }
                    }
                }
            }

            $oneOnOneDTO = new OneOnOneDTO();
            $oneOnOneDTO->setOneOnOneId($tOneOnOne->getId());
            $oneOnOneDTO->setOneOnOneType($tOneOnOne->getOneOnOneType());
            $oneOnOneDTO->setSenderUserId($tOneOnOne->getSenderUserId());
            $fromUserEntity = $mUserRepos->find($tOneOnOne->getSenderUserId());
            $oneOnOneDTO->setSenderUserName($fromUserEntity->getLastName() . $fromUserEntity->getFirstName());
            $oneOnOneDTO->setSenderUserImageVersion($fromUserEntity->getImageVersion());
            $oneOnOneDTO->setToNames($toNames);
            $oneOnOneDTO->setLastUpdate($tOneOnOne->getCreatedAt());
            $oneOnOneDTO->setText($tOneOnOne->getBody());

            $oneOnOneDTOArray[] = $oneOnOneDTO;
        }

        $oneOnOneDialogDTO->setDialog($oneOnOneDTOArray);

        return $oneOnOneDialogDTO;
    }

    /**
     * 前回送信先ユーザリスト取得処理
     *
     * @param integer $userId ユーザID
     * @return array
     */
    public function getDefaultDestinationsArray(int $userId): array
    {
        // 1on1種別配列
        $oneOnOneTypeArray = array(
                DBConstant::ONE_ON_ONE_TYPE_DAILY_REPORT,
                DBConstant::ONE_ON_ONE_TYPE_PROGRESS_MEMO,
                DBConstant::ONE_ON_ONE_TYPE_HEARING,
                DBConstant::ONE_ON_ONE_TYPE_FEEDBACK,
                DBConstant::ONE_ON_ONE_TYPE_INTERVIEW_NOTE
        );

        $tOneOnOneToRepos = $this->getTOneOnOneToRepository();
        $oneOnOneDefaultDestinationDTOArray = array();
        foreach ($oneOnOneTypeArray as $oneOnOneType) {
            $userInfoArray = $tOneOnOneToRepos->getPreviousDestinations($userId, $oneOnOneType);

            $basicUserInfoDTOArray = array();
            foreach ($userInfoArray as $userInfo) {
                $basicUserInfoDTO = new BasicUserInfoDTO();
                $basicUserInfoDTO->setUserId($userInfo['user_id']);
                $basicUserInfoDTO->setName($userInfo['last_name'] . $userInfo['first_name']);

                $basicUserInfoDTOArray[] = $basicUserInfoDTO;
            }

            $oneOnOneDefaultDestinationDTO = new OneOnOneDefaultDestinationDTO();
            $oneOnOneDefaultDestinationDTO->setType($oneOnOneType);
            $oneOnOneDefaultDestinationDTO->setTo($basicUserInfoDTOArray);

            $oneOnOneDefaultDestinationDTOArray[] = $oneOnOneDefaultDestinationDTO;
        }

        return $oneOnOneDefaultDestinationDTOArray;
    }

    /**
     * 前回送信先ユーザリスト取得処理
     *
     * @param integer $userId ユーザID
     * @param string $oneOnOneType 1on1種別
     * @return array
     */
    public function getDefaultDestinations(int $userId, string $oneOnOneType): array
    {
        $tOneOnOneToRepos = $this->getTOneOnOneToRepository();
        $userInfoArray = $tOneOnOneToRepos->getPreviousDestinations($userId, $oneOnOneType);

        $basicUserInfoDTOArray = array();
        foreach ($userInfoArray as $userInfo) {
            $basicUserInfoDTO = new BasicUserInfoDTO();
            $basicUserInfoDTO->setUserId($userInfo['user_id']);
            $basicUserInfoDTO->setName($userInfo['last_name'] . $userInfo['first_name']);

            $basicUserInfoDTOArray[] = $basicUserInfoDTO;
        }

        return $basicUserInfoDTOArray;
    }
}
