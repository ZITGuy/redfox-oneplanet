<h2 style="margin-left: 20px;">Dashboard</h2>
<hr style="margin-left: 20px;"/>
<div id="chart_students_per_class" style="margin-top:20px; margin-left:20px; width:500px; height:400px;"></div>

<script type="text/javascript">$(document).ready(function(){
        var male = [20, 36, 27, 40, 45];
        var female = [17, 25, 30, 29, 28];
        var ticks = ['Nursery', 'Lower KG', 'Upper KG', 'Grade 1', 'Grade 2'];
        
        plot2 = $.jqplot('chart_students_per_class', [male, female], {
            title: {
				text: 'Students Per Grade',   // title for the plot,
				show: true,
			},
			animate : true,
			seriesDefaults: {
                renderer:$.jqplot.BarRenderer,
                pointLabels: { show: true }
            },
			axesDefaults: {
				tickRenderer: $.jqplot.CanvasAxisTickRenderer
			},
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks,
					tickOptions: {
						angle: -30,
						fontSize: '10pt'
					}
                }
            },
            legend: {
                show: true,
                location: 'e',
                placement: 'outside'
            },
			series:[
				{label: 'Male'},
				{label: 'Female'}
			],
			cursor:{
				show: true
			}
        });
    });
</script>