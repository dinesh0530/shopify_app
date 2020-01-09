<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div id="site-footer-section">
			<div class="container">
				<div class="site-top-footer">
					<h3>Import App Integrates With The Top Shopify Platforms</h3>
					<p>
						<a href="#"><img src="<?php echo base_url("assets/images/f-logo.png") ?>"></a>
					</p>
				</div>
				<div class="site-bottom-footer">
					<ul class="site-bottom-footer-nav">
						<?php $this->load->view('menus/menus' , $this->data); ?> <!-- menus View -->
					</ul>
					<div class="bottom-header-social-icons">
						<?php $this->load->view('socialIcons' , $this->data); ?> <!-- Social icon view -->
					</div>
				</div>
		    </div>
		<div class="site-copyright-section">
			<p>
				Copyright Import App 2018 All Rights Reserved
			</p>
		</div>
		<span id="start_chat"><a href="javascript:void(0)"><i class="fab fa-rocketchat"></i></a></span>
		<div id="chat_mail"> 		
		<div id="any_query"><a href="javascript:void(0)"><h2>Ask Question</h2></a></div>          
				<fieldset>					
					<div id = "manedotery" style="color: green"; font-size:13px; class="chat_success_msg"> </div>
					<?php echo form_label('Name','Name'); ?> <br>
					<?php echo form_input(array( "type" =>  "text" ,"name" => "name" , "id" => "name" , "placeholder" => "Enter your name" )); ?>	<br>
					<div id="nm_msg" class="chat_error"> </div>
					<?php echo form_label('Email', 'Email'); ?>
					<div class="price_inventory_input">
					<?php echo form_input(array( "type" =>  "email" ,"name" => "Email" , "id" => "email" , "placeholder" => "example@xyz.com" )); ?><br> 
					<div id="email_msg" class="chat_error mail_msg"> </div>
					<?php echo form_label('Message','Query'); ?> <br>
					<?php echo form_textarea(array( "type" =>  "query" ,"name" => "query" , "id" => "query" , "placeholder" => "Enter your message here")); ?>	<br>
					<div id="query_msg" class="chat_error"> </div>
					<?php  echo form_button(array( "id" => "send_mail" , "type" => "submit" , "value" => "Submit" , "content" => "Submit" )); ?>	
					</div>
				</fieldset>
		</div>      
<style>
#chat_mail{ 
  display : none;
} 
</style>	 
	 </div>	 
  </body>
</html>
<script>
		$(document).ready(function(){	
    $("#start_chat").click(function(){
		//	alert();
        $("#chat_mail").toggle();
		$("#start_chat").hide();
    });
});     
	$(document).ready(function(){
	$("#any_query").click(function(){
		$("#chat_mail").hide();
		$("#start_chat").show();
	});
});
 $("#send_mail").click(function(e){
	 var email = $("#email").val();
	 var query = $("#query").val();	
	//	alert(email);
	//	alert(query);
		/* if ($.trim(email).length == 0 || $("#query").val()=="") 
		{			
			$(".manedotery").html("All  fields are mandetory"); 	
			e.preventDefault();
		}	 */
		if($("#name").val()==""){
			 $("#nm_msg").html("Please enter name");			
				e.preventDefault();
		}	
		else{
			$("#nm_msg").html("");
		}		
		 var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		if($.trim(email).length == 0 ){
			 $("#email_msg").html("Please enter valid email"); 
		    	e.preventDefault();
		}	
		else if(!filter.test(email)){
			 $(".email_msg").html("Please enter valid email");  //.delay(5000).fadeOut(); 
				e.preventDefault();
			
		}
		else{
			$("#email_msg").html("");
		//	$("#mail_msg").hide();
		}
		if($("#query").val()==""){
			 $("#query_msg").html("Please enter query");  //.delay(5000).fadeOut(); 
		//	 $('#query_msg').delay(5000).fadeOut('slow');
				e.preventDefault();
		}	
		else{
			$("#query_msg").html("");
		}			 
			if (filter.test(email) && $("#query").val()!="" && $("#name").val()!="") {  
			//  alert();
					 $.ajax({
						type : 'post',
						url : '<?php echo base_url("Support/chat")?>',
						data: {'email' : email,'query' : query},
						success:function(data){	
								$('#manedotery').html(data).fadeIn('slow');
								 $('#manedotery').delay(5000).fadeOut('slow');
						}
					});	 		
			}			
			/* else {				
	        $(".mail_msg").html("Please enter valid email");  //.delay(5000).fadeOut(); 
				e.preventDefault();
			}	 */ 
 });		
</script>