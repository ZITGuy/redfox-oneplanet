//<script>
<?php if ($ay === false) { ?>
    Ext.Msg.alert("Error", "There is no active academic year.");
<?php } else { ?>
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'edu_quarters', 'action' => 'index2', $ay['EduAcademicYear']['id'])); ?>",
            success: function(response, opts) {
                var parent_eduSections_data = response.responseText;

                eval(parent_eduSections_data);

                parentEduQuartersViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("Error",
                    "Cannot get the <?php echo $term_name; ?> Management form. Error code': " + response.status);
            }
        });

<?php } ?>