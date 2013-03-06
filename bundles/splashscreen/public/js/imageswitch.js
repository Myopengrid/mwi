var counter = 1;
function bkg_switch() {

	$('#background_image').fadeOut('slow', function(){
      $(this).attr('src',BKG_IMAGES[counter]).bind('onreadystatechange load', function(){
         if (this.complete) $(this).fadeIn('slow');
      });
   });
    
    counter += 1;

    if (counter == BKG_IMAGES.length) {
        counter = 0;
    }
}

$(function() {
	
    if(EFFECT == "time_of_day")
    {
	   bkg_rotate();
    }

    if(EFFECT == "loop_background")
    {
        // Generate a random number for
    	// starting image
    	var number = 1 + Math.floor(Math.random() * BKG_IMAGES.length-1);
    	$('#background_image').attr('src', BKG_IMAGES[number]);
    	
    	var bkg_switch_time = BKG_DELAY_TIME;
    	
    	setInterval(bkg_switch, bkg_switch_time);
    }
});

function bkg_rotate() 
{ 
	var images = BKG_IMAGES;
	var myDate = new Date();
	var hour = myDate.getHours(); 
	var index = Math.floor(hour/8); 

	if      (hour < 5) index = 3; 
	else if (hour < 10) index = 0;
	else if (hour < 18) index = 1; 
	else if (hour < 21) index = 2;
	else if (hour < 24) index = 3;
	else    index = 1;

	d = new Date();
	
	$('#background_image').attr("src", images[index]+"?"+d.getTime());
}

