<?php

class Import extends CI_Controller{

    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Shop');
        $this->load->model('List_model');
    }
    public function importProducts()
	{
		
		$session_data = $this->session->userdata('login');
		$shop_details =  $this->Shop->get_shop_details($session_data->shop);		
		$accessToken = $shop_details->access_token;				
		$this->load->library( "shopifyclient" , array( "shop"  => $session_data->shop , "token" => $accessToken , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
		  $products_array = array(
								"product"=>array(
									'title'=>'',
									"title"=> "om",
									"body_html"=> "<strong>Good snowboard!</strong>",
									"vendor"=> "Burton",
									"product_type"=> "Snowboard",
									"published"=> false ,
									"variants"=>array(
													array(
													"sku"=>"t_009",
													"price"=>20.00,
													"grams"=>200,
													"taxable"=>false,
													)
									)
								)
							);
		$product = $this->shopifyclient->call( 'POST' , '/admin/products.json' , $products_array);
	}
	
	public function addToStore()
	{
		$err = 0;
		$errmsg = 0;
		$proIds = array();
	    if(isset($_POST['product_data'])){
			foreach($_POST['product_data'] as $products){
				$session_data = $this->session->userdata('login');
				$shop_details =  $this->Shop->get_shop_details($session_data->shop);
				$accessToken = $shop_details->access_token;				
				$this->load->library( "shopifyclient" , array( "shop"  => $session_data->shop , "token" => $accessToken , "key" =>$this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
				
				$pt = $products['title'];
				$proTitle  = str_replace(' ', '+' , $pt);
				$matchProURL = "/admin/products.json?title=$proTitle";
				$matchPro = $this->shopifyclient->call( 'GET', "$matchProURL");
				if(!empty($matchPro)){
					foreach($matchPro as $proMatch){
						if($proMatch['title'] == $products['title']){
							$proIds[] = $products['id'];
							$err = 1;
						}else{
							$err = 0;
						}
					}
				}
				if($err == 0){	
					$product_tags = explode(' ', $products['tags']);
					$tags = implode(',' , $product_tags);
					$productId = 	$products['id'];
					$vendor_id  = 	$products['vendor_id'];
					$product_details =  $this->Shop->getProduct($productId);				
					$product_images =  $this->Shop->getProductallImages($productId);
					$product_variants =  $this->Shop->getProductVariants($productId);
					
					if(isset($products['variants'][0]['variant_id'])){
						$all_variants = array();						
						foreach ($products['variants'] as $pv){
							$varimg = array();
							$varimg[] = array("src" => "https://static.thenounproject.com/png/17241-200.png");
							$option1 = isset($pv['variant_option1'])?$pv['variant_option1']:'';
							$option2 = isset($pv['variant_option2'])?$pv['variant_option2']:'';
							$option3 = isset($pv['variant_option3'])?$pv['variant_option3']:'';
							$all_variants[] = array(
								"option1"=>$option1,
								"option2"=>$option2,
								"option3"=>$option3,
								"price"=>$pv['variant_price'],
								"sku"=>$pv['variant_sku'],
								"inventory_quantity" => $pv['variant_product_inventory'],
								"weight"=>$pv['variant_weight'],
								"weight_unit" => "g",
								"images" => $varimg
							);
						}  					 					 					
					}else{	
					    $varimg = array();
                        $varimg[] = array("src" => "https://static.thenounproject.com/png/17241-200.png");
						$all_variants[] =  array(
							"price"=>$products['variants'][0]['variant_price'],
							"sku"=>$products['variants'][0]['variant_sku'],
							"images" => $varimg
						);
					}
					
					/// ---------------- product images -------- 
					
					$productMainImages = array();
					if(isset($products['images'])){
						foreach($products['images'] as $product_image){
							 $productMainImages[] = array('src' => $product_image['src']);
							 $all_Imag[] = $product_image['src'];
						} 
					}
					$variantOptions = $this->List_model->getVariantOptions($productId);
					$options_array = array();
					 if(!empty($variantOptions)){
						for($i=1; $i<4; $i++){
							if(!empty($variantOptions['option'.$i.'_name']) && !empty($variantOptions['option'.$i.'_value']) ){
								$options_array[] = array(
									'name' => $variantOptions['option'.$i.'_name'],	
									'values' => array($variantOptions['option'.$i.'_value']));
								}
						}
					} 
				
					$products_array = array(
						"product"=>array(
							"title"=> $products['title'],
							"body_html"=> $products['description'],
							"vendor"=> "Dbuglab",
							"product_type"=> $products['type'],
							"published"=> true ,
							"tags"=>$tags, 
							"variants"=>$all_variants, 
							"images"=>	$productMainImages,
							"options" => $options_array	
						)
					);					
					
					$savedProducts =array();				
					$savedProducts[] = $this->shopifyclient->call('POST' , '/admin/products.json' , $products_array);
					
					$shop_products = array(
						"product_id "=> $productId,
						"p_title "=> $products['title'],
						"wholesale_price"=> $products['wholesale'][0],
						"shopify_product_id"=> $savedProducts[0]['id'], 
						"vendor_id " => $vendor_id,
						"shop_name" => $shop_details->shop_name,
						"created_by" => $this->session->userdata('login')->id,
						"amount"    => $products['variants'][0]['variant_price']							
					);	 	       
				    $shop_details =  $this->Shop->insert_shop_products($shop_products);
				  
					$pid = $savedProducts[0]['id'];
					$countUrl = "/admin/products/$pid/images.json";	
					$Productimages = $this->shopifyclient->call( 'GET' , "$countUrl");	
					
				    $Product_var = $this->shopifyclient->call( 'GET' , "/admin/products/$pid/variants.json");
					
					$dbproimg = array();
					foreach($product_images as $prodb){
						if(!empty($prodb['variant_id'])){
							$dbproimgpng = explode(".",$prodb['src']);
							$dbproimg[] = $dbproimgpng[0];
						}
					}
				   
					$pro_imgs = array();
					foreach($Productimages as $Product_image){
							$hasimg = explode("products/",$Product_image['src']);
							$hasimgpro = explode("?",$hasimg[1]);
							$hasimgprosh = explode("_",$hasimgpro[0]);
							$hasimgprojpg = explode(".",$hasimgprosh[0]);
							if(in_array($hasimgprojpg[0], $dbproimg)){
								$pro_imgs[] = $Product_image['id'];
							}
					}
					
					//printR($Productimages); printR($pro_imgs); printR($dbproimg);
					
					if(!empty($pro_imgs)){
						$varidsimg = 0;
						foreach($Product_var as $provar){
							if(isset($pro_imgs[$varidsimg])){
								$var_img_id = $pro_imgs[$varidsimg];
							}else{
								$var_img_id = "";
							}
							$var_id = $provar['id'];
							$var_array = array(
								"variant"=>array(
									"id"=> $var_id,
									"image_id"=> $var_img_id
								)
							);
							
							$this->shopifyclient->call( 'PUT' , "/admin/variants/$var_id.json", $var_array);
							$varidsimg++;						
						}
					}
				
					$all_variants = array();
					$options_array = array();			
					$userid = $this->session->userdata('login')->id;
					$this->List_model->remove_added_product($productId, $userid); 
					$this->List_model->remove_added_mylist_product($productId, $userid); 
					$this->List_model->remove_main_mylist_product($productId, $userid); 
					
				}else{
					$errmsg = 1; 
				}
			}
			if($errmsg > 0){
				return $this->output->set_content_type('application/json')
									->set_output(json_encode(array('response' => $proIds,)));
			}
		}else {
			echo 'Please try again';
		}
	}
	
	public function remove_linked_product(){	
        $product_id ="";	
		$get_product=array();
		$session_data = $this->session->userdata('login');
		$shop_details =  $this->Shop->get_shop_details($session_data->shop);		
		$accessToken = $shop_details->access_token;				
		$this->load->library( "shopifyclient" , array( "shop"  => $session_data->shop , "token" => $accessToken , "key" => $this->config->item('shopify_api_key') , "secret" => $this->config->item('shopify_secret') ));
		$product_id = $_POST['product_id'][0];		
		$get_product = $this->shopifyclient->call('GET ', "/admin/products.json");
		 $delete_product = $this->shopifyclient->call("DELETE ", "/admin/products/$product_id.json");
		$this->session->set_flashdata('disconnected-msg','Product has been  disconnected  successfully');
	  	 
	}

}