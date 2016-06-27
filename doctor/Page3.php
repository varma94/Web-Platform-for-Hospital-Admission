<?php

$a =isset($_GET['id'])?$_GET['id']:'not yet';
$conn = mysql_connect("omega.uta.edu","sxv7644","Ab112233");
$db = mysql_select_db("sxv7644");
if(1){
  
$sql="select National_Provider_ID,Type_Of_Admission,Age,Sex,Race from Admissions where National_Provider_ID = '$a' LIMIT 400";

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
</script>
</head>
<body>
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
                <h3 class="panel-title" style="font-size:160%">Users</h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
                </div>
            </div>
            <table class="table" style="font-size:120%">
                <thead>
                    <tr class="filters"  >
                        
                        <th>National Provider ID</th>
                        <th><input type="text" class="form-control" placeholder="Type Of Admission" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Age" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Sex" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Race" disabled></th>
                          

     </tr>
                </thead>
                <tbody>

<?php
while($row = mysql_fetch_assoc($records))
{
  
  echo "<tr>";
  echo "<td>".$row['National_Provider_ID']."</td>";
   if($row['Type_Of_Admission']==1){
    echo "<td>"."Emergency"."</td>"; ;
  }
    else if($row['Type_Of_Admission']==2){
     echo "<td>"."Urgent"."</td>";
    }
    else if($row['Type_Of_Admission']==3){
    echo "<td>"."Elective"."</td>";
  }
    else if($row['Type_Of_Admission']==4){
    echo "<td>"."New Born"."</td>";
  }
    else if($row['Type_Of_Admission']==5){
    echo "<td>"."Trauma"."</td>";
    }
 if($row['Age']==0){
    echo "<td>"."Under 1 year"."</td>"; ;
  }
    else if($row['Age']==1){
     echo "<td>"."1 to 5 years"."</td>";
    }
    else if($row['Age']==2){
    echo "<td>"."6 TO 14 YEARS"."</td>";
  }
    else if($row['Age']==3){
    echo "<td>"."15 TO 20 YEARS"."</td>";
  }
    else if($row['Age']==4){
    echo "<td>"."21 TO 44 YEARS"."</td>";
    }
    else if($row['Age']==5){
    echo "<td>"."45 TO 64 YEARS"."</td>";
    }
    else if($row['Age']==6){
    echo "<td>"."65 TO 74 YEARS"."</td>";
    }
    else if($row['Age']==7){
    echo "<td>"."75 TO 84 YEARS"."</td>";
    }
    else if($row['Age']==8){
    echo "<td>"."85 AND OVER"."</td>";
    }
    else if($row['Age']==9){
    echo "<td>"."Unknown"."</td>";
    }

   if($row['Sex']==1)
            echo "<td>"."Male"."</td>";
        else
	  echo "<td>"."Female"."</td>";
  if($row['Race']==1)
		echo "<td>"."White"."</td>";
	else if($row['Race']==2)
		echo "<td>"."Black"."</td>";
    else if($row['Race']==3)
    	echo "<td>"."Other"."</td>";
    else if($row['Race']==4)
    	echo "<td>"."Asian"."</td>";
    else if($row['Race']==5)
    	echo "<td>"."Hispanic"."</td>";
     else if($row['Race']==6)
     	echo "<td>"."North America Native"."</td>";
     else if($row['Race']==0)
     	echo "<td>"."Unknown"."</td>";
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