<?php
	/**
	*  A Wrapper to MongoDB
	*/
	class clsMongo {
		
		function __construct() {
			$this->client 		= new MongoClient();
			$this->db 			= $this->client->GetSetHome;
			$this->collection 	= "";

		}

		function setCollection($coll_name) {
			try {

				$db 				= $this->db;
				$this->collection 	= $db->$coll_name;
				return true;

			} catch(Exception $e) {
				return false;
			}
		}

		function findAll() {
			try {

				$cursor = $this->collection->find();

				$arr = [];
				
				foreach ($cursor as $key) {
					array_push($arr, $key);
				}

				return $arr;

			} catch(Exception $e) {
				return false;
			}
		}

		function findDocument($arr) {
			try {
				
				$cursor 	= [];

				$cursor = $this->collection->findOne($arr);

				return $cursor;

			} catch(Exception $e) {
				return $cursor;
			}
		}


		function findMultipleDocuments($arr) {
			try {
				
				$cursor 	= [];
				$arrReturn 	= [];

				$cursor = $this->collection->find($arr);

				foreach ($cursor as $key) {
					array_push($arrReturn, $key);
				}

				return $arrReturn;

			} catch(Exception $e) {
				return $arrReturn;
			}
		}


		function search($key, $val) {
			try {

				$regex = new MongoRegex("/$val/i");
				$arr = [];

				$arrParams = [
					$key => $regex
				];

				$cursor = $this->collection->find($arrParams);

				foreach ($cursor as $key) {
					array_push($arr, $key);
				}

				return $arr;

			} catch(Exception $e) {
				return $arr;
			}
		}

		function insertDocument($arrDoc) {
			try {

				$this->collection->insert($arrDoc);
				return true;

			} catch(Exception $e) {
				return false;
			}
		}

		function updateDocument($where, $arrDocument) {
			try {

				$updateArray = [];
				$updateArray['$set'] = $arrDocument;

				$this->collection->update($where, $updateArray);
				return true;

			} catch(Exception $e) {
				return false;
			}
		}

		function deleteAll() {
			try {

				$this->collection->remove(array(),array('safe' => true));
				return true;

			} catch(Exception $e) {
				return false;
			}
		}

		function deleteDocument($arr) {
			try {

				$this->collection->remove($arr, array("justOne" => true));
				return true;

			} catch(Exception $e) {
				return false;
			}
		}
	}

	$mongo = new clsMongo();

?>