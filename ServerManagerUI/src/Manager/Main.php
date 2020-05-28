<?php

namespace Manager;

use FormAPI\FormAPI;
use Manager\API\ProxyAPI;
use Manager\Commands\ServerManagerCommand;
use Manager\Tasks\KickAllTask;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Binary;
use pocketmine\utils\Config;
use pocketmine\utils\MainLogger;
use pocketmine\utils\TextFormat;

class Main extends PluginBase
{
    const PREFIX = "§bServerManager §8| §7";

    public $prefix = "§cBroadcast §8| §7";

    const BROADCAST = "§cBroadcast §8| §7";

    public function onEnable()
    {
        $this->getLogger()->info(self::PREFIX . "§aThis Plugin was enabled.");
        $this->getLogger()->info(self::PREFIX . "§bThis Plugin was made by NxtCloud!");

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getCommandMap()->register("servermanager", new ServerManagerCommand($this));

    }

    public function onDisable()
    {
        $this->getLogger()->info("§cThis Plugin was disabled!");
        $this->getLogger()->info("§bThis Plugin was made by HowToRush!");
    }


    public function ManageUI(Player $sender)
    {

        $formapi = FormAPI::getInstance();
        $form = $formapi->createSimpleForm(function (Player $sender, int $data = null) {
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0;
                $this->ManageServerUI($sender);
                    return;
                    break;
            }
        });
        $form->setTitle("§8» §aServerManager §8«");
        $form->setContent("§aManage Servers!");
        $form->addButton("§cManage Server");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function ManageServerUI(Player $sender) {
        $formapi = FormAPI::getInstance();
        $form = $formapi->createSimpleForm(function (Player $sender, int $data = null) {
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0;
                $this->BroadcastUI($sender);
                    return;
                    break;
                case 1:
                    $config = new Config($this->getDataFolder() . "/Maintenance.yml", Config::YAML);
                    if ($config->get("Maintenance")) {
                        $config->set("Maintenance", false);
                        $sender->sendMessage(self::PREFIX . "§cThe Maintenance Mode was disabled!");
                        $this->ManageUI($sender);
                    } else {
                        $config->set("Maintenance", true);
                        $sender->sendMessage(self::PREFIX . "§aThe Maintenance Mode was enabled!");
                        $this->ManageUI($sender);
                    }
                    break;
                case 2:
                    $this->getScheduler()->scheduleDelayedTask(new KickAllTask($this), 5);
                    break;
            }
        });
        $form->setTitle("§8» §aServerManager §8«");
        $form->setContent("§aManage Servers!");
        $form->addButton("§cBroadcast");
        $form->addButton("§cSet Maintenance");
        $form->addButton("§bKickAll");
        $form->sendToPlayer($sender);
        return $form;
    }

    public function BroadcastUI(Player $sender)
    {

        $formapi = FormAPI::getInstance();
        $form = $formapi->createCustomForm(function (Player $sender, int $data = null) {
            if (isset($data[0])) {
            	$all = Server::getInstance()->getOnlinePlayers();
	            var_dump(ProxyAPI::getBungeePlayers());
	            foreach (ProxyAPI::getBungeePlayers() as $player) {
		            $message = $this->prefix . TextFormat::GREEN . $data[0];
		            ProxyAPI::sendMessage($message, $player);
		            $this->ManageUI($sender);
	            }
            } else {
                $this->ManageUI($sender);
            }
        });

        $form->setTitle("§8» §aServerManager §8«");
        $form->addInput("§7Put the Broadcast Message here.");
        $form->sendToPlayer($sender);
        return $form;
    }

}
