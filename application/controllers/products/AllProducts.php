<?php defined('BASEPATH') OR exit('No direct script access allowed');
class AllProducts extends MY_Controller {	
    public function __construct(){
		parent::__construct();
		$userid = $this->session->userdata('login')->id;
		if($userid == 0)
		{
			redirect('login');
		}
		$this->load->model('Categories_model');
		$role= $this->session->userdata('login')->role_id;
		$this->data['role'] = $role;
		if($role == 2){
			$user_pay_status = $this->User_model->get_pay_status($userid); 
			$expiry_date = $this->User_model->get_date($userid);
			$this->data["user_pay_status"] = $user_pay_status;
			$this->data["expiry_date"] = $expiry_date;
			$result = $this->User_model->check_status($userid); 
			$expiry_date = $this->User_model->get_date($userid);
			$date1 = date("Y-m-d h:i:s");
			$datetime1 = new DateTime($date1);
			$datetime2 = new DateTime($expiry_date[0]['expiry_date']);
			$shop = $this->session->userdata('shop');
			$shop_payment = $this->Shop->shop_payment_status($shop); 
			if(!empty($shop_payment)){
				if(empty($result) || $datetime1 > $datetime2 || $shop_payment[0]['payment_status_shop'] != 1){
					redirect('user/payment');
				}
			}
			if(empty($result) || $datetime1 > $datetime2){
				redirect('user/payment'); 
			}
		}
		$this->date	= date("Y-m-d H:i:s");
		$this->load->helper("image_resize_helper");
		$this->load->helper("image_crop_helper");		
	}

/* get all products for admin */
	public function index(){				
		$role_id = $this->session->userdata('login')->role_id;  
		$user_id = $this->session->userdata('login')->id;		
		$this->data['categories'] = $this->Categories_model->getCategory();		
		if($role_id != 3){
			$this->data['suppliers'] = $this->Suppliers_model->fetch_suppliers();
		}
		else{
			$this->data['suppliers'] = $this->Suppliers_model->vandor_supplier_data($user_id);
		}
		$this->data['bodyClass'] = 'product_page';
		$this->data["active"] = "test";
		$this->load->view('headerView', $this->data);
		
		$params = array();
        $limit = 20;
        $page = $this->uri->segment(2);
		
		if($role_id != 3){
        $total_records = $this->Product_model->record_counts();
		}
		else{			
			 $total_records = $this->Product_model->vendor_record_counts($user_id);
		}
		
		if($this->input->get('supplier') || $this->input->get('category')){
			$keyword = $this->input->get('supplier');
			$category = $this->input->get('category');			
			if($role_id != 3){
			$total_records = $this->Product_model->keyword_record_counts($keyword,$category);
			}
			else{				
				 $total_records = $this->Product_model->vendor_keyword_record_counts($user_id,$keyword,$category);				 
			}
		}	
		  if(empty($page)){
			  $page = 0;
			
		  } else {
			
			  $page = $page-1;
		  } 		
		
        if($total_records > 0){
			$user_id = $this->session->userdata('login')->id;
            $params["results"] = $this->Product_model->cate_data($limit, $page*$limit,$user_id);
			
			if($this->input->get('supplier') || $this->input->get('category')){
              $params["results"] = $this->Product_model->keyword_data($limit, $page*$limit,$user_id,$keyword,$category);
            }
            $config['base_url'] = base_url() . 'all-products';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit;
            $config["uri_segment"] = 2;           
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
             
            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';
             
            $config['first_link'] = 'First Page';
            $config['first_tag_open'] = '<span class="firstlink">';
            $config['first_tag_close'] = '</span>';
             
            $config['last_link'] = 'Last Page';
            $config['last_tag_open'] = '<span class="lastlink">';
            $config['last_tag_close'] = '</span>';
             
            $config['next_link'] = 'Next Page';
            $config['next_tag_open'] = '<span class="nextlink">';
            $config['next_tag_close'] = '</span>';
 
            $config['prev_link'] = 'Prev Page';
            $config['prev_tag_open'] = '<span class="prevlink">';
            $config['prev_tag_close'] = '</span>';
 
            $config['cur_tag_open'] = '<span class="curlink">';
            $config['cur_tag_close'] = '</span>';
 
            $config['num_tag_open'] = '<span class="numlink">';
            $config['num_tag_close'] = '</span>';
             
            $this->pagination->initialize($config);                
            
            $params["links"] = $this->pagination->create_links();
        } 
		if(isset($_POST['Export']))	{				
			header('Content-Type: text/csv; charset=utf-8');  
			header('Content-Disposition: attachment; filename=Export_product.csv');  
			$output = fopen("php://output", "w");  
			fputcsv($output, array('Name','Category','Description','Supplier','Price','SKU','Stock level','Wait time','Weight','Price from vendor','Wholesale price','img'));  
			$result = $this->Product_model->export_product($role_id,$user_id);	
			if(empty($result)){die;}
			else{
				foreach($result as $row)
				{		
					$img = $this->Product_model->get_img($row['id']);
					$productId = $row['id'];			
					$row['id'] = $config['base_url']."/uploads/product$productId/".$img;			
					fputcsv($output, $row);			
				}
				fclose($output);  
				die;
			}
		}
		
      /********** Import Product Code start Here *************/
	  
		if(isset($_FILES["file"])){		
			$filename=$_FILES["file"]["tmp_name"];				
			if($_FILES["file"]["size"] > 0){
				$file = fopen($filename, "r");	
				$counter = 1;
				while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
					if($counter >1){		
						$category = $this->Product_model->get_categoryId($getData[1]);
						$supplier = $this->Product_model->get_supplierId($getData[3]);	
						$title_name = $this->Product_model->title_check($getData[0]);
						if($title_name > 0){
							$this->session->set_flashdata('errorImportp', "Some Product(s) name already exists");
							continue;						
						}else{
							$_FILES['product_images']=$getData[11];						
							$data = array(
								'product_add_by' =>$user_id,
								'product_title' => $getData[0],
								'product_category'=> $category[0]['id'],
								'product_desc' =>$getData[2],
								'product_supplier'=>$supplier[0]['id'],
								'product_price'=>$getData[4],
								'product_sku'=>$getData[5],
								'product_stock'=>$getData[6],
								'product_wait_time'=>$getData[7],
								'product_weight'=>$getData[8],
								'price_form_vendor'=>$getData[9],
								'wholesale_price'=>$getData[10]
							);								
							$result = $this->Product_model->import_product($data);	
							$productid = $this->Product_model->get_pId($getData[0]);
							$productId = $productid[0]['id'];						
							if(!empty($getData[11]))
							{
							  $show_image = explode(',',$getData[11]);					
								foreach($show_image as $pic)
								{
									settype($pic, "string");
									$url = $pic;
									$folder = "./uploads/product$productId";	
									if (!is_dir($folder))
									{
										mkdir($folder);
									}	
									$contents=file_get_contents($url);
									$productId."-".rand()."img.jpg";
									$image_name = $productId."-".rand()."img.jpg";
									$save_path="./uploads/product$productId/".$image_name;
									file_put_contents($save_path,$contents);
									$data = array(
										'product_id' => $productId,
										'src' => $image_name						
										);									
									$this->Product_model->import_images($data);
								}
							}
							else
							{  
								continue; 
							}
							$this->session->set_flashdata('successImportp', "Product(s) successfully imported");
						}
					}
					$counter++;
				}					
				fclose($file);	
			 }
		 redirect('all-products');
		} 
	  /************ End here *************/		
			
		$this->load->view('products/allProductsView', $params);
		$this->load->view('footerView', $this->data);		
	}    

/*********** Download template ********/

	public function download_temp(){
		header('Content-Type: text/csv; charset=utf-8');  
		header('Content-Disposition: attachment; filename=product_template.csv');  
		$output = fopen("php://output", "w");  
		fputcsv($output, array('Name','Category','Description','Supplier','Price','SKU','Stock level','Wait time','Weight','Price from vendor','img')); 
		fclose($output);  
		die;			
	}
		
/*********** Add new Item **********/ 
	public function createItem(){
        $user_id = $this->session->userdata('login')->id;	

        if($this->input->method(TRUE) == "GET"){
			$src_id = $this->input->get('src_id');
			$req_by = $this->input->get('req_by');
			$this->data['src_pro'] = $this->Product_model->get_src_pro($src_id,$req_by);
		}
		
		if($this->input->method(TRUE)=="POST")
		{			
			$this->form_validation->set_rules('product_title', 'name', 'required');
			$this->form_validation->set_rules('product_category', 'category', 'required');
			$this->form_validation->set_rules('product_supplier', 'supplier', 'required');
			$this->form_validation->set_rules('product_price', 'price', 'required');
			$this->form_validation->set_rules('product_sku', 'SKU', 'required');
			$this->form_validation->set_rules('img_src', '', 'callback_file_check');
			$this->form_validation->set_rules('product_title', 'name', 'callback_name_check');
			$this->form_validation->set_rules('wholesale_price', 'Wholesale price', 'callback_name_check');
			$this->form_validation->set_rules('default_variants_price', 'Default variants price', 'required');
			$this->form_validation->set_rules('live_in', 'Product Live in(Days)', 'required');
			
			if ($this->form_validation->run() == TRUE)
			{
		        $data = array(			  
					'product_add_by' => $user_id,
					'product_category' => $this->input->post('product_category'),
					'product_desc' => $this->input->post('product_desc'),
					'product_title' => $this->input->post('product_title'),
					'product_supplier' => $this->input->post('product_supplier'),
					'product_price' => $this->input->post('product_price'),
					'product_sku' => $this->input->post('product_sku'),
					'product_stock' => $this->input->post('product_stock'),
					'product_wait_time' => $this->input->post('product_wait_time'),
			    	'product_weight' => $this->input->post('product_weight'),
			    	'price_form_vendor' => $this->input->post('price_form_vendor'),
			    	'wholesale_price' => $this->input->post('wholesale_price'),
			    	'default_variants_price' => $this->input->post('default_variants_price'),
			    	'live_in' => $this->input->post('live_in'),
			    	'shipping_price' => 0
			    );	
				$role= $this->session->userdata('login')->role_id;
				if($role == 1 && $this->input->post('sourcing_id') > 0){
					$data['publically_status'] = $this->input->post('publically_status');
				}else if($role == 3 && $this->input->post('sourcing_id') > 0){
					$data['publically_status'] = 1;
				}
				
				$product_name = $data['product_title'];
				$supplierId = $data['product_supplier'];
				$productId = $this->Product_model->saveProduct($data);
				if($this->input->post('sourcing_id') > 0 && $this->input->post('requested_by') > 0 && !empty($productId)){
					$sourced_data = array(
						'sourcing_id' => $this->input->post('sourcing_id'),
						'requested_by_id' => $this->input->post('requested_by'),
						'product_id' => $productId,
						'created_at' => $this->date
					);
					$insert_sourced = $this->Product_model->insert_source_approved($sourced_data);
					$updatesource = $this->Product_model->updatesource($this->input->post('sourcing_id'));
				}
				$files = $_FILES['img_src'];				
				$folder = "./uploads/product$productId";
				
				if (!is_dir($folder))
				{
					mkdir($folder);
				}
				
				$config['upload_path']   = $folder;
                $config['allowed_types'] = 'gif|jpg|png|mp4|3gp|avi|flv|mkv|m4v|mov|mpeg|mpg';
                $config['max_size']      = 102400;
				$filesCount = count($files['name']);
				$errors = array();
				for($j = 0; $j < $filesCount; $j++){				
					$this->upload->initialize($config);
					$_FILES['img_srcs']['name'] = $productId."-".rand().str_replace("_","",$files['name'][$j]);
					$_FILES['img_srcs']['type'] = $files['type'][$j];
					$_FILES['img_srcs']['tmp_name'] = $files['tmp_name'][$j];
					$_FILES['img_srcs']['error'] = $files['error'][$j];
					$_FILES['img_srcs']['size'] = $files['size'][$j];
					if($this->upload->do_upload('img_srcs')){					
						$data_upload_files = $this->upload->data();
						$uploadData[$j]['product_id'] = $productId;						
						$uploadData[$j]['src'] = $data_upload_files['file_name'];
						$uploadData[$j]['created_at'] = $this->date;						
						$file_name = $_FILES['img_srcs']['name'];
						$width = THUMB_BIG; $height = THUMB_BIG;$thumb_marker ="thumb-big_";
						$width = THUMB_SMALL; $height = THUMB_SMALL;$thumb_marker ="thumb_";				
					}
					else{					
						$errors[] = $this->upload->display_errors("<span class='error'>".$_FILES['img_srcs']['name']." ", "</span>");
					}
				}
				if(!empty($uploadData)){
					$result = $this->Product_model->uploadImages($uploadData);				
				}  					 
				$optionsNameArray	 = $this->input->post('options-name'); 
				$optionsValueArray	 = $this->input->post('options-values');
				$variantOptionsData = array('pid'=>$productId);
				$count = 1;
				foreach( $optionsValueArray as $k => $v  ){
					if(!empty($v)){
						$opt = 'option'.$count.'_name';						
						$val = 'option'.$count.'_value';
						$variantOptionsData[$opt] = $optionsNameArray[$k];
						$variantOptionsData[$val] = $v;
						$count++;
					}
				}
				if( count($variantOptionsData) > 1){						
						$optionsId = $this->Product_model->saveVariantOptions($variantOptionsData);
				}
				if(isset($_POST['var'])){
					$variants  =  $_POST['var'];
					foreach($variants as $variant){
						$vardata = array();	
						$vardata['product_id'] = $productId;
						for($x=1; $x<4; $x++){
							 if(isset($variant['opt'.$x])){
								$vardata['option'.$x] = $variant['opt'.$x];
							} 
						}
						
						$vardata['sku'] = $variant['sku'];
						$vardata['price'] = $variant['price'];
						$vardata['barcode'] = $variant['barcode'];
						$vardata['inventory_quantity'] = $variant['inventory'];
						$vardata['created_at'] = $this->date;
						$this->Product_model->Insert_Product_Variant($vardata);
						
					}
					
				}
				redirect("product/edit-product/$productId");
			}
            $values_error = array(			  
					'product_add_by' => $user_id,
					'product_category' => $this->input->post('product_category'),
					'product_desc' => $this->input->post('product_desc'),
					'product_title' => $this->input->post('product_title'),
					'product_supplier' => $this->input->post('product_supplier'),
					'product_price' => $this->input->post('product_price'),
					'product_sku' => $this->input->post('product_sku'),
					'product_stock' => $this->input->post('product_stock'),
					'product_wait_time' => $this->input->post('product_wait_time'),
			    	'product_weight' => $this->input->post('product_weight'),
			    	'price_form_vendor' => $this->input->post('price_form_vendor'),
			    	'wholesale_price' => $this->input->post('wholesale_price'),
			    	'default_variants_price' => $this->input->post('default_variants_price'),
					'live_in' => $this->input->post('live_in'),
					'sourcing_id' => $this->input->post('sourcing_id'),
			    	'requested_by' => $this->input->post('requested_by'),
			    	'shipping_price' => 0
			    );
				$values_error['img'] = $_FILES['img_src'];
            $this->data['values_error'] = $values_error;
			
		}
		$role_id = $this->session->userdata('login')->role_id;		
		$this->data['bodyClass'] = 'product_page';
		$this->data["role_id"] = $this->session->userdata('login')->role_id;
        $this->data["categories"]= $this->Categories_model->getCategory();
		if($role_id != 3){
			$this->data["suppliers"] = $this->Suppliers_model->fetch_suppliers();  		
		}
		else{
			$this->data["suppliers"] = $this->Suppliers_model->vandor_supplier_data($user_id);  		
		}
		$this->data["active"] = "all-products";
		$this->load->view('headerView', $this->data);
		$this->load->view('products/createView', $this->data);
		$this->load->view('footerView', $this->data);
	}
	public function download_templte(){
		header('Content-Type: text/csv; charset=utf-8');  
		header('Content-Disposition: attachment; filename=productdata.csv');  
		$output = fopen("php://output", "w");  
		fputcsv($output, array('Name','Category','Description','Supplier','Price','SKU','Stock level','Wait time','Weight','Price from vendor','Wholesale price'));
		$title = $this->input->post('title');
		$category = $this->input->post('category');
		$desc = $this->input->post('desc');
		$supplier = $this->input->post('supplier');
		$price = $this->input->post('price');
		$sku = $this->input->post('sku');
		$stock = $this->input->post('stock');
		$wait_time = $this->input->post('wait_time');
		$weight = $this->input->post('weight');
		$vendor_price = $this->input->post('vendor_price');
		$wholesale = $this->input->post('wholesale');	
   $export_data[] =array($title,$category,$desc,$supplier,$price,$sku,$stock,$wait_time,$weight,$vendor_price,$wholesale);		
		foreach($export_data as $data){
		fputcsv($output, $data);  
		}
		fclose($output);  
		exit;
	}	
	/******************* Edit Items ******************/
	
	public function editItem(){
		if($this->input->method(TRUE)=="POST")
		{
			$this->form_validation->set_rules('product_title', 'Name', 'required');
			$this->form_validation->set_rules('product_category', 'Category', 'required');
			$this->form_validation->set_rules('product_supplier', 'Supplier', 'required');
			$this->form_validation->set_rules('product_price', 'Price', 'required');
			$this->form_validation->set_rules('product_sku', 'SKU', 'required');
		    $this->form_validation->set_rules('wholesale_price', 'Wholesale price', 'required');
		    $this->form_validation->set_rules('default_variants_price', 'Default variants price', 'required');
			$this->form_validation->set_rules('img_src', '', 'callback_file_check');
			if ($this->form_validation->run() == TRUE)
			{
				$user_id = $this->session->userdata('login')->id;
		        $data = array(			  
					'product_add_by' => $user_id,
					'product_category' => $this->input->post('product_category'),
					'product_desc' => $this->input->post('product_desc'),
					'product_title' => $this->input->post('product_title'),
					'product_supplier' => $this->input->post('product_supplier'),
					'product_price' => $this->input->post('product_price'),
					'product_sku' => $this->input->post('product_sku'),
					'product_stock' => $this->input->post('product_stock'),
					'product_wait_time' => $this->input->post('product_wait_time'),
					'product_weight' => $this->input->post('product_weight'),
			    	'price_form_vendor' => $this->input->post('price_form_vendor'),
			    	'wholesale_price' => $this->input->post('wholesale_price'),
			    	'default_variants_price' => $this->input->post('default_variants_price'),
			       	'shipping_price' => 0
			    );	
			
				$productId = $this->Product_model->saveProduct($data);			
				if(is_array($_POST['variant_name']) && is_array($_POST['variant_price']) && is_array($_POST['variant_sku']) && is_array($_POST['variant_supplier'])) {
					for($i = 0; $i < count($_POST['variant_name']); $i++) {
						$vardata[$i]['product_id'] = $productId;
						$vardata[$i]['supplier'] = $_POST['variant_supplier'][$i];
						$vardata[$i]['sku'] = $_POST['variant_sku'][$i];
						$vardata[$i]['price'] = $_POST['variant_price'][$i];
						$vardata[$i]['title'] = $_POST['variant_name'][$i];
						$vardata[$i]['created_at'] = $this->date;
					}
				}
				$variantId = $this->Product_model->saveVariant($vardata);
				foreach ($variantId as $key => $val){				 
					$variantIds[ $key+3 ] = $val;
                }				
				$files = $_FILES['img_src'];
				array_push($files,$variantIds);
                $folder = "./uploads/product$productId";
				if (!is_dir($folder))
				{
					mkdir($folder);
				}				
				$config['upload_path']   = $folder;
                $config['allowed_types'] = 'gif|jpg|png|mp4|3gp|avi|flv|mkv|m4v|mov|mpeg|mpg';
                $config['max_size']      = 102400;
				$filesCount = count($files['name']);
				
				$errors = array();
				for($j = 0; $j < $filesCount; $j++){				
					$this->upload->initialize($config);
					$_FILES['img_srcs']['name'] = $productId."-".rand().str_replace("_","",$files['name'][$j]);
					$_FILES['img_srcs']['type'] = $files['type'][$j];
					$_FILES['img_srcs']['tmp_name'] = $files['tmp_name'][$j];
					$_FILES['img_srcs']['error'] = $files['error'][$j];
					$_FILES['img_srcs']['size'] = $files['size'][$j];
					if($this->upload->do_upload('img_srcs')){
						$data_upload_files = $this->upload->data();
						$uploadData[$j]['product_id'] = $productId;						
						$uploadData[$j]['src'] = $data_upload_files['file_name'];
						$uploadData[$j]['created_at'] = $this->date;
                        if($j<3){
							$uploadData[$j]['variant_id'] = "";
                        }							
						if($j >= 3){
							$uploadData[$j]['variant_id'] = $files[0][$j];
						}
					}
					else{					
						$errors[] = $this->upload->display_errors("<span class='error'>".$_FILES['img_srcs']['name']." ", "</span>");
					}
				}
				if(!empty($uploadData)){
					$result = $this->Product_model->uploadImages($uploadData);				
				}
			}
		}
		$this->data['bodyClass'] = 'product_page';
		$this->data["role_id"] = $this->session->userdata('login')->role_id;
        $this->data["categories"]= $this->Categories_model->getCategory();
        $this->data["suppliers"] = $this->Suppliers_model->fetch_suppliers();  		
		$this->data["active"] = "all-products";
		$this->load->view('headerView', $this->data); 
		$this->load->view('products/createView', $this->data);
		$this->load->view('footerView', $this->data);
	}
	
	/*************************** End Here ************/
	
	public function file_check(){
		 $filesCount = count($_FILES['img_src']['name']);		 
		 for($i = 0; $i < $filesCount; $i++){
			$allowed_mime_type_arr = array('image/gif','image/jpeg','image/pjpeg','image/png','image/x-png','video/mp4','video/3gp','video/avi','video/flv','video/mkv','video/m4v','video/mov','video/mpeg','video/mpg');
			$mime = get_mime_by_extension($_FILES['img_src']['name'][$i]);
			if(in_array($mime, $allowed_mime_type_arr)){
				return true;
			}else{
				$this->form_validation->set_message('file_check', 'Please select only gif/jpg/png file.');
				return false;
			}
		 }
    }
	
	public function addVariants(){
		$this->data["suppliers"] = $this->Suppliers_model->fetch_suppliers();
		$this->load->view('products/addVariantsView', $this->data);
	}
	
	
	public function productsGird($cat_id=0){
       	$categoryName = $this->Product_model->getbestsellercat();
	    $config = array();
		$keywords =  $this->input->get('keywords');
		if($cat_id == 0){
			$config["base_url"] = base_url() . "user/products";
            if(isset($keywords) && !empty($keywords)){}else{
				$cat_id = $categoryName['id'];
            }			
		}else{			
			$config["base_url"] = base_url() . "user/products/$cat_id";
		}
		
		$total_row = $this->Product_model->categoryProductsCount($cat_id,$keywords);		
        
        $config["total_rows"] = $total_row;
        $config["per_page"] =12;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 1;    
      	$config['page_query_string']=TRUE;
        $config['query_string_segment'] = 'page';		
		$config['full_tag_open'] = '<div class="pagination">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = FALSE;		
		$config['first_tag_open'] = '<span class="firstlink">';
		$config['first_tag_close'] = '</span>';

		$config['last_link'] = FALSE;
		$config['last_tag_open'] = '<span class="lastlink">';
		$config['last_tag_close'] = '</span>';

		$config['next_link'] = 'Next Page';
		$config['next_tag_open'] = '<span class="nextlink">';
		$config['next_tag_close'] = '</span>';

		$config['prev_link'] = 'Prev Page';
		$config['prev_tag_open'] = '<span class="prevlink">';
		$config['prev_tag_close'] = '</span>';

		$config['cur_tag_open'] = '<span class="curlink">';
		$config['cur_tag_close'] = '</span>';

		$config['num_tag_open'] = '<span class="numlink">';
		$config['num_tag_close'] = '</span>';

        $this->pagination->initialize($config);
	
		if($this->input->get('page') !=""){
			$start = $this->input->get('page')-1;
		}else{
			$start =0;
		}
		if($filter = $this->input->get('filter')){
			$filter = $this->input->get('filter', TRUE); 
			$filter_value = $this->input->get('value', TRUE);
			$this->data['products'] = $this->Product_model->products_sorting($cat_id,$filter,$filter_value);
			$this->data['cat_name'] =  $this->Product_model->category_name($cat_id);
		}else{
			if($cat_id > 0 || $keywords!=""){
				$this->data['products'] = $this->Product_model->getProduct_categories($cat_id,$keywords,$config["per_page"],$start*$config["per_page"]);
				$this->data['cat_name'] =  $this->Product_model->category_name($cat_id);
				$str_links = $this->pagination->create_links();
				$this->data["links"] = $this->pagination->create_links();		
			}else{ 
				$this->data['products'] = $this->Product_model->getAllProduct($config["per_page"],$start*$config["per_page"]);			
				$str_links = $this->pagination->create_links();
				$this->data["links"] = $this->pagination->create_links();		
			}
		}	
		$this->data['all_prdct'] = ""; 
		$this->data['productsImg'] = $this->Product_model->getProductImg();
		$this->data['categories'] = $this->Categories_model->getCategory();		
		$this->data['suppliers'] = $this->Suppliers_model->fetch_suppliers();		
		$this->data['bodyClass'] = 'UserProducts_Grid';
		$this->data['active_category'] = $cat_id;
        $this->data["active"] = "products";
		$this->load->view('headerView', $this->data);
		$this->data["product_categories"] = $this->Product_model->get_categories();
		$userid = $this->session->userdata('login')->id;
		$this->data['p_ids'] = $this->List_model->get_list($userid);
		$this->load->view('user/user-ProductsView', $this->data);
		$this->load->view('footerView', $this->data);
		
	}
	
	
	
	public function category_search($selected_category=0){  
	$this->data['products'] = $this->Product_model->getProduct_categories($selected_category);
	$this->data['cat_name'] =  $this->Product_model->category_name($this->data['products'][0]['product_category']);
	$this->data['productsImg'] = $this->Product_model->getProductImg();
	$this->data['categories'] = $this->Categories_model->getCategory();		 
	$this->data['suppliers'] = $this->Suppliers_model->fetch_suppliers();		
	$this->data['bodyClass'] = 'UserProducts_Grid';
	$this->data["active"] = "products"; // active class for menu	
	$this->data['Total_products'] = count($this->Product_model->getProduct_categories($selected_category));
	$this->load->view('headerView', $this->data); // call header view	
	$this->data["product_categories"] = $this->Product_model->get_categories();
	$this->load->view('user/user-Products-categoryView', $this->data);
	$this->load->view('footerView', $this->data); 
	}
	
	public function product_details($product_id=0){  
		$check_productId = $this->Product_model->check_productId($product_id);
		if($check_productId ==""){
			redirect('user/products');
		}
		
		$this->data['product_details'] = $this->Product_model->get_product_detail($product_id);	
       // printR($this->data['product_details']);	 die();	
		$this->data['get_product_supplier'] = $this->Product_model->get_product_suppliers($product_id);		
		$product_category = $this->data['product_details'][0]['product_category'];
		$this->data['variants_image_product'] = $this->Product_model->variants_image_product($product_id);
		$this->data['related_product'] = $this->Product_model->get_related_product($product_category,$product_id);
		$this->load->view('headerView', $this->data);
		$userid = $this->session->userdata('login')->id;
	
		$this->data['p_ids'] = $this->List_model->get_list($userid);
		/********* get product vairents *******/
		
		
		$this->data["variant_options"] = $this->Product_model->getVariantOptions($product_id);
		$this->data["product_variants_options"] = $this->Product_model->get_product_variants_optins($product_id);
		
					
			
		/************** End Here **************/
		
		$this->load->view('products/product_detail_view', $this->data);
		$this->load->view('footerView', $this->data);
	}	

	//Delete Products From Admin
	public function deleteProducts(){
		if($this->input->post()){
			$productId = $this->input->post('productId');
			$result=$this->Product_model->delete_product($productId);
			if($result==true){
				$this->session->set_flashdata('delete_cat','Product has been deleted successfully');
			}
		}
	}

	//Edit products
	public function editProducts($product_id){

		$user_id = $this->session->userdata('login')->id;	
		$check_productId = $this->Product_model->check_productId($product_id);
		if($check_productId ==""){
			redirect('all-products');
		}
	
		if($this->input->method(TRUE)=="POST")
		{
			
			$this->form_validation->set_rules('product_title', 'Name', 'required');
			$this->form_validation->set_rules('product_category', 'Category', 'required');
			$this->form_validation->set_rules('product_supplier', 'Supplier', 'required');
			$this->form_validation->set_rules('product_price', 'Price', 'required');
			$this->form_validation->set_rules('product_sku', 'SKU', 'required');
            $this->form_validation->set_rules('wholesale_price', 'Wholesale price', 'required');
            if ($this->form_validation->run() == TRUE){
				$user_id = $this->session->userdata('login')->id;
		        $data = array(			  
					'product_add_by' => $user_id,
					'product_category' => $this->input->post('product_category'),
					'product_desc' => $this->input->post('product_desc'),
					'product_title' => $this->input->post('product_title'),
					'product_supplier' => $this->input->post('product_supplier'),
					'product_price' => $this->input->post('product_price'),
					'product_sku' => $this->input->post('product_sku'),
					'product_stock' => $this->input->post('product_stock'),
					'product_wait_time' => $this->input->post('product_wait_time'),
					'product_weight' => $this->input->post('product_weight'),
			    	'price_form_vendor' => $this->input->post('price_form_vendor'),
			    	'wholesale_price' => $this->input->post('wholesale_price'),
					'default_variants_price' => $this->input->post('default_variants_price'),
			   	    'shipping_price' => 0
			    );
                if($this->input->post('live_in')){
					$data['live_in'] = $this->input->post('live_in');
				}
				$role= $this->session->userdata('login')->role_id;
				if($role == 1 && $this->input->post('sourcing_id') > 0){
					$data['publically_status'] = $this->input->post('publically_status');
				}
				
				$productId = $this->Product_model->Edit_Product_Data($data,$product_id);		
				
				if(isset($_FILES['main_img_src']))
				{
					$files = $_FILES['main_img_src'];
					$folder = "./uploads/product$product_id";
					if (!is_dir($folder))
					{
						mkdir($folder);
					}				
					$config['upload_path']   = $folder;
					$config['allowed_types'] = 'gif|jpg|png|mp4|3gp|avi|flv|mkv|m4v|mov|mpeg|mpg';
					$config['max_size']      = 102400;
					$filesCount = count($files['name']);
					$errors = array();
					for($j = 0; $j < $filesCount; $j++){				
						$this->upload->initialize($config);
						$_FILES['main_img_src']['name'] = $product_id."-".rand().str_replace("_","",$files['name'][$j]);
						$_FILES['main_img_src']['type'] = $files['type'][$j];
						$_FILES['main_img_src']['tmp_name'] = $files['tmp_name'][$j];
						$_FILES['main_img_src']['error'] = $files['error'][$j];
						$_FILES['main_img_src']['size'] = $files['size'][$j];
						if($this->upload->do_upload('main_img_src')){
							$data_upload_files = $this->upload->data();
							$uploadData[$j]['product_id'] = $product_id;						
							$uploadData[$j]['src'] = $data_upload_files['file_name'];
							$uploadData[$j]['created_at'] = $this->date;                    
							$uploadData[$j]['variant_id'] = "";
						
							$file_name = $data_upload_files['file_name'];					
							$width = THUMB_BIG;$height = THUMB_BIG;$thumb_marker ="thumb-big_";
							$width = THUMB_SMALL;$height = THUMB_SMALL;	$thumb_marker ="thumb_";
						}
						else{					
							$errors[] = $this->upload->display_errors("<span class='error'>".$_FILES['main_img_src']['name']." ", "</span>");
						}
					}
					if(!empty($uploadData)){
						$result = $this->Product_model->Edit_UploadImages($uploadData);				
					}				
				}
		
				if(isset($_POST['var'])){
					$variants_data = $_POST['var'];
					$variant_images = $_FILES['variant_images'];
					$i = 0;
					foreach($variants_data as $key => $variant) {
						$vardata =array();
						$vardata['variant_id'] = $key;
						$vardata['product_id'] = $product_id;	
						$vardata['sku'] = $variant['sku'];
						$vardata['price'] = $variant['price'];
						$vardata['barcode'] = $variant['barcode'];
						$vardata['inventory_quantity'] = $variant['inventory'];
						$vardata['updated_at'] = $this->date;						
						$variantids = $this->Product_model->Edit_Product_Variant($vardata);	
						
						if(!empty($variant_images['name'][$i])){
							$files = $_FILES['variant_images'];
							$folder = "./uploads/product$product_id";
							if (!is_dir($folder)){
								mkdir($folder);
							}				
							$config['upload_path']   = $folder;
							$config['allowed_types'] = 'gif|jpg|png|mp4|3gp|avi|flv|mkv|m4v|mov|mpeg|mpg';
							$config['max_size']      = 102400;
							$errors = array();
						
							$this->upload->initialize($config);
							$_FILES['edit_img_srcd']['name'] = $product_id."-".rand().str_replace("_","",$files['name'][$i]);
							$_FILES['edit_img_srcd']['type'] = $files['type'][$i];
							$_FILES['edit_img_srcd']['tmp_name'] = $files['tmp_name'][$i];
							$_FILES['edit_img_srcd']['error'] = $files['error'][$i];
							$_FILES['edit_img_srcd']['size'] = $files['size'][$i];
							if($this->upload->do_upload('edit_img_srcd')){
								$data_upload_files = $this->upload->data();
								$edit_img_upload = $data_upload_files['file_name'];
							}
							
							if(!empty($edit_img_upload)){
								$isImageExists = $this->Product_model->getVariantImage($product_id,$key);
								if($isImageExists > 0){
									$result = $this->Product_model->Edit_Variant_Images($edit_img_upload,$key);	
								} else {
									$uploadDatas[0]['product_id'] = $product_id;						
									$uploadDatas[0]['src'] = $data_upload_files['file_name'];
									$uploadDatas[0]['created_at'] = $this->date;
									$uploadDatas[0]['variant_id'] = $key ;
									$result = $this->Product_model->Edit_UploadImages($uploadDatas);
									$uploadDatas= array();
								}
							}
						}
					  $i++;
					}
				}
		
				//////////// save new variant ///////////////////////
				if(isset($_POST['vars'])){
					$optionsNameArray	 = $this->input->post('options-name'); 
					$optionsValueArray	 = $this->input->post('options-values');
					$variantOptionsData = array('pid'=>$product_id);
					$count = 1;
					foreach( $optionsValueArray as $k => $v  ){
						
						if(!empty($v)){
							$opt = 'option'.$count.'_name';						
							$val = 'option'.$count.'_value';
							
							$variantOptionsData[$opt] = $optionsNameArray[$k];
							$variantOptionsData[$val] = $v;
							$count++;
							
						}
					}
					if( count($variantOptionsData) > 1){						
							$optionsId = $this->Product_model->saveVariantOptions($variantOptionsData);
					}
					if(isset($_POST['vars'])){
						$variants  =  $_POST['vars'];
						foreach($variants as $variant){
							$vardata = array();	
							$vardata['product_id'] = $product_id;
							for($x=1; $x<4; $x++){
								 if(isset($variant['opt'.$x])){
									$vardata['option'.$x] = $variant['opt'.$x];
								} 
							}
							
							$vardata['sku'] = $variant['sku'];
							$vardata['price'] = $variant['price'];
							$vardata['barcode'] = $variant['barcode'];
							$vardata['inventory_quantity'] = $variant['inventory'];
							$vardata['created_at'] = $this->date;
							$this->Product_model->Insert_Product_Variant($vardata);
						}
					}
				}
				/////////////////////////////////////////////////////		
				if($this->input->post('variant_name')){
					if(is_array($_POST['variant_name']) && is_array($_POST['variant_price']) && is_array($_POST['variant_sku'])){
							$imgcount=0;	
							for($i = 0; $i < count($_POST['variant_name']); $i++) {
								$vardata =array();
								$vardata['product_id'] = $product_id;
								
									if($_POST['variant_id'][$i]==""){
										$vardata['variant_id'] ="";
									}else{
										$vardata['variant_id'] = $_POST['variant_id'][$i];
									}						
								
							$vardata['sku'] = $_POST['variant_sku'][$i];
							$vardata['price'] = $_POST['variant_price'][$i];
							$vardata['title'] = $_POST['variant_name'][$i];
							$vardata['created_at'] = $this->date;					
							if($vardata['variant_id']!=""){	
							$v_id =$vardata['variant_id'];
							$variantids = $this->Product_model->Edit_Product_Variant($vardata);	
							if(!empty($_FILES['edit_img_src']['name'][$i])){
							$files = $_FILES['edit_img_src'];
							$folder = "./uploads/product$product_id";
							if (!is_dir($folder))
							{
								mkdir($folder);
							}				
							$config['upload_path']   = $folder;
							$config['allowed_types'] = 'gif|jpg|png|mp4|3gp|avi|flv|mkv|m4v|mov|mpeg|mpg';
							$config['max_size']      = 102400;
							$errors = array();
						
							$this->upload->initialize($config);
							$_FILES['edit_img_srcd']['name'] = $product_id."-".rand().str_replace("_","",$files['name'][$i]);
							$_FILES['edit_img_srcd']['type'] = $files['type'][$i];
							$_FILES['edit_img_srcd']['tmp_name'] = $files['tmp_name'][$i];
							$_FILES['edit_img_srcd']['error'] = $files['error'][$i];
							$_FILES['edit_img_srcd']['size'] = $files['size'][$i];
							
						
							if($this->upload->do_upload('edit_img_srcd')){		 
									$data_upload_files = $this->upload->data();
									$edit_img_upload = $data_upload_files['file_name'];	        
									
							}		
							else{					
									$errors[] = $this->upload->display_errors("<span class='error'>".$_FILES['edit_img_srcd']['name']." ", "</span>");
								}
							if(!empty($edit_img_upload)){					
								$result = $this->Product_model->Edit_Variant_Images($edit_img_upload,$v_id);			
							}  
								
							} 

								
								}else{		
							$variantId = $this->Product_model->Insert_Product_Variant($vardata);
							$files = $_FILES['img_src'];
							$folder = "./uploads/product$product_id";
							if (!is_dir($folder))
							{
								mkdir($folder);
							}				
							$config['upload_path']   = $folder;
							$config['allowed_types'] = 'gif|jpg|png|mp4|3gp|avi|flv|mkv|m4v|mov|mpeg|mpg';
							$config['max_size']      = 102400;
							$filesCount = count($files['name']);
							$errors = array();
						
							$this->upload->initialize($config);
							$_FILES['img_new']['name'] = $product_id."-".rand().str_replace("_","",$files['name'][$imgcount]);
							$_FILES['img_new']['type'] = $files['type'][$imgcount];
							$_FILES['img_new']['tmp_name'] = $files['tmp_name'][$imgcount];
							$_FILES['img_new']['error'] = $files['error'][$imgcount];
							$_FILES['img_new']['size'] = $files['size'][$imgcount];
							
						
							if($this->upload->do_upload('img_new')){		
								$data_upload_files = $this->upload->data();
								$uploadDatas[$imgcount]['product_id'] = $product_id;						
								$uploadDatas[$imgcount]['src'] = $data_upload_files['file_name'];
								$uploadDatas[$imgcount]['created_at'] = $this->date;
								$uploadDatas[$imgcount]['variant_id'] = $variantId ;	
								
								$file_name = $_FILES['img_new']['name'];
								
								$width = THUMB_BIG;$height = THUMB_BIG;$thumb_marker ="thumb-big_";				
									
								$width = THUMB_SMALL;$height = THUMB_SMALL;
								$thumb_marker ="thumb_";						
							}		
							else{					
									$errors[] = $this->upload->display_errors("<span class='error'>".$_FILES['img_new']['name']." ", "</span>");
								}
							if(!empty($uploadDatas)){
								
								$result = $this->Product_model->Edit_UploadImages($uploadDatas);
								$uploadDatas= array();
							} 
						   $imgcount++;
							} 
						}
					}
				}
				$this->data['saved'] = 'Product has been updated successfully';
			}
		}
		$role_id = $this->session->userdata('login')->role_id;  
		$user_id = $this->session->userdata('login')->id;			
		$this->data['bodyClass'] = 'product_page';
		$this->data["role_id"] = $this->session->userdata('login')->role_id;
        $this->data["categories"]= $this->Categories_model->getCategory();
        if($role_id != 3){
        $this->data["suppliers"] = $this->Suppliers_model->fetch_suppliers();  		
		}
		else{
			$this->data["suppliers"] = $this->Suppliers_model->vandor_supplier_data($user_id);  		
		}  		
	    $this->data["product_data"] = $this->Product_model->get_product_By_Id($product_id);  	
        $this->data["product_variants"] = $this->Product_model->get_product_product_variants($product_id);
		$this->data["variant_options"] = $this->Product_model->getVariantOptions($product_id);
		
	    $this->data["product_Img"] = $this->Product_model->get_product_product_images($product_id);
		
		$this->data["active"] = "all-products";
		$this->load->view('headerView', $this->data);	
		$this->load->view('products/editProductView', $this->data);
		$this->load->view('footerView', $this->data);
		

	}
	
	public function delete_product_variants(){ 
		if($this->input->method(TRUE)=="POST")
		{
			$product_id = $this->input->post('product_id');			
			$result = $this->Product_model->delete_product_variant($product_id);
            redirect("product/edit-product/$product_id");			
	    }	

	}
	
	public function delete_product_Img(){ 
		if($this->input->method(TRUE)=="POST")
		{
		    $img_id = $this->input->post('img_id');
			$result = $this->Product_model->delete_product_Images($img_id[0]);	
	    }	
	}	
	public function delete_variant($id){ 
		if($this->input->method(TRUE)=="POST")
		{
				
			$variant_id = $this->input->post('variantId');
			$product_id = $this->input->post('productId');
			$this->Product_model->delete_variant_withOptions($variant_id,$product_id);		
			$this->session->set_flashdata('delete_variant', 'Variant has been deleted successfully.');
			return true;
	    }	
	}	
	
		public function deleteAllVariants($id){ 
			if($this->input->method(TRUE)=="POST")
			{				
				$product_id = $this->input->post('productId');
				$this->Product_model->delete_all_product_variants($product_id);
						$this->session->set_flashdata('delete_variant', 'Variants has been deleted successfully.');
				return true;
			}	
		}	
	public function keyword(){ 
		if($this->input->method(TRUE)=="POST")
		{
		   
	    }	
	}	
	function name_check($name)
	{
		$userid = $this->session->userdata('login')->id;
		$count = $this->Product_model->check_title($name, $userid);
		
		if ($count > 0)
		{		
			
			$this->form_validation->set_message('name_check', 'Product name is already exist.');
			return FALSE;		
							
		}
		else
		{
			return TRUE;
		}
	}
	
	
	public function get_variant_price(){ 
	
			if($this->input->method(TRUE)=="POST")
			{
				
				$product_id = $this->input->post('product_id');
				$option1 = $this->input->post('option1');
				$option2 = $this->input->post('option2');
				$option3 = $this->input->post('option3');		
			if(empty($option1)){
				$option1="";
			} if(empty($option2)){
				$option2="";
			} if(empty($option3)){
				$option3="";
				}
				$option_price = $this->Product_model->variant_option_price($product_id,$option1,$option2,$option3);
				if (!empty($option_price) || !empty($option_price))
				$price = $option_price[0]->price;				
				$variant_id = $option_price[0]->id;
				$option_imag = $this->Product_model->variant_option_image($product_id,$variant_id);
				if(empty($option_imag)){
					$result =array(
					'option_price' => $price, 'option_image' => ""
					);
				}else{
						$option_image = $option_imag[0]->src;
						$result =array(
						'option_price' => $price, 'option_image' => $option_image
						);
				     }
				
				header('Content-Type: application/json');
				$result = json_encode($result);
				echo $result;	die; 
			}	
		}
		public function user_products(){
			$limit = 12;
			$page = $this->uri->segment(4);
			$total_records = $this->Product_model->user_record_counts();
			 if(empty($page))
			 {
				$page = 0;
		     } 
			 else{
			  $page = $page-1;
			}
			$config['base_url'] = base_url() . 'user/all-products';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit;
            $config["uri_segment"] = 4;           
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
             
            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';
			
             
            $config['first_link'] = 'First Page';
            $config['first_tag_open'] = '<span class="firstlink">';
            $config['first_tag_close'] = '</span>';
             
            $config['last_link'] = 'Last Page';
            $config['last_tag_open'] = '<span class="lastlink">';
            $config['last_tag_close'] = '</span>';
             
            $config['next_link'] = 'Next Page';
            $config['next_tag_open'] = '<span class="nextlink">';
            $config['next_tag_close'] = '</span>';
 
            $config['prev_link'] = 'Prev Page';
            $config['prev_tag_open'] = '<span class="prevlink">';
            $config['prev_tag_close'] = '</span>';
 
            $config['cur_tag_open'] = '<span class="curlink">';
            $config['cur_tag_close'] = '</span>';
 
            $config['num_tag_open'] = '<span class="numlink">';
            $config['num_tag_close'] = '</span>';
             
            $this->pagination->initialize($config);                
            
            $this->data["links"] = $this->pagination->create_links();
			$this->data['active_category'] = 0;
			$this->data['productsImg'] = $this->Product_model->getProductImg();
			$this->data['categories'] = $this->Categories_model->getCategory();	
			$this->load->view('headerView', $this->data);
			$this->data["product_categories"] = $this->Product_model->get_categories();
			$this->data['products'] = $this->Product_model->getAllProduct($limit, $page*$limit);
			$this->data['all_prdct'] = "prdcts";	
			$userid = $this->session->userdata('login')->id;
			$this->data['p_ids'] = $this->List_model->get_list($userid);
			$this->load->view('user/user-ProductsView', $this->data);
			$this->load->view('footerView', $this->data);
		
		}
		
		
		public function sourced_products(){
			$userid = $this->session->userdata('login')->id;
			$limit = 12;
			$page = $this->uri->segment(4);
			$total_records = $this->Product_model->sourced_counts($userid);
			 if(empty($page))
			 {
				$page = 0;
		     } 
			 else{
			  $page = $page-1;
			}
			$config['base_url'] = base_url() . 'user/sourced-products';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit;
            $config["uri_segment"] = 4;           
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
             
            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';
			
             
            $config['first_link'] = 'First Page';
            $config['first_tag_open'] = '<span class="firstlink">';
            $config['first_tag_close'] = '</span>';
             
            $config['last_link'] = 'Last Page';
            $config['last_tag_open'] = '<span class="lastlink">';
            $config['last_tag_close'] = '</span>';
             
            $config['next_link'] = 'Next Page';
            $config['next_tag_open'] = '<span class="nextlink">';
            $config['next_tag_close'] = '</span>';
 
            $config['prev_link'] = 'Prev Page';
            $config['prev_tag_open'] = '<span class="prevlink">';
            $config['prev_tag_close'] = '</span>';
 
            $config['cur_tag_open'] = '<span class="curlink">';
            $config['cur_tag_close'] = '</span>';
 
            $config['num_tag_open'] = '<span class="numlink">';
            $config['num_tag_close'] = '</span>';
             
            $this->pagination->initialize($config);                
            
            $this->data["links"] = $this->pagination->create_links();
			$this->data['sourced'] = "sourced";
			$this->data['productsImg'] = $this->Product_model->getProductImg();
			$this->data['categories'] = $this->Categories_model->getCategory();	
			$this->load->view('headerView', $this->data);
			$this->data["product_categories"] = $this->Product_model->get_categories();
			$this->data['products'] = $this->Product_model->sourced_products($limit, $page*$limit, $userid);
			
			$this->data['all_prdct'] = "prdcts";	
			
			$this->data['p_ids'] = $this->List_model->get_list($userid);
			$this->load->view('user/user-ProductsView', $this->data);
			$this->load->view('footerView', $this->data);
		
		}

} 