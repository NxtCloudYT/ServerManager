<?php

namespace FormAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class FormAPIListener implements Listener{
    public $f;

    public function __construct() {
        $this->f = FormAPI::getInstance();
    }

    /**
     * @param DataPacketReceiveEvent $ev
     */
    public function onPacketReceived(DataPacketReceiveEvent $ev): void {
        $pk = $ev->getPacket();
        if ($pk instanceof ModalFormResponsePacket) {
            $player = $ev->getPlayer();
            $formId = $pk->formId;
            $data = json_decode($pk->formData, true);
            if (isset($this->f->forms[$formId])) {
                /** @var Form $form */
                $form = $this->f->forms[$formId];
                if (!$form->isRecipient($player)) {
                    return;
                }
                $form->processData($data);
                $callable = $form->getCallable();
                if ($callable !== null) {
                    $callable($ev->getPlayer(), $data);
                }
                unset($this->f->forms[$formId]);
                $ev->setCancelled();
            }
        }
    }

    /**
     * @param PlayerQuitEvent $ev
     */
    public function onPlayerQuit(PlayerQuitEvent $ev) {
        $player = $ev->getPlayer();
        /**
         * @var int $id
         * @var Form $form
         */
        foreach ($this->f->forms as $id => $form) {
            if ($form->isRecipient($player)) {
                unset($this->f->forms[$id]);
                break;
            }
        }
    }
}