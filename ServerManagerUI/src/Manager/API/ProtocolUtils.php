<?php


namespace Manager\API;

use pocketmine\utils\Binary;

class ProtocolUtils
{
    public static function writeString(string $str, string &$datastream)
    {
        $datastream .= Binary::writeShort(strlen($str)) . $str;
    }
}