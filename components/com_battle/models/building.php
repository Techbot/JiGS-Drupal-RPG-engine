<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');
class BattleModelBuilding extends JModel
{

////////////////////////////////////////////////////////////////////////////////////////////////////////////

//      Work Methods

//////////////////////////////////////////////////////////////////////////////////////////////////////////

	///// CREATE NEW GENERATOR FROM A BUILDING /////
	function work_turbine()
	{
		$building_id		= JRequest::getvar(building_id);
		$line				= JRequest::getvar(line);
		$type				= JRequest::getvar(type);
		$quantity			= JRequest::getvar(quantity);
		$now				= time();
		$db					=  JFactory::getDBO();
		$q					= "INSERT INTO #__jigs_generators (building, quantity, timestamp) VALUES ($building_id,$quantity,$now ) ON DUPLICATE KEY UPDATE quantity = $quantity , timestamp= $now";
		$db->setQuery($q);
		$result				= $db->query();
		return $result;
	}

	function work_conveyer()
	{
<<<<<<< HEAD
		$now			= time();
		$db				= & JFactory::getDBO();
		$sql			= "SELECT * FROM #__jigs_objects WHERE id = " . $type;
		$db->setQuery($sql);
		$product		= $db->loadObject();
		$user			=& JFactory::getUser();
		$name			= $product->name;
		$description	= $product->description;
		$level			= $product->level;
		$man_time		= $product->man_time;
		$metal_1		= $product->metal_1;
		$quantity_1		= $product->quantity_1;
		$metal_2		= $product->metal_2;
		$quantity_2		= $product->quantity_2;

		
		
		$sql= "SELECT quantity FROM #__jigs_metals WHERE item_id = " . $metal_1 . " AND player_id = " . $user->id ;
		$db->setQuery($sql);
		$player_qty_1			= $db->loadResult();
		
		$sql= "SELECT quantity FROM #__jigs_metals WHERE item_id = " . $metal_2 . " AND player_id = " . $user->id ;
		$db->setQuery($sql);
		$player_qty_2			= $db->loadResult();		
		
		
	 	If ($player_qty_1 >= $quantity_1 && $player_qty_2 >= $quantity_2)
	 	{
	 	$player_qty_1	= $player_qty_1 - $quantity_1;
	 	$player_qty_2	= $player_qty_2 - $quantity_2;
	 	
	 	$finished		= $now + ($quantity * $man_time * 60 );
	 	$finished		= $now + (1 * 1 * 60);
	 	$sql			= "INSERT INTO `#__jigs_factories` (`building`,`line`,`type`, `quantity`,`timestamp`,`finished`) VALUES ($building_id, $line, $type, $quantity, $now, $finished ) ON DUPLICATE KEY UPDATE `type` =  $type , `quantity` = $quantity , `timestamp`= $now ,`finished` = $finished";
	 	$db->setQuery($sql);
	 	
	 	If (!$db->query()){

	 		echo Json_encode($sql);
	 		return;
	 	};
	 	
	 	$sql			= "UPDATE `#__jigs_metals` SET quantity = $player_qty_1 WHERE `item_id` = " . $metal_1 . " AND player_id =". $user->id;
	 	$db->setQuery($sql);
	 	If (!$db->query()){

	 		echo Json_encode($sql);
	 		return;
	 	};
	 	
	 	
	 	
	 	
	 	$sql			= "UPDATE `#__jigs_metals` SET quantity = $player_qty_2 WHERE item_id = " . $metal_2 . " AND player_id =". $user->id;;
	 	$db->setQuery($sql);
	 	If (!$db->query()){

	 		echo Json_encode($sql);
	 		return;
	 	}; 	
	 }
	 
	 else{
	 	
	 	$message="not enough metals";
	 	echo Json_encode($message);
	 	
	 }
	 
	return true;
=======
		$energy_unit		= 1;
		$building_id		= JRequest::getvar('building_id');
		$line				= JRequest::getvar('line');
		$type				= JRequest::getvar('type');
		$quantity			= JRequest::getint('quantity');
		$energy_units		= $energy_unit * $quantity;
		$now				= time();
		$db					= JFactory::getDBO();
		$sql				= "SELECT * FROM #__jigs_objects WHERE id = " . $type;
		$db->setQuery($sql);
		$product			= $db->loadObject();
		$user				= JFactory::getUser();
		$name				= $product->name;
		$description		= $product->description;
		$level				= $product->level;
		$man_time			= $product->man_time;
		$metal_1			= $product->metal_1;
		$quantity_1			= $product->quantity_1;
		$metal_2			= $product->metal_2;
		$quantity_2			= $product->quantity_2;
		$sql				= "SELECT quantity FROM #__jigs_metals WHERE item_id = " . $metal_1 . " AND player_id = " . $user->id ;
		$resource 			= $db->setQuery($sql);
		$player_qty_1		= $db->loadResult();
		$sql				= "SELECT quantity FROM #__jigs_metals WHERE item_id = " . $metal_2 . " AND player_id = " . $user->id ;
		$resource 			= $db->setQuery($sql);
		$player_qty_2		= $db->loadResult();		
		$model				= JModel::getInstance('jigs','BattleModel');
		$energy_required	= $quantity * 1;	 	
		if (!$model->use_battery($building_id, $energy_required))
		{
			return false;
		}

		if ($player_qty_1 >= $quantity_1 && $player_qty_2 >= $quantity_2)
		{
			$player_qty_1	= $player_qty_1 - $quantity_1;
			$player_qty_2	= $player_qty_2 - $quantity_2;
			$finished		= $now + ($quantity * $man_time * 60 );
			$finished		= $now + (1 * 1 * 60);
			$sql			= "INSERT INTO #__jigs_factories (building,line,type, quantity,timestamp,finished) VALUES ($building_id, $line, $type, $quantity, $now, $finished ) 
				ON DUPLICATE KEY UPDATE type =  $type , quantity = $quantity , timestamp= $now ,finished = $finished";
			$db->setQuery($sql);
			if (!$db->query())
			{
				echo Json_encode($sql);
				return false;
			};
			$sql			= "UPDATE #__jigs_metals SET quantity = $player_qty_1 WHERE item_id = " . $metal_1 . " AND player_id =". $user->id;
			$db->setQuery($sql);
			if (!$db->query())
			{
				echo Json_encode($sql);
				return false;
			};
			$sql			= "UPDATE #__jigs_metals SET quantity = $player_qty_2 WHERE item_id = " . $metal_2 . " AND player_id =". $user->id;;
			$db->setQuery($sql);
			if (!$db->query())
			{
				echo Json_encode($sql);
				return false;
			}; 	
		}

		else{

			$message="not enough metals";
			echo Json_encode($message);
			return false;
		}

		return true;
>>>>>>> upstream/master
	}
	
/////////////////////////////////////////////////////////////	

	function work_reprocessor($building_id, $quantity, $type, $line)
	{
		$now			    = time();
		$db			        = JFactory::getDBO();
		$sql			    = "SELECT * FROM #__jigs_objects WHERE id = " . $type;
		$db->setQuery($sql);
		$product		    = $db->loadObject();
		$user			    = JFactory::getUser();
		$name			    = $product->name;
		$description		= $product->description;
		$level			    = $product->level;
		$man_time		    = $product->man_time;
		$metal_1		    = $product->metal_1;
		$quantity_1		    = $quantity * $product->quantity_1;
		$metal_2		    = $product->metal_2;
		$quantity_2		    = $quantity * $product->quantity_2;

		$query			    = "SELECT id FROM #_jigs_inventory WHERE item_id = $type player_id = " . $user->id ;
		$resource 		    = $db->setQuery($sql);
		$player_items		= $db->loadAssocList();		
		$player_items_count = count($player_items);

	/*	$sql			= "SELECT quantity FROM #__jigs_metals WHERE item_id = " . $metal_1 . " AND player_id = " . $user->id ;
		$resource 		= $db->setQuery($sql);
		$player_qty_1		= $db->loadResult();

		$sql			= "SELECT quantity FROM #__jigs_metals WHERE item_id = " . $metal_2 . " AND player_id = " . $user->id ;
		$resource 		= $db->setQuery($sql);
		$player_qty_2		= $db->loadResult();		
	 */	
		$model			    = JModel::getInstance('jigs','BattleModel');

		$energy_required	= $quantity * 1;	 	

		if (!$model->use_battery($building_id, $energy_required))
		{
			return false;
		}

		if ($player_items_count >=  $quantity)
		{
			$finished	= $now + ($quantity * $man_time * 60 );
			$finished	= $now + (1 * 1 * 60);
			$sql		= "INSERT INTO #__jigs_reprocessors (building, type_name, type_quantity, line, metal1, metal2, quantity_1, quantity_2, timestamp, finished) VALUES ($building_id, '$name',$quantity, $line, $metal_1,$metal_2, $quantity_1,$quantity_2, $now, $finished ) ON DUPLICATE KEY UPDATE metal1 = $metal_1, metal2 = $metal_2, quantity_1 =$quantity_1, quantity_2 = $quantity_1 ,timestamp = $now, finished = $finished";
			$db->setQuery($sql);

			if (!$db->query()){

				echo Json_encode($sql);
				return false;
			};

			$sql		= "DELETE FROM #__jigs_inventory WHERE item_id = $type AND player_id = $user->id LIMIT $quantity";
			$db->setQuery($sql);
			if (!$db->query()){

				echo Json_encode($sql);
				return false;
			}; 	
		}
		else
		{
			$message="not enough items";
			echo Json_encode($message);
			return false;
		}
		return true;
	}
/////////////////////////////////////////////////////////////////////////
	function work_field()
	{
		$db				= JFactory::getDBO();
		$user			= JFactory::getUser();
		$field			= JRequest::getvar('field');
		$building_id	= JRequest::getvar('building_id');
		$now			= time();
		$finished		= $now + 50;

		//$crop	= JRequest::getvar('crop');
		$query			= "SELECT status,crop FROM #__jigs_farms  WHERE building =" . $building_id . " AND field =" . $field;
		$db->setQuery($query);
		$result			= $db->loadRowList();

		if (isset ($result[0][0]))
		{ 
		    $status			= $result[0][0];
		}
		else
		{
		    $status			= 0;
		}   
		    
		if (isset ($result[0][1]))
		{ 
		    $crop			= $result[0][1];
		}
		else
		{
		    $crop			= 0;
		} 		    
	
		$status++;

		$sql			= "INSERT INTO #__jigs_farms (building,field, status,timestamp, crop,finished ) values  ($building_id,$field, $status,$now , $crop, $finished) 
			ON DUPLICATE KEY UPDATE status =  $status ,timestamp = $now,  crop = $crop , finished = $finished ";

		$db->setQuery($sql);
		if(!$db->query())
		{
			$message	="error";
			return $query;
		}
		else
		{
			return true;
		}
	}
///////////////////////////////////////////////////////

	function get_avatar($resident)
	{
		$db		= JFactory::getDBO();
		$db->setQuery("SELECT #__comprofiler.avatar
			FROM #__comprofiler 
			WHERE #__comprofiler.user_id = ". $resident);
		$result		= $db->loadResult();
		return $result;
	}

	function work_flat()
	{
		$db				= JFactory::getDBO();
		$user			= JFactory::getUser();
		$flat			= JRequest::getvar('flat');
		$building_id	= JRequest::getvar('building_id');
		$query			= "SELECT status,resident FROM #__jigs_flats  WHERE building = $building_id AND flat = $flat ";
		$db->setQuery($query);
		$result			= $db->loadAssoc();		
		$status			= $result['status'];
		$resident		= $result['resident'];
		$now			= time();
		$date			= date('jS-m-y :  h:i',$now);	

		//////////// if place is currently occupied
		if ($resident == 0)
		{
			$query		= "SELECT bank FROM #__jigs_players WHERE iduser = ". $user->id;
			$db->setQuery($query);
			$bank       = $db->loadResult();
			if ($bank >= 1000)
			{
				$bank			= $bank - 1000;
				$resident		= $user->id;
				$status			= 1; // occupied

				$avatar			= $this->get_avatar($user->id);
				$query			= "UPDATE #__jigs_players set bank = $bank WHERE iduser = " . $user->id;
				$db->setQuery($query);
				$db->query();
			}
			else
			{		
				$result[0]= 'broke';
				return $result;
			}	

		}
		elseif ($resident == $user->id)// if user click they are releasing contract
		{
			$resident	= 0;
			$now		= 0;
			$status		= 0 ; //empty
			$avatar		= 'gallery/black.gif';
		}

		$x = "INSERT INTO #__jigs_flats (building,flat, status, resident,timestamp) values ($building_id, $flat, $status, $resident, $now)
			ON DUPLICATE KEY UPDATE status = $status , resident = $resident , timestamp = $now ;" ;
		$db->setQuery($x);

		if(!$db->query())
		{
			return $x ;
		}
		else
		{
			$pre_result = "<img src='" .  JURI::base()  . "images/comprofiler/" . $avatar . "' height='20px' width='20px' >" ;
			$result[0]	= 'message_' . $flat;
			$result[1]	= $this->get_message($resident);
			$result[2]	= 'avatar_' . $flat;
			$result[3]	= $pre_result ;
			$result[4]	= $flat;

			// Lisa
			if($status == "0")  
			{  
				$status_word		= "Vacant"; 
				$status_tooltip		= "Rent";	  
			}  
			else 
			{  
				$status_word		= "Occupied";  
				$status_tooltip		= "Vacate";	  
			} 

			$result[5]	= "<h4><a href='#' title='Click Here to " . $status_tooltip . "'>" . $status_word . "</a></h4>" ;
			$result[6]	= 'timer_' . $flat;
			$result[7]	=  $date;

			//  echo Json_encode ($y);
			return $result;
		}
	}
	
	
	function work_mine()
	{
		$db				= JFactory::getDBO();
		$user			= JFactory::getUser();
		$type			= JRequest::getvar('type');
		$building_id	= JRequest::getvar('building_id');
		$now			= time();
		$query			= "INSERT INTO #__jigs_mines (building, type, timestamp ) values  ($building_id,$type,$now) ON DUPLICATE KEY	UPDATE type =  $type , timestamp = " . $now;
		$db->setQuery($query);
		$db->query();
		$result[0]		= $type;
		//$result[1]	= $now;
		$result[1]		= $query;
		return $result;
	}
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////


//    Check Methods


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function check_mine()
	{
		$now					= time();
		$building_id			= JRequest::getvar('building_id');
		$user					= JFactory::getUser();
		$db						= JFactory::getDBO();
		$query					= "SELECT timestamp FROM #__jigs_mines WHERE building = $building_id";
		$db->setQuery($query);
		$result['timestamp']	= $db->loadResult();
		$result['now']			= date('l jS \of F Y h:i:s A',$now);
		$result['since']		= date('l jS \of F Y h:i:s A',$result['timestamp']);
		$result['elapsed']		= (int)(($now-$result['timestamp']));
		$result['remaining']	= (int)(50-((($now-$result['timestamp']))));
		return $result;
	}

	function check_farm()
	{
		$building_id			= JRequest::getvar('building');
		$field_id				= JRequest::getvar('field');
		//$user =& JFactory::getUser();
		$now					= time();
		$db						= JFactory::getDBO();
		$query					= "SELECT status,timestamp,finished 
			FROM #__jigs_farms 
			WHERE building = $building_id 
			AND field = $field_id";
		$db->setQuery($query);
		$result					= $db->loadAssoc();
		$result['now']			= date('l jS \of F Y h:i:s A',$now);
		$result['since']		= date('l jS \of F Y h:i:s A',$result['timestamp']);
		$result['elapsed']		= (int)(($now-$result['timestamp']));
		$result['remaining']	= (int)($result['finished'] - $now );
		$result['status']		= (int)($result['status']);
		$result['field']		= $field_id;

		return $result;
	}

	function check_reprocessor($building_id,$line_id)
	{
		$building_id			= JRequest::getvar('building');
		$line_id				= JRequest::getvar('line');
		//$user =& JFactory::getUser();
		$now					= time();
		$db						= JFactory::getDBO();
		$query					= "SELECT timestamp,finished FROM #__jigs_reprocessors WHERE building = $building_id AND line = $line_id";
		$db->setQuery($query);
		$result					= $db->loadAssoc();
		$result['now']			= date('l jS \of F Y h:i:s A',$now);
		$result['since']		= date('l jS \of F Y h:i:s A',$result['timestamp']);
		$result['elapsed']		= (int)(($now-$result['timestamp']));
		$result['remaining']	= (int)($result['finished'] - $now );
		return $result;
	}

	function check_factory()
	{
		$building_id			= JRequest::getvar('building');
		$line_id				= JRequest::getvar('line');
		//$user =& JFactory::getUser();
		$now					= time();
		$db						= JFactory::getDBO();
		$query					= "SELECT timestamp,finished FROM #__jigs_factories WHERE building = $building_id AND line = $line_id";
		$db->setQuery($query);
		$result					= $db->loadAssoc();
		$result['now']			= date('l jS \of F Y h:i:s A',$now);
		$result['since']		= date('l jS \of F Y h:i:s A',$result['timestamp']);
		$result['elapsed']		= (int)(($now-$result['timestamp']));
		$result['remaining']	= (int)($result['finished'] - $now );
		return $result;
	}
	                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
//////////////////////////////////////////////////////////////////////////////////////////////////////




//Get Methods




///////////////////////////////////////////////////////////////////////////////////////////////

	function get_drills($building_id)
	{
		$user		= JFactory::getUser();
		//	$result = array() ;
		$now		= time();
		$db			= JFactory::getDBO();
		$query		="SELECT * FROM #__jigs_mines WHERE building =" . $building_id;
		$db->setQuery($query);
		$result		= $db->loadAssoc();
		$payment	= 100;
		$type		= $result['type'];
		$name		= $user->id;
		if (($result['timestamp'] > 0) && ($now-$result['timestamp'] > 50))
		{
			$query	= "UPDATE #__jigs_mines SET timestamp = 0 WHERE building = " . $building_id;
			$db->setQuery($query);
			$db->query();
			$result[timestamp] = 0;
			if ($type==1)
			{
				$type_crystal	= rand( 1 , 30 ) ;
				$query		= "INSERT INTO #__jigs_crystals (player_id , item_id, quantity )
					VALUES($name ,$type_crystal, 1)
					ON DUPLICATE KEY 
					UPDATE quantity = quantity + 1";
				$db->setQuery($query);
				$db->query();
			}
			if ($type==2)
			{
				$type_metal	= rand( 1 , 60 ) ; //30 limit see next line
				if ($type_metal >30)
				{
					$type_metal 	= 1; // this is to increase the chances that carbon will be excavated.
				};
				$query		= "INSERT INTO #__jigs_metals (player_id , item_id, quantity )
					VALUES( $name ,$type_metal, 1) 
					ON DUPLICATE KEY 
					UPDATE quantity = quantity + 1";
				$db->setQuery($query);
				$db->query();
			}
			else
			{
				$query_1		= "SELECT money FROM #__jigs_players WHERE iduser = '$user->id'";
				$db->setQuery($query_1);
				$money_saved	= $db->loadResult();
				$money			= $money_saved + $payment;
				$x				=	"Update #__jigs_players SET money = $money WHERE iduser= " . $user->id;
				$db->setQuery($x);
				$db->query();
			}
		}
		//exit();
		$result['now']		= date('l jS \of F Y h:i:s A',$now);
		$result['since']	= date('l jS \of F Y h:i:s A',$result['timestamp']);
		$result['elapsed']	= (int)(($now-$result['timestamp']));
		$result['remaining']= (int)(50-((($now-$result['timestamp']))));
		return $result;
	}
	
	
	//////////////////////////////////////////////////////////////////////////////////////
	
	
	
	
	function get_crops()
	{
		$total_crop	= 0;
		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$query		= "SELECT total FROM #__jigs_farms LEFT JOIN #__jigs_buildings ON #__jigs_farms.building = #__jigs_buildings.id	WHERE #__jigs_buildings.owner = $user->id;";
		$db->setQuery($query);
		$result		= $db->loadResultArray();
		foreach($result as $row)
		{
			$total_crop = $total_crop + $row;
		}
		return ($total_crop);
	}

	function get_fields($building_id)
	{
		$total_crop	= 0;
		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		
		for ($i=0 ;$i<=7;$i++){
		$fields->status_field[$i] = 0 ;
		}
		
		$query		= "SELECT * FROM #__jigs_farms WHERE building = $building_id";
		$db->setQuery($query);
		$result		= $db->loadObjectList();
		$i			= 1;

		foreach($result as $row)
		{
			$fields->status_field[$i] = $row->status;
			$i++	;	
		}

		return ($fields);
	}








	function old_sell_crops()
	{
		$crops		= $this->get_crops();
		$payment	= $crops * 1000 ;
		$db		= JFactory::getDBO();
		$user		= JFactory::getUser();
		$query_1	="SELECT money FROM #__jigs_players WHERE iduser = '$user->id'";
		$db->setQuery($query_1);
		$money_saved	= $db->loadResult();
		$money		= $money_saved + $payment;
		$x		= "UPDATE #__jigs_players SET money = $money WHERE iduser= " . $user->id;
		$db->setQuery($x);
		$db->query();
		$x		= "UPDATE #__jigs_farms
			LEFT JOIN #__jigs_buildings on #__jigs_farms.building = #__jigs_buildings.id
			SET crops = 0 WHERE #__jigs_buildings.owner = $user->id";
		$db->setQuery($x);
		$db->query();
		return($money_saved);
	}



	function get_flats($building_id)
	{
		$db	=& JFactory::getDBO();
		$query	= "SELECT * FROM #__jigs_flats WHERE building=" . $building_id;
		$db->setQuery($query);
		$result	= $db->loadAssocList();
		$i	= 0;

		foreach ($result as $row)
		{
			if ($result[$i]['timestamp'] !=0)
			{
				$now				= time();
				$leasetime			= 1 * 60 * 60 * 24 * 7; // one week
				$result[$i]['remaining']	= ($result[$i]['timestamp'] + $leasetime - $now)/(60*60*24);
				$result[$i]['remaining_days']	= intval(($result[$i]['timestamp']+$leasetime-$now)/(60*60*24));
				$result[$i]['remaining_hours']	= intval(($result[$i]['remaining'] - $result[$i]['remaining_days'])*24);
			}
			else
			{
				$leasetime			= 0;
				$result[$i]['remaining']	= 0;
				$result[$i]['remaining_days']	= 0;
				$result[$i]['remaining_hours']	= 0; 
			}
			$i++;
		}
		return $result;
	}



	function get_message($resident)
	{
		$player =& JFactory::getUser($resident);
		$flatlink="index.php?option=com_battle&view=room";
		$message=null;
		$message_1= "Apartment is vacant Click to Rent";
		$message_2= "Apartment is Owned by " . $player->username;
		$message_3= "Apartment is Owned by you. Click to <a href ='". $flatlink ."'>HERE</a> to Enter "; 	
		$user =& JFactory::getUser();
		if ($resident== 0)
		{
			$message=$message_1;
		}
		elseif($resident != $user->id)
		{
			$message=$message_2;
		}
		else
		{
			$message = $message_3;
		}
		return $message;
	}		


	function get_blueprint() {
		$db		= JFactory::getDBO();
		$user		= JFactory::getUser();
		$db->setQuery("SELECT #__jigs_blueprints.id, #__jigs_objects.name " .
			"FROM #__jigs_blueprints " .
			"LEFT JOIN #__jigs_objects " .
			"ON #__jigs_blueprints.object = #__jigs_objects.id " .
			"WHERE #__jigs_blueprints.user_id =".$user->id);
		$result		= $db->loadAssocList();
		return $result;

	}


	function get_blueprints()
	{
		$user		= JFactory::getUser();
		$db			= JFactory::getDBO();

		$query		= "SELECT * FROM #__jigs_blueprints
			LEFT JOIN #__jigs_objects
			ON #__jigs_blueprints.object = #__jigs_objects.id
			WHERE #__jigs_blueprints.user_id = $user->id ";
		$db->setQuery($query);
		$blueprints	= $db->loadObjectList();

		foreach($blueprints as $blueprint)
		{
			$user	= JFactory::getUser();
			$query	= "SELECT name FROM #__jigs_metal_names WHERE #__jigs_metal_names.id = $blueprint->metal_1 ";
			$db->setQuery($query);
			$blueprint->metal_1_name = $db->loadResult();
			$query 	= "SELECT name FROM #__jigs_metal_names WHERE #__jigs_metal_names.id = $blueprint->metal_2 ";
			$db->setQuery($query);
			$blueprint->metal_2_name = $db->loadResult();
		}	
		foreach($blueprints as $blueprint)
		{
			$query 	= "SELECT quantity FROM #__jigs_metals 
				WHERE #__jigs_metals.player_id = $user->id
				AND #__jigs_metals.item_id = $blueprint->metal_1 ";
			$db->setQuery($query);
			$blueprint->metal_1_stock = $db->loadResult();

			$query	= "SELECT quantity FROM #__jigs_metals 
				WHERE #__jigs_metals.player_id = $user->id
				AND #__jigs_metals.item_id = $blueprint->metal_2 ";
			$db->setQuery($query);
			$blueprint->metal_2_stock = $db->loadResult();
			return $blueprints ;
		}	
	}

	function get_objects_required($blueprints)
	{

		$db		= JFactory::getDBO();
		$user	= JFactory::getUser();
		
		if(isset($blueprints))
		{
		    foreach($blueprints as $blueprint)
		    {
			    $query	= "SELECT id FROM #__jigs_inventory 
				    WHERE #__jigs_inventory.player_id = $user->id
				    AND #__jigs_inventory.item_id = $blueprint->object ";
			    $db->setQuery($query);
			    $blueprint->object_total = count($db->loadObjectlist());
		    }
		}
		
		return $blueprints ;
	}	




	function get_gen_type($building){
		
		$db     = JFactory::getDBO();
		$user   = JFactory::getUser();
		$now    = time();
		$factor = 10;
		$query	= "SELECT *
			FROM    #__jigs_generators 
			WHERE   building = $building
			";

		$db->setQuery($query);
		$result = $db->loadAssoc();
		
		return $result;
		
		}

	function get_board_messages($id,$type)
	{

		$db			= JFactory::getDBO();
		$query		= "SELECT messages FROM #__jigs_buildings WHERE id =" . $id;
		$db->setQuery($query);
		$string		= $db->loadResult();
		$result		= array();
		$numbers	= array();
		$numbers	= explode( ',' ,  $string );

		foreach ($numbers as $message_id)
		{
			$db	= JFactory::getDBO();
			$query	= "SELECT string FROM #__jigs_messages
				WHERE id = $message_id ";
			$db->setQuery($query);
			if($db->loadResult())
			{
				$result[] = $db->loadResult();
			}
		}
		//	print_r($result);
		//	exit();
		return $result;
	}
	
	
	
	
	
		function get_battery_slots()
		{
			$db     	= JFactory::getDBO();
			$building	= JRequest::getvar('building_id');
			$now    	= time();
			$factor		= 10;

			$query		= "
			SELECT * 
			FROM #__jigs_batteries
			WHERE iduser = $building
			";

			$db->setQuery($query);
			$batteries = $db->loadAssocList();

		/*	foreach($batteries as $battery)
		{
			$id        = $battery['id'];
			$timestamp = $battery['timestamp'];
			$elapsed   = $now - $timestamp;
			$units     = $battery['units'];
			$max_units = $battery['max_units'];
			$new_units = intVal($elapsed/$factor);

			if($units + $new_units < $max_units)
			{
				$query	= "
					UPDATE #__jigs_batteries SET
					units      = units + $new_units,
					timestamp  = $now
					WHERE id   = $id
					";
			}
			else
			{
				$query	= "
					UPDATE #__jigs_batteries SET
					units      = max_units,
					timestamp  = $now
					WHERE id   = $id
					";
			}


			$db->setQuery($query);
			$db->query();
		
		
		}*/
		return $batteries;
	}

	
	
	
	
	function get_papers() {

		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$db->setQuery("SELECT #__jigs_papers.item_id, #__jigs_paper_names.name, #__jigs_papers.buy_price " .
			"FROM #__jigs_papers " .
			"LEFT JOIN #__jigs_paper_names " .
			"ON #__jigs_papers.item_id = #__jigs_paper_names.id " .
			"WHERE #__jigs_papers.player_id =".$user->id);
		$result		= $db->loadAssocList();
		return $result;

	}


	function get_shop_papers() {

		$db				= JFactory::getDBO();
		$user			= JFactory::getUser();
		$building_id	= JRequest::getvar('building_id');
		$db->setQuery("SELECT #__jigs_papers.item_id, " .
			"#__jigs_papers.sell_price, " . 
			"#__jigs_paper_names.name " .
			"FROM #__jigs_papers LEFT JOIN  #__jigs_paper_names ON #__jigs_papers.item_id = #__jigs_paper_names.id " .
			"WHERE #__jigs_papers.player_id =" . $building_id);
		$result			= $db->loadAssocList();
		return $result;

	}
	
	
		function get_my_blueprints_list() {
		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$db->setQuery("SELECT #__jigs_blueprints.id, #__jigs_objects.name " .
			"FROM #__jigs_blueprints " .
			"LEFT JOIN #__jigs_objects " .
			"ON #__jigs_blueprints.object = #__jigs_objects.id " .
			"WHERE #__jigs_blueprints.user_id =".$user->id);
		$result		= $db->loadAssocList();
		return $result;

	}
	
	
	
	
	
	
	
	
	
	

}// end of class