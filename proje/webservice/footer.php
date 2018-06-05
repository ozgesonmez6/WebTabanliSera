</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Morris Charts JavaScript -->
<script src="js/plugins/morris/raphael.min.js"></script>
<script src="js/plugins/morris/morris.min.js"></script>
<script src="js/plugins/morris/morris-data.js"></script>

<!-- Flot Charts JavaScript -->
<!--[if lte IE 8]><script src="js/excanvas.min.js"></script><![endif]-->
<script src="js/plugins/flot/jquery.flot.js"></script>
<script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="js/plugins/flot/jquery.flot.resize.js"></script>
<script src="js/plugins/flot/jquery.flot.pie.js"></script>
<script src="js/plugins/flot/flot-data.js"></script>

</body>

<script>
    new Morris.Line({
        // ID of the element in which to draw the chart.
        element: 'device-data-chart',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: <?php echo json_encode($data); ?>,
        // The name of the data record attribute that contains x-values.
        xkey: 'datetime',
        // A list of names of data record attributes that contain y-values.
        ykeys: ['data'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['veri'],

        xLabels: ['second']
    });
</script>

</html>
