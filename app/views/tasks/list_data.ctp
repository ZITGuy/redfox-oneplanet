
[
<?php
	$st = false;
	foreach($tasks as $c){
		if($st) echo ",";
		CreateNode($c);
		$st = true;
	}
	
	function CreateNode($node) {
		echo "{\n";
		echo "id:'" . $node['id'] . "',\n";
		echo "name:'" . $node['name'] . "',\n";
		echo "controller:'" . $node['controller'] . "',\n";
		echo "action:'" . $node['action'] . "',\n";
		echo "iconCls:'" . $node['iconcls'] . "',\n";
		echo "list_order:" . $node['list_order'] . ",\n";
		echo "built_in:'" . $node['built_in'] . "',\n";
		if(count($node['children']) > 0) {
			echo ( $node['name'] == 'Home')? "expanded: true,\n":  "expanded: false,\n";
			echo "children:[\n";
			$started = false;
			foreach($node['children'] as $cnode){
				if($started) echo ",\n";
				CreateNode($cnode);
				$started = true;
			}
			echo "],\n";
		} else {
			echo "leaf: true\n";
		}
		echo "}\n";
	}
?>
]