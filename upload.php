<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style>
	* {
		margin-left: 2px;
		margin-right: 2px;
	}	
</style>

<?php /*session_start();*/ ?>

<?php

    require __DIR__ . "/vendor/autoload.php";

    require_once('vendor/autoload.php');

	use \ConvertApi\ConvertApi;
	use \setasign\Fpdi\Fpdi;
    use \Gumlet\ImageResize;
    use Zxing\QrReader;

		
	//FUNCTIONS
	include('includes/conn.db.php' );
	include('includes/class.db.php' );
	
	ConvertApi::setApiSecret('wstP7reeFC7aPBtk');
	
	/*$directory1 = __DIR__ . "/compress";
	$directory2 = __DIR__ . "/split/compress";
    $directory3 = __DIR__ . "/img";
    $directory4 = __DIR__ . "/resized";
	$directory5 = __DIR__ . "/resized_bottom";
	$directory6 = __DIR__ . "/movetest";
	$filenam = '';*/

	//DecodeQR('mergedPDF_8.jpg');

    //$flcount 	= file_count($directory3);
    //$imgCount 	= file_count($directory3);
	//DecodeQR('mergedPDF_1.jpg', '2_20211128_102550');
	
    $base_url="http://".$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER["REQUEST_URI"].'?').'/';
    	
    //pdfToImage($flcount, '');

    //$doc  = __DIR__ . "/split/compress/mergedPDF_2.pdf";
    //convToImage($doc);

	//check_docs(10,"2_20211126_160701");
	
    //$info = ConvertApi::getUser();
	//echo 'Credits Remaining: '.$info['SecondsLeft'];
	/*$newNam  = "compress/mergedPDF".".pdf";
	$pages = count_pdf_pages($newNam);
	echo $pages;*/
	
	$uid = 2;
	
	$d2  = new Datetime("now");
	$d22 = $d2->format('Ymd_His');
	$code = $uid.'_'.$d22; //->format('U');
	//echo $code;
	
	//processScan($uid, $code);

	//function processScan($uid, $code) {
		//echo $_SESSION["time"];	
		if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['uploadd'])) {	
			//print_r($_POST);			
			//$timm = $_POST['timee'];
			//echo 'Processing...';			
			//echo $code;
		    //echo $_SERVER['REQUEST_METHOD'];
		    //echo $_POST['uploadd'];
		    //$countfiles = count($_FILES['file']['name']);
		  
	  	    //unlinkr($directory1); 
		    //unlinkr($directory2); 
		    //unlinkr($directory3); 
		    //unlinkr($directory4); 
		   
		    $filenam = $_FILES['file']['name'];	 
		  
			$newNam  = "compress/mergedPDF".".pdf";
			
			if ($filenam =='') {	  
			
				pAlert('no attachment');		
				//header("Location: create.php"); 
				//exit();
				 
			} else {
					
				$path = "split/".$code."/";
				//echo $path;
				
				//move_uploaded_file($_FILES["file"]["tmp_name"], "upload_multiple/" . $newNam);
				move_uploaded_file($_FILES["file"]["tmp_name"], "" . $newNam);
				
				compressPdf($newNam);
				
				$pages = count_pdf_pages($newNam);			
			
				createPackage($uid,$code,$pages);
				$r = pdfSplitPDF($newNam, $path, $code);
				//var_dump($r);
				//dir_resizes();
				//var_dump($rs);
				//if ($split) {
				//check_docs($flcount);
				//}
				//echo json_encode(array('success' => 1));
				
				//echo $timm;
				
				$data = ['success' => true,
					'data' => $r,
					'message' => 'success'];

				return json_encode($r);
				
				//return response()->json($data);				
				//return response()->json('success');
			  
			} 
			  
		   /*else {
			  
				  echo json_encode(array('success' => 0));
			}*/
			  
			//} 
				/*else {
					echo 'No attached file(s).';
				}*/
			//echo phpinfo(); 
			
		}		

	//}
	
	function createPackage($uid, $code, $pages) {

		$database = new DB();   
		$noww = date("Y-m-d h:i:s");
		
		$user_data = array(
			   'user_id' => $uid, 
			   'code' => $code, 
			   'date_created' => $noww, 
			   'doc_name' => 'DOCUMENT TEST'.$noww, 
			   'page_num' => $pages
		);

		$rs = $database->insert('doc_packages', $user_data);
		
		if ($rs) {	
			createPath($code);
		}
		//echo json_encode($rs);
	}
		  
	function generateID($length = 16) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	function createPath($trxn) {
		if (!file_exists('img/'.$trxn)) {
			mkdir('img/'.$trxn, 0777, true);
		}
		//echo 'OK';
	}
	
	function dir_resizes() {

		$directory = __DIR__ . "/img/";
		//echo $directory ;
		$filecount = 0;
		$files = glob($directory . "*");		
		if ($files){
		 $filecount = count($files);
		}

		$dir = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
		$it  = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
		$it->setMaxDepth(1);

		echo '<div class="panel panel-primary">';
		echo '<div id="dataa" class="panel-heading text-left"><strong>PDF Document Type</strong></div>';
		echo '</div>';
		foreach ($it as $fileinfo) {
		    /*if ($fileinfo->isDir()) {
		        printf("Folder - %s\n", $fileinfo->getFilename());
		    } elseif ($fileinfo->isFile()) {
		        //echo '<a href="../img/'.$fileinfo->getFilename().'" target="_blank" />'.$fileinfo->getFilename();'</a>';
		        //echo '   |   ';
		        $fileinfo->getFilename().'<br>';
		        $filename = $fileinfo->getFilename();
		        resizeImage($filename);		  		              	            
		    }*/
		    $fileinfo->getFilename().'<br>';
		    $filename = $fileinfo->getFilename();
		    //resizeImage($filename);		  
		}
		echo '<br><br>';
	}
  
	function compressPdf($filename) {
		try {
			$result = ConvertApi::convert('compress', [
				 'File' => $filename,
		       	], 'pdf'
		   	);

		   	$r = $result->saveFiles('compress/');	
		  	//var_dump($r);
	   } catch (\ConvertApi\Error\Api $error) {
			echo 'Oppsss. An error occured.';	
		    //echo "Got API error code: " . $error->getCode() . "\n";
		    //echo $error->getMessage();
		}
	}
	
    function convToImage($filename) {
    	//echo $filename;
       try {
			$result = ConvertApi::convert('extract-images', [
	             'File' => $filename,
	            ], 'pdf'
	       	);

	       	$r = $result->saveFiles('img/');    

		} catch (\ConvertApi\Error\Api $error) {
			echo '';
			//echo 'Error on conversion.';	
		    //echo "Got API error code: " . $error->getCode() . "\n";
		    //echo $error->getMessage();
		}
		/*finally {
  			
		}*/
    }

	function pdfSplitPDF($filename, $pdf_directory = false, $code) {

		$pdf_directory = $pdf_directory ? $pdf_directory : './';
		$new_path = preg_replace('/[\/]+/', '/', $pdf_directory.'/'.substr($filename, 0, strrpos($filename, '/')));
		
		if (!is_dir($new_path))
		{
			mkdir($new_path, 0777, true);
		}
		
		//$pdf = new setasign\Fpdi\FPDI();
		$pdf = new FPDI();
		$pagecount = $pdf->setSourceFile($filename);
		$templateId = $pdf->importPage($pagecount);
		
		//$typ = array("","AffiliatedBusiness","AnnuityIntent","AMORT");
		//echo "<div style='margin-left:20px;'>";
		//echo "<h4>Document Preview:</h4></div>";
		//echo '<div class="panel panel-primary">';
		//echo '<div id="dataa" class="panel-heading text-left"><strong>Document Preview</strong></div>';
		//echo '</div>';

		for ($i = 1; $i <= $pagecount; $i++) {
			$newPdf = new setasign\Fpdi\FPDI();
		
			$size = $pdf->getTemplateSize($pdf->importPage($i));

			//$f = $newPdf->setSourceFile($filename);
			//$newPdf->addPage();      
			//$pdf->AddPage("P","A4");			
			$newPdf->AddPage('P', array($size['width'], $size['height']));		
            //$newPdf->SetCompression(true);
			$newPdf->setSourceFile($filename);
			$newPdf->useTemplate($newPdf->importPage($i));
			
			try {
			
				//$newFilename = $pdf_directory.str_replace('.pdf', '', $filename).'_'.$i."_".$typ[$i].".pdf";
				$newFilename = $pdf_directory.str_replace('.pdf', '', $filename).'_'.$i.".pdf";

				$newPdf->output($newFilename, 'F');
				//download file
				//$newPdf->Output($newFilename, 'I');				
				//echo "Page ".$i." - ".$newFilename."<br/>\n";				
				//$result = get_doc_type($newFilename);						
				//"Page ".$i." - ";
				//$results = '<div style="margin-left:20px;"><a href="'.$newFilename.'" target="_blank">'.$newFilename.'</a>'.'<br/></div>'; //.' - '.$result.'<br/>';
				//echo $results;				
				
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}

		}
		
		echo '<br>';		
		$data = imageParsing($filename, $code, $pagecount);
							
        //pdfToImage($pagecount, $code, '');
        //check_docs($pagecount,$code);
		//$pdf->close(); 
		//echo json_encode($data);		
	}
	
	function pdfToImage($filecount, $code, $pdf_directory='') {

        $pdf_directory = $pdf_directory ? $pdf_directory : './';

        for ($i = 1; $i <= $filecount; $i++) {

            $doc  = __DIR__ . "/split/".$code."/compress/mergedPDF_".$i.".pdf";            
            //$img  = __DIR__ . "/img/mergedPDF_".$i.".jpg"; 

            $filename 	= "mergedPDF_".$i.".jpg";
			//echo $filename;
            //$imgName 	= $pdf_directory.str_replace('.jpg', '', $filename).'_'.$i.".jpg";
            //echo $imgName;
            //imageParsing($doc, $code); 
			//convToImage($doc); //commented out
            //echo $filename.' done resizing. <br>';
            //resizeImage($filename);
      
        }
		
    }
	
	function imageParsing($file,$code,$pagecount) {
				
		if (!$file || !file_exists($file)) {
        	return false;
     	}
		$parser = new \Smalot\PdfParser\Parser(); 
		$pdf = $parser->parseFile($file);
		//echo $file;

		$images = $pdf->getObjectsByType('XObject', 'Image');
		//echo basename($file) . " path: " . realpath($file) . "<br>";

		$objectList = $pdf->getObjectsByType('XObject');
		$i = 0;
		
		echo '<a href="index.php" class="btn btn-success"> <i class="fa fa-backward" aria-hidden="true"></i> Back</a><br><br>';
		echo '<div class="panel panel-primary">';
		echo '<div class="panel-heading text-left"><strong>PDF Document Result</strong></div>';
		echo '</div>';
		/*echo '<div style="font-size:20px;">	
				<label>Time Elapsed: </label><span id="timee"></span>
				</div>';*/
		
		foreach($objectList as $xobject) {
		  // Each XObject has its own header, get its own header as well as its detail array
		  $type = $xobject->getHeader()->getDetails();
		 
		  // The JPG has a subtype of 'Image' and require the PDF reader to use DCTDecode filter
		  if('Image' === $type['Subtype'] || 'DCTDecode' === $type['Filter']) {
		    //$filename = sprintf('%03d.jpg', ++$i);
		    $filename = sprintf('mergedPDF_'.'%d.jpg', ++$i);
		    //var_dump($filename);
		 
		    $dir_to_save = "img/".$code."/";
			
			if (!is_dir($dir_to_save)) {
			  mkdir($dir_to_save);
			}
			
		    file_put_contents($dir_to_save.$filename, $xobject->getContent());	 
		    //resizeImage($filename);   
			
			//check doc type
			//if(is_file($filename)) {
				
				try {       
				
					$directory = __DIR__ . "/img/".$code."/";
					
					$qrcode = new QrReader($directory.$filename);
					$decodeText = $qrcode->text(); 
					$result = $decodeText;       
				
					if ($result) {
						$result = $decodeText;
					} else {
						$result = "---";      
					}
					//}				
					//$newFilename = $pdf1_directory.str_replace('.pdf', '', $doc).'_'.$i.".pdf";					
					$res = '<div id="dataa" style="margin-left:20px;">Page '.$i.' - '.$result.'<br></div> ';		
					/*$res = '<div style="margin-left:20px;">Page '.$i.' - '.$docx.' - '.$result.'<br>
					<a href="'.$doc.'" target="_blank">'.$doc.'</a></div>';*/
					echo $res;
				
					} catch (Exception $e) {
						echo 'Caught exception: ',  $e->getMessage(), "<br>\n";
				}
				
			//}
			
		  } else {
			  echo 'No scanned image inside PDF... Not allowed.';
		  }

		}

		/*foreach( $images as $image ) {	
		    echo '<img src="data:image/jpg;base64,'.base64_encode($image->getContent()).'" />';
		}*/

		//check_docs($pagecount,$code);
		//echo json_encode('success');
		$noww = date("Y-m-d h:i:s");
		
		$database = new DB();   
		$update = array('date_ended' => $noww);
		$update_where = array('code' => $code);
		$database->update('doc_packages', $update, $update_where, 1);
		
		exit();
		//echo 'Done parsing...';
	}

	function resizeImage($filename) {

        $directory = __DIR__ . "/img/";
        $newName = $directory.$filename;

        $data = getimagesize($newName);
		$width = $data[0];
		$height = $data[1];
		//echo $width.' '.$height;
		$result = "";

		$image = new ImageResize($newName);
		
		if($image == null || $image == '') {
			$image->freecrop(230, 230, $x=1420, $y=45);			
        	$image->save('resized/'.$filename);	
		}

		if ($width <= 200 || $height <= 200) {        		
				$image->resizeToBestFit(250, 250);
				$image->save('resized/'.$filename);
        	} 
        else {
        		//$image->freecrop(700, 300, $x=1380, $y=45); //get top position
        		$image->crop(1200, 185, true, ImageResize::CROPTOP);
        		//$image->crop(1200, 166, true, ImageResize::CROPBOTTOM);
				$top = $image->save('resized/'.$filename);
        			
        		//$image1->crop(1200, 166, true, ImageResize::CROPBOTTOM);
				//$image1->save('resized_bottom/'.$filename);	
				if ($top) {
					//$image->freecrop(300, 300, $x=960, $y=1950);
					//$image->freecrop(700, 300, $x=660, $y=1950); //get bottom position
					$image->crop(1200, 166, true, ImageResize::CROPBOTTOM);
					$image->save('resized_bottom/'.$filename);
				}
        		
        }

        try {        	
				$directory44 = __DIR__ . "/resized";
				$directory55 = __DIR__ . "/resized_bottom";
				
				move_files($directory55, $directory44); //commented out

				/*$qrcode = new QrReader($newName);					
				$decodeText = $qrcode->text(); 
				$result = $decodeText;     */
		
			} catch (Exception $e) {
    			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}

        //echo $filename. '-' .$result.'<br>' ;     
        //$results = '<div style="margin-left:10px;"><a href="'.$filename.'" target="_blank">'.$filename.'</a>'.'-' .$result.'<br/></div>'; 	
        //$results = '<div style="margin-left:10px;">'.$filename.' - ' .$result.'<br/></div>'; 	
        //echo $results;		 
    }    
	
	function move_files($origin, $destination){
		$files = scandir($origin);
		$oldfolder = $origin."/";
		$newfolder = $destination."/";
		//$date = date('Y-m-d');
		foreach($files as $fname) {
			if($fname != '.' && $fname != '..') {
				if (file_exists($newfolder.$fname)) { 
					//echo 'File exist. <br>';
					$qrcode = new QrReader($oldfolder.$fname);					
					$decodeText = $qrcode->text(); 
					
					if ((filesize($oldfolder.$fname) > filesize($newfolder.$fname)) || $decodeText != false ) {						
						rename($oldfolder.$fname, $newfolder.$fname);
					} else {
						//echo 'smaller filesize';	
					}
				} else {								
					rename($oldfolder.$fname, $newfolder.$fname);				
				}
			}
		}
	}
	
    function file_count($directory) {

        $files = scandir($directory);
        $num_files = count($files)-2;

        return $num_files;
    }
	
	function check_docs($filecount, $code) {
  	
		//$pdf1_directory = '/split/compress'; //.$id;
		//$pdf1_directory = $pdf1_directory ? $pdf1_directory : './';
		
		//echo '<br><br>';
		//echo "No. of files: $filecount "."<br><br>";
		//echo '<h4>Results:';
		echo '<br><br><br>';
		echo '<div class="panel panel-primary">';
		echo '<div id="dataa" class="panel-heading text-left"><strong>Document Type:</strong></div>';
		echo '</div>';

		for ($i = 1; $i <= $filecount; $i++) {

			//$doc    =  __DIR__ . "/split/compress/mergedPDF_".$i.".pdf"; 		      
            //$rzDoc	=  __DIR__ . "/img/mergedPDF_".$i.".jpg"; 
            $docx    = "mergedPDF_".$i.".jpg";

			//$pp_count = count_pdf_pages($doc);
			//$result = get_doc_type($doc); //commented out
           
            //if ($result) {
            $directory = __DIR__ . "/img/".$code."/";
            			
			$newName = $directory.$docx;
			
			if(is_file($newName)) {
				$qrcode = new QrReader($newName);
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
				
				//$newFilename = $pdf1_directory.str_replace('.pdf', '', $doc).'_'.$i.".pdf";
				$res = '<div style="margin-left:20px;">Page '.$i.' - '.$result.'<br></div> ';		
				/*$res = '<div style="margin-left:20px;">Page '.$i.' - '.$docx.' - '.$result.'<br>
				<a href="'.$doc.'" target="_blank">'.$doc.'</a></div>';*/
				echo $res;
			} else {
				echo 'No scanned image inside PDF...<br>';
			}
		}

		echo '<br><br>';

	}	
	
	function unlinkr($dir, $pattern = "*") {
		$files = glob($dir . "/$pattern"); 
		foreach($files as $file){ 
			if (is_dir($file) and !in_array($file, array('..', '.')))  {
				//echo "<p>opening directory $file </p>";
				unlinkr($file, $pattern);  
				//echo "<p> deleting directory $file </p>";
				rmdir($file);
			} else if(is_file($file) and ($file != __FILE__)) {
				// make sure you don't delete the current script
				//echo "<p>deleting file $file </p>";
				unlink($file); 
			}
		}
	}
	
	function get_doc_type($doc) {

		$parser = new Smalot\PdfParser\Parser();

		$document = $parser->parseFile($doc);
		$decodetext = $document->getText();

		$prefix = "Printed:";
		$index = strpos($decodetext, $prefix) + strlen($prefix)+11;
		$result = substr($decodetext, $index);
		$result2 = substr($result, 0, strpos($result, "/"));
		if ($result2 == '') {
			$result2 = $result;
		}
		if (strlen($result2) > 80) {
			$result2 = '<b>UNKNOWN_TYPE</b>';
		}
		return $result2;
	}
	
	function count_pdf_pages($pdfname) {
		$pdftext = file_get_contents($pdfname);
		$num = preg_match_all("/\/Page\W/", $pdftext, $dummy);

		return $num;
	} 
	
	function DecodeQR($filename, $code) {

		$directory = __DIR__ . "/img/".$code."/";
	
		$mv = $directory.$filename;
		//echo $mv,"<br>";
		
		$qrcode = new QrReader($mv);					
		$decodeText = $qrcode->text(); 
		$result = $decodeText;     
		echo $result."<br>";

		return $result;

	}

	function pAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
		//header("Location: create.php"); 
		//exit();
	}
  
?>

