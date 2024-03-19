<?php

namespace sse\sseCPS;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\event\player\PlayerQuitEvent;

class sseCPS extends PluginBase implements Listener{
	private $attack = [];

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onQuit(PlayerQuitEvent $ev){
		$player = $ev->getPlayer();
		$name = $player->getName();
		if(isset($this->attack[$name]))
			unset($this->attack[$name]);
	}
	
public function onHit(EntityDamageEvent $ev){
	$entity = $ev->getEntity();
	if($entity instanceof Player){
		if($ev instanceof EntityDamageByEntityEvent){
			$player = $ev->getDamager();
			if($player instanceof Player){
				$name = $player->getName();
				if(!isset($this->attack[$name])){
					$this->attack[$name] = $this->makeTimestamp();
				} else if($this->makeTimestamp() - $this->attack[$name] < 0.005){
					$player->sendPopup("§l§6[§fCPS제한§6]§r§f 아직 때릴 수 없습니다.");
					$ev->setCancelled();
					return false;
				}
				$this->attack[$name] = $this->makeTimestamp();
			}
		}
	}
}
	public function makeTimestamp() {
		$date = date ( "Y-m-d H:i:s" );
		$yy = substr ( $date, 0, 4 );
		$mm = substr ( $date, 5, 2 );
		$dd = substr ( $date, 8, 2 );
		$hh = substr ( $date, 11, 2 );
		$ii = substr ( $date, 14, 2 );
		$ss = substr ( $date, 17, 2 );
		return mktime ( $hh, $ii, $ss, $mm, $dd, $yy );
	}
}
