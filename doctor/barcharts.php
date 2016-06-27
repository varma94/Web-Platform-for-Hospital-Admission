<!DOCTYPE html>

<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
        <title>Health Service</title>
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:600italic,400,800,700,300' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=BenchNine:300,400,700' rel='stylesheet' type='text/css'>
        <script src="js/modernizr.js"></script>
        <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

</head>
<body>

        <!-- ====================================================
        header section -->
<header class="top-header">
                <div class="container">
                        <div class="row">
                                <div class="col-xs-5 header-logo">
                                        <br>
                                        <a href="index.html"><img src="img/logo.png" alt="" class="img-responsive logo"></a>
                                </div>

                                <div class="col-md-7">
                                        <nav class="navbar navbar-default">
                                          <div class="container-fluid nav-bar">
                                            <!-- Brand and toggle get grouped for better mobile display -->
                                            <div class="navbar-header">
                                              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                                <span class="sr-only">Toggle navigation</span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                              </button>
                                            </div>

                                            <!-- Collect the nav links, forms, and other content for toggling -->
                                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                                              <ul class="nav navbar-nav navbar-right">
                                                <li><a class="menu" href="index.html" >Home</a></li>
                                                <li><a class="menu active" href="#">Statistics</a></li>
                                              </ul>
                                            </div><!-- /navbar-collapse -->
                                          </div><!-- / .container-fluid -->
                                        </nav>
                                </div>
                        </div>
                </div>
        </header> <!-- end of header area -->
<section class="slider">
<pre>



</pre>
    <!-- where the chart will be rendered -->
    <div id="visualization" style="width: 1400px; height: 700px;"></div>
 
    <?php
   $diag_code = $_POST['diag1'];
    //include database connection
   $json = array();
   $conn = mysql_connect("omega.uta.edu","sxv7644","Ab112233");
   $db = mysql_select_db("sxv7644");
 
    //query all records from the database
    $query = "select p.total,p.National_Provider_ID,p.prob,p.Avg,p.Avg1,p.Sex,p.Age ,p.Race,o.Long_Description, 
case when (p.avg-s.minimum)<2 and p.prob<5 then 5
     when (p.avg-s.minimum)<4 and p.prob<20 then 4
     when (p.avg-s.minimum)<6 and p.prob<50 then 3
     when (p.avg-s.minimum)<8 and p.prob<60 then 2
     else 1
     end as Rating
from 

(select g.total,g.National_Provider_ID,g.prob,g.Avg,l.Avg1, g.Age,g.Sex,g.Race
 from(select f.total,f.National_Provider_ID,f.Discharge_Status,f.prob, c.Avg, f.Age,f.Sex,f.Race
      from  (select b.total,a.National_Provider_ID,a.Discharge_Status ,((a.con)/(b.total))*100 prob, a.Age,a.Sex,a.Race 
             from (select distinct National_Provider_ID,Discharge_Status,count(Discharge_Status) con, Age,Sex,Race 
                   from Admissions where Admission_Diagnosis_Code = '$diag_code' and Discharge_Status =2
                   group by National_Provider_ID,Discharge_Status) a, 
                  (select National_Provider_ID, count(National_Provider_ID) total 
                   from Admissions where Admission_Diagnosis_Code='$diag_code' group by National_Provider_ID) b
             where a.National_Provider_ID=b.National_Provider_ID order by con desc)f
      INNER JOIN
      (Select National_Provider_ID,avg(Admissions.LENGTH_OF_STAY) Avg from Admissions  group by National_Provider_ID) c
      ON f.National_Provider_ID = c.National_Provider_ID) g
INNER JOIN 
(select National_Provider_ID,avg(total_charges) Avg1 from Hospital_Charges group by National_Provider_ID)l
ON g.National_Provider_ID = l.National_Provider_ID order by g.total desc) p,

(select u.minimum from(select max(g.total) ,g.National_Provider_ID,g.prob,g.Avg,l.Avg1,g.Avg minimum 
 from(select f.total,f.National_Provider_ID,f.Discharge_Status,f.prob, c.Avg
      from  (select b.total,a.National_Provider_ID,a.Discharge_Status ,((a.con)/(b.total))*100 prob 
             from (select distinct National_Provider_ID,Discharge_Status,count(Discharge_Status) con 
                   from Admissions where Admission_Diagnosis_Code = '$diag_code' and Discharge_Status =2
                   group by National_Provider_ID,Discharge_Status) a, 
                  (select National_Provider_ID, count(National_Provider_ID) total 
                   from Admissions where Admission_Diagnosis_Code='$diag_code' group by National_Provider_ID) b
             where a.National_Provider_ID=b.National_Provider_ID  order by con desc)f
      INNER JOIN
      (Select National_Provider_ID,avg(Admissions.LENGTH_OF_STAY) Avg from Admissions  group by National_Provider_ID) c
      ON f.National_Provider_ID = c.National_Provider_ID) g
INNER JOIN 
(select National_Provider_ID,avg(total_charges) Avg1 from Hospital_Charges group by National_Provider_ID)l
ON g.National_Provider_ID = l.National_Provider_ID) u )s
INNER JOIN Diagnosis_Code o
WHERE Diagnosis_Code = '$diag_code' Order by Rating DESC LIMIT 0,20 ";
 
    //execute the query
    $result = mysql_query( $query );
 
    //get number of rows returned
    //$num_results = $result->num_rows;
    $selectOption = $_POST['options1'];
  
 
    if( $_POST['diag1'] && $selectOption==1){
    ?>
        <!-- load api -->
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        
        <script type="text/javascript">
            //load package
            google.load('visualization', '1', {packages: ['corechart']});
        </script>
 
        <script type="text/javascript">
            function drawVisualization() {
                // Create and populate the data table.
                var data = google.visualization.arrayToDataTable([
                    ['National_Provider_ID', 'Mortality_Rate'],
                    <?php
                    while( $row = mysql_fetch_assoc($result) ){
                        extract($row);
                        echo "['{$National_Provider_ID}',{$prob} ],";
                    }
                    ?>
                ]);
                 var options = {
                title: 'Average Mortality Rate',
                subtitle : 'Mortality Rate',
                 legend: { position: 'none' },
                 };

 
                // Create and draw the visualization.
                new google.visualization.ColumnChart(document.getElementById('visualization')).
                draw(data, options);
            }
 
            google.setOnLoadCallback(drawVisualization);
        </script>
    <?php
 
    }
    if( $_POST['diag1'] && $selectOption==2){
    ?>
        <!-- load api -->
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        
        <script type="text/javascript">
            //load package
            google.load('visualization', '1', {packages: ['corechart']});
        </script>
 
        <script type="text/javascript">
            function drawVisualization() {
                // Create and populate the data table.
                var data = google.visualization.arrayToDataTable([
                    ['National_Provider_ID', 'Length_Of_stay'],
                    <?php
                    while( $row = mysql_fetch_assoc($result) ){
                        extract($row);
                        echo "['{$National_Provider_ID}',{$Avg} ],";
                    }
                    ?>
                ]);
                 var options = {
                title: 'Average Length Of Stay',
                subtitle: 'Average Length Of Stay',
                 legend: { position: 'none' },
                 };

 
                // Create and draw the visualization.
                new google.visualization.ColumnChart(document.getElementById('visualization')).
                draw(data, options);
            }
 
            google.setOnLoadCallback(drawVisualization);
        </script>
    <?php
 
    }
    if( $_POST['diag1'] && $selectOption==3){
    ?>
</section>
        <!-- load api -->
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        
        <script type="text/javascript">
            //load package
            google.load('visualization', '1', {packages: ['corechart']});
        </script>
 
        <script type="text/javascript">
            function drawVisualization() {
                // Create and populate the data table.
                var data = google.visualization.arrayToDataTable([
                    ['National_Provider_ID', 'Cost'],
                    <?php
                    while( $row = mysql_fetch_assoc($result) ){
                        extract($row);
                        echo "['{$National_Provider_ID}',{$Avg1} ],";
                    }
                    ?>
                ]);
                 var options = {
                title: ' Average Cost',
                subtitle : 'Average Cost',
                 legend: { position: 'none' },
                 };

 
                // Create and draw the visualization.
                new google.visualization.ColumnChart(document.getElementById('visualization')).
                draw(data, options);
            }
 
            google.setOnLoadCallback(drawVisualization);
        </script>
    <?php
 
    }
    ?>
 <footer class="footer">
                <div class="container">
                        <div class="row">
                                <div class="col-xs-6 footer-para">
                                        <p>&copy;TeamC All right reserved</p>
                                </div>
                                <div class="col-xs-6 text-right">
                                        <a href=""><i class="fa fa-facebook"></i></a>
                                        <a href=""><i class="fa fa-twitter"></i></a>
                                        <a href=""><i class="fa fa-skype"></i></a>
                                </div>
                        </div>
                </div>
        </footer>

    
</body>
</html>
