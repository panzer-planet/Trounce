<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

/**
    * This abstract class provides many functions you can use to interact
    * with a database.
    */
abstract class SnideModel implements \Iterator{
    
    protected $properties;		#The properties of the object
    protected $table_name;		#The name of the table the object corresponds to
    protected $loaded;			#Was data loaded from the database into this object?
    protected $primary_key;		#The primary key of the database table
    
    private $required_fields;	#An array of required fields names
    private $args;				#The constructor arguments
    private $filters;			#Regex Filters
    private $generic;           #Is this a generic instance?
    public $errors;				#Errors occured
    
    
    /**
        *	The Object's constructor
        * 		A. 	One argument string/int can be the primary key of the
        *			table. This will also load the row into the object.
        *			e.g new TableObjectChild(4);
        *		B.	As many arguments as there are properties of the object, in the correct order.
        *			Any auto_incrementing column must be set to false
        *			e.g new TableObjectChild(false,'Joe','Soap','joe.soap@gmail.com');
        *		C.	An array of as many arguments as there are properties of the object.
        *			Any auto_incrementing column must be set to false.
        *			Must be in the correct order.
        *			e.g (php5.5) new TableObjectChild([false,'Joe','Soap','joe.soap@gmail.com']); 
        *			e.g (php5.4) new TableObjectChild(new array(false,'Joe','Soap','joe.soap@gmail.com'));
        *	@param array of data to initialise with or int primary_key of row to load
        */
    protected function __construct($args){
        
        
        $this->table_name = strtolower(substr(get_class($this),0,strpos(get_class($this),'SnideModel')));
        $this->primary_key = 'id'; #hard coded default
        $this->errors = array();
        $this->properties = [];
        $this->required_fields = [];
        $this->loaded = false;
        $this->generic = false;
        $this->args = $args;
    }
    
    /**
        *	Magic method when property is being set.
        *  When a property of the TableObject is assigned to
        *  it triggers this.
        */		 
    public function __set($name, $value){
        
        if(array_key_exists($name,$this->properties)){
            $this->properties[$name] = $value;
        }else{
            array_push($this->errors, 'No such property: '.$name.' of '.get_class($this));
            throw new TableObjectNoSuchPropertyException('No such property: '.$name.' of '.get_class($this));
        }
    }
    
    /**
        * Magic method when property is being retrieved.
        * When a property of the TableObject is retreived
        * it triggers this.
    */
    public function __get($name){
        if(array_key_exists($name,$this->properties)){
            return $this->properties[$name];
        }else{
            array_push($this->errors, 'No such property: '.$name.' of '.get_class($this));
            throw new TableObjectNoSuchPropertyException('No such property: '.$name.' of '.get_class($this));
        }
    }
    
    /**
        * Get the name of the object's database table.
        * @return string table name
        */
    public function get_table_name(){
        return $this->table_name;
    }
    
    /**
        * Get the primary key 
        * @return string/int primary key
    */
    public function get_primary_key(){
        return $this->primary_key;
    }
    
    /**
        * Check if the object is loaded or not
        * @return boolean true if object has already been loaded
        */
    public function is_loaded(){
        return $this->loaded;
    }
    
    /* The functions below are implentations of Iterator methods */
    
    /* Move the pointer to the first element */
    public function rewind()
    {
        reset($this->properties);
    }
    
    /* Retreive the element from the position the pointer is at */
    public function current()
    {
        $var = current($this->properties);
        return $var;
    }
    
    /* Retreive the key from the position the pointer is at */
    public function key() 
    {
        $var = key($this->properties);
        return $var;
    }
    
    /* Move the pointer to the next element */
    public function next() 
    {
        $var = next($this->properties);
        return $var;
    }
    
    /* Test a value from validity before assigning */
    public function valid()
    {
        $key = key($this->properties);
        $var = ($key !== NULL);
        return $var;
    }
    
    /**
        * Used to initiate the object with constructor
        * arguments in various forms
        *  @throws TableObjectNoPropertiesSetException
        *  @throws TableObjectInvalidConstructorArgumentsException
        */
    private function init(){
    
        if(!$this->properties){
            throw new TableObjectNoPropertiesSetException('Properties must first be set using \'$this->set_properties()\' function in '.get_class($this));
        }
        
        if($this->args){
            
            #Enter primary key as single argument which also loads
            if(count($this->args) == 1){
                if( gettype($this->args[0]) == gettype($this->primary_key) ){
                
                    $this->load($this->args[0]);
                }
            #The user can either specify n parameters
            }else if(count($this->args) == count($this->properties)){
                $i = 0;
                foreach($this->properties as $k => $v){
                    if(array_key_exists($i,$this->args)){
                        $this->properties[$k] = $this->args[$i++];
                    }else{
                        throw new TableObjectInvalidConstructorArgumentsException('Invalid array arguments for '.get_class($this));
                    }
                }
                
            #Or a single key => value array with n elements
            }else if(is_array($this->args[0]) ){#and count($this->args[0]) == count($this->properties)){
                foreach($this->args[0] as $k => $v){
                    if(array_key_exists($k,$this->properties)){
                        $this->properties[$k] = $v;
                    }else{
                        throw new TableObjectInvalidConstructorArgumentsException('Invalid array arguments for '.get_class($this));
                    }
                }
                
            }else{
                throw new TableObjectInvalidConstructorArgumentsException('Invalid constructor arguments for '.get_class($this));
            }
        }
    }
    
    /**
        * Set regex filters using an associative array
        * @param array key => value pair of property => filter
        */
    protected function set_filters($filter_array){
        if(!empty($filter_array) && is_array($filter_array)){
            foreach($filter_array as $k => $v){
                $this->filters[$k] = $v;
            }	
        }else{
            throw new TableObjectInvalidFiltersSetException('The RegEx filters you set were invalid');
        }
        
    }
    
    /**
        * Set the properties of the class.
        * This is done using an associative array where key 
        * is the property name and value is true for required
        * fields. This is required to use the class.
        * @example set_properties(['name' => true, 'middle_name' => false])
        * @param array associative array of key => property value => required
        * @throws TableObjectInvalidPropertiesSetException
        */
    protected function set_properties($properties_array){
        
        if(!empty($properties_array) && is_array($properties_array)){
        
            foreach($properties_array as $k => $v){
            
                $this->properties[$k] = false;
                if($v) $this->required_fields[] = $k;
            }
        }elseif(is_bool($properties_array) && $properties_array){
            $this->generic = true;
        }else{
        
            throw new TableObjectInvalidPropertiesSetException('The properties you set were invalid');
        }
        
        $this->init();
        
        unset($this->args);
    }
    
    /**
        * Get the data held in the properties property
        * @example $data = $obj->get_data()
        *  @return array property data
        */
        public function get_data(){
        return $this->properties;
        }
        
    /**
        * Set many properties at once. Incorrect properties will be discarded
        * @param array row_data
        * @example set_data(['name' => 'joe', 'surname' => 'soap']);
        */
    public function set_data($row_data){
        if(!empty($row_data) && is_array($row_data)){
            foreach($row_data as $k => $v){
                if(array_key_exists($k,$this->properties)) $this->properties[$k] = $row_data[$k];
            }
        }else{
            throw new TableObjectInvalidRowDataException('Row data must be in column => value associative array');
        }
    }
    
    /**
        * Used to set a list of required fields to check
        * @param array required fields
        * @throws TableObjectNoPropertiesSetException
        */
    private function set_required_fields($required_fields_array){
        if(empty($this->properties)){
            throw new TableObjectNoPropertiesSetException('Properties must first be set using \'set_properties\' function in '.get_class($this));
        }
        $this->required_fields = $required_fields_array;
    }
    
    /**
        * Loads a row out of the database
        * and assigns its columns as properties
        * of the object
        * @param int primary key id
        * @return array row data
        * @throws TableObjectRowNotFoundException
        */
    public function load($id){
        $this->properties[$this->primary_key] = $id;
        if(is_int($id))
            $row = \DB::queryFirstRow("SELECT * FROM {$this->table_name} WHERE `{$this->primary_key}`= {$id}");
        else
            $row = \DB::queryFirstRow("SELECT * FROM {$this->table_name} WHERE `{$this->primary_key}`= '{$id}'");
        
        if(empty($row)){
            throw new TableObjectRowNotFoundException("{$this->primary_key} =>'{$this->properties[$this->primary_key]}' does not exist in {$this->table_name}");
        }
        
        #Assign properties to row data
        foreach($this->properties as $k => $v){
            $this->properties[$k] = $row[$k];
        }
        $this->loaded = true;
        
        #Return the properties for instant use
        return $this->properties;
    }
    
    /**
        * Loads a row out of the database
        * and assigns its columns as properties
        * of the object
        * @param string column
        * @param int/string value
        * @return array row data
        * @throws TableObjectRowNotFoundException
        */
    public function load_unique($column, $value){
        
        $sql = "SELECT * FROM {$this->table_name} WHERE `{$column}`= '{$value}'";
        
        if( is_int($value) ) $sql = "SELECT * FROM {$this->table_name} WHERE `{$column}`= {$value}";
        
        $row = \DB::queryFirstRow($sql);
        
        if(empty($row)){
            throw new TableObjectRowNotFoundException("{$column} =>'{$value}' does not exist in {$this->table_name}");
        }
        
        #Assign properties to row data
        foreach($this->properties as $k => $v){
            $this->properties[$k] = $row[$k];
        }
        $this->loaded = true;
        
        #Return the properties for instant use
        return $this->properties;
    }
    
    /**
        * Saves the properties of the object
        * into it's database table
        * @return 	true on update, new entry id on create,
        *			false on failure.
        * @throws TableObjectDuplicateEntryException
        * @throws TableObjectRequiredFieldMissingException
        * @throws TableObjectRegexNotMatchException
        */
    public function save(){
        if(!$this->check_required_fields()){
            throw new TableObjectRequiredFieldMissingException(implode(' ',$this->errors));
        }
        if(!$this->check_filters()){
            throw new TableObjectRegexNotMatchException(implode(' ',$this->errors));
        }
        #Build the save map
        $save_map = [];
    
        foreach($this->properties as $k => $v){
            $save_map[$k] = $v;
        }

        #Update if loaded, insert if new
        try{
            if($this->loaded){
                
                \DB::update($this->table_name,$save_map,'id=%i',$this->id);
                
                return \DB::affectedRows() ? true : false;
            }else{
                #INSERT
                unset($save_map['id']);
                \DB::insert($this->table_name,$save_map);
                
                $this->loaded = true;
                $this->properties['id'] = \DB::insertId();
                
                return $this->properties['id'];
            }
        }catch(\MeekroDBException $e){
            array_push($this->errors, $e->getMessage().'\n'.$e->getQuery());
            if(strpos($e->getMessage(),'Duplicate entry') !== false){
                throw new TableObjectDuplicateEntryException($e->getMessage().'<br>'.$e->getQuery());
            }
        }
        return false;
    }
    
    /**
        * Deletes a row from the database
        * @return true on success, false on failure
        * @throws TableObjectForeignKeyConstraintException
        */
    public function delete(){
        try{
            $result = \DB::delete($this->table_name,"{$this->primary_key}=%i",$this->properties[$this->primary_key]);
        }catch(MeekroDbException $e){
            if(strpos($e->getMessage(),'foreign key') !== false){
                throw new TableObjectForeignKeyConstraintException($e->getMessage().'<br>'.$e->getQuery());
                
            }
        }
        $this->loaded = false;
        return \DB::affectedRows() ? true : false;

    }
    
    /**
        * Check that all properties pass their regex filters.
        * Errors are available in $this->errors
        * @return boolean true if all filters passed
        * @throws TableObjectRegexFilterException
        */
    private function check_filters(){
        $failed_filters = false;
        if(!$this->filters) return true;
        foreach($this->filters as $k => $v){
            if(! preg_match('$'.$v.'$',$this->properties[$k])){
                $failed_filters = true;
                array_push($this->errors, "{$this->table_name} ({$this->properties[$k]}) does not match regex {$v}");
            }
        }
        
        if ($failed_filters) {
            throw new TableObjectRegexNotMatchException(implode($this->errors));
        }
        return !$failed_filters;
    }
    
    /**
        * Check that all required fields have values
        * @return boolean true if all required fields are present
        * @throws TableObjectRequiredFieldMissingException
        */
    private function check_required_fields(){
        $missing_fields = false;
        foreach($this->required_fields as $field){
            if(!$this->properties[$field]){
                $missing_fields = true;
                array_push($this->errors, "{$this->table_name} requires {$field}");
            }
        }
        
        if ($missing_fields) {
            throw new TableObjectRequiredFieldMissingException(implode($this->errors));
        }
        return !$missing_fields;
    }
    

}

/**
    * Base TableObjectException. The other TableObjectExceptions
    * inherit from this
    */
class TableObjectException extends \Exception{
    public function __construct($message,$code,\Exception $prev = null){
        parent::__construct($message,$code,$prev);
    }
    
    public function __toString(){
        return __CLASS__.":[{$this->code}]:  {$this->message}";
    }
}
/**
    * This Exception is thrown when invalid constructor
    * arguments are provided.
    */
class TableObjectInvalidConstructorArgumentsException extends TableObjectException{
    public function __construct($message, $code = 0){
        parent::__construct($message,$code);
    }
}

/**
    * When Meekro finds a unique column already contains
    * a certain value this exception should be raised
    */
class TableObjectDuplicateEntryException extends TableObjectException{
    public function __construct($message, $code = 1){
        parent::__construct($message,$code);
    }
}

/**
    * When Meekro cannot complete an action due to
    * foreign key constraint this exception should
    * be raised
    */
class TableObjectForeignKeyConstraintException extends TableObjectException{
    public function __construct($message, $code = 2){
        parent::__construct($message,$code);
    }
}

/**
    * When Meekro cannot find a row in a table
    * this exception is raised.
    */
class TableObjectRowNotFoundException extends TableObjectException{
    public function __construct($message, $code = 3){
        parent::__construct($message,$code);
    }
}

/**
    * When a property that does not exist is accessed this exception
    * is raised
    */
class TableObjectNoSuchPropertyException extends TableObjectException{
    public function __construct($message, $code = 4){
        parent::__construct($message,$code);
    }
}
/**
    * When a function is called that requires the properties to
    * be set already, this exception israised.
    */
class TableObjectNoPropertiesSetException extends TableObjectException{
    public function __construct($message, $code = 5){
        parent::__construct($message,$code);
    }
}

/**
    * When all required feels are needed for a function then
    * this exception is thrown.
    */
class TableObjectRequiredFieldMissingException extends TableObjectException{
    public function __construct($message, $code = 6){
        parent::__construct($message,$code);
    }
}

/**
    * When a RegEx filter does not match this exception is thrown
    */
class TableObjectRegexNotMatchException extends TableObjectException{
    public function __construct($message, $code = 7){
        parent::__construct($message,$code);
    }
}

/**
    * When invalid row data is detected
    */
class TableObjectInvalidRowDataException extends TableObjectException{
    public function __construct($message, $code = 8){
        parent::__construct($message,$code);
    }
}

/**
    * When child attempts to set invalid propertes
    */
class TableObjectInvalidPropertiesSetException extends TableObjectException{
    public function __construct($message, $code = 9){
        parent::__construct($message,$code);
    }
}

/**
    * When child attempts to set invalid filters
    */
class TableObjectInvalidFiltersSetException extends TableObjectException{
    public function __construct($message, $code = 10){
        parent::__construct($message,$code);
    }
}
