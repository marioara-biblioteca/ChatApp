<?php

class Wbb_CouchDB
{
  public $server_api_url;
  private $_my_db_username;
  private $_my_db_password;
  private $_curl_initialization;

  public function __construct($server_api_url = '', $my_db_username = FALSE, $my_db_password = FALSE)
  {

    $this->server_api_url = $server_api_url;
    $this->_my_db_username = $my_db_username;
    $this->_my_db_password = $my_db_password;
  }


  public function InitConnection()
  {

    $this->_curl_initialization = curl_init();
  }

  public function CloseConnection()
  {
    curl_close($this->_curl_initialization);
  }


  public function isRunning()
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url);
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, 'admin:password');
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    $response = curl_exec($this->_curl_initialization);

    return $response;
  }
  public function getAllDbs()
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . '/_all_dbs');
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    $response = curl_exec($this->_curl_initialization);

    return $response;
  }

  public function createDb($db_name)
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . '/' . $db_name);
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    $response = curl_exec($this->_curl_initialization);

    return $response;
  }

  public function GetUUID()
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . '/_uuids');
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    $response  = curl_exec($this->_curl_initialization);
    $_response = json_decode($response, TRUE);

    $UUID = $_response['uuids'];

    return $UUID;
  }
  public function createDocument($document_data = array(), $database, $username)
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . '/' . $database . '/' . $username);
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'PUT'); /* or PUT */
    curl_setopt($this->_curl_initialization, CURLOPT_POSTFIELDS, json_encode($document_data));
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    $response = curl_exec($this->_curl_initialization);

    return $response;
  }

  public function createAttachmentDocument($database, $documentID, $attachment)
  {

    $finfo       = finfo_open(FILEINFO_MIME_TYPE);
    $contentType = finfo_file($finfo, $attachment);
    $payload     = file_get_contents($attachment);

    curl_setopt($this->_curl_initialization, CURLOPT_URL, sprintf($this->server_api_url . '/' . $database . '/' . $documentID . '/' . $attachment));
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'PUT');
    //curl_setopt( $this->_curl_initialization, CURLOPT_POST, true);
    curl_setopt($this->_curl_initialization, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: ' . $contentType,
        'Accept: */*',
      )
    );

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    $response = curl_exec($this->_curl_initialization);

    return $response;
  }

  public function getDocument($database, $documentID)
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . '/' . $database . '/' . $documentID);
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    $response = curl_exec($this->_curl_initialization);
    $result = array();
    $result = json_decode($response, true);
    return $result;
  }
  public function getDocuments($database)
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . '/' . $database . '/_all_docs');
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    $response = curl_exec($this->_curl_initialization);
    $result = array();
    $result = json_decode($response, true);
    return $result;
  }


  public function updateDocument($document_data = array(), $database, $document, $rev)
  {
    if ($rev) {

      $document_data['_rev'] = $rev;
    }
    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . '/' . $database . '/' . $document);
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'PUT'); /* or PUT */
    curl_setopt($this->_curl_initialization, CURLOPT_POSTFIELDS, json_encode($document_data));
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    $response = curl_exec($this->_curl_initialization);

    return $response;
  }

  public function deleteDocument($database, $documentID, $rev)
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . '/' . $database . '/' . $documentID . '?rev=' . $rev);
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    $response = curl_exec($this->_curl_initialization);

    return $response;
  }
  public function deleteDb($database)
  {

    curl_setopt($this->_curl_initialization, CURLOPT_URL, $this->server_api_url . $database);
    curl_setopt($this->_curl_initialization, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($this->_curl_initialization, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt(
      $this->_curl_initialization,
      CURLOPT_HTTPHEADER,
      array(
        'Content-type: application/json',
        'Accept: */*',
      )
    );

    if ($this->_my_db_password && $this->_my_db_username) {
      curl_setopt($this->_curl_initialization, CURLOPT_USERPWD, $this->_my_db_username . ':' . $this->_my_db_password);
    }

    $response = curl_exec($this->_curl_initialization);

    return $response;
  }
}
//https://gist.github.com/mingsai/fffad39664f42f2d5061775c420d46de
//https://stackoverflow.com/questions/6516902/how-to-get-response-using-curl-in-php
$conn = new Wbb_CouchDB('http://admin:password@10.0.0.4:5984', 'admin', 'password');

$conn->InitConnection();

//$message_id = count($conn->getDocuments('chatapp')) ;
//$var_dump($message_id);
/*
//$response = $tryal->createDb('trial');
//$response = $tryal->deleteDb('trial');
$response = $tryal->getAllDbs();

//$user = array("username" => "patri", "email" => "patri@mta.ro", "password" => "1234");
//$response = $tryal->createDocument($user, 'users', 'patri');
$response = $tryal->getDocument('users', 'mari');
$resArr = array();
$resArr = json_decode($response, true);
var_dump($resArr);
//$response = $tryal->deleteDocument('users', 'patri', $resArr['_rev']);
//$response = $tryal->getDocuments('users');


$tryal->CloseConnection();
*/
