<?php
class Crud_m extends CI_model{

  public $blogs_id    = 'blogs_id';
  public $products_id    = 'products_id';
  public $services_id    = 'services_id ';
  public $products_cat_id    = 'products_cat_id';
  public $id_identitas    = 'id_identitas';
  public $table_identitas = 'identitas';
  public $table_products ='products';
  public $table_services ='services';
  public $table_products_category ='products_category';
  public $table_blogs ='blogs';


  public $id_kabupaten = 'id';
  public $id_kecamatan = 'id';
  public $uname = 'username';
  public $kabupaten = 'kabupaten';
  public $table_users_bisnis = 'user_bisnis';
  public $id_bisnis = 'id_bisnis';
  public $id_project = 'id_project';
  public $id_harga = 'id_harga';
  public $table_bisnis = 'user_bisnis';
  public $table_harga = 'harga';
  public $table_kategori ='user_company';
  public $table_kecamatan ='kecamatan';
  public $id_kategori='user_company_account';
  public $kecamatan ='kecamatan';
  public $table_user='user';
  public $table_project='project';

  function get_by_id_harga($id)
  {
    $this->db->select('*');
    $this->db->from($this->table_harga);
    $this->db->join($this->table_users_bisnis, $this->table_harga.'.'.$this->uname.'='.$this->table_users_bisnis.'.'.$this->uname);
    $this->db->where('judul_seo', $id);
    $this->db->join($this->table_kategori, $this->table_users_bisnis.'.'.$this->id_kategori.'='.$this->table_kategori.'.'.$this->id_kategori);
    $this->db->join($this->table_user, $this->table_users_bisnis.'.'.$this->uname.'='.$this->table_user.'.'.$this->uname);
    $this->db->join($this->kabupaten, $this->table_users_bisnis.'.'.$this->kabupaten.'='.$this->kabupaten.'.'.$this->id_kabupaten);
    $this->db->join($this->table_kecamatan, $this->table_users_bisnis.'.'.$this->kecamatan.'='.$this->table_kecamatan.'.'.$this->id_kecamatan);
    return $this->db->get()->row();
  }

  public function edit($table, $data){
      return $this->db->get_where($table, $data);
  }

  public function view_ones($table1,$order,$ordering){
      $this->db->select('*');
      $this->db->from($table1);
      $this->db->order_by($order,$ordering);
      return $this->db->get()->result();
  }
  public function view_where2($table,$data){
      $this->db->where('username',$data);
      return $this->db->get($table);
  }
  public function view_where3($table,$data,$data2){
      $this->db->where('username',$data);
      $this->db->where('harga_status',$data2);
      return $this->db->get($table);
  }
  public function view_where4($table,$data,$data2){
      $this->db->where('username',$data);
      $this->db->where('project_status',$data2);
      return $this->db->get($table);
  }

  public function verifyemail($key)
  {
      $data = array('user_status' => 1);
      $this->db->where('md5(email)', $key);
      return $this->db->update('user', $data);
  }

  public function view($table){
      return $this->db->get($table);
  }

  public function view_ordering2($table,$order,$ordering){
      $this->db->select('*');
      $this->db->from($table);
      $this->db->order_by($order,$ordering);
      return $this->db->get();
  }

  public function view_ordering_like($table,$field,$data,$order,$ordering){
      $this->db->select('*');
      $this->db->from($table);
      $this->db->where($field,$data);
      $this->db->order_by($order,$ordering);
      return $this->db->get();
  }

  function get_by_id_vendors($id)
    {
      $this->db->select('*');
      $this->db->from($this->table_users_bisnis);
      $this->db->join($this->table_harga, $this->table_users_bisnis.'.'.$this->id_harga.'='.$this->table_harga.'.'.$this->id_harga);
      $this->db->join($this->table_kategori, $this->table_users_bisnis.'.'.$this->id_kategori.'='.$this->table_kategori.'.'.$this->id_kategori);
      $this->db->join($this->kabupaten, $this->table_users_bisnis.'.'.$this->kabupaten.'='.$this->kabupaten.'.'.$this->id_kabupaten);
      $this->db->join($this->table_kecamatan, $this->table_users_bisnis.'.'.$this->kecamatan.'='.$this->table_kecamatan.'.'.$this->id_kecamatan);
      $this->db->join($this->table_user, $this->table_users_bisnis.'.'.$this->uname.'='.$this->table_user.'.'.$this->uname);
      $this->db->where($this->id_bisnis, $id);
      $this->db->or_where('namabisnis_seo', $id);
      return $this->db->get()->row();
    }


  public function view_join_three($table1,$table2,$table3,$table4,$field1,$field2,$fieldlimit){
      $this->db->select('*');
      $this->db->from($table1);
      $this->db->join($table2, $table1.'.'.$field1.'='.$table2.'.'.$field1);
      $this->db->join($table3, $table2.'.'.$field2.'='.$table3.'.'.$field2);
      $this->db->join($table4, $table2.'.'.$this->kabupaten.'='.$table4.'.'.$this->id_kabupaten);
      $this->db->limit($fieldlimit);
      return $this->db->get()->result();
  }


  public function view_where_join_three_limit($table1, $table2, $table3, $table4, $field1, $field2, $fieldlimit, $where, $order = 'RAND()') {
    $this->db->select('*');
    $this->db->from($table1);
    $this->db->join($table2, $table1.'.'.$field1.'='.$table2.'.'.$field1);
    $this->db->join($table3, $table2.'.'.$field2.'='.$table3.'.'.$field2);
    $this->db->join($table4, $table2.'.'.$this->kabupaten.'='.$table4.'.'.$this->id_kabupaten);
    $this->db->where($where);
    $this->db->order_by($order);
    $this->db->limit($fieldlimit);
    return $this->db->get()->result();
  }

  public function view_where_join_three($table1,$table2,$table3,$table4,$field1,$field2,$where){
      $this->db->select('*');
      $this->db->from($table1);
      $this->db->join($table2, $table1.'.'.$field1.'='.$table2.'.'.$field1);
      $this->db->join($table3, $table2.'.'.$field2.'='.$table3.'.'.$field2);
      $this->db->join($table4, $table2.'.'.$this->kabupaten.'='.$table4.'.'.$this->id_kabupaten);
      $this->db->where($where);
      return $this->db->get()->result();
  }

  public function view_ordering($table,$order,$ordering)
  {
      $this->db->select('*');
      $this->db->from($table);
      $this->db->order_by($order,$ordering);
      return $this->db->get()->result_array();
  }

  public function view_ordering_admin($table,$order,$ordering)
  {
      
      $this->db->from($table);
      $this->db->where_in('user_level_id', [3,4,5,6,7,8]);
      $this->db->order_by($order,$ordering);
      return $this->db->get()->result_array();
  }
  function get_by_id_identitas($id)
  {
      $this->db->where($this->id_identitas, $id);
      return $this->db->get($this->table_identitas)->row();
  }
  function get_by_id_products($products_id)
  {
      $this->db->where($this->products_id, $products_id);
      $this->db->or_where('products_judul_seo', $products_id);
      return $this->db->get($this->table_products)->row();
  }
  function get_by_id_services($services_id)
  {
      $this->db->where($this->services_id , $services_id );
      $this->db->or_where('services_judul_seo', $services_id );
      return $this->db->get($this->table_services)->row();
  }

  function get_by_id_products_category($products_cat_id)
  {
      $this->db->where($this->products_cat_id, $products_cat_id);
      $this->db->or_where('products_cat_judul_seo', $products_cat_id);
      return $this->db->get($this->table_products_category)->row();
  }

  function get_by_id_blogs($id)
  {
      $this->db->where($this->blogs_id, $id);
      $this->db->or_where('blogs_judul_seo', $id);
      return $this->db->get($this->table_blogs)->row();
  }
  function get_by_id_post($id,$table_ids,$table_nama,$judul_seo)
  {

      $this->db->where($table_ids, $id);
      $this->db->or_where($judul_seo, $id);
      return $this->db->get($table_nama)->row();
  }
  function get_by_id_post_one($id,$table_ids,$table_nama)
  {

      $this->db->where($table_ids, $id);
      return $this->db->get($table_nama)->row();
  }

  public function view_join_where($table1,$table2,$field,$where)
  {
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
        return $this->db->get()->result();
    }

    public function update_join_where($table1,$table2,$field,$data,$where)
    {
          $this->db->where($where);
          $this->db->from($table1);
          $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);

          $this->db->set($data);
          $this->db->update($table1,$table2);

      }

    public function view_join_where_array($table1,$table2,$field,$where){
          $this->db->select('*');
          $this->db->from($table1);
          $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
          $this->db->where($where);
          return $this->db->get();
      }
    public function view_join_one($table1,$table2,$field,$where,$order,$ordering,$baris,$dari){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        $this->db->limit($dari, $baris);
        return $this->db->get()->result();
    }
  public function view_join_where_promo($table1,$table2,$field,$status,$status2,$order,$ordering,$baris,$dari)
  {
          $this->db->select('*');
          $this->db->from($table1);
          $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
          $this->db->where($status,'publish');
          $this->db->where($status2,'PROMO');
          $this->db->order_by($order,$ordering);
          $this->db->limit($dari, $baris);
          return $this->db->get()->result();
  }
  public function view_join_where_publish($table1,$table2,$field,$status,$order,$ordering,$baris,$dari)
  {
            $this->db->select('*');
            $this->db->from($table1);
            $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
            $this->db->where($status,'publish');
            $this->db->order_by($order,$ordering);
            $this->db->limit($dari, $baris);
            return $this->db->get()->result();
  }
  public function view_where_ordering($table,$data,$order,$ordering)
  {
      $this->db->where($data);
      $this->db->order_by($order,$ordering);
      $query = $this->db->get($table);
      return $query->result_array();
  }

  public function view_where_user_ordering($table,$order,$ordering)
  {   
      $this->db->where_in('level', [1,2,3,4,8]);
      $this->db->order_by($order,$ordering);
      $query = $this->db->get($table);
      return $query->result_array();
  }
  public function view_where_like_ordering($table,$data,$data2,$order,$ordering)
  {
      $this->db->select('*');
      $this->db->from($table);
      $this->db->like($data,$data2,'after');
      $this->db->limit(5,1);
      $this->db->order_by($order,$ordering);
      return $this->db->get()->result_array();
  }
  public function view_where_like_ordering1($table,$data,$data2,$order,$ordering)
  {
      $this->db->select('*');
      $this->db->from($table);
      $this->db->like($data,$data2,'after');
      $this->db->limit(15,1);
      $this->db->order_by($order,$ordering);
      return $this->db->get()->result_array();
  }
  public function view_limit_ordering($table,$order,$ordering,$data2,$data3)
  {
      $this->db->select('*');
      $this->db->from($table);
      $this->db->limit($data2,$data3);
      $this->db->order_by($order,$ordering);
      return $this->db->get()->result_array();
  }
  public function view_join_where_ordering($table1,$table2,$field,$data,$order,$ordering)
  {
    $this->db->select('*');
    $this->db->from($table1);
    $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
      $this->db->where($data);
      $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
  }

  public function view_join_where_ordering_field2($table1,$table2,$field,$field2,$data,$order,$ordering)
  {
    $this->db->select('*');
    $this->db->from($table1);
    $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field2);
      $this->db->where($data);
      $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
  }
  public function view_join_where2($table1,$table2,$field,$data)
  {
    $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
    return $this->db->get_where($table1, $data);
  }
  public function view_join_where2_ordering($table1,$table2,$field,$field2,$data,$order,$ordering)
  {
    $this->db->select('*');
    $this->db->from($table1);
    $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field2);
      $this->db->where($data);
      $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
  }
  public function view_join_where3_ordering($table1,$table2,$table3,$field,$field2,$field3,$data)
  {
    $this->db->select('*');
    $this->db->from($table1);
    $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field2);
    $this->db->join($table3, $table1.'.'.$field3.'='.$table3.'.'.$field3);
    $this->db->where($data);
    return $this->db->get()->result_array();
  }
  public function view_where_non_ordering($table1,$data)
  {
    $this->db->select('*');
    $this->db->from($table1);
    $this->db->where($data);
    return $this->db->get()->result_array();
  }


  public function view_join_ordering($table1,$table2,$field,$order,$ordering)
  {
    $this->db->select('*');
    $this->db->from($table1);
    $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
    $this->db->order_by($order,$ordering);
    return $this->db->get()->result_array();
  }

  public function view_join_ordering2($table1,$table2,$field,$field2,$order,$ordering)
  {
    $this->db->select('*');
    $this->db->from($table1);
    $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field2);
    $this->db->order_by($order,$ordering);
    return $this->db->get()->result_array();
  }
  public function view_where_ordering_publish($table,$data,$order,$ordering)
  {
      $this->db->where($data);
      $this->db->where('blogs_status','publish');
      $this->db->order_by($order,$ordering);
      $query = $this->db->get($table);
      return $query->result_array();
  }
  public function delete($table, $where)
  {
      return $this->db->delete($table, $where);
  }
  public function insert($table,$data)
  {
      return $this->db->insert($table, $data);
  }
  public function view_where($table,$data)
  {
      $this->db->where($data);
      return $this->db->get($table);
  }
  public function views_row($table1,$status,$order,$ordering)
  {
     $this->db->limit(3);
     $this->db->from($table1);
     $this->db->where($status,'publish');
     $this->db->order_by($order,$ordering);
     return $this->db->get()->num_rows();
   }
  public function view_one_limit($table1,$status,$order,$ordering,$baris,$dari)
  {
         $this->db->from($table1);
         $this->db->where($status,'publish');
         $this->db->order_by($order,$ordering);
         $this->db->limit($dari, $baris);
         return $this->db->get()->result();
  }
  public function view_where_orders($table1,$status,$order,$ordering)
  {
         $this->db->from($table1);
         $this->db->where($status,'publish');
         $this->db->order_by($order,$ordering);
         return $this->db->get()->result();
  }
  public function view_where_order($table1,$where,$order,$ordering)
  {
         $this->db->from($table1);
         $this->db->where($where);
         $this->db->order_by($order,$ordering);
         return $this->db->get()->result();
  }
  public function view_where_order_limit($table1,$where,$order,$ordering,$baris,$dari)
  {
         $this->db->from($table1);
         $this->db->where($where);
         $this->db->order_by($order,$ordering);
         $this->db->limit($dari, $baris);
         return $this->db->get()->result();
  }

  public function viewz($table,$order,$ordering)
  {
      $this->db->from($table);
      $this->db->order_by($order,$ordering);
      return $this->db->get()->result();
  }
  public function view_one_limit_v2($table1,$status,$status2,$order,$ordering,$baris,$dari)
  {
         $this->db->from($table1);
         $this->db->where($status,'publish');
         $this->db->where($status2,'PROMO');
         $this->db->order_by($order,$ordering);
         $this->db->limit($dari, $baris);
         return $this->db->get()->result();
  }
  function update_counter_products($products_id)
  {
       //return current article views
       $this->db->where('products_judul_seo', urldecode($products_id));
       $this->db->select('products_dibaca');
       $count = $this->db->get('products')->row();
       // then increase by one
       $this->db->where('products_judul_seo', urldecode($products_id));
       $this->db->set('products_dibaca', ($count->products_dibaca + 1));
       $this->db->update('products');
   }


   function update_counter_views($id,$table,$judul_seo)
   {
       //return current article views
       $this->db->where($judul_seo, urldecode($id));
       $this->db->select('views');
       $count = $this->db->get($table)->row();
       // then increase by one
       $this->db->where($judul_seo, urldecode($id));
       $this->db->set('views', ($count->views + 1));
       $this->db->update($table);
   }

  function update_counter_blogs($id)
   {
        //return current article views
        $this->db->where('blogs_judul_seo', urldecode($id));
        $this->db->select('blogs_dibaca');
        $count = $this->db->get('blogs')->row();
        // then increase by one
        $this->db->where('blogs_judul_seo', urldecode($id));
        $this->db->set('blogs_dibaca', ($count->blogs_dibaca + 1));
        $this->db->update('blogs');
    }
    function update_counter_bisnis($id)
      {
          //return current article views
          $this->db->where('bisnis_judul_seo', urldecode($id));
          $this->db->select('bisnis_dibaca');
          $count = $this->db->get('bisnis')->row();
          // then increase by one
          $this->db->where('bisnis_judul_seo', urldecode($id));
          $this->db->set('bisnis_dibaca', ($count->bisnis_dibaca + 1));
          $this->db->update('bisnis');
      }
    function update_counter_templates($id)
      {
            //return current article views
            $this->db->where('templates_judul_seo', urldecode($id));
            $this->db->select('templates_dibaca');
            $count = $this->db->get('templates')->row();
            // then increase by one
            $this->db->where('templates_judul_seo', urldecode($id));
            $this->db->set('templates_dibaca', ($count->templates_dibaca + 1));
            $this->db->update('templates');
      }

      function update_counter($id,$table,$judul_seo,$dibaca)
        {
              //return current article views
              $this->db->where($judul_seo, urldecode($id));
              $this->db->select($dibaca);
              $count = $this->db->get($table)->row();
              // then increase by one
              $this->db->where($judul_seo, urldecode($id));
              $this->db->set($dibaca, ($count->$dibaca + 1));
              $this->db->update($table);
        }
      function update_counter_paketharga($id)
      {
            //return current article views
            $this->db->where('paketharga_judul_seo', urldecode($id));
            $this->db->select('paketharga_dibaca');
            $count = $this->db->get('paketharga')->row();
            // then increase by one
            $this->db->where('paketharga_judul_seo', urldecode($id));
            $this->db->set('paketharga_dibaca', ($count->paketharga_dibaca + 1));
            $this->db->update('paketharga');
        }

      function update_counter_berita($id)
       {
            //return current article views
            $this->db->where('blogs_judul_seo', urldecode($id));
            $this->db->select('blogs_dibaca');
            $count = $this->db->get('blogs')->row();
            $this->db->reset_query();
            // then increase by one
            $this->db->where('blogs_judul_seo', urldecode($id));
            $this->db->set('blogs_dibaca', ($count->blogs_dibaca + 1));
            $this->db->update('blogs');
        }

  public function view_where_ordering_limits($table,$data,$order,$ordering,$baris,$dari)
    {
         $this->db->select('*');
         $this->db->from($table);
         $this->db->where($data);
         $this->db->order_by($order,$ordering);
         $this->db->limit($dari, $baris);
         return $this->db->get()->result();
     }
     public function view_where_orderings($table,$data,$order,$ordering)
       {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where($data);
            $this->db->order_by($order,$ordering);
            return $this->db->get()->result();
        }

     public function view_ordering_limits($table,$order,$ordering,$baris,$dari)
       {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->order_by($order,$ordering);
            $this->db->limit($dari, $baris);
            return $this->db->get()->result();
        }
  public function view_join_where_kategori($table1,$table2,$table3,$table4,$field,$field2,$where,$order,$ordering,$baris,$dari)
    {
         $this->db->select('*');
         $this->db->from($table1);
         $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
          $this->db->join($table3, $table1.'.'.$field2.'='.$table3.'.'.$field2);
          $this->db->join($table4, $table1.'.'.$this->kabupaten.'='.$table4.'.'.$this->id_kabupaten);
         $this->db->where($where);
         $this->db->order_by($order,$ordering);
         $this->db->limit($dari, $baris);
         return $this->db->get();
     }

     public function view_join_where_kategori2($table1,$table2,$table3,$field,$where,$order,$ordering,$baris,$dari)
       {
            $this->db->select('*');
            $this->db->from($table1);
            $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
             $this->db->join($table3, $table1.'.'.$this->kabupaten.'='.$table3.'.'.$this->id_kabupaten);
            $this->db->where($where);
            $this->db->order_by($order,$ordering);
            $this->db->limit($dari, $baris);
            return $this->db->get();
        }
     public function view_join_all($table1,$table2,$table4,$field,$order,$ordering,$baris,$dari)
       {
            $this->db->select('*');
            $this->db->from($table1);
            $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
             $this->db->join($table4, $table1.'.'.$this->kabupaten.'='.$table4.'.'.$this->id_kabupaten);

            $this->db->order_by($order,$ordering);
            $this->db->limit($dari, $baris);
            return $this->db->get();
        }
  public function tambah_user($data)
   	{
   		$this->db->insert('user', $data);
   		$id = $this->db->insert_id();
   		return (isset($id)) ? $id : FALSE;
   	}

    public function cek_register($username,$email,$table){
          return $this->db->query("SELECT * FROM $table where username='".$this->db->escape_str($username)."' OR email='".$this->db->escape_str($email)."' ");
      }

  public function tambah_user_detail($data)
   {
     $this->db->insert('user_detail', $data);
     $id = $this->db->insert_id();
     return (isset($id)) ? $id : FALSE;
   }
  public function tambah_order($data)
   {
      $this->db->insert('products_order', $data);
      $id = $this->db->insert_id();
      return (isset($id)) ? $id : FALSE;
    }

  public function tambah_detail_order($data)
	 {
		   $this->db->insert('products_order_detail', $data);
	 }
   function total_rows() {
    $this->db->where('blogs_status','publish');
  return $this->db->get('blogs')->num_rows();
}
function get_all_blogs($per_page,$dari)
  {
    $this->db->order_by('blogs_id', 'DESC');
    $this->db->where('blogs_status','publish');
    $query = $this->db->get('blogs',$per_page,$dari);
    return $query->result();
  }
  function get_all_blogs2($per_page,$dari)
  {
    $this->db->order_by('blogs_id', 'DESC');
    $this->db->where('blogs_status','publish');
    $query = $this->db->get('blogs',$per_page,$dari);
    return $query->result();
  }
}
