<?php

namespace Manager\API;

use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;
use Manager\API\ProxyAPI;

class BufferFactory
{
    public static function constructBuffer(array $data, int $type): ?string
    {
        switch ($type) {
            case RequestType::TYPE_GET_SERVER_LIST:
                $str = "";
                ProxyAPI::writeString("GetServers", $str);
                return $str;
                break;
            case RequestType::TYPE_GET_SERVER:
                $str = "";
                ProxyAPI::writeString("GetServer", $str);
                return $str;
            case RequestType::TYPE_GET_SERVER_IP:
                $str = "";
                ProxyAPI::writeString("ServerIP", $str);
                ProxyAPI::writeString($data["server"], $str);
                return $str;
                break;
            case RequestType::TYPE_GET_PLAYER_COUNT:
                $str = "";
                ProxyAPI::writeString("PlayerCount", $str);
                ProxyAPI::writeString($data["server"], $str);
                return $str;
                break;
            case RequestType::TYPE_GET_PLAYER_LIST:
                $str = "";
                ProxyAPI::writeString("PlayerList", $str);
                ProxyAPI::writeString($data["server"], $str);
                return $str;
                break;
            case RequestType::TYPE_GET_PLAYER_IP:
                $str = "";
                ProxyAPI::writeString("IP", $str);
                return $str;
                break;
            case RequestType::TYPE_GET_PING:
                $str = "";
                ProxyAPI::writeString("GetPing", $str);
                ProxyAPI::writeString($data["player"], $str);
                return $str;
                break;
            default:
                return null;
                break;
        }

    }

    public static function constructPacket(array $data, int $type): ?ScriptCustomEventPacket
    {
        $pk = new ScriptCustomEventPacket();
        $pk->eventName = "bungeecord:main";
        $pk->eventData = self::constructBuffer($data, $type);
        return $pk;
    }
}
