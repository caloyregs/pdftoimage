<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
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

<?php error_reporting(-1); ?>
<?php ini_set('display_errors', 'true'); ?>

<!--<php include('includes/conn.db.php' ); >
<php include('includes/class.db.php' ); >-->

<?php 

	/*$database = new DB();
   
	$query = "SELECT 
              doc_packages.id,
              doc_packages.user_id,
			  doc_packages.code,
              doc_packages.date_created,
              doc_packages.doc_name,
              doc_packages.page_num,
              users.name
            FROM
              doc_packages
              INNER JOIN users ON (doc_packages.user_id = users.id)";

	$results = $database->get_results($query);*/
	//var_dump($results);

	require __DIR__ . "/vendor/autoload.php";

	//require_once('vendor/autoload.php');

	/*use \ConvertApi\ConvertApi;
   use \setasign\Fpdi\Fpdi;
   use \Gumlet\ImageResize;*/
   use Zxing\QrReader;

   define( 'base_url', "http://".$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER["REQUEST_URI"].'?').'/' );

   if (isset($_GET['id'])) {
      $id = $_GET['id'];
      //echo $_GET['id'];    

		$directory3 = __DIR__ . '/split/'.$id.'compress/'; // "/img/".$id;

		//$flcount = file_count($directory3);
		//check_docs(10,$id);
		dir_listing($id);
		
    }

    function file_count($directory) {

        $files = scandir($directory);
        $num_files = count($files)-2;

        return $num_files;
    }
   
   	function check_docs2($filecount,$id) {
  	
		$pdf1_directory = '/split/compress'; //.$id;
		$pdf1_directory = $pdf1_directory ? $pdf1_directory : './';
		
		//echo '<br><br>';
		//echo "No. of files: $filecount "."<br><br>";
		//echo '<h4>Results:';
		echo '<br><br><br>';
		echo '<div class="panel panel-primary">';
		echo '<div class="panel-heading text-left"><strong>Document Type:</strong></div>';
		echo '</div>';

		for ($i = 1; $i <= $filecount; $i++) {

			$doc    =  __DIR__ . "/split/compress/".$id."/mergedPDF_".$i.".pdf"; 		
            //$rzDoc	=  __DIR__ . "/resized/".$id."/mergedPDF_".$i.".jpg"; 
            $rzDoc	=  __DIR__ . "/img/mergedPDF_".$i.".jpg"; 
            $docx    = "mergedPDF_".$i.".pdf";

			//$pp_count = count_pdf_pages($doc);
			//$result = get_doc_type($doc); //commented out
           
            //if ($result) {
            $qrcode = new QrReader($rzDoc);
            $decodeText = $qrcode->text(); 
            $result = $decodeText;            
            //echo $result;
            if ($result) {
            	$result = $decodeText;
            } else {
            	$result = "---";      
            }
            //}

			//$version= getPdfVersion($doc);			
			
			$newFilename = $pdf1_directory.str_replace('.pdf', '', $doc).'_'.$i.".pdf";
			/*$res = '<div style="margin-left:20px;">Page '.$i.' - '.$result.'<br></div> ';		*/
			//$res = '<div style="margin-left:20px;">Page '.$i.' - '.$docx.' - '.$result.'<br>
         	//<a href="'.$doc.'" target="_blank">'.$doc.'</a></div>';     				
			$results = '<div style="margin-left:20px;"><a href="'.$newFilename.'" target="_blank">'.$newFilename.'</a>'.'<br/></div>'; //.' - '.$result.'<br/>';
			echo $results;	
		}

		echo '<br><br>';

	}
	
   	function dir_listing($code) {

		$directory = __DIR__ . "/split/".$code.'/compress/';
		//echo $directory ;
		//$doc  = __DIR__ . "/split/compress/mergedPDF_1.pdf";   

		$filecount = 0;
		$files = glob($directory . "*");		
		if ($files){
		 $filecount = count($files);
		}

		$dir = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
		$it  = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
		$it->setMaxDepth(1);

		echo '<a href="index.php" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to List</a><br><br>';
		echo '<div class="panel panel-primary">';
		echo '<div class="panel-heading text-left"><strong>PDF Document List</strong></div>';
		echo '</div>';
		
		$index = 1;
		foreach ($it as $fileinfo) {	
			//echo $it;
			$file =  base_url."/split/".$code."/compress/".$fileinfo->getFilename();  			
			//echo $file;
			
		    if ($fileinfo->isDir()) {
		        printf("Folder - %s\n", $fileinfo->getFilename());
		    } elseif ($fileinfo->isFile()) {		
			
		        echo $index.']. '. $fileinfo->getFilename().' '.'<a href="'.$file.'" target="_blank" class="btn btn-success"/> View</a>';
				echo '<br><br>';
				//$res = '<div style="margin-left:20px;"><a href="'.$fileinfo->getFilename().'" target="_blank" class="btn btn-success">View</a><hr></div>';       
				//echo $res;
		 
		        //echo '   |   ';
		        //$fileinfo->getFilename().'<br>';
		        //$filename = $fileinfo->getFilename();
		        //resizeImage($filename);		  		              	            
		    }
		    //$fileinfo->getFilename().'<br>';
		    //$filename = $fileinfo->getFilename();
		    //resizeImage($filename);		
			$index++;			
		}
		echo '<br><br>';
	}

   function check_docs($filecount,$id) {
   
      $pdf1_directory = '/split/'.$id.'compress/';
      $pdf1_directory = $pdf1_directory ? $pdf1_directory : './';

      echo '<br><br><br>';
      echo '<div class="panel panel-primary">';
      echo '<div class="panel-heading text-left"><strong>Document Type:</strong></div>';
      echo '</div>';

      for ($i = 1; $i <= $filecount; $i++) {

         $doc     =  __DIR__ . "/split/".$id."/compress/mergedPDF_".$i.".pdf";        
         $filename=  base_url."/split/".$id."/compress/mergedPDF_".$i.".pdf";  
		 $rzDoc   =  __DIR__ . "/img/".$id."/mergedPDF_".$i.".jpg"; 
         $docx    = "mergedPDF_".$i.".pdf";

         //$result = get_doc_type($doc); //commented out           
            //if ($result) {
            $qrcode = new QrReader($rzDoc);
            $decodeText = $qrcode->text(); 
            $result = $decodeText;            
            //echo $result;
            if ($result) {
               $result = $decodeText;
            } else {
               $result = "---";      
            }
            //} 
         
         $newFilename = $pdf1_directory.str_replace('.pdf', '', $filename).'_'.$i.".pdf";
         $res = '<div style="margin-left:20px;">Page '.$i.' - '.$docx.' - '.$result.'
         <a href="'.$filename.'" target="_blank" class="btn btn-success">View</a><hr></div>';       
         echo $res;
      }

      echo '<hr><br><br>';

   }

?>


