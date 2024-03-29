<?php

/***
 *       _____ _                 _      ______         _   _
 *      / ____(_)               | |    |  ____|       | | (_)
 *     | (___  _ _ __ ___  _ __ | | ___| |__ __ _  ___| |_ _  ___  _ __
 *      \___ \| | '_ ` _ \| '_ \| |/ _ \  __/ _` |/ __| __| |/ _ \| '_ \
 *      ____) | | | | | | | |_) | |  __/ | | (_| | (__| |_| | (_) | | | |
 *     |_____/|_|_| |_| |_| .__/|_|\___|_|  \__,_|\___|\__|_|\___/|_| |_|
 *                        | |
 *                        |_|
 */
namespace Ayzrix\SimpleFaction\Utils;

use Ayzrix\SimpleFaction\Main;
use Ayzrix\SimpleFaction\Tasks\Async\QueryTask;

class Provider {

    /**
     * @return \MySQLi|\SQLite3
     */
    public static function getDatabase() {
        if (Utils::getProvider() === "mysql") {
            return new \MySQLi(Utils::getIntoConfig("mysql_address"), Utils::getIntoConfig("mysql_user"), Utils::getIntoConfig("mysql_password"), Utils::getIntoConfig("mysql_db"));
        } else return new \SQLite3(Main::getInstance()->getDataFolder() . "SimpleFaction.db");
    }
    
    public static function init(): void {
        self::getDatabase()->query("CREATE TABLE IF NOT EXISTS faction (player VARCHAR(255) PRIMARY KEY, faction VARCHAR(255), role VARCHAR(255));");
        self::getDatabase()->query("CREATE TABLE IF NOT EXISTS power (faction VARCHAR(255) PRIMARY KEY, power int);");
        self::getDatabase()->query("CREATE TABLE IF NOT EXISTS home (faction VARCHAR(255) PRIMARY KEY, x int, y int, z int, world VARCHAR(255));");
        self::getDatabase()->query("CREATE TABLE IF NOT EXISTS bank (faction VARCHAR(255) PRIMARY KEY, money int);");
        self::getDatabase()->query("CREATE TABLE IF NOT EXISTS lang (player VARCHAR(255) PRIMARY KEY, lang VARCHAR(255));");
        if (Utils::getProvider() === "mysql") {
            self::getDatabase()->query("CREATE TABLE IF NOT EXISTS claim (ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT, faction VARCHAR(255), x int, z int, world VARCHAR(255));");
        } else self::getDatabase()->query("CREATE TABLE IF NOT EXISTS claim (ID INT PRIMARY KEY, faction VARCHAR(255), x int, z int, world VARCHAR(255));");
        if (Utils::getProvider() === "mysql") {
            self::getDatabase()->query("CREATE TABLE IF NOT EXISTS allies (ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT, faction1 VARCHAR(255), faction2 VARCHAR(255));");
        } else self::getDatabase()->query("CREATE TABLE IF NOT EXISTS allies (ID INT PRIMARY KEY, faction1 VARCHAR(255), faction2 VARCHAR(255));");
    }

    /**
     * @param string $text
     */
    public static function query(string $text): void {
        if (Utils::getProvider() === "mysql") {
            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new QueryTask($text));
        } else self::getDatabase()->query($text);
    }
}