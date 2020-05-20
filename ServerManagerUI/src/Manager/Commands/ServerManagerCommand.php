<?php


namespace Manager\Commands;


use Manager\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ServerManagerCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("servermanager", "Manage other Servers!", "/servermanager", ["sm"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("manage.use") or $sender->isOp()) {
            if ($sender instanceof Player) {
                $this->plugin->ManageUI($sender);
            }
        } else {
            $sender->sendMessage(Main::PREFIX . "§cYou don't have Permissions to use this Command!");
        }
    }
}