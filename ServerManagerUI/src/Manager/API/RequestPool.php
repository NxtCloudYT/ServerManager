<?php

namespace Manager\API;

use pocketmine\Player;
use pocketmine\Server;

abstract class RequestPool
{
    /** @var Request[] */
    public static $requests = [];

    public static function addRequest(Player $throughpass, Request $request): void
    {
        self::$requests[$throughpass->getName()] = $request;
    }

    public static function removeRequest(String $name): void
    {
        unset(self::$requests[$name]);
    }

    public static function hasRequestOpen(String $name): bool
    {
        return isset(self::$requests[$name]);
    }

    public static function getFreePlayerForRequest(): ?Player
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $p) {
            if (!(self::hasRequestOpen($p->getName()))) return $p;
        }
        return null;
    }
    public static function getRequestForPlayer(String $name): ?Request{
        if(isset(self::$requests[$name])) return self::$requests[$name]; else return null;
    }
}
