<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

 
abstract class SnideModelCollection implements \Iterator{

    protected $table_name;			#The name of the database table acting upon
    protected $primary_key;			#Primary Key of the database table
    protected $object_class;		#Class of the objects
    protected $object_array;		#The objects
    protected $sort;				#Sort info

    /**
        * The Object's constructor
        */
    protected function __construct($args){
        $this->object_class = substr(get_class($this),0,strpos(get_class($this),'Collection'));
        $c = new $this->object_class;
        $this->primary_key = $c->get_primary_key();
        $this->table_name = $c->get_table_name();
        $this->object_array = [];
        $this->sort = false;
        
        #Process the arguments if there are any
        if(!empty($args)){
            $this->init($args);
            
            
            #If $args[0] is an array
            if(isset($args[0]) && is_array($args[0])){
                #Initiate the collection with these arguments
                
            }else{
                throw new CollectionObjectInvalidConstructorArgumentsException('Invalid constructor arguments');

            }
            
            
            
            
        }

    }
    
    /**
        * Initialises the object using constructor argumetns
        * @param array the argument
        * @return true on success
        */
    private function init($args){
        #If a single array is passed through
        if(count($args) == 1 && isset($args[0]) && is_array($args[0])){
            #Use the arguments as initial contents
            $this->set_contents($args[0]);
            return true;

        }else{
            throw new CollectionObjectInvalidConstructorArgumentsException('Invalid constructor arguments');
        }
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




    /**
        * Adds a TableObject to the end of the collection
        * @param TableObject $object
        * @return true on success
        */
    public function add(TableObject $object){
        if($object instanceof $this->object_class){
            $this->object_array[] =  $object;
            return true;
        }
        return false;
        #should probably throw an exception here
    }

    /**
        * Set the data in the collection using an array of objects
        * @example $col->set_collection([new TableObjectChild(),new TableObjectChild]);
        * @param array $object_array
        */
    public function set_contents(array $object_array){

        if( is_array($object_array) && count($object_array) > 0 ){
            foreach($object_array as $object){

                if(!is_object($object)){
                    ## wrong exepction should be something like invalid contents exception
                    throw new CollectionObjectInvalidContentsException('Invalid data to set as contents');
                }

                if(!is_a($object,$this->object_class,true)){
                    ## wrong exepction
                    throw new CollectionObjectInvalidContentsException('Invalid data to set as contents');
                }

            }
        }else{
            ## wrong exepction
            throw new CollectionObjectInvalidContentsException('Contents is expected as array of TableObject objects');
        }

        $this->object_array = $object_array;

        return true;
    }

    /**
        * Return the array of objects that makes up the collection
        * @return array of TableObjects
        */
    public function get_contents(){
        return $this->object_array;
    }

    /**
        * Load a meekrodb row onto the table stack
        * @param row as returned by meekro db
        */
    protected function load($row){
        #if( $this->object_class)
        # initiate object of this class
        # else
        # initiate generic object?
        $table_object = new $this->object_class;
        $table_object->load($row[$this->primary_key]);
        $this->object_array[] = $table_object;
    }

    /**
        * Empties the collection of its contents
        */
    protected function clear(){
        $this->object_array = [];
    }

    /**
        * Sort by is used to set the collection to a particular
        * load-sort mode. When data is loaded into the CollectionObject
        * from the database it will be sorted by the specified order
        * till this is changed or the sort_by variable is set to false
        */
    #$admin->sort_by('age','desc')->load_all();
    public function sort_by(array $on_by){
        $args = func_get_args();
        if(count($args == 1 )){
            if(is_array($args[0]))
                $on_by = $args[0];
            else throw new CollectionObjectInvalidArgumentException('Valid arguments for sort_by are sort_by( (string)$column, (string)$order) or sort_by( array((string)$column => (string)$order)');
        }elseif(count($args) == 2){
            if(is_string($args[0]) && is_string($args[1]))
                $on_by = [ $args[0] => $args[1] ];
            else throw new CollectionObjectInvalidArgumentException('Valid arguments for sort_by are sort_by( (string)$column, (string)$order) or sort_by( array((string)$column => (string)$order)');
        }else{
            throw new CollectionObjectInvalidArgumentException('Valid arguments for sort_by are sort_by( (string)$column, (string)$order) or sort_by( array((string)$column => (string)$order)');
        }
        $this->sort = $on_by;
        return $this;
    }

    private function add_sort_sql($sql){
        $sql = trim($sql);
        foreach($this->sort as $on => $by){
            $sql.=" ORDER BY {$on} {$by},";
        }
        return rtrim($sql,',');
    }
    
    /**
        * Load all the records in the table into this collection
        * @return int rows_affected
        */
    public function load_all(){

        $sql = "SELECT {$this->primary_key} FROM {$this->table_name}";
        if(!empty($this->sort)){
            $sql = $this->add_sort_sql($sql);
        }
        $rows = \DB::query($sql);
        if(\DB::affectedRows() == 0){
            return false;
        }

        $this->clear();
        foreach($rows as $row){
            $this->load($row);
        }

        return \DB::affectedRows();
    }

    /**
        * Load all the records in the table into this collection
        * where value of the given column matches the given value.
        * @param $column, $value
        * @return int rows_affected
        */
    public function load_where($column, $value){
        #should make it possible to pass a single associative array with multiple values for each
        if(is_string($column)){
            if(is_int($value)){
                $sql = "SELECT * FROM {$this->table_name} WHERE `{$column}` = {$value}";
            }elseif(is_string($value)){
                $sql = "SELECT * FROM {$this->table_name} WHERE `{$column}` = '{$value}'";
            }else{
                throw new CollectionObjectInvalidArgumentException("The (string)column argument for the load_where function is not a valid.");
            }
        }else{
            throw new CollectionObjectInvalidArgumentException("The (string)column argument for the load_where function is not a valid.");
        }

        if(!empty($this->sort)){
            $sql = $this->add_sort_sql($sql);
        }
        $rows = \DB::query($sql);

        if(\DB::affectedRows() == 0) return false;

        $this->clear();
        foreach($rows as $row){
            $this->load($row);
        }
        return \DB::affectedRows();
    }

    
    /**
        * Load all the records in the table into this collection
        * where value of the given column is like the given value.
        * @param $column, $value
        * @return int rows_affected
        */
    #should make it possible to pass a single associative array with multiple values for each
    public function load_where_like($column, $value){

        if(is_string($column)){
            if(is_int($value)){
                $sql = "SELECT * FROM {$this->table_name} WHERE `{$column}` LIKE {$value}";

            }elseif(is_string($value)){
                $sql = "SELECT * FROM {$this->table_name} WHERE `{$column}` LIKE '{$value}'";
            }else{
                throw new CollectionObjectInvalidArgumentException("The (mixed)value argument for the load_where_like function is not a valid.");
            }
        }else{
            throw new CollectionObjectInvalidArgumentException("The (string)column argument for the load_where_like function is not a valid.");
        }

        if(!empty($this->sort)){
            $sql = $this->add_sort_sql($sql);
        }
        $rows = \DB::query($sql);

        if(\DB::affectedRows() == 0) return false;

        $this->clear();
        foreach($rows as $row){
            $this->load($row);
        }
        return \DB::affectedRows();
    }

    /**
        * Load a so called page of the database using
        * a limit and offset
        * @param int $limit The limit
        * @param int $offset The offset
        */
    public function load_page($limit, $offset){

        if(is_int($limit)){
            if(is_int($offset)){
                $sql = "SELECT * FROM {$this->table_name}";
                if(!empty($this->sort)){
                    $sql = $this->add_sort_sql($sql);
                }
                $sql .= " LIMIT {$limit} OFFSET {$offset}";
                $rows = \DB::query($sql);
            }else{
                throw new CollectionObjectInvalidArgumentException("The (int)offset argument for the load_page function is not a valid.");
            }
        }else{
            throw new CollectionObjectInvalidArgumentException("The (int)limit argument for the load_page function is not valid.");
        }

        if(\DB::affectedRows() == 0) return false;

        $this->clear();
        foreach($rows as $row){
            $this->load($row);
        }
        return \DB::affectedRows();
    }

    /**
        * Load a custom query out of the database
        * @param string $sql The SQL query
        */
    public function load_sql($sql){
        $this->clear();
        if(is_string($sql)){
            $rows = \DB::query($sql);
        }

        if(\DB::affectedRows() == 0){
            return false;
        }
        foreach($rows as $row){
            $this->load($row);
        }
        return \DB::affectedRows();
    }

    public function save(){
        foreach($this->object_array as $object){
            $object->save();
        }
    }

    public function delete(){
        foreach($this->object_array as $object){
            $object->delete();
        }
    }
}

/* Base CollectionObjectException
    */
class CollectionObjectException extends \Exception{
    public function __construct($message,$code,\Exception $prev = null){
        parent::__construct($message,$code,$prev);
    }

    public function __toString(){
        return __CLASS__.":[{$this->code}]:  {$this->message}";
    }
}

/**
    * This Exception is raised when invalid arguments are provided for the object's constructor
    */
class CollectionObjectInvalidConstructorArgumentsException extends CollectionObjectException{
    public function __construct($message, $code = 0){
        parent::__construct($message,$code);
    }
}

/**
    * This Exception is raised when a function is passed an invalid argument.
    */
class CollectionObjectInvalidArgumentException extends CollectionObjectException{
    public function __construct($message, $code = 1){
        parent::__construct($message,$code);
    }
}

/**
    * This Exception is raised when a function is passed an invalid argument.
    */
class CollectionObjectInvalidContentsException extends CollectionObjectException{
    public function __construct($message, $code = 2){
        parent::__construct($message,$code);
    }
}
