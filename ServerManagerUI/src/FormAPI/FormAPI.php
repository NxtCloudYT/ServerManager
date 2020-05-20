<?php

declare(strict_types=1);

namespace FormAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class FormAPI implements Listener {

    /** @var int */
    public $formCount = 0;
    /** @var array */
    public $forms = [];

    /** @var FormAPI */
    private static $instance;

    public $playerdata = [];

    public function __construct() {
    }

    /**
     * @return FormAPI
     */
    static public function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param Plugin $plugin
     */
    public static function enable(Plugin $plugin) {
        self::getInstance();

        self::getInstance()->formCount = rand(0, 0xFFFFFFFF);
        Server::getInstance()->getPluginManager()->registerEvents(new FormAPIListener(), $plugin);
    }

    /**
     * @param callable $function
     * @return CustomForm
     */
    public function createCustomForm(callable $function = null): CustomForm {
        $this->formCountBump();
        $form = new CustomForm($this->formCount, $function);
        $this->forms[$this->formCount] = $form;
        return $form;
    }

    public function createSimpleForm(callable $function = null): SimpleForm {
        $this->formCountBump();
        $form = new SimpleForm($this->formCount, $function);
        $this->forms[$this->formCount] = $form;
        return $form;
    }

    public function createModalForm(callable $function = null): ModalForm {
        $this->formCountBump();
        $form = new ModalForm($this->formCount, $function);
        $this->forms[$this->formCount] = $form;
        return $form;
    }

    public function formCountBump(): void {
        ++$this->formCount;
        if ($this->formCount & (1 << 32)) { // integer overflow!
            $this->formCount = rand(0, 0xFFFFFFFF);
        }
    }
}
