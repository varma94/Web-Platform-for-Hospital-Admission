<?php

$conn = mysql_connect("omega.uta.edu","sxv7644","Ab112233");
$db = mysql_select_db("sxv7644");
$i=1;
if($_POST['diag']){
  $diag_code = $_POST['diag'];
$sql="select p.total,p.National_Provider_ID,p.prob,p.Avg,p.Avg1,p.Sex,p.Age ,p.Race,o.Long_Description, 
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
                   from Admissions where Admission_Diagnosis_Code ='$diag_code' and Discharge_Status =2
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
                   from Admissions where Admission_Diagnosis_Code ='$diag_code' and Discharge_Status =2
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
WHERE Diagnosis_Code ='$diag_code' Order by Rating DESC";

$records= mysql_query($sql);


}
?>
<!DOCTYPE html>

<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
        <title>Diagnosis</title>
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
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
    /*
Please consider that the JS part isn't production ready at all, I just code it to show the concept of merging filters and titles together !
*/
$(document).ready(function(){
    $('.filterable .btn-filter').click(function(){
        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
        if ($filters.prop('disabled') == true) {
            $filters.prop('disabled', false);
            $filters.first().focus();
        } else {
            $filters.val('').prop('disabled', true);
            $tbody.find('.no-result').remove();
            $tbody.find('tr').show();
        }
    });

    $('.filterable .filters input').keyup(function(e){
        /* Ignore tab key */
        var code = e.keyCode || e.which;
        if (code == '9') return;
        /* Useful DOM data and selectors */
        var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');
        /* Dirtiest filter function ever ;) */
        var $filteredRows = $rows.filter(function(){
            var value = $(this).find('td').eq(column).text().toLowerCase();
            return value.indexOf(inputContent) === -1;
        });
        /* Clean previous no-result if exist */
        $table.find('tbody .no-result').remove();
        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
        $rows.show();
        $filteredRows.hide();
        /* Prepend no-result row if all rows are filtered */
        if ($filteredRows.length === $rows.length) {
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        }
    });
});
 jQuery(document).ready(function($) {

    $(".clickable-row").click(function() {
        var id = $(this).closest("tr").find('td:eq(0)').text();
     
      window.document.location = "http://omega.uta.edu/~sxv7644/doctor/Page3.php"+"?"+"id="+id;
      });
});
    </script>

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
                                                <li><a class="menu active" href="#">Diagnosis</a></li>
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
<div class="container">
    
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title" style="font-size:160%"><b>Specified Disease is 
                <?php
                     $s = mysql_fetch_assoc($records);
                    echo $s['Long_Description'];
                ?>
                </b></h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
                </div>
            </div>
            <table class="table table-hover" style="font-size:120%" >
                <thead>
                    <tr class="filters table-success" >
                    <th><input size="19" type="text" class="form-control" placeholder="National Provider ID" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Number Of Patients" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Ranking" disabled></th>
                       
                        <th><input type="text" class="form-control" placeholder="Mortality Rate" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Average Stay" disabled></th>
                         <th><input type="text" class="form-control" placeholder="Cost" disabled></th>
                          <th><class="form-control fixed"><class="text-center">Ratings</th>
                          
                    </tr>
                </thead>
                <tbody>
                   <?php

while($row = mysql_fetch_assoc($records))
{
  
  echo "<tr class='clickable-row'  data-href='http://omega.uta.edu/~sxv7644/doctor/Page3.php'>";
   echo "<td>".$row['National_Provider_ID']."</td>";
  echo "<td>".$row['total']."</td>";
  echo "<td>".$i."</td>";
 // echo "<td>".$row['Long_Description']."</td>";
  echo "<td>".$row['prob']."</td>";
  echo "<td>".$row['Avg']."</td>";
  echo "<td>".$row['Avg1']."</td>";
 //   if($row['Sex']==1)
 //            echo "<td>"."Male"."</td>";
 //        else
	//   echo "<td>"."Female"."</td>";
 //  echo "<td>".$row['Age']."</td>";
 //  if($row['Race']==1)
	// 	echo "<td>"."White"."</td>";
	// else if($row['Race']==2)
	// 	echo "<td>"."Black"."</td>";
 //    else if($row['Race']==3)
 //    	echo "<td>"."Other"."</td>";
 //    else if($row['Race']==4)
 //    	echo "<td>"."Asian"."</td>";
 //    else if($row['Race']==5)
 //    	echo "<td>"."Hispanic"."</td>";
 //     else if($row['Race']==6)
 //     	echo "<td>"."North America Native"."</td>";
 //     else if($row['Race']==0)
 //     	echo "<td>"."Unknown"."</td>";
  echo "<td>";
  for($x=1;$x<=$row['Rating'];$x++) {
        echo '<img src="http://omega.uta.edu/~sxv7644/fullstar.png" />';
    }
    if (strpos($row['Rating'],'.')) {
        echo '<img src="http://omega.uta.edu/~sxv7644/halfstar.png" />';
        $x++;
    }
    while ($x<=5) {
        echo '<img src="http://omega.uta.edu/~sxv7644/emptystar.png" />';
        $x++;
    }
    $i=$i+1;
  echo "</td>";
  // /  if($row['Type_Of_Admission']==1){
  //   echo "<td>".$row['count']."</td>";
  //   echo "<td>".$row['National_Provider_ID']."</td>";
  //   echo "<td>"."Emergency"."</td>";
  //   echo "<td>".$row['Average']."</td>";}
  //   else if($row['Type_Of_Admission']==2){
  //   echo "<td>".$row['count']."</td>";
  //   echo "<td>".$row['National_Provider_ID']."</td>";
  //   echo "<td>"."Urgent"."</td>";
  //   echo "<td>".$row['Average']."</td>";}
  //   else if($row['Type_Of_Admission']==3){
  //   echo "<td>".$row['count']."</td>";
  //   echo "<td>".$row['National_Provider_ID']."</td>";
  //   echo "<td>"."Elective"."</td>";
  //   echo "<td>".$row['Average']."</td>";}
  //   else if($row['Type_Of_Admission']==4){
  //   echo "<td>".$row['count']."</td>";
  //   echo "<td>".$row['National_Provider_ID']."</td>";
  //   echo "<td>"."New Born"."</td>";
  //   echo "<td>".$row['Average']."</td>";}
  //   else if($row['Type_Of_Admission']==5){
  //   echo "<td>".$row['count']."</td>";
  //   echo "<td>".$row['National_Provider_ID']."</td>";
  //   echo "<td>"."Trauma"."</td>";
  //   echo "<td>".$row['Average']."</td>";
  //   }
  echo "</tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>
    </body>
</html>
