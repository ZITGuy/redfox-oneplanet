
[
<?php
	$st = false;
	foreach($acct_accounts as $c){
		if($st) echo ",";
		CreateNode($c);
		$st = true;
	}
	
	function CreateNode($node){
		echo "{\n";
		echo "id:'" . $node['id'] . "',\n";
		echo "name:'" . $node['name'] . "',\n";
		echo "code:'" . $node['code'] . "',\n";
		echo "balance:'" . $node['balance'] . "',\n";
                echo "acct_category:'" . $node['acct_category'] . "',\n";
                echo "created_by:'" . $node['created_by'] . "',\n";
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