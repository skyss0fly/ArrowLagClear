<?php

namespace aqua\ArrowClear;

use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\projectile\Arrow;

class ArrowClear extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onProjectileHit(ProjectileHitEvent $event): void {
        $entity = $event->getEntity();
        if ($entity instanceof Arrow) {
            $this->getScheduler()->scheduleDelayedTask(new ClearArrowTask($entity), 40); // 20 ticks per second, 2 seconds delay
        }
    }
}

class ClearArrowTask extends \pocketmine\scheduler\Task {

    private $arrow;

    public function __construct(Arrow $arrow) {
        $this->arrow = $arrow;
    }

    public function onRun(): void {
        if (!$this->arrow->isClosed()) {
            $this->arrow->flagForDespawn();
        }
    }
}
