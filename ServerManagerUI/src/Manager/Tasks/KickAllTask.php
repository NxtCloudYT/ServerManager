<?php


namespace Manager\Tasks;


use Manager\Main;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class KickAllTask extends Task
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        $all = Server::getInstance()->getOnlineMode();
        if ($all instanceof Player) {

            $all->kick("§bAll Players was kicked");
        }
    }
}