<!DOCTYPE html>
<head>
  <title>Live cricket scoreboard!</title>
  <script src="jquery.js"></script>
  <link rel="stylesheet" href="bootstrap.css">
</head>

<script>
	$(document).ready(function(){
    //Handle button click and send the selected value to processdata.php
    $('#btnSubmit').click(function() {
      $.ajax({
        type: "POST",
        url: "processdata.php",  
        data: { 
         country: $('#selMatch').val() 
       }
     }).done(function(output) {	
      alert("Selected match is: "+$('#selMatch').val().replace(/[^a-z\s]/gi,'').replace(" v ",'vs '));  
    });    
   });
  });
</script>

<body>

  <?php
  //Get the current ongoing matches using the cricapi. Please change the apikey to your own key.
  $cricketMatchesTxt = file_get_contents('http://cricapi.com/api/cricket/?apikey=123456789');
  $cricketMatches = json_decode($cricketMatchesTxt);
  ?>

  <form method="post">	
   <div class="container">
    <h1>Welcome to live scoreboard!</h1>
    <div class="row">
      <div class="col-md-4">          
        <div class="form-group">
          <label for="selCountry">Match:</label>
          <select class="form-control" id="selMatch">
            <option>Select Match...</option>
            <?php
            //Load ongoing matches in dropdown box. Then remove special symbols and numbers and split the string to get country names.
            foreach($cricketMatches->data as $item) {			
             $rm_amp = str_replace('&amp;', '',  $item->title);	
             $rm_special = preg_replace('/[^\p{L}\p{N}\s]/u', '', $rm_amp);
             $rm_number = preg_replace('/\d+/u', '', $rm_special);	
             $v_separate = explode(" v ", $rm_number);
             ?>    
             <option value="<?php echo $item->title ?>"><?php echo $v_separate[0]."vs".$v_separate[1]?></option>	
             <?php } ?>
           </select>            
         </div>
         <div class="form-group">
          <button class="btn btn-success" id="btnSubmit" name="btnSubmit">Select to Diplay!</button>
        </div>
      </div>
    </div>
  </div>
  <footer class="footer">
    <div class="container">
      <span class="text-muted">Please like, share and subscribe to my channel! <a target="_" href="https://www.youtube.com/channel/UCPm5InL8fMRQO2ijjzrRt0Q">Link here</a></span>
    </div>
  </footer>
</body>
</html>

