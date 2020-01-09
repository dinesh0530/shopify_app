<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta name="viewport" content="width=device-width">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<title>Importapp</title>
		<link rel="shortcut icon" type="image/png" href="<?php echo base_url("assets/images/favicon-new.png") ?>"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<?php $this->load->view('cssView'); ?>
		<?php $this->load->view('jsView'); ?>
		<script src="<?php echo base_url('js/jquery.validate.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />		
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />		
	</head>
		<body class="<?php echo isset($bodyClass) ? $bodyClass : ''; ?>">
		<header id="site-header">
			<div class="site-top-heaer">
				<div class="container">
					<div class="top-header-social-icons">
						<?php $this->load->view('socialIcons' , $this->data); ?>
					</div>
					<div class="top-header-logo">
						<a href="<?php echo base_url() ?>">
							<img width="200px" src="<?php echo base_url("assets/images/logo.png") ?>">
						</a>
					</div>
					<div class="top-header-user-login">
						<?php if(isset($result)){?>
							<div class="dropdown_main">
							<div class="login_name">
							 <a href="javascript:void(0)" class="login___user">
							 <?php echo $result->firstname; ?></a>
							 </div>
							 <ul class="loggedin_dropdown" style="display:none;">
							 	<li class="dropdown_li"><a href="<?php echo site_url("profile/edit-profile"); ?>" class="edit_profile_link">Edit your Profile </a></li>
							 	<li class="dropdown_li"><a href="" class="shipping_link">Shipping Tool </a></li>
							 	<li class="dropdown_li"><a class="logout" href="<?php echo site_url("logout"); ?>">Logout</a></li>
							 </ul>
							</div>
						 <?php }else if(isset($login)){ ?>
						 	<div class="dropdown_main">
						 	<div class="login_name">
							 <a href="javascript:void(0)" class="login___user">
							  <?php echo $login->firstname; ?>
							 </a>
							 </div>
							 	<ul class="loggedin_dropdown" style="display:none;">
							 	<li class="dropdown_li"><a href="<?php echo site_url("profile/edit-profile"); ?>" class="edit_profile_link">Edit your Profile </a></li>
							 <li class="dropdown_li">	<a href="" class="shipping_link">Shipping Tool </a></li>
							 	<li class="dropdown_li"><a class="logout" href="<?php echo site_url("logout"); ?>">Logout</a></li>
							 </ul>
							</div>
						 <?php }else{ ?>
							<a href="<?php echo site_url("login"); ?>" class="fa fa-user"><span>Login</span></a> 
						 <?php } ?>
					</div>
				</div>
			</div>
			<div class="site-bottom-header">
				<div class="container">
					<ul class="desktop_menu">                    				
						<?php $this->load->view('menus/menus' , $this->data); ?>
					</ul>
				</div>
					<div class="mobile_menu">
						<div id="toggle-bar">
							<a class="mtoggle expand" href="javascript:void(0)"><span></span><span></span><span></span></a>
						</div>
						<ul id="mmenu">                    				
							<?php $this->load->view('menus/menus' , $this->data); ?>
						</ul>
					</div>
				
			</div>
		 </header>

<script type="text/javascript">
	jQuery(document).ready(function($) {		 
		$(".login___user").click(function(){
		   $('.loggedin_dropdown').toggle();
		});	
		$("body").addClass("import-app-view");
		
		$("#mmenu").css("display","none");
		$(".close").hide();
		$(".mtoggle").click(function() {
			$("#mmenu").toggle();
			$(".mtoggle").toggleClass("close");
		});
		
	});
</script>