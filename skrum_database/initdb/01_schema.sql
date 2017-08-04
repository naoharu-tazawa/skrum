-- MySQL Script generated by MySQL Workbench
-- Fri Aug  4 12:32:06 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema skm
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema skm
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `skm` DEFAULT CHARACTER SET utf8 ;
USE `skm` ;

-- -----------------------------------------------------
-- Table `skm`.`m_company`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_company` (
  `company_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会社ID',
  `company_name` VARCHAR(255) NULL COMMENT '会社名',
  `vision` VARCHAR(255) NULL COMMENT 'ヴィジョン',
  `mission` VARCHAR(255) NULL COMMENT 'ミッション',
  `subdomain` VARCHAR(45) NOT NULL COMMENT 'サブドメイン名',
  `default_disclosure_type` CHAR(2) NULL COMMENT 'デフォルト公開種別',
  `has_image` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '画像保存済みフラグ',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`company_id`),
  UNIQUE INDEX `ui_company_01` (`subdomain` ASC),
  INDEX `idx_company_01` (`company_name` ASC))
ENGINE = InnoDB
COMMENT = '会社';


-- -----------------------------------------------------
-- Table `skm`.`m_role_assignment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_role_assignment` (
  `role_assignment_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ロール割当ID',
  `role_id` VARCHAR(45) NOT NULL COMMENT 'ロールID',
  `role_level` SMALLINT(2) UNSIGNED NOT NULL COMMENT 'ロールレベル',
  `company_id` INT(11) UNSIGNED NOT NULL COMMENT '会社ID',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`role_assignment_id`),
  INDEX `idx_role_assignment_01` (`role_level` ASC))
ENGINE = InnoDB
COMMENT = 'ロール割当';


-- -----------------------------------------------------
-- Table `skm`.`m_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_user` (
  `user_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ユーザID',
  `company_id` INT(11) UNSIGNED NOT NULL COMMENT '会社ID',
  `last_name` VARCHAR(255) NULL COMMENT '姓',
  `first_name` VARCHAR(255) NULL COMMENT '名',
  `email_address` VARCHAR(255) NOT NULL COMMENT 'Eメールアドレス',
  `password` VARCHAR(255) NOT NULL COMMENT 'パスワード',
  `role_assignment_id` INT(11) UNSIGNED NOT NULL COMMENT 'ロール割当ID',
  `position` VARCHAR(255) NULL COMMENT '役職',
  `phone_number` VARCHAR(45) NULL COMMENT '電話番号',
  `has_image` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '画像保存済みフラグ',
  `archived_flg` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'アーカイブ済フラグ',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`user_id`),
  INDEX `idx_user_01` (`company_id` ASC),
  INDEX `idx_user_02` (`role_assignment_id` ASC),
  INDEX `idx_user_03` (`last_name` ASC),
  INDEX `idx_user_04` (`email_address` ASC),
  CONSTRAINT `fk_user_company_id`
    FOREIGN KEY (`company_id`)
    REFERENCES `skm`.`m_company` (`company_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_role_assignment_id`
    FOREIGN KEY (`role_assignment_id`)
    REFERENCES `skm`.`m_role_assignment` (`role_assignment_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'ユーザ';


-- -----------------------------------------------------
-- Table `skm`.`m_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_group` (
  `group_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'グループID',
  `company_id` INT(11) UNSIGNED NOT NULL COMMENT '会社ID',
  `group_name` VARCHAR(255) NULL COMMENT 'グループ名',
  `group_type` CHAR(2) NOT NULL COMMENT 'グループ種別',
  `leader_user_id` INT(11) UNSIGNED NULL COMMENT 'リーダーユーザID',
  `mission` VARCHAR(255) NULL COMMENT 'ミッション',
  `company_flg` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '会社フラグ',
  `has_image` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '画像保存済みフラグ',
  `archived_flg` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'アーカイブ済フラグ',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`group_id`),
  INDEX `idx_group_01` (`company_id` ASC),
  INDEX `idx_group_02` (`group_name` ASC),
  INDEX `idx_group_03` (`leader_user_id` ASC),
  CONSTRAINT `fk_group_company_id`
    FOREIGN KEY (`company_id`)
    REFERENCES `skm`.`m_company` (`company_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'グループ';


-- -----------------------------------------------------
-- Table `skm`.`t_group_member`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_group_member` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` INT(11) UNSIGNED NOT NULL COMMENT 'グループID',
  `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ユーザID',
  `post_share_flg` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '投稿シェアフラグ',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `idx_group_member_01` (`group_id` ASC),
  INDEX `idx_group_member_02` (`user_id` ASC),
  CONSTRAINT `fk_group_member_group_id`
    FOREIGN KEY (`group_id`)
    REFERENCES `skm`.`m_group` (`group_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_member_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `skm`.`m_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'グループメンバー';


-- -----------------------------------------------------
-- Table `skm`.`m_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_role` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` VARCHAR(45) NOT NULL COMMENT 'ロールID',
  `plan_id` INT(11) UNSIGNED NOT NULL COMMENT 'プランID',
  `name` VARCHAR(45) NOT NULL COMMENT 'ロール名',
  `level` SMALLINT(2) UNSIGNED NOT NULL COMMENT 'ロールレベル',
  `description` VARCHAR(255) NOT NULL COMMENT '説明',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `ui_role_01` (`role_id` ASC))
ENGINE = InnoDB
COMMENT = 'ロール';


-- -----------------------------------------------------
-- Table `skm`.`m_role_permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_role_permission` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` VARCHAR(45) NOT NULL COMMENT 'ロールID',
  `permission_id` VARCHAR(45) NOT NULL COMMENT '権限ID',
  `name` VARCHAR(45) NOT NULL COMMENT '権限名',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  INDEX `idx_role_permission_02` (`role_id` ASC, `permission_id` ASC),
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'ロール権限';


-- -----------------------------------------------------
-- Table `skm`.`m_permission_settings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_permission_settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `permission_id` VARCHAR(45) NOT NULL,
  `operation_id` VARCHAR(45) NOT NULL COMMENT 'オペレーションID',
  `name` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  INDEX `idx_permission_settings_01` (`permission_id` ASC, `operation_id` ASC),
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = '権限設定';


-- -----------------------------------------------------
-- Table `skm`.`t_timeframe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_timeframe` (
  `timeframe_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'タイムフレームID',
  `company_id` INT(11) UNSIGNED NOT NULL COMMENT '会社ID',
  `timeframe_name` VARCHAR(255) NOT NULL COMMENT 'タイムフレーム名',
  `start_date` DATE NOT NULL COMMENT '開始日',
  `end_date` DATE NOT NULL COMMENT '終了日',
  `default_flg` TINYINT(1) NULL COMMENT 'デフォルトフラグ',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`timeframe_id`),
  INDEX `idx_timeframe_01` (`company_id` ASC),
  CONSTRAINT `fk_timeframe_company_id`
    FOREIGN KEY (`company_id`)
    REFERENCES `skm`.`m_company` (`company_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'タイムフレーム';


-- -----------------------------------------------------
-- Table `skm`.`t_okr`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_okr` (
  `okr_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'OKRID',
  `timeframe_id` INT(11) UNSIGNED NOT NULL COMMENT 'タイムフレームID',
  `parent_okr_id` INT(11) UNSIGNED NULL COMMENT '親OKRID',
  `type` CHAR(2) NOT NULL COMMENT '種別',
  `name` VARCHAR(255) NOT NULL COMMENT 'OKR名',
  `detail` VARCHAR(255) NULL COMMENT 'OKR詳細',
  `target_value` BIGINT(20) UNSIGNED NOT NULL DEFAULT 100 COMMENT '目標値',
  `achieved_value` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '達成値',
  `achievement_rate` DECIMAL(15,1) NOT NULL COMMENT '達成率',
  `tree_left` DOUBLE(15,14) NULL COMMENT '左値(入れ子区間モデル)',
  `tree_right` DOUBLE(15,14) NULL COMMENT '右値(入れ子区間モデル)',
  `unit` VARCHAR(45) NOT NULL DEFAULT '％' COMMENT '単位',
  `owner_type` CHAR(2) NOT NULL COMMENT 'オーナー種別',
  `owner_user_id` INT(11) UNSIGNED NULL COMMENT 'オーナーユーザID',
  `owner_group_id` INT(11) UNSIGNED NULL COMMENT 'オーナーグループID',
  `owner_company_id` INT(11) UNSIGNED NULL COMMENT 'オーナー会社ID',
  `start_date` DATE NULL COMMENT '開始日',
  `end_date` DATE NULL COMMENT '終了日',
  `status` CHAR(2) NOT NULL COMMENT 'ステータス',
  `disclosure_type` CHAR(2) NOT NULL COMMENT '公開種別',
  `weighted_average_ratio` DECIMAL(4,1) NULL DEFAULT NULL COMMENT '加重平均比率',
  `ratio_locked_flg` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '持分比率ロックフラグ',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`okr_id`),
  INDEX `idx_okr_01` (`timeframe_id` ASC),
  INDEX `idx_okr_02` (`parent_okr_id` ASC),
  INDEX `idx_okr_04` (`owner_group_id` ASC),
  INDEX `idx_okr_05` (`owner_user_id` ASC),
  INDEX `idx_okr_06` (`tree_left` ASC, `tree_right` ASC),
  INDEX `idx_okr_07` (`name` ASC),
  CONSTRAINT `fk_okr_timeframe_id`
    FOREIGN KEY (`timeframe_id`)
    REFERENCES `skm`.`t_timeframe` (`timeframe_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_okr_parent_okr_id`
    FOREIGN KEY (`parent_okr_id`)
    REFERENCES `skm`.`t_okr` (`okr_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_okr_owner_group_id`
    FOREIGN KEY (`owner_group_id`)
    REFERENCES `skm`.`m_group` (`group_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_okr_owner_user_id`
    FOREIGN KEY (`owner_user_id`)
    REFERENCES `skm`.`m_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'OKR';


-- -----------------------------------------------------
-- Table `skm`.`m_plan`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_plan` (
  `plan_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'プランID',
  `name` VARCHAR(45) NOT NULL COMMENT 'プラン名',
  `price` DECIMAL(15,0) NOT NULL COMMENT 'プラン価格',
  `price_type` CHAR(2) NOT NULL COMMENT '価格種別',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`plan_id`))
ENGINE = InnoDB
COMMENT = 'プラン';


-- -----------------------------------------------------
-- Table `skm`.`t_contract`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_contract` (
  `contract_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '契約ID',
  `company_id` INT(11) UNSIGNED NOT NULL COMMENT '会社ID',
  `plan_id` INT(11) UNSIGNED NOT NULL COMMENT 'プランID',
  `price` DECIMAL(15,0) NULL COMMENT 'プラン価格',
  `price_type` CHAR(2) NULL COMMENT '価格種別',
  `plan_start_date` DATE NULL COMMENT 'プラン利用開始日',
  `plan_end_date` DATE NULL COMMENT 'プラン利用終了日',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`contract_id`),
  INDEX `idx_contract_01` (`company_id` ASC),
  INDEX `idx_contract_02` (`plan_id` ASC),
  INDEX `idx_contract_03` (`plan_start_date` ASC))
ENGINE = InnoDB
COMMENT = '契約';


-- -----------------------------------------------------
-- Table `skm`.`t_payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_payment` (
  `payment_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '請求ID',
  `contract_id` INT(11) UNSIGNED NOT NULL COMMENT '契約ID',
  `payment_date` DATE NOT NULL COMMENT '請求日',
  `status` CHAR(2) NOT NULL COMMENT '請求ステータス',
  `charge_amount` DECIMAL(15,0) NOT NULL COMMENT '請求金額',
  `settlement_amount` DECIMAL(15,0) NULL DEFAULT NULL COMMENT '約定金額',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  INDEX `idx_payment_01` (`contract_id` ASC),
  CONSTRAINT `fk_payment_contract_id`
    FOREIGN KEY (`contract_id`)
    REFERENCES `skm`.`t_contract` (`contract_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '請求';


-- -----------------------------------------------------
-- Table `skm`.`m_normal_timeframe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`m_normal_timeframe` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` VARCHAR(255) NOT NULL COMMENT '規定タイムフレーム名',
  `cycle_type` CHAR(2) NOT NULL COMMENT 'サイクル種別',
  `display_order` TINYINT(2) NOT NULL COMMENT '表示順',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `idx_normal_timeframe_01` (`display_order` ASC))
ENGINE = InnoDB
COMMENT = '規定タイムフレーム';


-- -----------------------------------------------------
-- Table `skm`.`t_group_tree`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_group_tree` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` INT(11) UNSIGNED NOT NULL COMMENT 'グループID',
  `group_tree_path` VARCHAR(3072) CHARACTER SET 'latin1' NOT NULL COMMENT 'グループパス(経路列挙モデル)',
  `group_tree_path_name` VARCHAR(3072) NULL COMMENT 'グループパス名',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `idx_group_tree_01` (`group_id` ASC),
  UNIQUE INDEX `ui_group_tree_01` (`group_tree_path` ASC),
  CONSTRAINT `fk_group_tree_group_id`
    FOREIGN KEY (`group_id`)
    REFERENCES `skm`.`m_group` (`group_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'グループツリー';


-- -----------------------------------------------------
-- Table `skm`.`t_okr_activity`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_okr_activity` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `okr_id` INT(11) UNSIGNED NOT NULL COMMENT 'OKRID',
  `type` CHAR(2) NOT NULL COMMENT '種別',
  `activity_datetime` DATETIME NOT NULL COMMENT 'アクティビティ日時',
  `target_value` BIGINT(20) UNSIGNED NULL COMMENT '目標値',
  `achieved_value` BIGINT(20) UNSIGNED NULL COMMENT '達成値',
  `achievement_rate` DECIMAL(15,1) UNSIGNED NULL COMMENT '達成率',
  `changed_percentage` DECIMAL(15,1) NULL COMMENT '増加パーセンテージ',
  `new_parent_okr_id` INT(11) UNSIGNED NULL COMMENT '新親OKRID',
  `previous_parent_okr_id` INT(11) UNSIGNED NULL COMMENT '旧親OKRID',
  `new_timeframe_id` INT(11) UNSIGNED NULL COMMENT '新タイムフレームID',
  `previous_timeframe_id` INT(11) UNSIGNED NULL COMMENT '旧タイムフレームID',
  `new_owner_user_id` INT(11) UNSIGNED NULL COMMENT '新オーナーユーザID',
  `previous_owner_user_id` INT(11) UNSIGNED NULL COMMENT '旧オーナーユーザID',
  `new_owner_group_id` INT(11) UNSIGNED NULL COMMENT '新オーナーグループID',
  `previous_owner_group_id` INT(11) UNSIGNED NULL COMMENT '旧オーナーグループID',
  `new_owner_company_id` INT(11) UNSIGNED NULL COMMENT '新オーナー会社ID',
  `previous_owner_company_id` INT(11) UNSIGNED NULL COMMENT '旧オーナー会社ID',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `idx_okr_activity_01` (`okr_id` ASC),
  INDEX `idx_okr_activity_02` (`activity_datetime` DESC),
  CONSTRAINT `fk_okr_activity_okr_id`
    FOREIGN KEY (`okr_id`)
    REFERENCES `skm`.`t_okr` (`okr_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'OKRアクティビティ';


-- -----------------------------------------------------
-- Table `skm`.`t_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_post` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `timeline_owner_group_id` INT(11) UNSIGNED NOT NULL COMMENT 'タイムラインオーナーグループID',
  `poster_type` CHAR(2) NOT NULL COMMENT '投稿者種別',
  `poster_user_id` INT(11) UNSIGNED NULL COMMENT '投稿ユーザID',
  `poster_group_id` INT(11) UNSIGNED NULL COMMENT '投稿グループID',
  `poster_company_id` INT(11) UNSIGNED NULL COMMENT '投稿会社ID',
  `post` VARCHAR(3072) NULL COMMENT '投稿',
  `auto_post` VARCHAR(3072) NULL COMMENT '自動投稿',
  `posted_datetime` DATETIME NOT NULL COMMENT '投稿日時',
  `okr_activity_id` BIGINT(20) UNSIGNED NULL COMMENT 'OKRアクティビティID',
  `parent_id` BIGINT(20) UNSIGNED NULL COMMENT '親タイムラインID',
  `disclosure_type` CHAR(2) NULL COMMENT '公開種別',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `idx_post_01` (`okr_activity_id` ASC),
  INDEX `idx_post_02` (`parent_id` ASC),
  INDEX `idx_post_03` (`timeline_owner_group_id` ASC),
  CONSTRAINT `fk_post_okr_activity_id`
    FOREIGN KEY (`okr_activity_id`)
    REFERENCES `skm`.`t_okr_activity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_parent_id`
    FOREIGN KEY (`parent_id`)
    REFERENCES `skm`.`t_post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '投稿';


-- -----------------------------------------------------
-- Table `skm`.`s_group_tree_path_id`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`s_group_tree_path_id` (
  `current_value` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '現在値',
  PRIMARY KEY (`current_value`))
ENGINE = MyISAM
COMMENT = 'グループツリーパスIDシーケンス';


-- -----------------------------------------------------
-- Table `skm`.`t_email_reservation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_email_reservation` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `to_email_address` VARCHAR(255) NOT NULL COMMENT 'TOアドレス',
  `title` VARCHAR(255) NOT NULL COMMENT 'タイトル',
  `body` TEXT NOT NULL COMMENT '本文',
  `reception_datetime` DATETIME NOT NULL COMMENT '受付日時',
  `sending_reservation_datetime` DATETIME NOT NULL COMMENT '送信予約日時',
  `sending_datetime` DATETIME NULL DEFAULT NULL COMMENT '送信日時',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'メール送信予約';


-- -----------------------------------------------------
-- Table `skm`.`t_authorization`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_authorization` (
  `authorization_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '認可ID',
  `company_id` INT(11) NOT NULL COMMENT '会社ID',
  `plan_id` INT(11) NOT NULL COMMENT 'プランID',
  `contract_id` INT(11) NULL COMMENT '契約ID',
  `authorization_start_datetime` DATETIME NOT NULL COMMENT '認可開始日時',
  `authorization_end_datetime` DATETIME NOT NULL COMMENT '認可終了日時',
  `authorization_stop_flg` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '認可停止フラグ',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`authorization_id`),
  INDEX `idx_authorization_01` (`company_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skm`.`t_pre_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_pre_user` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `urltoken` VARCHAR(128) NOT NULL COMMENT 'URLトークン',
  `email_address` VARCHAR(255) NOT NULL COMMENT 'Eメールアドレス',
  `subdomain` VARCHAR(45) NOT NULL COMMENT 'サブドメイン',
  `company_id` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT '会社ID',
  `role_assignment_id` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'ロール割当ID',
  `initial_user_flg` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '初期ユーザフラグ',
  `invalid_flg` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '無効フラグ',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `idx_pre_user_01` (`urltoken` ASC))
ENGINE = InnoDB
COMMENT = '仮登録ユーザ';


-- -----------------------------------------------------
-- Table `skm`.`t_like`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_like` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ユーザID',
  `post_id` BIGINT(20) UNSIGNED NOT NULL COMMENT '投稿ID',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `idx_like_01` (`user_id` ASC),
  INDEX `idx_like_02` (`post_id` ASC))
ENGINE = InnoDB
COMMENT = 'いいね';


-- -----------------------------------------------------
-- Table `skm`.`t_login`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_login` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ユーザID',
  `login_datetime` DATETIME NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `idx_login_01` (`user_id` ASC),
  INDEX `idx_login_02` (`login_datetime` DESC))
ENGINE = InnoDB
COMMENT = 'ログイン';


-- -----------------------------------------------------
-- Table `skm`.`t_email_settings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_email_settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ユーザID',
  `okr_achievement` CHAR(2) NOT NULL DEFAULT '1' COMMENT '達成率通知メール設定',
  `okr_timeline` CHAR(2) NOT NULL DEFAULT '1' COMMENT '投稿通知メール設定',
  `okr_reminder` CHAR(2) NOT NULL DEFAULT '1' COMMENT '進捗登録リマインドメール設定',
  `report_member_achievement` CHAR(2) NOT NULL DEFAULT '1' COMMENT 'メンバー進捗状況レポートメール設定',
  `report_group_achievement` CHAR(2) NOT NULL DEFAULT '1' COMMENT 'グループ進捗状況レポートメール設定',
  `report_feedback_target` CHAR(2) NOT NULL DEFAULT '1' COMMENT 'フィードバック対象者通知メール設定',
  `service_notification` CHAR(2) NOT NULL DEFAULT '1' COMMENT 'サービスお知らせメール設定',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'メール通知設定';


-- -----------------------------------------------------
-- Table `skm`.`t_achievement_rate_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skm`.`t_achievement_rate_log` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `owner_type` CHAR(20) NOT NULL COMMENT 'オーナー種別',
  `owner_user_id` INT(11) UNSIGNED NULL COMMENT 'オーナーユーザID',
  `owner_group_id` INT(11) UNSIGNED NULL COMMENT 'オーナーグループID',
  `owner_company_id` INT(11) UNSIGNED NULL COMMENT 'オーナー会社ID',
  `timeframe_id` INT(11) UNSIGNED NOT NULL COMMENT 'タイムフレームID',
  `achievement_rate` DECIMAL(15,1) NOT NULL COMMENT '達成率',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '登録日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = '目標進捗達成率ログ';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
