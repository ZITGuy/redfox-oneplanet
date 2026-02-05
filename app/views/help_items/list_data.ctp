
[
<?php
	$st = false;
	foreach($help_items as $c){
		if($st) echo ",";
		CreateNode($c);
		$st = true;
	}
	
	function CreateNode($node) {
		echo "{\n";
		echo "\tid:'" . $node['id'] . "',\n";
		echo "\ttitle:'" . $node['title'] . "',\n";
		echo "\tcontent:'" . $node['content'] . "',\n";
		echo "\tversion:'" . $node['version'] . "',\n";
		echo "\tlist_order:'" . $node['list_order'] . "',\n";
		if(count($node['children']) > 0) {
			echo ( $node['title'] == 'RedFox Help')? "\texpanded: true,\n":  "\texpanded: false,\n";
			echo "\tchildren:[\n";
			$started = false;
			foreach($node['children'] as $cnode){
				if($started) echo ",\n";
				CreateNode($cnode);
				$started = true;
			}
			echo "],\n";
		} else {
			echo "\tleaf: true\n";
		}
		echo "}\n";
	}
?>
]