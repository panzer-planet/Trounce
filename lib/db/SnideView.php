<?php
/**
 * (c) 2013 CoboltDB
 *
 * @author Werner Roets <cobolt.exe@gmail.com>
 *
 */
 namespace CDB{
	/**
	 * This abstract class provides many functions you can use to interact
	 * with a database.
	 */
	class TempTable implements \Iterator{
        
        private $sql;
        
        public function __construct(){
            $this->sql = "";
        }
        
        public function get_sql(){
            return $this->sql;
        }
        public function select($column_array, $table_name){
            $sql = "SELECT ";
            $temp_sql = "";
            foreach($column_array as $column){
                $temp_sql .= ', '.$column;
            }
            $sql .= substr($temp_sql,1);
            $sql .= " FROM {$table_name}";
            $this->sql .= $sql;
            return $this;
        }
        
        /**
		 * Adds join sql to the query sql
		 * @param array table_array
		 * @param array join_array
		 * @return this
		 */
        public function join(array $table_array, $join_array){
            $sql = " JOIN (";
            $temp_sql = "";
            foreach($table_array as $table){
                $temp_sql .= ",{$table} ";
            }
            
            $sql .= substr($temp_sql,1);
            $sql .= ") ON (";
            
            foreach($join_array as $k => $v){
                
                $sql .= " {$k}={$v}";
            }
            $sql .= ") ";
            $this->sql .= $sql;
            return $this;
        }
        
        public function where($match_array){
            $sql =  " WHERE ";
            foreach($match as $k => $v){
                $sql .= ",{$k}={$v} ";
            }
            
            return $this;
        }
        
        		public function rewind()
		{
			reset($this->object_array);
		}

		public function current()
		{
			$var = current($this->object_array);
			return $var;
		}

		public function key()
		{
			$var = key($this->object_array);
			return $var;
		}

		public function next()
		{
			$var = next($this->object_array);
			return $var;
		}

		public function valid()
		{
			$key = key($this->object_array);
			$var = ($key !== NULL && is_int($key));
			return $var;
		}
    }
    
 }
		