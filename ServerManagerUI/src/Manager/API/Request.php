<?php

namespace Manager\API;

use Closure;
use pocketmine\utils\MainLogger;

class Request
{
    /** @var Closure */
    private $action;
    /** @var string */
    private $query;
    /** @var string */
    private $player;
    /** @var array */
    private $data;
    /** @var Integer */
    private $type;
    /** @var mixed[] */
    private $extradata;

    public function __construct(string $query, string $player, int $type, Closure $action, array $extradata)
    {
        $this->setQuery($query);
        $this->setPlayer($player);
        $this->setAction($action);
        $this->setExtraData($extradata);
        $this->setType($type);
    }

    public function notify(string $eventData)
    {
        $stream = new StringStream($eventData);
        switch ($this->getType()) {
            case RequestType::TYPE_GET_PLAYER_COUNT:
                $stream->readString();
                $stream->readString();
                $this->setData([
                    "count" => $stream->readInt()
                ]);
                break;
            case RequestType::TYPE_GET_PLAYER_LIST:
                $stream->readString();
                $this->setData([
                    "server" => $stream->readString(),
                    "players" => explode(", ", $stream->readString())
                ]);
                break;
            case RequestType::TYPE_GET_SERVER:
                $stream->readString();
                $this->setData([
                    "server" => $stream->readString()
                ]);
                break;
            case RequestType::TYPE_GET_SERVER_IP:
                $stream->readString();
                $this->setData([
                    "servername" => $stream->readString(),
                    "ip" => $stream->readString(),
                    "port" => ($port = $stream->readUnsignedShort()) != null ? $port : null
                ]);
                break;
            case RequestType::TYPE_GET_SERVER_LIST:
                $stream->readString();
                $this->setData([
                    "servers" => explode(", ", $stream->readString())
                ]);
                break;
            case RequestType::TYPE_GET_PING:
                // Thanks to fives, this request doesn't has any other data than the result
                $this->setData([
                    "ping" => $stream->readInt()
                ]);
                break;
            default:
                MainLogger::getLogger()->warning("Unrecognized RequestType: " . $this->type);
                return;
                break;

        }
        $this->exec();
    }

    public function exec()
    {
        $action = $this->action;
        $action($this->getData(), $this->getExtraData());
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getExtraData(): ?array
    {
        return $this->extradata;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function setAction(Closure $action): void
    {
        $this->action = $action;
    }

    public function getAction(): ?Closure
    {
        return $this->action;
    }

    public function setExtraData(array $data): void
    {
        $this->extradata = $data;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function setQuery(string $query): void
    {
        $this->query = $query;
    }

    public function setPlayer(string $player): void
    {
        $this->player = $player;
    }
}