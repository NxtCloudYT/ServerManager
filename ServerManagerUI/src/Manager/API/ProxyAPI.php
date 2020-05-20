<?php

namespace Manager\API;

use Manager\Main;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Binary;

class ProxyAPI implements Listener {

	public $plugin;
	public static $bungeeplayers = [];

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public static function getRandomPlayer(): ?Player
	{
		if (count(Server::getInstance()->getOnlinePlayers()) > 0) {
			return Server::getInstance()->getOnlinePlayers()[array_rand(Server::getInstance()->getOnlinePlayers())];
		} else {
			return null;
		}
	}

	public static function kickAll(String $player, String $message): bool
	{
		if (($sendthrough = static::getRandomPlayer()) != null) {
			if (!$sendthrough->hasPermission("core.team")) {
				$packet = new ScriptCustomEventPacket();
				$packet->eventName = "bungeecord:main";
				$packet->eventData = "";
				ProxyAPI::writeString("KickPlayer", $packet->eventData);
				ProxyAPI::writeString($player, $packet->eventData);
				ProxyAPI::writeString($message, $packet->eventData);
				$sendthrough->sendDataPacket($packet);
				return true;
			} else {
				return false;
			}
		}
	}

	public static function getBungeePlayers(): array
	{
		if (($player = static::getRandomPlayer()) != null) {
			$pk = BufferFactory::constructPacket(["server" => 'ALL'], RequestType::TYPE_GET_PLAYER_LIST);
			RequestPool::addRequest($player, new Request($pk->eventData, $player->getName(), RequestType::TYPE_GET_PLAYER_LIST, function (array $result) use ($player) {
				self::$bungeeplayers = $result['players'];
			}, ["player" => $player->getName(), "server" => 'ALL']));
			$player->sendDataPacket($pk);
		}
		return ProxyAPI::$bungeeplayers;
	}

	public static function sendMessage(String $message, String $player): bool
	{
		if (($sendthrough = static::getRandomPlayer()) != null) {
			$packet = new ScriptCustomEventPacket();
			$packet->eventName = "bungeecord:main";
			$packet->eventData = "";
			ProxyAPI::writeString("Message", $packet->eventData);
			ProxyAPI::writeString($player, $packet->eventData);
			ProxyAPI::writeString($message, $packet->eventData);
			$sendthrough->sendDataPacket($packet);
			return true;
		} else {
			Server::getInstance()->getLogger()->warning("Cannot execute API::sendMessage(): No Player online for abusing");
			return false;
		}
	}

	public static function writeString(String $str, String &$datastream)
	{
		$datastream .= Binary::writeShort(strlen($str)) . $str;
	}

	public static function readString(String $str, String &$datastream)
	{
		$datastream .= Binary::readShort(strlen($str)) . $str;
	}

}
