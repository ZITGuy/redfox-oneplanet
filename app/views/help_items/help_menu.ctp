//<script>
[
<?php
    function CreateNode($node) {
        if(true) {
            echo "{\n";
            echo "\tid:'" . $node['id'] . '|||' . $node['content'] . "',\n";
            echo "\ttext:'" . $node['title'] . "',\n";
            echo "\tcontent:'" . $node['content'] . "',\n";
            echo "\tlist_order:'" . $node['list_order'] . "',\n";
            if(count($node['children']) > 0) {
                echo ( $node['title'] == 'RedFox Help')? "\texpanded: true, \n\ticonCls: 'icon-help',\n":  "\texpanded: false, \n\ticonCls: 'icon-report',\n";
                echo "\tchildren:[\n";
                foreach($node['children'] as $cnode){
                    CreateNode($cnode);
                }
                echo "]\n";
            } else {
				echo "\ticonCls: 'icon-activity',\n";
                echo "\tleaf: true\n";
            }
            echo "},\n";
        }
    }

    $st = false;
    foreach($help_items as $c){
        CreateNode($c);
        $st = true;
    }
?>
]