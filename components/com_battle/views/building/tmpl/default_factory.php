
<?php defined( '_JEXEC' ) or die( 'Restricted access' );
 
	$blueprints = $this->blueprints;
	$x = count($this->blueprints);
	$index = $x+1;
	$now = time();
	
/*
	function array_unshift_assoc(&$arr, $key, $val)
	{
	$arr = array_reverse($arr, true);
	$arr[$key] = $val;
	return  array_reverse($arr, true);
	}
*/	
	
	
	$javascript			= 'onchange="change();"';
	$directory			= '/images/banners';


if (isset ($this->blueprints)){


	$this->lists['blueprints']	=  JHTML::_('select.genericlist', $this->blueprints , 'blueprints',$javascript, 'id', 'name' );

}


?>

<div id="slider-id" class="liquid-slider">

			<div>
				<?php 
					echo $this->loadTemplate ('factory_conveyer_progress');
					
					if(isset($this->blueprints))
					{
						echo $this->loadTemplate ('factory_conveyer_1');
					}
					else
					{
						echo "<br>You need to buy some blueprints to activate the conveyers<br><br><br><br><br><br>";
					}
				?>
			</div>
			
			<div>
				<h2 class="title">C2</h2>
				<div id="meter">
					<img src="/components/com_battle/images/meter01.jpg"><img
						src="/components/com_battle/images/meter01.jpg"><img
						src="/components/com_battle/images/meter01.jpg"><img
						src="/components/com_battle/images/meter01.jpg"><img
						src="/components/com_battle/images/meter01.jpg">
				</div>
				<!--end of meter-->
				<p>These conveyors will be enbled as the player's experience and
					wealth increase.</p>
			</div>

			<div>
				<h2 class="title">C3</h2>
				<ul>
					<li>Number of Employees: 7</li>
					<li>Employees Morale: 70%</li>
					<li>Employee Efficiency: 95%</li>
				</ul>
				<p>These conveyors will be enbled as the player's experience and
					wealth increase.</p>
			</div>

			<div>
				<h2 class="title">C4</h2>
				<img
					src="<?php echo $this->baseurl ?>/components/com_battle/includes/img4.jpg"
					alt="Photo" />
				<p>These conveyors will be enbled as the player's experience and
					wealth increase.</p>
			</div>

			<div>
				<h2 class="title">C5</h2>
				<img
					src="<?php echo $this->baseurl ?>/components/com_battle/includes/img5.jpg"
					alt="Photo" />
				<p>These conveyors will be enbled as the player's experience and
					wealth increase.</p>
			</div>

			<div>
				<h2 class="title">C6</h2>
				<img
					src="<?php echo $this->baseurl ?>/components/com_battle/includes/img6.jpg"
					alt="Photo" />
				<p>These conveyors will be enbled as the player's experience and
					wealth increase.</p>
			</div>

			<div>
				<h2 class="title">C7</h2>
				<img
					src="<?php echo $this->baseurl ?>/components/com_battle/includes/img7.jpg"
					alt="Photo" />
				<p>These conveyors will be enbled as the player's experience and
					wealth increase.</p>
			</div>

			<div>
				<h2 class="title">C8</h2>
				<img
					src="<?php echo $this->baseurl ?>/components/com_battle/includes/img8.jpg"
					alt="Photo" />
				<p>These conveyors will be enbled as the player's experience and
					wealth increase.</p>
			</div>

</div>

		
