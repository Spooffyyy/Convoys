<?php

namespace Terpz710\Convoys;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\World;
use pocketmine\block\Block;
use pocketmine\inventory\ChestInventory;
use pocketmine\tile\Chest as ChestTile;
use pocketmine\math\Vector3;
use Biswajit\BankNote\BankNote;


class Main extends PluginBase {
    private $crateLocations = [];

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->crateLocations = (new Config($this->getDataFolder() . "crateLocations.yml", Config::YAML))->getAll();
        $this->getScheduler()->scheduleRepeatingTask(new CrateSpawnTask($this), 20 * 60);
    }

    public function onDisable(): void {
        (new Config($this->getDataFolder() . "crateLocations.yml", Config::YAML))->setAll($this->crateLocations);
    }

    public function setCrateLocation(string $world, float $x, float $y, float $z) {
        $this->crateLocations[$world][] = ["x" => $x, "y" => $y, "z" => $z];
    }

    public function removeCrateLocation(string $world, int $index) {
        if (isset($this->crateLocations[$world][$index])) {
            unset($this->crateLocations[$world][$index]);
        }
    }

    public function spawnCrateAtLocation(World $world, float $x, float $y, float $z) {
        $chest = Block::get(Block::CHEST);
        $world->setBlock(new Vector3($x, $y, $z), $chest, true);
        $tile = $world->getTile(new Vector3($x, $y, $z));
        if ($tile instanceof ChestTile) {
            $tile->pairWith($chest);
            $this->addLootToCrate($tile->getInventory());
        }
    }

    public function addLootToCrate(ChestInventory $chestInventory) {
    $bankNotePlugin = $this->getServer()->getPluginManager()->getPlugin("BankNote");
    if ($bankNotePlugin instanceof BankNote) {
        $bankNotePlugin->addBankNoteToCrate($chestInventory);
    } else {
        $this->getLogger()->warning("BankNote plugin is not installed. Bank notes will not be added to crates.");
    }
    
    $lootTable = $this->getServer()->getLootTableManager()->getLootTable("path/to/custom_loot.json");
    $lootItems = $lootTable->getRandomItems();

    foreach ($lootItems as $item) {
        if ($item->getId() === Block::DIAMOND) {
            $chestInventory->addItem($item);
        } elseif ($item->getId() === Block::EMERALD) {
            $chestInventory->addItem($item);
        } elseif ($item->getId() === Block::GOLDEN_APPLE) {
            $chestInventory->addItem($item);
            }
        }
    }
}
