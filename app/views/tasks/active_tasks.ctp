//<script>
<?php
if($this->Session->check('PermittedTasks')) {
    $tasks = $this->Session->read('PermittedTasks');
?>
[
<?php
	function CreateNode($node) {
            if((count($node['children']) > 0 && $node['controller'] == '#') || $node['controller'] != '#') {
		echo "{\n";
		echo "\tid:'" . $node['id'] . '|' . $node['controller'] . "/" . $node['action'] . "',\n";
		echo "\ttext:'" . $node['name'] . "',\n";
		echo "\tcontroller:'" . $node['controller'] . "',\n";
		echo "\taction:'" . $node['action'] . "',\n";
		echo "\ticonCls:'" . $node['iconcls'] . "',\n";
		echo "\tlist_order:'" . $node['list_order'] . "',\n";
		echo "\tbuilt_in:'" . $node['built_in'] . "',\n";
		if(count($node['children']) > 0) {
			echo ( $node['name'] == 'Home')? "\texpanded: true,\n":  "\texpanded: false,\n";
			echo "\tdraggable: false,\n";
			echo "\tchildren:[\n";
			$started = false;
			foreach($node['children'] as $cnode){
				//if($started) echo ",\n";
				CreateNode($cnode);
				$started = true;
			}
			echo "]\n";
		} else {
			echo "\tdraggable: true,\n";
			echo "\tleaf: true\n";
		}
		echo "},\n";
            }
	}
	
	$st = false;
	foreach($tasks as $c){
		//if($st) echo ",";
		CreateNode($c);
		$st = true;
	}
?>
]
<?php 
}
?>