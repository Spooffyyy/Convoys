<?php

namespace Terpz710\Convoys\Task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\World;

class CrateSpawnTask extends Task {
    private $plugin;

    public function __construct(\Terpz710\Convoys\Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick) {
        $server = Server::getInstance();

        foreach ($this->plugin->crateLocations as $worldName => $locations) {
            $world = $server->getWorldByName($worldName);
            if ($world instanceof World) {
                foreach ($locations as $location) {
                    $x = $location["x"];
                    $y = $location["y"];
                    $z = $location["z"];
                    $this->plugin->spawnCrateAtLocation($world, $x, $y, $z);
                }
            }
        }
    }
}
