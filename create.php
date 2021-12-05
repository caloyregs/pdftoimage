<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style>
	* {
		margin-left: 1px;
		margin-right: 2px;
	}	
</style>

<style>
	#button{
	  display:block;
	  margin:20px auto;
	  padding:10px 30px;
	  background-color:#eee;
	  border:solid #ccc 1px;
	  cursor: pointer;
	}
	#overlay{	
	  position: fixed;
	  top: 0;
	  z-index: 100;
	  width: 100%;
	  height:100%;
	  display: none;
	  background: rgba(0,0,0,0.6);
	}
	.cv-spinner {
	  height: 100%;
	  display: flex;
	  justify-content: center;
	  align-items: center;  
	}
	.spinner {
	  width: 120px;
	  height: 120px;
	  border: 4px #ddd solid;
	  border-top: 4px #2e93e6 solid;
	  border-radius: 50%;
	  animation: sp-anime 0.8s infinite linear;
	}
	@keyframes sp-anime {
	  100% { 
		transform: rotate(360deg); 
	  }
	}
	.is-hide{
	  display:none;
	}
</style>

<br>
<?php /*session_start();*/ ?>

<body style="text-align: left;">
<div class="overlay"></div>

<div style="margin-left:10px;">
	<!--<button type="submit" onClick="location.href=location.href" class="btn btn-info">
	Refresh Page</button> <label id="clock">1</label>-->
	<br><br>
	<div class="panel panel-primary">
      <div class="panel-heading text-center"><strong>Split Document Package</strong></div>
      <div class="panel-body">
         <div class="row">
            <div class="col-sm-8">
               <form id="upload_form" action="upload.php" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                     <label><b>Select PDF Document:</b></label>
                     <!--<input type="file" class="form-control-file border" name="image">-->
                     <input type="file" class="form-control-file border" name="file" id="file" accept="application/pdf" class="btn" required>					 
					 <!--<progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
					 <h3 id="status"></h3>
					 <p id="loaded_n_total"></p>-->			 
					<input type="hidden" id="timee" name="timee" readonly>					 
                  </div>
            </div>
            <input type="submit" class="btn btn-primary" id="uploadd" name="uploadd" value="Split PDF File" onclick="">
            </form>		
         </div>		 
      </div>
	   
    </div>       	
	<div>
		<span style=" font-style: italic;">* Process time will depend on the filesize and number of documents. </span>
	</div>
	<div id="timex" style="font-size:35px;text-align:center;padding-top:210px;">	
		<label>Time Elapsed: </label><span id="time1">0:0:0</span>		
	</div>
</div>

<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<div id='result'></div>

<!--<div id='loader' style='display: none;'>
  <img src='loader.gif' width='120px' height='120px'>
</div>
<div class='response'></div>-->
	
</body>
<br><br>

<?php /*include 'upload.php';*/ 
	$_SESSION["time"] = "<script>document.writeln(timee);</script>";
?>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script type='text/javascript'>

	/*$(document).ajaxStart(function(){
	  // Show image container
	  $("#overlay").show();
	});
	$(document).ajaxComplete(function(){
	  // Hide image container
	  $("#overlay").hide();
	});*/
	
	/*.ajaxStart(function () {
		$loading.show();
	})
	.ajaxStop(function () {
		$loading.hide();
	});*/
	
	$(document).ready(function(){	
		$("#timex").hide();
		$("#upload_form").submit(function() {  
		
			//var $form = $(this),
			//url = $form.attr('action');

			/*$.ajax({
			   type: "POST",
			   url: url,
			   data: $form.serialize(),
			   success: function(data)
			   {
				   console.log(data);
					//alert("form submitted successfully");
			   },
			   error:function(data){
				   alert("there is an error kindly check it now");
			   }

			});*/

			/*var posting = $.post(url, {
				timee: $('#timee').val()
			});
			posting.done(function(data) {
				$('#result').text('success');
			});
			posting.fail(function() {
				$('#result').text('failed');
			});*/
			
			/*var href = $(this).val();
				$.session.set("test", "123");
				alert($.session.get("test"));*/
			$("#timex").show();
			$("#overlay").show();	
			s.startStop();	
			//$('#timee').val();				
			//start();
			return true;						

		});	 
		
		$("#upload_form").bind('ajax:complete', function() {         
			alert('test');
			//s.resetLap();
			//end();
			//s.doDisplay();
		});
			
		//$("#uploadd").click(function(){
		/*$(".submit").click(function(e) {  
			e.preventDefault();
			//$("#loader").show();
			//alert('test');
			//console.log('ready');
			$('#dataa div').empty();		
		  
			$.ajax({
				url: 'upload.php',
				type: 'post',
				data: {},
				processData: false,
				contentType: false,
				headers:  {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				beforeSend: function(){
					$("#overlay").show();		
					s.startStop();					
				},
				success: function(response){
					console.log(response);
					//echo json_encode('success');
					$('.response').empty();
					$('.response').append(response);
				},
				complete:function(data){
					$("#overlay").hide();
					s.resetLap();
				},
				error: function(xhr, ajaxOptions, thrownError) {
				   console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});	 
		});*/	 
		/*$("#upload_form").bind('ajax:complete', function() {         
			s.resetLap();
			//end();
			//s.doDisplay();
		});*/
	});
	
	/*var startTime, endTime;

	function start() {
	  startTime = new Date();
	};

	function end() {
	  endTime = new Date();
	  var timeDiff = endTime - startTime; //in ms
	  // strip the ms
	  timeDiff /= 1000;

	  // get seconds 
	  var seconds = Math.round(timeDiff);
	  console.log(seconds + " seconds");
	}*/
	
</script>

<script>
	/*var $loading = $('#myDiv').hide();
	$(document)
	  .ajaxStart(function () {
		$loading.show();
	  })
	  .ajaxStop(function () {
		$loading.hide();
	  });*/
	  
	var Stopwatch;
	if (!Stopwatch) 
		Stopwatch = {};

	/**
	 * Constructs a new Stopwatch instance.
	 * @param {Object} displayTime the strategy for displaying the time
	 */
	function Stopwatch(displayTime){
		this.runtime = 0; // milliseconds
		this.timer = null; // nonnull iff runnig
		this.displayTime = displayTime; // not showing runtime anywhere
	}

	/**
	 * The increment in milliseconds.
	 * (This is a class variable shared by all Stopwatch instances.)
	 */
	Stopwatch.INCREMENT = 200

	/**
	 * Displays the time using the appropriate display strategy.
	 */
	Stopwatch.prototype.doDisplay = function(){
		if (!this.laptime) 
			this.displayTime(this.runtime);
		else 
			this.displayTime(this.laptime);
	}

	/**
	 * Handles an incoming start/stop event.
	 */
	Stopwatch.prototype.startStop = function(){
		if (!this.timer) {
			var instance = this;
			this.timer = window.setInterval(function(){
				instance.runtime += Stopwatch.INCREMENT;
				instance.doDisplay();
			}, Stopwatch.INCREMENT);
		}
		else {
			window.clearInterval(this.timer);
			this.timer = null;
			this.doDisplay();
		}
	}

	/**
	 * Handles an incoming reset/lap event.
	 */
	Stopwatch.prototype.resetLap = function(){
		if (!this.laptime) {
			if (this.timer) {
				this.laptime = this.runtime;
			}
			else {
				this.runtime = 0;
			}
		}
		else {
			delete this.laptime;
		}
		this.doDisplay();
	}


	/** 
	 * This file uses a Stopwatch instance to handle DOM events.
	 * It assumes that the document has elements with the following IDs:
	 * time: placeholder to display time
	 * start: source of start/stop event
	 * reset: source of reset/lap event
	 */

	// A Stopwatch instance that displays its time nicely formatted.
	var s = new Stopwatch(function(runtime) {
	  // format time as m:ss.d
	  var hours = Math.floor(runtime / 3600000);
	  var minutes = Math.floor(runtime / 60000);
	  var seconds = Math.floor(runtime % 60000 / 1000);
	  var decimals = Math.floor(runtime % 1000 / 100);
	  var displayText =  hours + ":" +  minutes + ":" + (seconds < 10 ? "0" : "") + seconds ;
	  //var displayText = minutes + ":" + (seconds < 10 ? "0" : "") + seconds + "." + decimals;
	  $("#time1").text(displayText);
	  var timee = $("#timee").val(displayText);
	  //Session['timee'] = $("#timee").val(displayText);
	});

	// A Stopwatch instance that displays its raw time in milliseconds.
	/*var t = new Stopwatch(function(runtime) {
	  // display time in milliseconds
	  $("#time2").text("" + runtime);
	});*/

	// Code to create instances and wire everything together.
	/*$(document).ready(function(){
	  // DOM Level 2 event model allows us to attach more than one event listener to a source!
	  // jQuery's bind method hides browser-specific differences
	  // first stopwatch
	  $("#start1").bind("click", function(){ s.startStop(); });
	  $("#reset1").bind("click", function(){ s.resetLap(); });
	  s.doDisplay();
	  // second stopwatch
	  $("#start2").bind("click", function(){ t.startStop(); });
	  $("#reset2").bind("click", function(){ t.resetLap(); });
	  t.doDisplay();
	});*/
</script> 

<script>
	/*$(document).on("click", "#uploadd", function(){
		$.get("upload.php", function(data){
			$("body").html(data);
		});       
	});
	
	$(document).on({
		ajaxStart: function(){
			$("body").addClass("loading"); 
		},
		ajaxStop: function(){ 
			$("body").removeClass("loading"); 
		}    
	});*/
</script>

<script>
	/*jQuery(function($){
	  /*$(document).ajaxSend(function() {
		$("#loader").fadeIn(300);　
	  });
			
	  $('#button2').click(function(){
		$.ajax({
		  url: "upload.php",
		  type: 'POST',
		   beforeSend: function(){
			// Show image container
			$("#loader").show();
		   },
		  success: function(data){
			console.log(data);
		  },
		  /*complete:function(data){
			// Hide image container
			$("#loader").hide();
		   }
		  });
		}).done(function() {
		  setTimeout(function(){
			$("#loader").fadeOut(300);
		  },500);
		});
		
	  });	
	});*/
	
	/*$(document).ready(function(){
         function getdate(){
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
             if(s < 10){
                 s = "0"+s;
             }
             if (m < 10) {
                m = "0" + m;
            }
            $("#clock").text(h+" : "+m+" : "+s);
             setTimeout(function(){getdate()}, 500);
            }
			
		/*$("#button2").click(function(){	
			//alert('ready');	
			$("#dataa").empty();			
			getdate();
		});
    });*/
</script>	

<script>
function _(el) {
  return document.getElementById(el);
}

function uploadFile() {
  var file = _("file").files[0];
  // alert(file.name+" | "+file.size+" | "+file.type);
  var formdata = new FormData();
  formdata.append("file", file);
  var ajax = new XMLHttpRequest();
  ajax.upload.addEventListener("progress", progressHandler, false);
  ajax.addEventListener("load", completeHandler, false);
  ajax.addEventListener("error", errorHandler, false);
  ajax.addEventListener("abort", abortHandler, false);
  ajax.open("POST", "upload.php"); // 
  //use file_upload_parser.php from above url
  ajax.send(formdata);
}

function progressHandler(event) {
  _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
  var percent = (event.loaded / event.total) * 100;
  _("progressBar").value = Math.round(percent);
  _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
}

function completeHandler(event) {
  _("status").innerHTML = event.target.responseText;
  _("progressBar").value = 0; //will clear progress bar after successful upload

}

function errorHandler(event) {
  _("status").innerHTML = "Upload Failed";
}

function abortHandler(event) {
  _("status").innerHTML = "Upload Aborted";
}
</script>

<script type="text/javascript">
	/*$(document).ready(function() {
		$('#upload_form').submit(function(e) {
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: 'upload.php',
				data: $(this).serialize(),
				headers:  {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success: function(response)
				{
					alert('OK');
					var jsonData = JSON.parse(response);
	  
					// user is logged in successfully in the back-end
					// let's redirect
					if (jsonData.success == "1")
					{
						alert('Done');
						//location.href = 'my_profile.php';
					}
					else
					{
						alert('Invalid!');
					}
			   }
		   });
		});
	});*/
</script>

