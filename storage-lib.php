<?php


	/**
	 * Author 		: Pratik Bhanushali
	 * Site 		: https://www.pratikbhanushali.in
	 * Github 		: https://github.com/pratikbhanushali/
	 * 
	 * Written @ 	: GetSetHome
	 * URL 			: https://www.getsethome.com
	 *
	 * Created On 	: 26th Feb, 2016
	 * Version 		: 1.0
	 */

	/*================================================
	
				What is a STORAGE-LIB ?

	-   Provides an abstraction to the developer. He/She
		need not bother about the underlying Data-system.

	- 	Written to work well with MySQL and MongoDB.

	- 	Functions Supported - CRUD.

	================================================*/


	require_once(__DIR__ .'/variables.php');
	require_once(__DIR__ .'/db.php');
	require_once(__DIR__ .'/mongo-lib.php');

	class clsStorage {

		protected $storageName;
		protected $tableName;
		
		function __construct($storage_name) {
			
			$this->storageName 	= $storage_name;
			$this->tableName 	= "";
		}



		function exists($tbl_name, $arr) {
			try {

				switch ($this->storageName) {
					case 'MySQL' 		: 	return $this->isSetMySQL($tbl_name, $arr);
											break;

					case 'Mongo' 		:	return $this->isSetMongo($tbl_name, $arr);
											break;
					
					default 			:	return false;
											break;
				}

			} catch(Exception $e) {
				
			}
		}

		function getRecord($tbl_name, $arr) {
			try {

				switch ($this->storageName) {
					case 'MySQL' 		: 	return $this->getRecordMySQL($tbl_name, $arr);
											break;

					case 'Mongo' 		:	return $this->getRecordMongo($tbl_name, $arr);
											break;
					
					default 			:	return false;
											break;
				}

			} catch(Exception $e) {

			}
		}


		function getMultipleRecords($tbl_name, $arr) {
			try {

				switch ($this->storageName) {
					case 'MySQL' 		: 	return $this->getRecordMySQL($tbl_name, $arr);
											break;

					case 'Mongo' 		:	return $this->getMultipleRecordMongo($tbl_name, $arr);
											break;
					
					default 			:	return false;
											break;
				}

			} catch(Exception $e) {

			}
		}


		function getAll($tbl_name) {
			try {

				switch ($this->storageName) {
					case 'MySQL' 		: 	return $this->getAllRecordMySQL($tbl_name);
											break;

					case 'Mongo' 		:	return $this->getAllRecordMongo($tbl_name);
											break;
					
					default 			:	return false;
											break;
				}

			} catch(Exception $e) {
				
			}
		}


		function set($tbl_name, $arr) {
			try {

				switch ($this->storageName) {
					case 'MySQL' 		: 	return $this->setToMySQL($tbl_name, $arr);
											break;

					case 'Mongo' 		:	return $this->setToMongo($tbl_name, $arr);
											break;
					
					default 			:	return false;
											break;
				}


			} catch(Exception $e) {
				return false;
			}
		}


		function update($tbl_name, $where, $arr) {
			try {

				switch ($this->storageName) {
					case 'MySQL' 		: 	return $this->updateMySQL($tbl_name, $where, $arr);
											break;

					case 'Mongo' 		:	return $this->updateMongo($tbl_name, $where, $arr);
											break;
					
					default 			:	return false;
											break;
				}

			} catch(Exception $e) {
				
			}
		}


		function deleteRecord($tbl_name, $arr) {
			try {

				switch ($this->storageName) {
					case 'MySQL' 		: 	return $this->deleteFromMySQL($tbl_name, $arr);
											break;

					case 'Mongo' 		:	return $this->deleteFromMongo($tbl_name, $arr);
											break;
					
					default 			:	return false;
											break;
				}

			} catch(Exception $e) {

			}
		}


		function searchRecord($tbl_name, $where, $what) {
            try {

                switch ($this->storageName) {
                    case 'MySQL'         :     return $this->searchRecordMySQL($tbl_name, $where, $what);
                                            break;

                    case 'Mongo'         :    return $this->searchRecordMongo($tbl_name, $where, $what);
                                            break;
                    
                    default             :    return false;
                                            break;
                }

            } catch(Exception $e) {

            }
        }


		/*===============================================
		=            PRIVATE CLASS FUNCTIONS            =
		===============================================*/
		

		///////////////////////////
		//     SET FUNCTIONS
		///////////////////////////
		
		private function setToMongo($collection_name, $arrParams) {
			try {

				global $mongo;
				$mongo->setCollection($collection_name);

				$bln = $mongo->insertDocument($arrParams);

				return $bln;

			} catch(Exception $e) {
				return false;
			}
		}
		

		private function setToMySQL($table_name, $arrParams) {
			try {

				global $con;

				$statement_1 = "INSERT INTO `$table_name` ";
			
				$statement_2 = "";						
				foreach ($arrParams as $key => $value) {
					$statement_2 .= "`$key`,"; 
				}

				// Filter $statement_2
				$statement_2 = substr($statement_2, 0, strlen($statement_2)-1);
				$statement_2 = "(" . $statement_2 . ")";
				$statement_2 .= " VALUES ";


				$statement_3 = "";
				foreach ($arrParams as $key => $value) {
					if($value === NULL) {
						$statement_3 .= "NULL" . ", "; 

					} else {
						$statement_3 .= "'" . $value . "', "; 
					}
				}

				// Filter $statement_3
				$statement_3 = substr($statement_3, 0, strlen($statement_3)-2);
				$statement_3 = "(" . $statement_3 . ")";

				$sql = $statement_1 . "" . $statement_2 . "" . $statement_3;

				// echo $sql;
				// exit;

				if ($con->query($sql) === TRUE) {
					return true;

				} else {
					return false;
				}

			} catch(Exception $e) {
				return false;
			}		
		}


		///////////////////////////
		//    DELETE FUNCTIONS
		///////////////////////////
		
		private function deleteFromMySQL($tbl_name, $arrRecord) {
			try {

				global $con;

				$sql = "DELETE FROM `$tbl_name` WHERE ";

				foreach($arrRecord as $key => $value) {
					$sql .= "`$key` = '$value' AND ";
				}
				
				$sql = substr($sql, 0, strlen($sql)-4);

				// echo $sql;
				// exit;

				if ($con->query($sql) === TRUE) 
					return true;
				else 
					return false;				


			} catch(Exception $e) {

			}
		}

		private function deleteFromMongo($coll_name, $arrRecord) {
			try {

				global $mongo;

				$mongo->setCollection($coll_name);
				return $mongo->deleteDocument($arrRecord);

			} catch(Exception $e) {
				return false;
			}
		}


		///////////////////////////
		//    ISSET FUNCTIONS
		///////////////////////////

		private function isSetMySQL($tbl_name, $arrRecord) {
			try {


			} catch(Exception $e) {

			}
		}

		private function isSetMongo($coll_name, $arrRecord) {
			try {

				global $mongo;
				$mongo->setCollection($coll_name);

				$arr = $mongo->findDocument($arrRecord);

				if(!empty($arr))
					return true;

				return false;

			} catch(Exception $e) {
				return false;
			}
		}


		///////////////////////////
		//    UPDATE FUNCTIONS
		///////////////////////////

		private function updateMySQL($tbl_name, $where, $arr) {
			try {
				
				global $con;

				$sql = "UPDATE `$tbl_name` SET ";
				
				foreach ($arr as $key => $value) {
					$sql .= "`$key` = '$value', ";
				}
				
				$sql = substr($sql, 0, strlen($sql)-2);
				
				$sql .= " WHERE ";
					
				foreach($where as $key => $value) {
					$sql .= "`$key` = '$value' AND ";
				}
				
				$sql = substr($sql, 0, strlen($sql)-4);
				
				if($con->query($sql) === TRUE)
					return true;
				
				return false;				
				
			} catch(Exception $e) {
				
			}
		}

		private function updateMongo($coll_name, $where, $arr) {
			try {

				global $mongo;
				$mongo->setCollection($coll_name);

				return $mongo->updateDocument($where, $arr);

			} catch(Exception $e) {
				return false;
			}
		}


		///////////////////////////////////
		//    GET Specific Records 
		///////////////////////////////////

		private function getRecordMySQL($tbl_name, $where) {
			try {

				global $con;

				$sql = "SELECT * FROM `$tbl_name` WHERE ";
				foreach($where as $key => $value) {
					$sql .= "`$key` = '$value' AND ";
				}

				$sql = substr($sql, 0, strlen($sql)-4);

				$arr = [];
				if($result = $con->query($sql)) {
					while($row = $result->fetch_assoc()) {
						array_push($arr, $row);
					}
				}

				return $arr;

			} catch(Exception $e) {

			}
		}

		private function getRecordMongo($coll_name, $arr) {
			try {

				global $mongo;
				$mongo->setCollection($coll_name);

				$cursor = [];
				$cursor = $mongo->findDocument($arr);
				return $cursor;

			} catch(Exception $e) {

			}
		}


		private function getMultipleRecordMongo($coll_name, $arr) {
			try {

				global $mongo;
				$mongo->setCollection($coll_name);

				$cursor = [];
				$cursor = $mongo->findMultipleDocuments($arr);
				return $cursor;

			} catch(Exception $e) {

			}
		}


		///////////////////////////
		//    GET All Records 
		///////////////////////////

		private function getAllRecordMySQL($tbl_name) {
			try {

				global $con;

				$sql = "SELECT * 
						FROM `$tbl_name`";

				$arr = [];
				if($result = $con->query($sql)) {
					while($row = $result->fetch_assoc()) {
						array_push($arr, $row);
					}
				}

				return $arr;

			} catch(Exception $e) {

			}
		}

		private function getAllRecordMongo($coll_name) {
			try {

				global $mongo;

				$mongo->setCollection($coll_name);
				$cursor = $mongo->findAll();

				$arr = [];
				foreach ($cursor as $key) {
					array_push($arr, $key);
				}

				return $arr;
			

			} catch(Exception $e) {

			}
		}

		private function searchRecordMySQL($tbl_name, $where, $what) {
            try {

                

            } catch (Exception $e) {

            }
        }

        private function searchRecordMongo($coll_name, $where, $what) {
            try {

                global $mongo;

                $mongo->setCollection($coll_name);
                return $mongo->search($where, $what);

            } catch (Exception $e) {

            }
        }

	}
?>