<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model{
	protected $_table 			        = '';
	protected $_primary_key 	        = 'id';
	protected $_primary_filter 	        = 'intval';
	protected $_order_by 		        = '';
	protected $_order 			        = 'DESC';
    protected $_orders                  = array();
    protected $_timestamps 		        = FALSE;
    protected $_log_user                = FALSE;
    protected $_softdelete 		        = FALSE;
    protected $_user_session            = 'unico_users_id';
	protected $return 			        = FALSE;
	protected $result			        = FALSE;
	protected $_fields 			        = array();
	protected $_fields_toshow	        = array();
    protected $_append_select           = array(); 
	protected $_data			        = array();
	protected $_limit			        = NULL;
	protected $_offset			        = 0;
	protected $_where			        = array();
	protected $_join			        = array();
    protected $_or_where			    = array();
    protected $_group_by			    = '';
	
	function __construct() {
		parent::__construct();
		$this->check_table_name();
        $this->check_fields();
        
        $this->db = $this->load->database('default',TRUE);

	}

	function check_table_name() {
		if (empty($this->_table)) {
			throw new Exception("_table variable must be set in model.", 99);
		}
	}

	function check_fields() {
		if (empty($this->_fields)) {
			throw new Exception("_fields variable must be set in model.", 99);
		}
	}

	function array_from_post($fields) {
		$data = array();
		foreach ($fields as $field => $value) {
            if(!empty($this->input->post($value))) {
                $data[$field] = $this->input->post($value);
            }
		}

		return $data;
    }
    
    public function is_unique($value, $field_label, $field) {
        $this->db->select('*')
                    ->from($this->_table)
                    ->where($this->_table.'.'.$field, $this->input->post($value));
        if ($this->_softdelete) {
            $this->db->group_start()
                        ->where($this->_table.'.'.'deleted =', null)
                        ->or_where($this->_table.'.'.'deleted !=', '1')
                    ->group_end();
        }

        $test = $this->db->get();

        if ($test->num_rows() > 0) {
            $this->lang->load('form_validation');
            $messages = $this->lang->line('form_validation_is_unique');
            $messages = error_delimeter(1).str_replace('{field}', $field_label, $messages).error_delimeter(2);

            return ['status' => false, 'message' => $messages];
        } else {
            return ['status' => true];
        }
    }

    public function queryValue($select, $where = FALSE) {
        if ($where == TRUE) {
            $this->db->select($select)
                ->from($this->_table)
                ->where($where);
        } else {
            $this->db->select($select)
                ->from($this->_table);
        }

        $result = $this->db->get();

		if ($result->num_rows() > 0)	{
	        return $result->result();        		
		} else {
	        return $result->row(); 
	    }
	}

    public function limit($limit, $offset = 0) {
        $this->_limit = $limit;
        $this->_offset = $offset;

        return $this;
    }

    public function push_to_data($key, $value) {
        $this->_data[$key] = $value;

        return $this;
    }

    public function unset_data($key) {
        unset($this->_data[$key]);

        return $this;
    }

    public function where(array $where) {
        $this->_where = array_merge($this->_where, $where);

        return $this;
    }

    public function or_where(array $or_where) {
        $this->_or_where = array_merge($this->_or_where, $or_where);

        return $this;
    }

    public function order_by($column, $type) {
        $this->_orders[] = (object) array('order_column' => $column, 'order_type' => $type);

        return $this;
    }

     public function group_by($column) {
        $this->_group_by = $column;

        return $this;
    }

    public function join($table_name, $join_keys, $join_type = null) {
        $this->_join[] = (object) array('foreign_table' => $table_name, 'foreign_key' => $join_keys, 'join_type' => $join_type);
        
        return $this;
    }

    public function push_select($column_name, $protect = true) {
        if (is_array($column_name)) {
            $this->_append_select[] = (object) array('column' => $column_name, 'protect' => $protect);
        } else {
            $this->_fields_toshow[] = $column_name;
        }

        return $this;
    }

    public function clear_select() {
        $this->_fields_toshow = array();
        $this->_append_select = array(); 
    
        return $this;
    }

    public function clear_group() {
        $this->_group_by = '';
        return $this;
    }
    
    public function clear_join() {
        $this->_join = array(); 

        return $this;
    }

    public function clear_where() {
        $this->_where = array(); 

        return $this;
    }

    public function clear_order() {
        $this->_where = array(); 
        $this->_order_by = '';
	    $this->_order = '';
    }

    public function clear_fields_toshow() {
        $this->_fields_toshow = array(); 

        return $this;
    }
	
	public function find($id) {
        
		if ($this->_fields_toshow) {
			$select = implode(',', $this->_fields_toshow);
		} else {
			$select = '*';
		}

		$this->db->select($select);

        foreach ($this->_append_select as $row) {
            $select = implode(',', $row->column);
            $this->db->select($select, $row->protect);
        }

		$filter = $this->_primary_filter;
		$id = $filter($id);

		$this->db->where($this->_table.".".$this->_primary_key, $id);

		if ($this->_order_by) {
			$this->db->order_by("$this->_table.$this->_order_by","$this->_order");
		}

        if ($this->_orders) {
			foreach ($this->_orders as $row) {
                $this->db->order_by($row->order_column, $row->order_type);
			}
		}

		if ($this->_where) {
			$this->db->where($this->_where);
		}

		if ($this->_join) {
			foreach ($this->_join as $row) {
				if (!empty($row->join_type)) {
					$this->db->join($row->foreign_table, $row->foreign_key, $row->join_type);
				} else {
					$this->db->join($row->foreign_table, $row->foreign_key);
				}
			}
        }

        if($this->_group_by) {
            $this->db->group_by($this->_group_by);
        }
        
        if ($this->_softdelete) {
            $this->db->group_start();
            $this->db->where($this->_table.'.'.'deleted =', null);
            $this->db->or_where($this->_table.'.'.'deleted !=', '1');
            $this->db->group_end();
        }

		return $this->db->get($this->_table)->row();
	}

	public function findAll() {
        
		if ($this->_fields_toshow) {
			$select = implode(',', $this->_fields_toshow);
		} else {
			$select = '*';
		}

		$this->db->select($select);

        foreach ($this->_append_select as $row) {
            $select = implode(',', $row->column);

            $this->db->select($select, $row->protect);
        }

		if ($this->_order_by) {
			$this->db->order_by("$this->_table.$this->_order_by","$this->_order");
		}

        if ($this->_orders) {
			foreach ($this->_orders as $row) {
                $this->db->order_by($row->order_column, $row->order_type);
			}
		}

        if($this->_group_by) {
            $this->db->group_by($this->_group_by);
        }

		if ($this->_limit) {
			$this->db->limit($this->_limit, $this->_offset);
		}

		if ($this->_where) {
			$this->db->where($this->_where);
        }
        
        if ($this->_softdelete) {
            $this->db->group_start();
            $this->db->where($this->_table.'.'.'deleted =', null);
            $this->db->or_where($this->_table.'.'.'deleted !=', '1');
            $this->db->group_end();
        }

		if ($this->_join) {
			foreach ($this->_join as $row) {
				if (!empty($row->join_type)) {
					$this->db->join($row->foreign_table, $row->foreign_key, $row->join_type);
				} else {
					$this->db->join($row->foreign_table, $row->foreign_key);
				}
			}
		}

		return $this->db->get($this->_table)->result();
    }
    
   

	public function save($id = NULL) {
        $this->_data = array_merge($this->array_from_post($this->_fields), $this->_data);
        
		if ($this->_timestamps == TRUE) {
			$now = date('Y-m-d H:i:s');
			($id ? $this->_data['updated_at'] = $now : $this->_data['created_at'] = $now);
        }

        if ($this->_log_user == TRUE) {
            ($id ? $this->_data['updated_by'] = $this->_user_id : $this->_data['created_by'] = $this->_user_id);
        }
        
		if ($id == NULL) {
			$this->db->set($this->_data);
            $this->db->insert($this->_table);

            if (empty($this->_data[$this->_primary_key])) {
                $id = $this->db->insert_id();
            } else {
                $id = $this->_data[$this->_primary_key];
            }
		} else {
			$this->db->set($this->_data);
			$this->db->where($this->_primary_key, $id);
			$this->db->update($this->_table);
        }
		
		return $id;
    }
    
    public function init_batch()
    {
        $this->clear_where();
        return $this;
    }
    public function save_batch(array $data, $column_key = null){

		if ($this->_timestamps == TRUE) {
            $now = date('Y-m-d H:i:s');
            $data = array_map(function($arr) use ($column_key, $now) {
                return (array) $arr + ($column_key ? ['updated_at' => $now] : ['created_at' => $now]);
            }, $data);
        }
        
        if ($this->_log_user == TRUE) {
            $user_id = $this->_user_id;
            $data = array_map(function($arr) use ($column_key, $user_id) {
                return (array) $arr + ($column_key ? ['updated_by' => $user_id] : ['created_by' => $user_id]);
            }, $data);
        }

		if ($column_key === NULL) {
			$this->db->insert_batch($this->_table, $data);
            
		} else {
            if ($this->_where) {
                $this->datatables->where($this->_where);
            }

			$this->db->update_batch($this->_table, $data, $column_key);
		}
		
		return true;
	}

	public function delete($id) {
		$filter = $this->_primary_filter;
		$id = $filter($id);
		
		if (!$id) {
			return FALSE;
		} else {
			$this->db->where($this->_primary_key, $id);

			if($this->_where) {
				$this->db->where($this->_where);
			}

            $this->db->limit(1);
            
            if ($this->_softdelete) {
                $data = ['deleted' => '1'];
                if ($this->_timestamps == TRUE) {
                    $data['deleted_at'] = date('Y-m-d H:i:s');
                }
                if ($this->_log_user == TRUE) {
                    $data['deleted_by'] = $this->_user_id;
                }
                $this->db->set($data);

                return $this->db->update($this->_table);
            } else {
                return $this->db->delete($this->_table);
            }
			
		}
	}

	public function totalRow() {

        if ($this->_join) {
			foreach ($this->_join as $row) {
				if (!empty($row->join_type)) {
					$this->db->join($row->foreign_table, $row->foreign_key, $row->join_type);
				} else {
					$this->db->join($row->foreign_table, $row->foreign_key);
				}
			}
		}
        
		if ($this->_where) {
			$this->db->where($this->_where);
		}

		$num_rows = $this->db->get($this->_table);
        
		return $num_rows->num_rows();
	}

    public function resultMax($field) {
        if ($this->_join) {
			foreach ($this->_join as $row) {
				if (!empty($row->join_type)) {
					$this->db->join($row->foreign_table, $row->foreign_key, $row->join_type);
				} else {
					$this->db->join($row->foreign_table, $row->foreign_key);
				}
			}
		}
        
		if ($this->_where) {
			$this->db->where($this->_where);
		}
            
        $this->db->select_max($field);
		$rows = $this->db->get($this->_table);

		return $rows->row();

    }

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */