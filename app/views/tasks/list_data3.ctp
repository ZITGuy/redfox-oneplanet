
[
<?php
	$st = false;
	
	foreach($tasks as $c){
		if($st) echo ",";
		CreateNode($c, $selected_tasks);
		$st = true;
	}
	
	function CreateNode($node, $selected_tasks){
		echo "{\n";
		echo "\tid:'" . $node['id'] . "',\n";
		echo "\ttext:'" . $node['name'] . "',\n";
		echo "\ticonCls:'" . $node['iconcls'] . "',\n";
		echo "\tqtip:'<b>URL</b><br>" . $node['controller'] . '/' . $node['action']  . "',\n";
		if(count($node['children']) > 0){
			echo "children:[\n";
			$started = false;
			foreach($node['children'] as $cnode){
				if($started) echo ",\n";
				CreateNode($cnode, $selected_tasks);
				$started = true;
			}
			echo "],\n";
		} else {
			echo "leaf: true,\n";
			echo in_array($node['id'], $selected_tasks)? "checked: true": "checked: false\n";
		}
		echo "}\n";
	}
?>
]