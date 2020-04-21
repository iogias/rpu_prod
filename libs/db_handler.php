<?php

class DbHandler {
    /*
     * @cHandler : static variabel private
     */

    private static $cHandler;

    private function __construct() {

    }

    private static function getHandler() {
        if (!isset(self::$cHandler)) {
            try {
                self::$cHandler = new PDO(PDO_DSN, DB_USER, DB_PASSWORD,
                                array(PDO::ATTR_PERSISTENT => DB_PERSISTENT));
                self::$cHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                self::cClose();
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }
        return self::$cHandler;
    }

    public static function cClose() {
        self::$cHandler = null;
    }

    /* Wrapper method PDOStatement::execute()
     * @sql : Query database
     * @params : parameter
     */

    public static function cExecute($sql, $params=null) {
        try {
            $getHandler = self::getHandler();
            $stmtHandler = $getHandler->prepare($sql);
            $stmtHandler->execute($params);
        } catch (PDOException $e) {
            self::cClose();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    /* Wrapper method PDOStatement::fetchAll()
     * @sql : Query database
     * @params : parameter
     * @fetchStyle : PDO::FETCH_ASSOC
     */

    public static function getAll($sql, $params=null, $fetchStyle=PDO::FETCH_ASSOC) {
        $result = null;
        try {
            $getHandler = self::getHandler();
            $stmtHandler = $getHandler->prepare($sql);
            $stmtHandler->execute($params);
            $result = $stmtHandler->fetchAll($fetchStyle);
        } catch (PDOException $e) {
            self::cClose();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        return $result;
    }

    /* Wrapper method PDOStatement::fetch()
     * @sql : Query database
     * @params : parameter
     * @fetchStyle : PDO::FETCH_ASSOC
     */

    public static function getRow($sql, $params=null, $fetchStyle=PDO::FETCH_ASSOC) {
        $result = null;
        try {
            $getHandler = self::getHandler();
            $stmtHandler = $getHandler->prepare($sql);
            $stmtHandler->execute($params);
            $result = $stmtHandler->fetch($fetchStyle);
        } catch (PDOException $e) {
            self::cClose();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        return $result;
    }

    /* Wrapper method PDOStatement::fetch()
     * @sql : Query database
     * @params : parameter
     * @return kolom pertama dari baris tabel
     */

    public static function getOne($sql, $params=null) {
        $result = null;
        try {
            $getHandler = self::getHandler();
            $stmtHandler = $getHandler->prepare($sql);
            $stmtHandler->execute($params);
            $result = $stmtHandler->fetch(PDO::FETCH_NUM);
            $result=$result[0];
        } catch (PDOException $e) {
            self::cClose();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        return $result;
    }

}

?>
