<?php

/**
 * Transaction class is globally accessible.
 * It do not starts new transaction if there aready one is in progress.
 *
 * @author faisal.kaleem
 */
class Transaction {
  
  /**
   * Static property of transaction. 
   * Holds the currently running instance of transaction.
   * 
   * @var CDbTransaction
   */
  static $transaction;
  
  /**
   * Begins a new transaction if no other transaction already started.
   */
  public static function begin() {
    if(Yii::app()->db->getCurrentTransaction()==null) {
      self::$transaction = Yii::app()->db->beginTransaction();
    }
  }
  
  /**
   * Commits local transacion if started by begin method.
   */
  public static function commit() {
    if(self::$transaction) {
      self::$transaction->commit();
    }
  }
  
  /**
   * Commits any runnign transaction.
   */
  public static function commitRunning() {
    $transaction = Yii::app()->db->getCurrentTransaction();
    if($transaction!=null) {
      $transaction->commit();
    }
  }
  
  /**
   * Rollbacks local transacion if started by begin method.
   */
  public static function rollback() {
    if(self::$transaction) {
      self::$transaction->rollback();
    }
  }
}
