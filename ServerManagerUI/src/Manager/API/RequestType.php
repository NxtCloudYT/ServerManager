<?php

namespace Manager\API;

abstract class RequestType{
    public const TYPE_GET_SERVER_LIST = 1;
    public const TYPE_GET_PLAYER_LIST = 2;
    public const TYPE_GET_PLAYER_COUNT = 3;
    public const TYPE_GET_SERVER = 4;
    public const TYPE_GET_PLAYER_IP = 5;
    public const TYPE_GET_SERVER_IP = 6;
    public const TYPE_GET_PING = 7;
}
