<?php
/**********************************************************
* File         : oracle.class.php
* Authors      : mie wakushima
* Date         : 2006.09.25
* Last Update  : 2006.10.04
* Copyright    : (c) Logic-Seed,Inc.
***********************************************************/
require_once('DB.php');

//----------------------------------------------------------
//  クラス名  ：dataBase
//  処理概要  ：データベースを操作する
//----------------------------------------------------------
class oracle
{
  var $db;
  var $msg;
  var $DebugInfo;

  function __construct($dsn="")
  {
    $this->connect($dsn);
  }

  //------------------------------------------------------
  // 関数名 ：connect
  // 引数   ：NONE
  // 処理概要 ：データベースと接続する
  //------------------------------------------------------
  function connect($dsn)
  {
    $options = array(
      'autofree'    => true,
      'debug'       => 2,
      'persistent'  => true,
      'portability' => DB_PORTABILITY_ERRORS | DB_PORTABILITY_NULL_TO_EMPTY | DB_PORTABILITY_NUMROWS | DB_PORTABILITY_RTRIM,
      'ssl'         => false
    );
    
    if ($dsn == "")
    {
    	$dsn = HIS_DSN;
    }
    
    $mydb = DB::connect($dsn,$options);
    if (PEAR::isError($mydb))
    {
      $this->msg = $mydb->getMessage();
      $this->DebugInfo = $mydb->getDebugInfo();
var_dump($this->msg);
var_dump($this->DebugInfo);
      return false;
    }
    else
    {
      $this->db = $mydb;
      return true;
    }
  }

  //------------------------------------------------------
  // 関数名 ：execute
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function execute($sql,$param = "")
  {
    unset($sth);
    unset($ret);
    $sth = $this->db->prepare($sql);
    if (PEAR::isError($sth))
    {
      $this->msg = $sth->getMessage();
      $this->DebugInfo = $sth->getDebugInfo();
      print "prepare_error";
      return false;
    }

    if($param == "")
    {
      $ret = $this->db->execute($sth);
    }
    else
    {
      $ret = $this->db->execute($sth,$param);
    }
    
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    else
    {
      return $ret;
    }
  }


  //------------------------------------------------------
  // 関数名 ：getAll
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function getAll($sql,$param=array())
  {
    $ret = array();
    $ret = $this->db->getAll($sql, $param, DB_FETCHMODE_ASSOC);
    if (PEAR::isError($ary))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：getAssoc
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function getAssoc($sql,$param=array())
  {
    $ret = array();
    $ret = $this->db->getAssoc($sql, TRUE, $param, DB_FETCHMODE_ASSOC);
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：getAssoc2Ary
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function getAssoc2Ary($sql,$param=array())
  {
    $ret = array();
    $ret = $this->db->getAssoc($sql, FALSE, $param, DB_FETCHMODE_ASSOC);
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：getAssoc2Group
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function getAssoc2Group($sql,$param=array())
  {
    $ret = array();
    $ret = $this->db->getAssoc($sql, TRUE, $param, DB_FETCHMODE_ASSOC, TRUE);
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：getRow
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function getRow($sql,$param=array())
  {
    $ret = array();
    $ret = $this->db->getRow($sql, $param, DB_FETCHMODE_ASSOC);
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：getRow2Obj
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function getRow2Obj($sql,$param=array())
  {
    $ret = array();
    $ret = $this->db->getRow($sql, $param, DB_FETCHMODE_OBJECT);
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：getOne
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function getOne($sql,$param=array())
  {
    $ret = array();
    $ret = $this->db->getOne($sql, $param, DB_FETCHMODE_ASSOC);
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：getCol
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function getCol($sql,$col=0,$param=array())
  {
    $ret = array();
    $ret = $this->db->getCol($sql, $col, $param);
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：query
  // 引数   ：NONE
  // 処理概要 ：SQLクエリの実行
  //------------------------------------------------------
  function query($sql)
  {
    $ret = $this->db->query($sql);
    if (PEAR::isError($ret))
    {
      $this->msg = $ret->getMessage();
      $this->DebugInfo = $ret->getDebugInfo();
      return false;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：fetchRow
  // 引数   ：NONE
  // 処理概要 ：実行結果からデータを配列にして返す
  //------------------------------------------------------
  function fetchRow($rs)
  {
    $ret = array();

    $i = 0;
    while($tmp = $rs->fetchRow(DB_FETCHMODE_ASSOC))
    {
      $ret[$i] = $tmp;
      $i++;
    }
    return $ret;
  }

  //------------------------------------------------------
  // 関数名 ：begin
  // 引数   ：NONE
  // 処理概要 ：トランザクションの開始
  //------------------------------------------------------
  function begin()
  {
    $this->db->autoCommit(false);
  }

  //------------------------------------------------------
  // 関数名 ：end
  // 引数   ：NONE
  // 処理概要 ：トランザクションの終了
  //------------------------------------------------------
  function end()
  {
    $this->db->commit();
  }

  //------------------------------------------------------
  // 関数名 ：rollback
  // 引数   ：NONE
  // 処理概要 ：ロールバック
  //------------------------------------------------------
  function rollback()
  {
    $this->db->rollback();
  }

  //------------------------------------------------------
  // 関数名 ：disconnect
  // 引数   ：NONE
  // 処理概要 ：DB切断
  //------------------------------------------------------
  function disconnect()
  {
    $this->db->disconnect();
  }

  //------------------------------------------------------
  // 関数名 ：getSequence
  // 引数   ：NONE
  // 処理概要 ：
  //------------------------------------------------------
  function getSequence($seqname)
  {
    //$id = $this->db->nextId($seqname);
    $id = $this->getOne("SELECT $seqname.NEXTVAL FROM DUAL");
    return $id;
  }

  //------------------------------------------------------
  // 関数名 ：getError
  // 引数   ：NONE
  // 処理概要 ：
  //------------------------------------------------------
  function getError()
  {
    $msg = "";
    $msg = "[" . $this->msg . "]->[" . $this->DebugInfo . "]";
    return $msg;
  }

}
?>
