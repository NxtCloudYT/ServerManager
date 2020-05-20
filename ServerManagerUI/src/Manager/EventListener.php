<?php


namespace Manager;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class EventListener implements Listener
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event) {

        $player = $event->getPlayer();

        $config = new Config($this->plugin->getDataFolder() . "/Maintenance.yml", Config::YAML);

        if ($config->get("Maintenance")) {
            if (!$player->hasPermission("manager.use")) {
                $player->setImmobile(true);
                $player->setGamemode(3);
                $player->sendMessage(Main::PREFIX . "§cThis Server is currently in Maintenance Mode!");
            } else {
                $player->sendMessage(Main::PREFIX . "§cYou're an Admin!");
            }
        } else {

            $player->setGamemode(0);
            $player->setImmobile(false);

        }
    }
}