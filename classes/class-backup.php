<?php

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class Backup {
	/**
	 * treitusQuote version.
	 *
	 * Increases whenever a new plugin version is released.
	 *
	 * @since 1.0.0
	 * @const string
	 */
	const version = '1.0.0';

	/**
	 * treitusQuote internal plugin version ("options scheme" version).
	 *
	 * Increases whenever the scheme for the plugin options changes, or on a plugin update.
	 *
	 * @since 1.0.0
	 * @const int
	 */
	const db_version = 32;

	/**
	 * treitusQuote "table scheme" (data format structure) version.
	 *
	 * Increases whenever the scheme for a $table changes,
	 * used to be able to update plugin options and table scheme independently.
	 *
	 * @since 1.0.0
	 * @const int
	 */
	const table_scheme_version = 3;


	/**
	 * Instance of the controller.
	 *
	 * @since 1.0.0
	 * @var treitusQuote_*_Controller
	 */
	public static $controller;

	/**
	 * Actions that have a view and admin menu or nav tab menu entry.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $view_actions = array();

  private $dbxClient;
   private $projectFolder;

   public function __construct($token,$project,$projectFolder){
           $this->dbxClient = new Dropbox\Client($token, $project);
           $this->projectFolder = $projectFolder;
       }



 public function upload($dirtocopy){

      if(!file_exists($dirtocopy)){

          exit("File $dirtocopy does not exist");

      } else {

          //if dealing with a file upload it
          if(is_file($dirtocopy)){
              return $this->uploadFile($dirtocopy);

          } else { //otherwise collect all files and folders

              $iter = new \RecursiveIteratorIterator(
                  new \RecursiveDirectoryIterator($dirtocopy, \RecursiveDirectoryIterator::SKIP_DOTS),
                  \RecursiveIteratorIterator::SELF_FIRST,
                  \RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
              );

              //loop through all entries
              foreach($iter as $file) {

                  $words = explode('/',$file);
                  $stop = end($words);

                  //if file is not in the ignore list pass to uploadFile method
                  if(!in_array($stop, $this->ignoreList())){
                     return $this->uploadFile($file);
                  }

              }
          }
      }
  }

  /**
   * uploadFile upload file to dropbox using the Dropbox API
   * @param  string $file path to file
   */
  public function uploadFile($file){
      $f = fopen($file, "rb");
     $result= $this->dbxClient->uploadFile("/".$this->projectFolder."/$file", Dropbox\WriteMode::add(), $f);
      fclose($f);
      return $result;
  }

  /**
   * ignoreList array of filenames or directories to ignore
   * @return array
   */
  public function ignoreList(){
      return array(
          '.DS_Store',
          'cgi-bin'
      );
  }

}
