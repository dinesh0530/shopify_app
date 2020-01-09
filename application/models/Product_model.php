<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Product_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}
		public function category_check(){
			$n = 680;
			$this->db->select('*');
			$this->db->from('importapp_categories');
			$this->db->where('id', $n);
			$query = $this->db->get();
			return $query->num_rows();
		}
	
		public function get_pId($pname){
			$this->db->select('id');
			$this->db->from('importapp_products');
			$this->db->where('product_title',$pname);
			$query = $this->db->get();
			return $query->result_array();
		}
		public function import_images($image){
			$this->db->insert('importapp_images',$image);
		}
		public function get_img($pid){
			$this->db->select('src');
			$this->db->from('importapp_images');
			$this->db->where('product_id',$pid);
			$query = $this->db->get();
			if($query->num_rows()>0){
				foreach($query->result_array() as $row){
					$img[] = $row['src'];
				}						
				$str = implode(",",$img);			
				return $str;
			}
			else{
				$abc = "No - images";			
				return $abc;
				}	
		}		
		public function get_categoryId($category){
			$this->db->select('id');
			$this->db->from('importapp_categories');
			$this->db->where('category_name',$category);
			$query = $this->db->get();
			if($query->num_rows()>0){
			return $query->result_array();
			}	
			else{
				$abc = array(array('id'=>'no'));
				return $abc;
			}			
		}		
		public function get_supplierId($supplier){
			$this->db->select('id');
			$this->db->from('importapp_suppliers');
			$this->db->where('name',$supplier);
			$query = $this->db->get();
			if($query->num_rows()>0){
			return $query->result_array();
			}
			 else{
				$abc = array(array('id'=>'no'));
				return $abc;
			} 
		}	
		public function title_check($name){
			$this->db->select('*');
			$this->db->from('importapp_products');
			$this->db->where('product_title',$name);
			$query = $this->db->get();
			return $query->num_rows();
		}
	public function export_product($r_id,$u_id){		
			$this->db->select('product_title,category_name,product_desc,name,product_price,product_sku,product_stock,product_wait_time,product_weight,price_form_vendor,wholesale_price,importapp_products.id');
			$this->db->from('importapp_products');
			$this->db->join('importapp_categories', 'importapp_categories.id = importapp_products.product_category', 'left');
			$this->db->join('importapp_suppliers', 'importapp_suppliers.id = importapp_products.product_supplier', 'left');	
			if($r_id!=1)
			{
				$this->db->where('product_add_by', $u_id);
			}				
		$query = $this->db->get();
		if($query->num_rows()>0){
			foreach($query->result_array() as $row){
				$data[] = $row;
			}			
			return $data;
		}
	}	
	
	public function import_product($data){		
		$this->db->insert('importapp_products',$data);		
	}	
	public function check_title($p_name, $s_id){
		$this->db->select('*');
		$this->db->from(PRODUCTS);
		$this->db->where('product_title',$p_name);
		$this->db->where('product_add_by',$s_id);
		$query=$this->db->get();
		return $query->num_rows();		 
	}
	
	public function getProduct(){
		$this->db->select('*');
		$this->db->from(PRODUCTS);
		$query = $this->db->get();
		return $query->result();
	}
	
		public function check_productId($product_id){
		$this->db->select('id');
		$this->db->from(PRODUCTS);
		$this->db->where('id',$product_id);
		$query = $this->db->get();
		return $query->row();
	}
	
	
	public function get_product_variants_optins($product_id){
		$this->db->select('*');
		$this->db->from('importapp_variant_options');
		$this->db->where('pid',$product_id);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function products_record_count(){
		   return $this->db->count_all(PRODUCTS);
	}
	public function products_record_cat(){
		$this->db->select('*');
		$this->db->from(PRODUCTS);
		$this->db->order_by("id", "DESC");
		   return $this->db->count_all();
	}
	public function category_best($limit=null, $start=null){
		$num = 680;
		$this->db->select('*');		
		$this->db->order_by("id", "DESC");
		$this->db->where('product_category', $num);
        $this->db->limit($limit,$start);		
        $query = $this->db->get(PRODUCTS);	
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
   	public function getAllProduct($limit=null, $start=null) {
		$this->db->select('*');
		$this->db->order_by("id", "DESC");
		$this->db->where('status', 1);
		$this->db->where('publically_status', 0);
        $this->db->limit($limit,$start);		
        $query = $this->db->get(PRODUCTS);	
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
         
            return $data;
        }
        return false;
   }
   

    public function variants_image_product($id){			
		$this->db->select('*');
		$this->db->from(IMAGES);
		$this->db->where('product_id=',$id);
		$this->db->where('variant_id=',""); 
		$query = $this->db->get();
		return $query->result();		
	}
	
	public function saveProduct($data){
	
		$data['created_at'] = $this->date;
		$this->db->insert(PRODUCTS, $data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	public function Edit_Product_Data($data,$id){
		$data['created_at'] = $this->date;
		$this->db->update(PRODUCTS, $data, "id =$id");
		return $id;
	}
	
	public function saveVariant($vardata){
        $count = count($vardata);
		for($i = 0; $i<$count; $i++){
			$entries = array(
				'product_id'=>$vardata[$i]['product_id'],
				'supplier'=>$vardata[$i]['supplier'],
				'sku'=>$vardata[$i]['sku'],
				'price'=>$vardata[$i]['price'],
				'title'=>$vardata[$i]['title'],
				'created_at'=>$vardata[$i]['created_at']
			);
            $this->db->insert(VARIANTS, $entries);
            $id[] = $this->db->insert_id();			
		}		
		return $id;
	}
	
	public function Edit_Product_Variant($vardata){		
        $this->db->where('id', $vardata['variant_id']);
		$this->db->update(VARIANTS, $vardata);	       
	}
	
	public function Insert_Product_Variant($vardata){   
		$this->db->insert(VARIANTS, $vardata);
		$id = $this->db->insert_id();   
        return $id;
	}
	
	public function uploadImages($uploadData){
		$this->db->insert_batch(IMAGES,$uploadData);
	}
	public function Edit_UploadImages($uploadData){	
		$this->db->insert_batch(IMAGES,$uploadData);
	}
	
	
	public function Edit_Variant_Images($uploadData,$id){
        $this->db->set('src',$uploadData);	
		$this->db->where('variant_id', $id);
		$this->db->update(IMAGES);
	}
	
	public function cate_data($limit, $start,$userId) {	
	
	   $query = $this->db->query("SELECT role_id FROM importapp_users WHERE id=$userId");
	    $row = $query->row();
	    $role_id = $row->role_id; 
		$this->db->limit($limit,$start);
		if($role_id!=1){
				$this->db->where('product_add_by', $userId);
		}	
		$this->db->order_by("id", "DESC");
		$query = $this->db->get(PRODUCTS);
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
		 return $data;
		}
		return false; 
	}	
	
	public function keyword_data($limit, $start,$userId,$keyword,$category) {	
	
	    $query = $this->db->query("SELECT role_id FROM importapp_users WHERE id=$userId");
	    $row = $query->row();
	    $role_id = $row->role_id; 
		$this->db->limit($limit,$start);
		if($role_id!=1){
				$this->db->where('product_add_by', $userId);
		}
		if($category=="" && $keyword!=""){
		$this->db->where('product_supplier=',$keyword);
		}
		elseif($keyword=="" && $category!=""){
		 $this->db->where('product_category=',$category);	
		}
		else{
			$this->db->where('product_supplier=',$keyword);
			$this->db->where('product_category=',$category);	
		}
		
		$this->db->order_by("id", "DESC");
		$query = $this->db->get(PRODUCTS);
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false; 
	}
 
    public function record_counts() {
		return $this->db->count_all(PRODUCTS);  
	}
	 
	public function keyword_record_counts($keyword,$category){
		$this->db->select('*');
		$this->db->from(PRODUCTS);	
		if($category=="" && $keyword!=""){ 
			$this->db->where('product_supplier',$keyword);
		}
		elseif($keyword=="" && $category!=""){ 
			$this->db->where('product_category',$category);	
		}
		else{
			$this->db->where('product_supplier=',$keyword);
			$this->db->where('product_category=',$category);	
		}
		
		$query = $this->db->get();      		
		return $query->num_rows();
	}
	
	public function user_record_counts(){
		$this->db->select('*');
		$this->db->from(PRODUCTS);
		$this->db->where('publically_status', 0 );
		$query = $this->db->get();      		
		return $query->num_rows();
	}
	
	
	
	public function sourced_counts($uid){
		$this->db->select('importapp_products.*');
		$this->db->from('importapp_products');
		$this->db->join('importapp_sourced_approved_pro', 'importapp_sourced_approved_pro.product_id = importapp_products.id');
		$this->db->where('importapp_sourced_approved_pro.requested_by_id',$uid);
		$query = $this->db->get();    	
		if ($query->num_rows() > 0) {
            return $query->num_rows(); 
        }
	}
	
	public function sourced_products($limit=null, $start=null , $uid) {
		
		$this->db->select('importapp_products.*');
		$this->db->from('importapp_products');
		$this->db->limit($limit, $start); 
		$this->db->join('importapp_sourced_approved_pro', 'importapp_sourced_approved_pro.product_id = importapp_products.id');
		$this->db->where('importapp_sourced_approved_pro.requested_by_id',$uid);
		$query = $this->db->get();    	
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
         
            return $data;
        }
        return false;
    }
	
		
	public function vendor_record_counts($user_id){	
		$this->db->select('*');
		$this->db->from(PRODUCTS);
		$this->db->where('product_add_by=',$user_id);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function vendor_keyword_record_counts($user_id,$keyword,$category){	
		$this->db->select('*');
		$this->db->from(PRODUCTS);
		$this->db->where('product_add_by=',$user_id);
		
		if($category=="" && $keyword!=""){
		$this->db->where('product_supplier=',$keyword);
		}
		elseif($keyword=="" && $category!=""){
		 $this->db->where('product_category=',$category);	
		}
		else{
			$this->db->where('product_supplier=',$keyword);
			$this->db->where('product_category=',$category);	
		}
		
		$query = $this->db->get();		
		return $query->num_rows();
	}
	
	
	
	public function getProductImg(){
		$this->db->select('*');
		$this->db->from(IMAGES);
		$this->db->where('variant_id=','');
		$query = $this->db->get();
		return $query->result();
	}

	// Delete Products 
	public function delete_product($id){
		
		$this->db->where('id', $id);
		$this->db->delete(PRODUCTS);
		
		$this->db->where('product_id', $id);
		$this->db->delete(MYLIST);
		
		$this->db->where('product_id', $id);
		$this->db->delete(VARIANTS);
		
		$this->db->where('pid', $id);
		$this->db->delete(VARIANTS_OPTIONS);
		
		$this->db->where('product_id', $id);
		$this->db->delete(IMAGES);
	    return true;
	}
	
	public function delete_product_variant($id){		
		$this->db->where('id', $id);
		$this->db->delete(VARIANTS);
		
		$this->db->where('variant_id', $id);
		$this->db->delete(IMAGES);
		
	}
	public function delete_product_Images($id){		
		$this->db->where('id', $id);
		$this->db->delete(IMAGES);	
        if($this->db->affected_rows() > 0 ){
			return true;
		}		
	}
	
	
	// Edit prodcucts
	public function edit_item($id){
	
	}
	public function get_categories(){
		$query = $this->db->get(CATEGORIES);
		if($query->num_rows() != 0) 
		{
			 return $query->result_array();
		}
		else
		{
			return false;
		}		
	}
	
	
	public function getProduct_categories($selected_category,$keywords,$limit,$start){		
		if($selected_category==0 && $keywords==""){
			$this->db->select('*');
			$this->db->order_by("id", "DESC");
			$this->db->limit($limit,$start);
			$this->db->from(PRODUCTS);
            $this->db->where('status', 1);
            $this->db->where('publically_status', 0);
			$query = $this->db->get();
			return $query->result();		
		}
		elseif($keywords !="" && $selected_category!=""){
			$this->db->select('*');
			$this->db->order_by("id", "DESC");
			$this->db->limit($limit,$start);
			$this->db->from(PRODUCTS);
			$this->db->like("product_title", $keywords);
			$this->db->where("product_category", $selected_category);
            $this->db->where('status', 1);
			$this->db->where('publically_status', 0);
			$query = $this->db->get();
			return $query->result();
		
		}
		elseif($keywords =="" && $selected_category!=0){
			$this->db->select('*');
			$this->db->order_by("id", "DESC");
			$this->db->limit($limit,$start);
			$this->db->from(PRODUCTS);
			$this->db->where("product_category", $selected_category);
			$this->db->where('status', 1);
			$this->db->where('publically_status', 0);			
			$query = $this->db->get();
			return $query->result();		
		}
		elseif($keywords !="" && $selected_category==0){
			$this->db->select('*');
			$this->db->order_by("id", "DESC");
			$this->db->limit($limit,$start);
			$this->db->from(PRODUCTS);
			$this->db->where('status', 1);
			$this->db->where('publically_status', 0);
			$this->db->like('product_title', $keywords);		
			$query = $this->db->get();
			return $query->result();
		}
	   else{
			$this->db->select("*");
			$this->db->order_by("id", "DESC");
			$this->db->limit($limit,$start);
			$this->db->from(PRODUCTS);
			$this->db->where('status', 1);
			$this->db->where('publically_status', 0);
			$query = $this->db->get();
			return $query->result(); 
	   }
	
	  
	}

    public function getbestsellercat(){
          $category = $this->db->query('SELECT * from importapp_categories where category_name = "best sellers"'); 
          return $category->row_array();
    }


	public function categoryProductsCount($cat_id,$keywords){
		$where = array();
		$where[] = "importapp_products.status=1";
		if( !empty($keywords)){
			$where[] = "importapp_products.product_title LIKE '%$keywords%'";
		}
		if($cat_id !=0)  {
			$where[] = "importapp_products.product_category=$cat_id";
		}
		if( count($where) < 1) { 
			$query = $this->db->query('SELECT * from importapp_products'); 
		}else{ 
			$where = ( empty($where) )? " ": implode(" && ", $where);
			$query = $this->db->query('SELECT * from importapp_products where '.$where);			
		}
		return $query-> num_rows();
	}
	
	public function get_product_detail($product_id){  
		$this->db->select("*");
		$this->db->from(PRODUCTS);
		$this->db->join(IMAGES, 'importapp_products.id = importapp_images.product_id');
		$this->db->where('importapp_products.id', $product_id);
		$query = $this->db->get(); 
		return $query->result_array();
	}
	
	public function get_product_suppliers($product_id){
		$this->db->select("product_supplier");
		$this->db->from(PRODUCTS);
		$this->db->where('id', $product_id);
		$query = $this->db->get();  
		$suppliers = $query->result_array();
		$supplier = $suppliers[0]['product_supplier'];

		
		$this->db->select("name");
		$this->db->from(SUPPLIERS);
		$this->db->where('id', $supplier);
		$query = $this->db->get();  
		$result = $query->result_array();
		
		return $result;
	}
	
	public function get_product_product_variants($product_id){
		$this->db->select('*, importapp_variants.id AS product_varinat_ids' );
		$this->db->from(VARIANTS);
		$this->db->join(IMAGES, 'importapp_images.variant_id = importapp_variants.id', 'left');
		$this->db->where(array('importapp_variants.product_id' => $product_id));
		$this->db->order_by("importapp_variants.id", "ASC");
		$query = $this->db->get();
	    if($query->num_rows() > 0){
		    return $query->result_array();
		}
		else{
		    return false;
		}
	}
	
	public function get_product_product_images($product_id){
		$this->db->select("*");
		$this->db->from(IMAGES);
		$this->db->where('importapp_images.product_id', $product_id);
		$this->db->where('importapp_images.variant_id', "");
		$this->db->order_by("importapp_images.id", "DESC");
		$query = $this->db->get();
		if($query->num_rows() > 0){
		    return $query->result_array();
		}
		else{
		    return false;
		}
	}
	
	public function get_product_By_Id($product_id){		
		$this->db->select('*');    
		$this->db->from(PRODUCTS);
		$this->db->where('importapp_products.id', $product_id);
		$query = $this->db->get();
		return $query->result_array(); 
	}
	
	
	
	
	public function get_related_product($product_category,$product_id){	   
		$this->db->select("*");
		$this->db->from(PRODUCTS);
		$this->db->join(IMAGES, 'importapp_products.id = importapp_images.product_id');	
		$this->db->where('importapp_products.product_category', $product_category );
		$this->db->where('importapp_products.id !=',$product_id);
		$this->db->group_by('importapp_images.product_id');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result_array(); 
	}
	
	public function category_name($id){	   
		$this->db->select("*")->from(CATEGORIES)->where("id ", $id);
		$query = $this->db->get();		
		if($query->num_rows() > 0){
		    return $query->result_array();
		}else{
		    return false;
		}
	}
	
	
	public function products_sorting($cat_id,$filter,$filter_value){
		if($filter=="date"){ $filter="created_at"; }else{ $filter=$filter; }
		if($cat_id==0){			
			$this->db->select('*');			
			$this->db->from(PRODUCTS);	
			$this->db->where('status', 1);
			$this->db->order_by($filter,$filter_value);
			$query = $this->db->get();
			return $query->result();		
		}else{
			$this->db->select('*');			
			$this->db->from(PRODUCTS);	
			$this->db->where('product_category',$cat_id);
			$this->db->where('status', 1);
			$this->db->order_by($filter,$filter_value);
			$query = $this->db->get();
			return $query->result();
		}
		if($query->num_rows() > 0){
		    return $query->result_array();
		}
		else{
		    return false;
		}
	}

	public function fetch_single_product($id){
		$this->db->select('*');
		$this->db->from(PRODUCTS);
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}		

	public function get_images($product_id){
		$this->db->select('*');
		$this->db->from(IMAGES);
		$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		return $query->result();
	}
	public function saveVariantOptions($data){
		$data['status'] = 0;
		$this->db->insert('variant_options', $data);
		$id = $this->db->insert_id();
		return $id;
	}
	public function getVariantOptions($product_id){
		$this->db->select('*');
		$this->db->from('variant_options');
		$this->db->where('pid=',$product_id);
		$query = $this->db->get();
		return $query->row();
	}
	public function getVariantImage($product_id,$variant_id){
		$this->db->select('*');
		$this->db->from(IMAGES);
		$this->db->where('variant_id=',$variant_id);
		$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function delete_all_product_variants($id){		
		$this->db->where('product_id', $id);
		$this->db->delete(VARIANTS);
		
		$this->db->where('product_id', $id);
		$this->db->where('variant_id !=', '');
		$this->db->delete(IMAGES);
		
		$this->db->where('pid', $id);
		$this->db->delete('variant_options');
		
	}
	public function delete_variant_withOptions($vid,$pid){		
		settype($vid, "integer"); 			
		$this->db->where('id', $vid);
		$this->db->delete(VARIANTS);		
		$this->db->select('*');
		$this->db->from(VARIANTS);
		$this->db->where('product_id', $pid);
		$query = $this->db->get();
		if($query->num_rows() < 1){
			$this->db->where('pid', $pid);
			$this->db->delete('variant_options');
		}
		$this->db->where('variant_id', $vid);
		$this->db->delete(IMAGES);
	}
	public function variant_option_price($product_id,$option1,$option2,$option3){
		$this->db->select('*');
		$this->db->from(VARIANTS);
		$this->db->where('product_id',$product_id);
		$this->db->where('option1',$option1);
		$this->db->where('option2',$option2);
	    $this->db->where('option3',$option3);	       	
		$query = $this->db->get();
		return $query->result();
	}
	public function variant_option_image($product_id,$variant_id){
		$this->db->select('src');
		$this->db->from(IMAGES);
		$this->db->where('product_id',$product_id);
		$this->db->where('variant_id',$variant_id);	       	
		$query = $this->db->get();
		return $query->result();
	}
	
	/****** database quries ********/
	public function products_database($limit, $start) {	
		$this->db->select('*');
		$query = $this->db->get(PRODUCTS);
		$this->db->limit($limit,$start);
        $this->db->order_by("id", "DESC");		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false; 
	}
	
	/******     End Here ***********/
	
	
	public function get_src_pro($src_id,$req_by){
		$this->db->select('*');
		$this->db->from("importapp_sourcing_products");
		$this->db->where('id',$src_id);
		$this->db->where('u_id',$req_by);	       	
		$query = $this->db->get();
		return $query->row();
	}
	
	public function updatesource($src_id){
		$data = array(
			'admin_status' => 1
		);
		$this->db->update("importapp_sourcing_products", $data, "id =$src_id");
	}
	
	public function insert_source_approved($sourced_data){
		$this->db->insert('importapp_sourced_approved_pro',$sourced_data);
	}
	
}