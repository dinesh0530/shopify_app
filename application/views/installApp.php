<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" type="image/png" href="<?php echo base_url("assets/images/favicon-new.png") ?>"/>
        <title>Imoprtapp</title>
        <script src="<?php echo base_url('assets/js/plugins/jquery-3.3.1.min.js'); ?>" type="text/javascript"></script>
        <link href="<?php echo site_url(); ?>assets/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />  
        <script src="<?php echo base_url('assets/js/plugins/jquery.validate.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/js/plugins/additional-methods.min.js'); ?>" type="text/javascript"></script>
        <link href="<?php echo site_url(); ?>assets/css/install_app.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="center-div">
            <div class="content">
            <?php $this->load->helper('form'); ?>
            <div class="page-header">
                <center>
                    <?php if ($this->session->flashdata('shop_error')) { ?>
                        <div class="alert alert-danger alert_message" role="alert"> 
                            <?php echo $this->session->flashdata('shop_error'); ?>
                        </div>
                    <?php } ?>
                    <h2>Please enter store url</h2>
                </center>
            </div>
            <?php echo form_open('auth/access/',['id'=>'installation_form']); ?>
                <div class="form-group url_form">
                    <span class="error-label text-danger"></span>
                    <input type="text" name="store_name" id="nameTextBox" class="form-control input_field" placeholder="Store URL" autofocus>
                    <span><em><strong>Note:</strong></em>&nbsp;Store url should be like as xyz.myshopify.com</span>
                </div>
                <div class="form-group">
                    <center><input type="submit" id="submit" class="btn btn-default"/></center>
                </div>
            <?php echo form_close(); ?>
            </div>
        </div>
    </body>
    <script src="<?php echo site_url(); ?>assets/js/installApp.js"></script>
</html>