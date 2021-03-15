<?php

namespace Ayzrix\SimpleFaction;

use Ayzrix\SimpleFaction\Commands\Faction;
use Ayzrix\SimpleFaction\Events\Listener\PlayerListener;
use Ayzrix\SimpleFaction\Utils\MySQL;
use Ayzrix\SimpleFaction\Utils\Utils;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    /** @var Main */
    private static $instance;

    public function onEnable(): bool {
        self::$instance = $this;
        $this->saveDefaultConfig();

        if (Utils::getIntoConfig("mysql_address") === "SERVER ADDRESS" or Utils::getIntoConfig("mysql_user") === "USER" or Utils::getIntoConfig("mysql_password") === "YOUR PASSWORD" or Utils::getIntoConfig("mysql_db") === "YOUR DB") {
            $this->getLogger()->error("Error, please setup a valid mysql server");
            $this->getServer()->disablePlugins();
            return false;
        }

        $this->getServer()->getCommandMap()->register("simplefaction", new Faction($this));
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);

        MySQL::init();
        return true;
    }

    /**
     * @return Main
     */
    public static function getInstance(): Main {
        return self::$instance;
    }
}