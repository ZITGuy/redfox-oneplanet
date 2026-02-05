
[
<?php
	$st = false;
	foreach($acct_categories as $c){
		if($st) echo ",";
		CreateNode($c);
		$st = true;
	}
	
	function CreateNode($node){
		echo "{\n";
		echo "id:'" . $node['id'] . "',\n";
		echo "name:'" . $node['name'] . "',\n";
		echo "normal_side:'" . $node['normal_side'] . "',\n";
		echo "prefix:'" . $node['prefix'] . "',\n";
                echo "code:'" . $node['code'] . "',\n";
                echo "postfix:'" . $node['postfix'] . "',\n";
                echo "last_code:'" . $node['last_code'] . "',\n";
		if(count($node['children']) > 0){
			echo "expanded: true,\n";
			echo "children:[\n";
			$started = false;
			foreach($node['children'] as $cnode){
				if($started) echo ",\n";
				CreateNode($cnode);
				$started = true;
			}
			echo "],\n";
		} else {
			echo "leaf:true\n";
		}
		echo "}\n";
	}
?>
]