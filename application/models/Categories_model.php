<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Categories_model extends MY_Model
{
	public function __construct()	{
		parent::__construct();
		$this->date	= date("Y-m-d H:i:s");
	}
	public function getCategory()
	{	
		$this->db->select('*');
		$this->db->from(CATEGORIES);
		$query = $this->db->get();
		return $query->result();
    } 
	public function saveCategory($data)
	{	
		$data['created_at'] = $this->date;
		$this->db->insert(CATEGORIES, $data);
		return true;
	}
	
	public function editCategory($data , $cat_Id)
	{	
	   if(!empty($cat_Id)){
		   $data['updated_at'] = $this->date;
		   $id = $this->db->where("id", $cat_Id)->update(CATEGORIES, $data);
		 //  return  $id;
		   return TRUE;
	   }
	}
	
	public function deleteCategory($catId){
		$this->db->where('id', $catId);
		$this->db->delete(CATEGORIES);
		return true;
	}
	
	
	public function cate_data($limit, $start) {
		$this->db->limit($limit,$start);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get(CATEGORIES);

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
		 return $data;
		}
		return false;
	}

	
    public function record_counts() {
		return $this->db->count_all(CATEGORIES);  
	}
	
	public function getCategories($cat_name)
	{	
       $where = "category_name='$cat_name'";
		$this->db->where($where);
		$query=$this->db->get(CATEGORIES);
		$result=$query->result();
		return $result;
    } 
	public function category_count($keyword){
		$this->db->select('*');		
		$this->db->where('category_name',$keyword);
		$query=$this->db->get('importapp_categories');
		return $query->num_rows();
	}
	public function category_data($keyword){
		$this->db->select('*');		
		$this->db->where('category_name',$keyword);
		$query=$this->db->get('importapp_categories');
		$result=$query->result();
		return $result;		
	}
}
?>