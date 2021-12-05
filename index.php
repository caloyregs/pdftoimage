<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />

<style>
	* {
	  margin-left: 1px;
	  margin-right: 2px;
	}
   body {
      margin: 2em;
   }

   thead input {
      width: 100%;
      padding: 3px;
      box-sizing: border-box;
   }	
</style>

<br>

<?php include('includes/conn.db.php' ); ?>
<?php include('includes/class.db.php' ); ?>

<?php 

    require_once('vendor/autoload.php');

	use \ConvertApi\ConvertApi;
	
	ConvertApi::setApiSecret('wstP7reeFC7aPBtk');
	
   $database = new DB();
   
   $query = "SELECT 
              doc_packages.id,
              doc_packages.user_id,
			  doc_packages.code,
              doc_packages.date_created,
              doc_packages.doc_name,
              doc_packages.page_num,
			  doc_packages.date_ended,
              users.name
            FROM
              doc_packages
              INNER JOIN users ON (doc_packages.user_id = users.id)";

   $results = $database->get_results($query);
   //var_dump($results);
   
   $info = ConvertApi::getUser();
   echo 'Credits Remaining: '.$info['SecondsLeft'];

	function dateDiff ($d1, $d2) {

		// Return the number of days between the two dates:    
		return round(abs(strtotime($d1) - strtotime($d2))/86400);

	}	
?>



<div style="margin-left:10px;">
	<div class="panel panel-primary">
      <div class="panel-heading text-center"><strong>Document Package List</strong></div>
      <div class="panel-body">
		<a href="create.php" class="btn btn-success">Create New Document Package</a>
		<br><br>
         <body class="wide example">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>Code</th>
                     <th>Creator</th>
                     <th>Date Created</th>
                     <th>Document Name</th>                     
                     <th>No. of Pages</th>
                     <th>Show Files</th>
					 <th>Time Elapsed</th>
                  </tr>
               </thead>
               <tbody>
                  <?php 
                     foreach ($results as $row)
                         {
                     ?>
                      <tr>                
                        <td><?php echo $row['code']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['date_created']; ?></td>
                        <td><?php echo $row['doc_name']; ?></td>
                        <td data-order="<?php echo $row['page_num']; ?>"><?php echo $row['page_num']; ?></td>
                        <td>
                           <!--<button id="btnLine" class="btn btn-primary">Show Files</button>-->
                           <a href="list-files.php?id=<?php echo $row['code']; ?>" class="btn btn-primary">Show Files</a>
                        </td>
						<td>
							<?php 
								$date1 = $row['date_created'];
								$date2 = $row['date_ended'];
								//$diff = abs((strtotime($date2) - strtotime($date1))/86400); 
								$diff = (strtotime($date2) - strtotime($date1));
								if ($diff < 0) {
									$diff = 0;
								}
								$avg = $diff / $row['page_num'];
								echo $diff.' sec(s)<br>'.$avg.' per page';								
							?>
						</td>
                     </tr>                      
                  <?php
                      }
                  ?>                  
                  <!--<tr>
                     <td>3</td>
                     <td>MANNY M</td>
                     <td>2016/01/23</td>
                     <td>MORTGAGE AGREEMENT</td>
                     <td data-order="20">20</td>
                     <td><button id="btnLine" class="btn btn-primary">Show Files</button></td>
                  </tr>-->
               </tbody>
               <tfoot>
                  <tr>
                     <th>Code</th>
                     <th>Creator</th>
                     <th>Date Created</th>
                     <th>Document Name</th>                     
                     <!--<th>No. of Pages</th>-->
                  </tr>
               </tfoot>
            </table>
      </div>
   </div>         	
</div>

<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready(function () {
      document.title = "";
      $("#example tfoot th").each(function () {
         var title = $(this).text();
         $(this).html('<input type="text" placeholder="Search ' + title + '" />');
      });

      var table = $("#example").DataTable({
         dom: '<"dt-buttons"Bf><"clear">lirtp',
         paging: true,
         autoWidth: true,
         buttons: [
            "colvis",
            "copyHtml5",
            "csvHtml5",
            "excelHtml5",
            "pdfHtml5",
            "print"
         ],
         initComplete: function (settings, json) {
            var footer = $("#example tfoot tr");
            $("#example thead").append(footer);
         }
      });

      $("#example thead").on("keyup", "input", function () {
         table.column($(this).parent().index())
         .search(this.value)
         .draw();
      });

      $('body').on('click', '#btnLine', function() {
         var row  = $(this).parents('tr')[0];
         var idd = table.row( row ).data()[0];
         console.log(idd);
      });
 
   });

</script>



