<?php

	require __DIR__ . "/vendor/autoload.php";
	include 'vendor/autoload.php';

	use \setasign\Fpdi\Fpdi;
	use \Gumlet\ImageResize;
	use Zxing\QrReader;

	$file = "compress/mergedPDF.pdf";
	//imageParsing($file);
	//check_docs2(100);
	//qrTest("mergedPDF_56.jpg");
	
	include('includes/conn.db.php' );
	include('includes/class.db.php' );

	$uid = 2;
	$trxn = $uid.'_'.generateID(20);
	$d2  = new Datetime("now");
	$d22 = $d2->format('Ymd_His');
	$code = $uid.'_'.$d22; //->format('U');
	//echo $code;
	
   	$database = new DB();   
   	$noww = date("Y-m-d h:i:s");
    
    /*$user_data = array(
		   'user_id' => 2, 
		   'code' => $code, 
           'date_created' => $noww, 
           'doc_name' => 'DOCUMENT TEST', 
           'page_num' => 10
    );

    $rs = $database->insert('doc_packages', $user_data);
	
	if ($rs) {	
		createPath($code);
	}*/
	
	function createPath($trxn) {
		if (!file_exists('img/'.$trxn)) {
			mkdir('img/'.$trxn, 0777, true);
		}
		//echo 'OK';
	}
	
	function makedirs($dirpath, $mode=0777) {
		return is_dir($dirpath) || mkdir($dirpath, $mode, true);
	}
	
	function imageParsing($file) {
				
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
		foreach($objectList as $xobject) {
		  // Each XObject has its own header, get its own header as well as its detail array
		  $type = $xobject->getHeader()->getDetails();
		 
		  // The JPG has a subtype of 'Image' and require the PDF reader to use DCTDecode filter
		  if('Image' === $type['Subtype'] || 'DCTDecode' === $type['Filter']) {
		    //$filename = sprintf('%03d.jpg', ++$i);
		    $filename = sprintf('mergedPDF_'.'%d.jpg', ++$i);
		    //var_dump($filename);
		 
		    $dir_to_save = "img/";
			if (!is_dir($dir_to_save)) {
			  mkdir($dir_to_save);
			}
		    file_put_contents($dir_to_save.$filename, $xobject->getContent());		 
		    resizeImage($filename);   
		  }

		}

		/*foreach( $images as $image ) {	
		    echo '<img src="data:image/jpg;base64,'.base64_encode($image->getContent()).'" />';
		}*/

		exit();
		//echo 'Done parsing...';
	}

	function pdfSplitPDF($filename, $pdf_directory = false) {

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

		/*echo '<div class="panel panel-primary">';
		echo '<div class="panel-heading text-left"><strong>Document Preview</strong></div>';
		echo '</div>';*/

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

				$filename 	= "mergedPDF_".$i.".jpg";
	            resizeImage($filename);

			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}

		}

		
		//echo '<br>';		
        //pdfToImage($pagecount, '');
        //check_docs($pagecount);
		//$pdf->close();        
		echo 'Done parsing...';
		exit();
	}

	function pdfToImage($filecount, $pdf_directory='') {

        $pdf_directory = $pdf_directory ? $pdf_directory : './';

        for ($i = 1; $i <= $filecount; $i++) {

            $doc  = __DIR__ . "/split/compress/mergedPDF_".$i.".pdf";            
            //$img  = __DIR__ . "/img/mergedPDF_".$i.".jpg"; 

            $filename 	= "mergedPDF_".$i.".jpg";
			//echo $filename;
            //$imgName 	= $pdf_directory.str_replace('.jpg', '', $filename).'_'.$i.".jpg";
            //echo $imgName;
            
			//convToImage($doc); //commented out
            //echo $filename.' done resizing. <br>';
            resizeImage($filename);
      
        }
		
    }

    function resizeImage($filename) {

        $directory = __DIR__ . "/img/";
        $newName = $directory.$filename;

        $data = getimagesize($newName);
        //$data = getimagesize($filename);
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

				$qrcode = new QrReader($newName);					
				$decodeText = $qrcode->text(); 
				$result = $decodeText;     
		
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

	function check_docs($filecount,$id) {
  	
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
			$res = '<div style="margin-left:20px;">Page '.$i.' - '.$docx.' - '.$result.'<br>
         	<a href="'.$doc.'" target="_blank">'.$doc.'</a></div>';     	
			echo $res;
		}

		echo '<br><br>';

	}

	function check_docs2($filecount) {
  	
		//$pdf1_directory = '/split/compress'; //.$id;
		//$pdf1_directory = $pdf1_directory ? $pdf1_directory : './';
		
		//echo '<br><br>';
		//echo "No. of files: $filecount "."<br><br>";
		//echo '<h4>Results:';
		echo '<br><br><br>';
		echo '<div class="panel panel-primary">';
		echo '<div class="panel-heading text-left"><strong>Document Type:</strong></div>';
		echo '</div>';

		for ($i = 1; $i <= $filecount; $i++) {

			//$doc    =  __DIR__ . "/split/compress/mergedPDF_".$i.".pdf"; 		      
            //$rzDoc	=  __DIR__ . "/img/mergedPDF_".$i.".jpg"; 
            $docx    = "mergedPDF_".$i.".jpg";

			//$pp_count = count_pdf_pages($doc);
			//$result = get_doc_type($doc); //commented out
           
            //if ($result) {
            $directory = __DIR__ . "/img/";
            $newName = $directory.$docx;

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
		}

		echo '<br><br>';

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

	function qrTest($filename) {
		$directory = __DIR__ . "/img/";
		$newName = $directory.$filename;
		//echo  $newName;
		$qrcode = new QrReader($newName);
		$decodeText = $qrcode->text(); 
		$result = $decodeText;            
		echo $result;
		if ($result) {
			$result = $decodeText;
		} else {
			$result = "---";      
		}
		//return $result;
	}

?>