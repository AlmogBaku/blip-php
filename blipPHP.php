<?php
/**
 *  * Blip-PHP Class
 * 			@version 0.4c
 *
 *
 *
 * @copyright Almog Baku.
 * 	@author Almog Baku - almog.baku@gmail.com
 * 	@license GPLv3
 */

###	SETTINGS: MUST ###
set_time_limit(0);				      /** Set timeout 								**/
include_once("http.class.php");	/** Include http.class | @license on the file 	**/


### Class ####

class blipPHP {
  const 	gateway = "http://uploads.blip.tv/";
  public 	$username = "";
  public 	$password = "";


  /**
   * blipPHP
   *
   * @param string[required] $username
   * @param string[required] $password
   * @throws Exception if $username is blank or null
   */
  function __construct($username="", $password="") {
    if(($username==null) or (empty($username)) or ($password==null) or (empty($password)))
      throw new Exception("AUTHENTICATION_REQUIRED: Bad login information.");
     
    $this->username = $username;
    $this->password	= $password;
  }

  /**
   * Upload file
   *
   * @param url[required] 	    $file
   * @param string[required] 	$title
   * @param string[optional] 	$description
   * @param bool[optional] 	    $hidden (false = public / true = private)
   * @return Response stdClass if succes, or FALSE if there error.
   */
  public function upload($file=null, $title=null, $description="", $hidden=false) {
    if(($title==null) or (empty($title)))
      throw new Exception("MISSING_PARAMETER: No title given.");

    if(($file==null) or (empty($file)))
      throw new Exception("MISSING_PARAMETER: No file given.");

    if(!file_exists($file))
      throw new Exception("MISSING_PARAMETER: File dosen`t exists.");


    //Blip.tv fields
    $data = array(
        'cmd'			=> "post",
        's'		        => "file",
        'item_type'		=> "file",
        'post'			=> "1",
        'skin'			=> "api",
        'userlogin'		=> $this->username,
        'password'		=> $this->password,
        'hidden'        => ($hidden == false)?"0":"1",
        'title'			=> $title,
        'description'	=> $description
    );


    //Setting http class settings
    $http=new http_class;
    $http->timeout		  = 0;
    $http->data_timeout	= 0;
    $arguments			    = array();
    $response			      = "";

    $http->GetRequestArguments(self::gateway . '?' . http_build_query($data), $arguments);

    $arguments["RequestMethod"]	= "POST";
    $arguments["PostValues"]	= $data;
    $arguments["User-Agent"]	= "blipPHP (http://code.google.com/p/blip-php/)";
    $arguments["PostFiles"]		= array(
        "file" => array(
            "Data"			=> file_get_contents($file),
            "Name"			=> basename($file),
            "Content-Type"	=> "Application/Octet-stream"
        )
    );

    //Make the request
    $http->Open($arguments);
    $http->SendRequest($arguments);
    $http->ReadWholeReplyBody($response);

    $xml_response = simplexml_load_string($response);

    if(strtoupper($xml_response->status) == "ERROR")
      if(strtoupper($xml_response->error->code) == "AUTHENTICATION_REQUIRED")
      throw new Exception("AUTHENTICATION_REQUIRED: Bad login information.");


    return $xml_response;
  }

  /**
   * Modify file
   *
   * @param int[required] 	    $id
   * @param string[required] 	$title
   * @param string[optional] 	$description
   * @param bool[optional] 	    $privacy (public / private)
   * @return Response stdClass if succes, or FALSE if there error.
   */
  public function modify($id=null, $title=null, $description="", $privacy='public') {
    if(($title==null) or (empty($title)))
      throw new Exception("MISSING_PARAMETER: No title given.");

    if(($id==null) or (empty($id)))
      throw new Exception("MISSING_PARAMETER: No id given.");

    //Blip.tv fields
    $data = array(
        'cmd'			=> "edit",
        's'	    => "file",
        'post' => "1",
        'skin' => 'api',
        'userlogin'		=> $this->username,
        'password'		=> $this->password,
        'hidden'        => ($privacy == 'public')?"0":"1",
        'id'	        => $id,
        'title'	        => $title,
    );
    if(!empty($description)) $data['description'] = $description;


    //Setting http class settings
    $http=new http_class;
    $http->timeout		    = 0;
    $http->data_timeout	  = 0;
    $arguments			      = array();
    $response			        = "";

    $http->GetRequestArguments(self::gateway . '?' . http_build_query($data),$arguments);

    $arguments["RequestMethod"]	= "POST";
    $arguments["PostValues"]	  = $data;
    $arguments["User-Agent"]	  = "blipPHP (http://code.google.com/p/blip-php/)";

    //Make the request
    $http->Open($arguments);
    $http->SendRequest($arguments);
    $http->ReadWholeReplyBody($response);

    $xml_response = simplexml_load_string($response);

    if(strtoupper($xml_response->status) == "ERROR")
      if(strtoupper($xml_response->error->code) == "AUTHENTICATION_REQUIRED")
      throw new Exception("AUTHENTICATION_REQUIRED: Bad login information.");


    return $xml_response;
  }

  /**
   * Set file public or private
   *
   * @param int[required] 	    $id
   * @param bool[optional] 	    $privacy (public / private)
   * @return Response stdClass if succes, or FALSE if there error.
   */
  public function setPrivacy($id=null, $privacy='public') {
    if(($id==null) or (empty($id)))
      throw new Exception("MISSING_PARAMETER: No id given.");
    
    if ($privacy != 'public' && $privacy != 'private') {
        throw new Exception("Privacy must be one of: public or private");
    }
    //Blip.tv fields
    $data = array(
        'cmd'			=> "edit",
        's'	    => "file",
        'post' => "1",
        'skin' => 'api',
        'userlogin'		=> $this->username,
        'password'		=> $this->password,
        'hidden'        => ($privacy == 'public')?"0":"1",
        'id'	        => $id,
    );

    //Setting http class settings
    $http=new http_class;
    $http->timeout		    = 0;
    $http->data_timeout	  = 0;
    $arguments			      = array();
    $response			        = "";

    $http->GetRequestArguments(self::gateway . '?' . http_build_query($data),$arguments);

    $arguments["RequestMethod"]	= "POST";
    $arguments["PostValues"]	  = $data;
    $arguments["User-Agent"]	  = "blipPHP (http://code.google.com/p/blip-php/)";

    //Make the request
    $http->Open($arguments);
    $http->SendRequest($arguments);
    $http->ReadWholeReplyBody($response);

    $xml_response = simplexml_load_string($response);

    if(strtoupper($xml_response->status) == "ERROR")
      if(strtoupper($xml_response->error->code) == "AUTHENTICATION_REQUIRED")
      throw new Exception("AUTHENTICATION_REQUIRED: Bad login information.");


    return $xml_response;
  }
  /**
   * Replace file
   *
   * @param int[required] 	    $id
   * @param url[required] 	    $file
   * @return Response stdClass if succes, or FALSE if there error.
   */
  public function replaceFile($id=null, $file=null) {
    if(($id==null) or (empty($id)))
      throw new Exception("MISSING_PARAMETER: No id given.");

    if(($file==null) or (empty($file)))
      throw new Exception("MISSING_PARAMETER: No file given.");

    if(!file_exists($file))
      throw new Exception("MISSING_PARAMETER: File dosen`t exists.");


    //Blip.tv fields
    $data = array(
        'cmd'			=> "edit",
        's'		        => "file",
        'item_type'		=> "file",
        'post'			=> "1",
        'skin'			=> "api",
        'userlogin'		=> $this->username,
        'password'		=> $this->password,
        'id'            => $id
    );


    //Setting http class settings
    $http=new http_class;
    $http->timeout		  = 0;
    $http->data_timeout	= 0;
    $arguments			    = array();
    $response			      = "";

    $http->GetRequestArguments(self::gateway . '?' . http_build_query($data), $arguments);

    $arguments["RequestMethod"]	= "POST";
    $arguments["PostValues"]	= $data;
    $arguments["User-Agent"]	= "blipPHP (http://code.google.com/p/blip-php/)";
    $arguments["PostFiles"]		= array(
        "file" => array(
            "Data"			=> file_get_contents($file),
            "Name"			=> basename($file),
            "Content-Type"	=> "Application/Octet-stream"
        )
    );

    //Make the request
    $http->Open($arguments);
    $http->SendRequest($arguments);
    $http->ReadWholeReplyBody($response);

    $xml_response = simplexml_load_string($response);

    if(strtoupper($xml_response->status) == "ERROR")
      if(strtoupper($xml_response->error->code) == "AUTHENTICATION_REQUIRED")
      throw new Exception("AUTHENTICATION_REQUIRED: Bad login information.");


    return $xml_response;
  }

  /**
   * Delete file
   *
   * @param id[required] 	$id
   * @param reason[required] 	$reason
   * @return Response stdClass if succes, or FALSE if there error.
   */
  public function delete($id=null, $reason="") {
    if(($id==null) or (empty($id)))
      throw new Exception("MISSING_PARAMETER: No id given.");

    if(($reason==null) or (empty($reason)))
      throw new Exception("MISSING_PARAMETER: No reason given.");

    //Blip.tv fields
    $data = array(
			'cmd'			=> "delete",
            's'             => "file",
			'skin'			=> "api",
			'userlogin'		=> $this->username,
			'password'		=> $this->password,
			'id'		    => $id,
			'reason'		=> $reason
    );


    //Setting http class settings
    $http = new http_class;
    $http->timeout = 0;
    $http->data_timeout	= 0;
    $arguments = array();
    $response = "";

    $http->GetRequestArguments(self::gateway . '?' . http_build_query($data),$arguments);

    $arguments["RequestMethod"]	= "POST";
    $arguments["User-Agent"]	  = "blipPHP (http://code.google.com/p/blip-php/)";
    $arguments["PostValues"]	  = $data;

    //Make the request
    $http->Open($arguments);
    $http->SendRequest($arguments);
    $http->ReadWholeReplyBody($response);

    $xml_response = simplexml_load_string($response);

    if(strtoupper($xml_response->status) == "ERROR")
      if(strtoupper($xml_response->error->code) == "AUTHENTICATION_REQUIRED")
      throw new Exception("AUTHENTICATION_REQUIRED: Bad login information.");


    return $xml_response;
  }

  /**
   * Information about item
   *
   * @param int[required] $id	- item id
   * @return Response stdClass if succes, or FALSE if there error.
   */
  public function info($id=null) {
    if(($id==null) or (empty($id)))
      throw new Exception("MISSING_PARAMETER: No id given.");

    return simplexml_load_file("http://www.blip.tv/file/" . $id . "?skin=api");
  }


  ## Aliases - making the using the class more intuitive.
  /**
   * Alias for `upload` method
   */
  public function add($file=null, $title=null, $description="", $hidden=false) { return $this->upload($file, $title, $description, $hidden); }
  /**
   * Alias for `upload` method
   */
  public function insert($file=null, $title=null, $description="", $hidden=false) { return $this->upload($file, $title, $description, $hidden); }

  /**
   * Alias for `delete` method
   */
  public function remove($id=null, $reason="") { return $this->delete($id, $reason); }


  /**
   * Alias for `modify` method
   */
  public function update($id=null, $title=null, $description="", $hidden=false) { return $this->modify($id, $title, $description, $hidden);}
  /**
   * Alias for `modify` method
   */
  public function edit($id=null, $title=null, $description="", $hidden=false) { return $this->modify($id, $title, $description, $hidden); }

  /**
   * Alias for `info` method
   */
  public function information($id) { return $this->info($id); }
  /**
   * Alias for `info` method
   */
  public function item($id) { return $this->info($id); }
}
?>
