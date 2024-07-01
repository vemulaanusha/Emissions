<?php
$servername = "localhost:3306";
 
//username to connect to the db
//the default value is root
$username = "root";
 

$password = "AnushAn33laJ0ey";
 
//name of the db under which the table is created
$dbName = "CO2_emissions";
 

 
   $con = mysqli_connect($servername, $username, $password, $dbName);
   $query = "SELECT * FROM `merged_data`";
   $result = mysqli_query($con, $query);
   $query_country = "SELECT DISTINCT Country FROM `merged_data`";
   $result2 = mysqli_query($con, $query_country);
   $query_issue = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'merged_data' ORDER BY ORDINAL_POSITION DESC LIMIT 28";
   $result3 = mysqli_query($con, $query_issue);
   $query_year = "SELECT DISTINCT Year FROM `merged_data`";
   $result4 = mysqli_query($con, $query_year);
   if (!$con || !$result2 || !$result3 || !$result4) {
    die("Connection or query failed: " . mysqli_error($con));
}
  //  if($con){
  //    echo "connected";
  //  }
  //  if (!$con) {
  //   die("Connection failed: " . mysqli_connect_error());
  //   }
  $result1 = $result5 = $result6 = null;

   if(isset($_POST['submit']))
    {
        // Store the Product name in a "name" variable
        $year = mysqli_real_escape_string($con,$_POST['Year']);
        $country = mysqli_real_escape_string($con,$_POST['Country']);
        $healthissue = mysqli_real_escape_string($con,$_POST['HealthIssue']);
        
         //echo `$healthissue`;
        // Store the Category ID in a "id" variable      
        // Creating an insert query using SQL syntax and
        // storing it in a variable.
        $graph1_query = "SELECT * FROM `merged_data` WHERE Year = $year";
        $result1 = mysqli_query($con, $graph1_query);
        $graph2_query = "SELECT * FROM `merged_data` WHERE Year = $year and Country = '$country'";
        $result5 = mysqli_query($con, $graph2_query);
        $graph3_query = "SELECT * FROM `merged_data` WHERE Country = '$country'";
        $result6 = mysqli_query($con, $graph3_query);
        
        if (!$result1 || !$result5 || !$result6) {
          die("Query failed: " . mysqli_error($con));
      }
          // The following code attempts to execute the SQL query
          // if the query executes with no errors 
          // a javascript alert message is displayed
          // which says the data is inserted successfully
        //   if(mysqli_query($con,$result1))
        // {
        //     echo '<script>alert("Product added successfully")</script>';
        // }
    }
?>

<!-- // Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve data
$query = "SELECT * FROM `dropped_data` WHERE Year = 1990";
$result = mysqli_query($con, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/main.css">
    <title>Document</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- <script>
function showUser(str) {
  if (str == "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET","getuser.php?q="+str,true);
    xmlhttp.send();
  }
}
</script> -->
<script type="text/javascript">

</script>
    <script type="text/javascript">


  
  
  //drawRegionsMap();
  google.charts.load('current', {
        'packages': ['corechart', 'line'],
      });
  google.charts.setOnLoadCallback(drawRegionsMap);
//   document.getElementById("graph").style.display = "block";
//   //event.preventDefault();
// }
     

      function drawRegionsMap() {
       var data1 = google.visualization.arrayToDataTable([
          ['Country_code', 'No.of Deaths due to <?php echo $healthissue ?>'],
          <?php
          // Loop through the data and format it as JavaScript array elements
          while ($row = mysqli_fetch_assoc($result1)) {
              echo "['". $row['Code_y'] ."',". $row[$healthissue]."], ";
          }
          ?>
        ]);

       
        var options1 = {
          title: '<?php echo $year; ?>',
          is3D: true,
          hAxis: {title: 'Countries'},
          vAxis: {title: 'No.of Deaths due to <?php echo $healthissue; ?>'},
        };

        // document.getElementById("loader").style.display = "none";
        // document.getElementById("graph").style.display = "block";
        var chart1 = new google.visualization.LineChart(document.getElementById('regions_div'));

        chart1.draw(data1, options1);


        var data2 = google.visualization.arrayToDataTable([
          ['Country', 'No.of Deaths due to <?php echo $healthissue ?>', 'CO2_emissions'],
          ['',0,0],
          <?php
          // Loop through the data and format it as JavaScript array elements
          while ($row = mysqli_fetch_assoc($result5)) {
            $emissions = $row["CO2_emissions"] /100000;
            echo "['". $row['Country'] ."',". $row[$healthissue].",". $emissions ."], ";
          }

          ?>
          ['',0,0]
        ]);

       
        var options2 = {
          title: '<?php echo $year; ?>',
          is3D: true,
          vAxis: {title: 'No.of Deaths due to <?php echo $healthissue; ?>'},

        };

        var chart2 = new google.visualization.AreaChart(document.getElementById('year_div'));

        chart2.draw(data2, options2);

        var data3 = google.visualization.arrayToDataTable([
          ['Year', 'No.of Deaths due to <?php echo $healthissue ?>'],
          <?php
          // Loop through the data and format it as JavaScript array elements
          while ($row = mysqli_fetch_assoc($result6)) {
            echo "['". $row['Year'] ."',". $row[$healthissue]."], ";
        }

          ?>
        
        ]);

       
        var options3 = {
          title: '<?php echo $country; ?>',
          is3D: true,
          // pieHole: 0.5,
          // chartArea:{
          //   width:'100%',
          //   height:'75%'
          // },
          // colors:['#CBE4F9','#CDF5F6','#EFF9DA','#F9EBDF','#F9D8D6','#D6CDEA']
         
        };

        var chart3 = new google.visualization.BarChart(document.getElementById('pie_div'));

        chart3.draw(data3, options3);
        var chart4 = new google.visualization.ColumnChart(document.getElementById('bar_div'));

        chart4.draw(data3, options3);
      }
    </script>
    
</head>
<body>
    <header id="header"><div class="inner">
        <a href="index.html" class="logo"><strong>CO2 Emission</strong>Tracking</a>
        <nav id="nav"><a href="index.html">Home</a>
            <a href="visualize.php">Visualize</a>
            <a href="health.php">Correlate with health</a>
        </nav><a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
    </div>
<div>
  <section>
    <form method="POST">
      <div class="flex-container">
  <select name="Year" id="Year" placeholder="Choose a Year" required>
  <option value="" disabled selected>Choose a Year to visualize the CO2 emissions</option>
  <?php
    while ($row = mysqli_fetch_assoc($result4)){
    ?>
    <option value="<?php echo $row['Year']; ?>">

<?php echo $row['Year']; ?>

</option>
 <?php
    }
    ?>
  </select>
  <select name="Country" id="Country" placeholder="Choose a Country" required>
  <option value="" disabled selected>Choose a Country</option>
 <?php
    while ($row = mysqli_fetch_assoc($result2)){
    ?>
    <option value="<?php echo $row['Country']; ?>">

<?php echo $row['Country']; ?>

</option>
 <?php
    }
    ?>
  <!-- <option value="1990">1990</option>
  <option value="1991">1991</option>
  <option value="1992">1992</option>
  <option value="1993">1993</option> -->
  </select>
  <select name="HealthIssue" id="HealthIssue" placeholder="Choose an Issue" required>
  <option value="" disabled selected>Choose an Issue</option>
  <?php
    while ($row = mysqli_fetch_assoc($result3)){
    ?>
    <option value="<?php echo $row['COLUMN_NAME']; ?>">

<?php echo $row['COLUMN_NAME']; ?>

</option>
 <?php
    }
    ?>
  <!-- <option value="1990">1990</option>
  <option value="1991">1991</option>
  <option value="1992">1992</option>
  <option value="1993">1993</option> -->
  </select>
  <input type="submit" value="Submit" name="submit"></div>
</form>
    </div>
  </section>
  <section>
    <div id="loader" style="display: none;"></div>
  <div class="grid-container" id="graph">
    <div class="grid-item" id="regions_div" ></div>
    <div class="grid-item" id="year_div" ></div>
    <div class="grid-item" id="pie_div" ></div>
    <div class="grid-item" id="bar_div"></div>
  </div>
  </section>
</header>
</body>
</html>