
[
<?php
	$st = false;
	
	foreach($sms_preferences as $c){
		if($st) echo ",";
		CreateNode2($c, array());
		$st = true;
	}
	
	function CreateNode2($node, $sel){
		echo "{\n";
		echo "\tid:'" . $node['id'] . "',\n";
		echo "\ttext:'" . $node['name'] . "',\n";
		//echo "\ticonCls:'icon-settings',\n";

		echo "\tqtip:'<b>URL</b><br>" . $node['name']  . "',\n";
		if(count($node['children']) > 0){
			echo "\texpanded: true,\n";
			echo "\tchildren:[\n";
			$started = false;
			foreach($node['children'] as $cnode){
				if($started) echo ",\n";
				CreateNode2($cnode, $sel);
				$started = true;
			}
			echo "\t]\n";
		} else {
			echo "\tleaf: true,\n";
			echo "\tchecked: " . (($node['is_selected'] == 1)? "true\n": "false\n");
		}
		echo "}\n";
	}
?>
]