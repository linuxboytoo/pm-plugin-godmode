<?php
/*
__PocketMine Plugin__
name=GodMode
version=0.0.1
description=Plugin to make a user nearly invincible
author=linuxboytoo
class=GodMode
apiversion=10
*/


class GodMode implements Plugin{
        private $api, $sessions, $path, $config;
        public function __construct(ServerAPI $api, $server = false){
                $this->api = $api;
                $this->sessions = array();
        }

        public function init(){
		$this->config['configpath'] = $this->path."plugins/GodMode/";
		$this->config['statusfile'] = "user.txt";
		$this->config['statuspath'] = $this->config['configpath'].$this->config['statusfile'];
  
  		if(!file_exists($this->config['configpath'])) { mkdir($this->config['configpath'],755,true); }
		if(!file_exists($this->config['statuspath'])) { $users = array(); file_put_contents($this->config['statuspath'],json_encode($users)); }

		$this->api->addHandler("entity.health.change", array($this, "healthChange"));
		$this->api->console->register("godmode", "God Mode", array($this, "godMode"));
	}

        public function __destruct(){

        }

	public function loadUsers()		{ return json_decode(file_get_contents($this->config['statuspath']),true); }
	public function saveUsers($users)	{ file_put_contents($this->config['statuspath'],json_encode($users)); }

	public function healthChange($data){
		$users = $this->loadUsers();
		if($users[$data['entity']->player->username]==1) { return false; }
		
		return true; 
	}

	public function godMode($cmd, $params, $issuer, $alias){
		$username = $issuer->username;

		$users = $this->loadUsers();

		if($users[$username]==1) 	{ $users[$username] = 0; 	$output = "God Mode Disabled"; 	}
		else 				{ $users[$username] = 1; 	$output = "God Mode Enabled";	}

		$this->saveUsers($users);
		return $output;
	}

	public function debug($data){
		console('test');
		console(var_dump($data,1));
	}
}
