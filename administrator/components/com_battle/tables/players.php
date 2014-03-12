<?php

defined('_JEXEC') or die('Restricted access');

class TablePlayers extends JTable
{
	var $id = null;
	var $name = null;
	var $id_weapon = null;
	var $money = null;	
	var $attack= null;	
	var $defence = null;
	var $nbrattacks = null;		
	var $nbrkills = null;

	var $health = null;	  
	var $level = null;     
	var $xp = null;   
	var $intelligence = null;    
	var $strength = null;      
	var $dexterity = null;    

	var $grid = null;	
	var $posx = null;	
	var $posy = null;	
	var $published = null;

	var $image = null;	
	var $coffre = null;	

	var $discretion = null;
	var $rapidte = null;
	var $visibilite = null;


	var $equipe = null;
	var $idvoiture = null;
	var $reservoir = null;	

	var $munition = null;	
	var $actif = null;
	var $tempsplanque = null;	
	var $tempsmove = null;
	var $ip = null;	
	var $comment = null;	
	var $banque = null;	
	var $casier = null;	
	var $mort = null;
	var $parrainage = null;
	var $stupefiant = null;
	var $volevoiture = null;	
	var $volearme = null;	
	var $voleargent = null;

 	

	function __construct(&$db)
	{
		parent::__construct( '#__jigs_players', 'id', $db );
	}
}