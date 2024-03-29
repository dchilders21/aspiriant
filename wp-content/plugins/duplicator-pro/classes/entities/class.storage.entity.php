<?php
defined("ABSPATH") or die("");
/**
 * Storage entity layer
 *
 * Standard: Missing
 *
 * @package DUP_PRO
 * @subpackage classes/entities
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 3.0.0
 *
 * @todo Finish Docs
 */
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/lib/DropPHP/DropboxV2Client.php');

require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/class.io.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/entities/class.json.entity.base.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/net/class.ftp.chunker.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/package/class.pack.runner.php');
if (DUP_PRO_U::PHP55()) {
    require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/lib/phpseclib/class.phpseclib.php');
}

if(DUP_PRO_U::PHP56()) {
	require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/net/class.u.onedrive.php');
}

// For those storage types that do not require any configuration ahead of time
abstract class DUP_PRO_Storage_Types
{
    const Local    = 0;
    const Dropbox  = 1;
    const FTP      = 2;
    const GDrive   = 3;
    const S3       = 4;  
    const SFTP     = 5;
    const OneDrive = 6;
}

// For those storage types that do not require any configuration ahead of time
abstract class DUP_PRO_Virtual_Storage_IDs
{
    const Default_Local = -2;

}

// Important: Should be aligned with the states in the storage edit view
abstract class DUP_PRO_Dropbox_Authorization_States
{
    const Unauthorized = 0;
    const Authorized   = 4;

}

abstract class DUP_PRO_OneDrive_Authorization_States
{
    const Unauthorized = 0;
    const Authorized   = 1;

}

abstract class DUP_PRO_GDrive_Authorization_States
{
    const Unauthorized = 0;
    const Authorized   = 1;

}

/**
 * @copyright 2016 Snap Creek LLC
 */
class DUP_PRO_Storage_Entity extends DUP_PRO_JSON_Entity_Base
{
    public $name                         = '';
    public $notes                        = '';
    public $storage_type                 = DUP_PRO_Storage_Types::Local;
    public $editable                     = true;
    // LOCAL FIELDS
    public $local_storage_folder         = '';
    public $local_max_files              = 10;
    public $local_filter_protection      = true;
    // DROPBOX FIELDS
    public $dropbox_access_token         = '';
    public $dropbox_access_token_secret  = '';
    public $dropbox_v2_access_token      = ''; //to use different name for OAuth 2 token
    public $dropbox_storage_folder       = '';
    public $dropbox_max_files            = 10;
    public $dropbox_authorization_state  = DUP_PRO_Dropbox_Authorization_States::Unauthorized;
    //ONEDRIVE FIELDS
    public $onedrive_endpoint_url        = '';
    public $onedrive_resource_id         = '';
    public $onedrive_access_token        = '';
    public $onedrive_refresh_token       = '';
    public $onedrive_token_obtained      = '';
    public $onedrive_user_id             = '';
    public $onedrive_storage_folder      = '';
    public $onedrive_max_files           = 10;
    public $onedrive_storage_folder_id   = '';
    public $onedrive_authorization_state = DUP_PRO_OneDrive_Authorization_States::Unauthorized;
    // FTP FIELDS
    public $ftp_server                   = '';
    public $ftp_port                     = 21;
    public $ftp_username                 = '';
    public $ftp_password                 = '';
    public $ftp_storage_folder           = '';
    public $ftp_max_files                = 10;
    public $ftp_timeout_in_secs          = 15;
    public $ftp_ssl                      = false;
    public $ftp_passive_mode             = false;
    // SFTP FIELDS
    public $sftp_server                  = '';
    public $sftp_port                    = 22;
    public $sftp_username                = '';
    public $sftp_password                = '';
    public $sftp_private_key             = '';
    public $sftp_private_key_password    = '';
    public $sftp_storage_folder          = '';
    public $sftp_timeout_in_secs         = 15;
    public $sftp_max_files               = 10;
    // GOOGLE DRIVE FIELDS
    public $gdrive_access_token_set_json = '';
    public $gdrive_refresh_token         = '';
    public $gdrive_storage_folder        = '';
    public $gdrive_max_files             = 10;
    public $gdrive_authorization_state   = DUP_PRO_Dropbox_Authorization_States::Unauthorized;
    public $quick_connect                = false;
    // S3 FIELDS
    public $s3_access_key;
    public $s3_bucket;
    public $s3_max_files                 = 10;
    public $s3_region;
    public $s3_secret_key;
    public $s3_storage_class             = 'STANDARD';
    public $s3_storage_folder            = '';
    private static $sort_error           = false;

    //   const PLACE_HOLDER   ='%domain%';
    function __construct()
    {
        parent::__construct();

        $this->verifiers['name'] = new DUP_PRO_Required_Verifier("Name must not be blank");

        $this->name = DUP_PRO_U::__('New Storage');

        $this->dropbox_storage_folder = self::get_default_storage_folder();

        $this->ftp_storage_folder = '/'.self::get_default_storage_folder();

        $this->sftp_storage_folder = '/';

        $this->gdrive_storage_folder = 'Duplicator Backups/'.self::get_default_storage_folder();

        $this->s3_storage_folder = 'Duplicator Backups/'.self::get_default_storage_folder();

        $this->onedrive_storage_folder = self::get_default_storage_folder();
    }

    public static function create_from_data($storage_data, $restore_id = false)
    {
        $instance = new DUP_PRO_Storage_Entity();

        $instance->name         = $storage_data->name;
        $instance->notes        = $storage_data->notes;
        $instance->storage_type = $storage_data->storage_type;
        $instance->editable     = $storage_data->editable;

        // LOCAL FIELDS
        $instance->local_storage_folder    = $storage_data->local_storage_folder;
        $instance->local_max_files         = $storage_data->local_max_files;
        $instance->local_filter_protection = $storage_data->local_filter_protection;

        // DROPBOX FIELDS
        $instance->dropbox_access_token        = $storage_data->dropbox_access_token;
        $instance->dropbox_v2_access_token     = $storage_data->dropbox_v2_access_token;
        $instance->dropbox_access_token_secret = $storage_data->dropbox_access_token_secret;
        $instance->dropbox_storage_folder      = $storage_data->dropbox_storage_folder;
        $instance->dropbox_max_files           = $storage_data->dropbox_max_files;
        $instance->dropbox_authorization_state = $storage_data->dropbox_authorization_state;

        //ONEDRIVE FIELDS
        $instance->onedrive_max_files           = $storage_data->onedrive_max_files;
        $instance->onedrive_storage_folder      = $storage_data->onedrive_storage_folder;
        $instance->onedrive_access_token        = $storage_data->onedrive_access_token;
        $instance->onedrive_refresh_token       = $storage_data->onedrive_refresh_token;
        $instance->onedrive_user_id             = $storage_data->onedrive_user_id;
        $instance->onedrive_token_obtained      = $storage_data->onedrive_token_obtained;
        $instance->onedrive_authorization_state = $storage_data->onedrive_authorization_state;

        // FTP FIELDS
        $instance->ftp_server          = $storage_data->ftp_server;
        $instance->ftp_port            = $storage_data->ftp_port;
        $instance->ftp_username        = $storage_data->ftp_username;
        $instance->ftp_password        = $storage_data->ftp_password;
        $instance->ftp_storage_folder  = $storage_data->ftp_storage_folder;
        $instance->ftp_max_files       = $storage_data->ftp_max_files;
        $instance->ftp_timeout_in_secs = $storage_data->ftp_timeout_in_secs;
        $instance->ftp_ssl             = $storage_data->ftp_ssl;
        $instance->ftp_passive_mode    = $storage_data->ftp_passive_mode;

        // SFTP FIELDS
        $instance->sftp_server              = $storage_data->sftp_server;
        $instance->sftp_port                = $storage_data->sftp_port;
        $instance->sftp_username            = $storage_data->sftp_username;
        $instance->sftp_password            = $storage_data->sftp_password;
        $instance->sftp_storage_folder      = $storage_data->sftp_storage_folder;
        $instance->sftp_private_key         = $storage_data->sftp_private_key;
        $instance->sftp_private_key_password= $storage_data->sftp_private_key_password;
        $instance->ftp_timeout_in_secs      = $storage_data->ftp_timeout_in_secs;
        $instance->sftp_max_files           = $storage_data->sftp_max_files;

        // GOOGLE DRIVE FIELDS
        $instance->gdrive_access_token_set_json = $storage_data->gdrive_access_token_set_json;
        $instance->gdrive_refresh_token         = $storage_data->gdrive_refresh_token;
        $instance->gdrive_storage_folder        = $storage_data->gdrive_storage_folder;
        $instance->gdrive_max_files             = $storage_data->gdrive_max_files;
        $instance->gdrive_authorization_state   = $storage_data->gdrive_authorization_state;
        $instance->quick_connect                = $storage_data->quick_connect;

        // S3 FIELDS
        $instance->s3_access_key     = $storage_data->s3_access_key;
        $instance->s3_bucket         = $storage_data->s3_bucket;
        $instance->s3_max_files      = $storage_data->s3_max_files;
        $instance->s3_region         = $storage_data->s3_region;
        $instance->s3_secret_key     = $storage_data->s3_secret_key;
        $instance->s3_storage_class  = $storage_data->s3_storage_class;
        $instance->s3_storage_folder = $storage_data->s3_storage_folder;

        if ($restore_id) {
            $instance->id = $storage_data->id;
        }

        return $instance;
    }

    public static function get_default_storage_folder()
    {
        $folder = site_url();

        if (empty($folder)) {
            $folder = home_url();
        }

        if (!empty($folder)) {
            $folder = str_replace('http://', '', $folder);
            $folder = str_replace('https://', '', $folder);
            $folder = preg_replace("([^\w\s\d\-_~,;:\[\]\(\)/.])", '', $folder);

            // Remove any runs of periods (thanks falstro!)
            $folder = preg_replace("([\.]{2,})", '', $folder);

            if (DUP_PRO_STR::endsWith($folder, '/') == false) {
                $folder .= '/';
            }
        }

        return $folder;
    }

    public function get_storage_folder()
    {
        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Local:
                return $this->local_storage_folder;
                break;

            case DUP_PRO_Storage_Types::Dropbox:
                return ($this->dropbox_storage_folder == '' ? '/' : $this->dropbox_storage_folder);
                break;

            case DUP_PRO_Storage_Types::FTP:
                return ($this->ftp_storage_folder == '' ? '/' : $this->ftp_storage_folder);
                break;

            case DUP_PRO_Storage_Types::SFTP:
                return ($this->sftp_storage_folder == '' ? '/' : $this->sftp_storage_folder);
                break;

            case DUP_PRO_Storage_Types::GDrive:
                return ($this->gdrive_storage_folder == '' ? '/' : $this->gdrive_storage_folder);
                break;

            case DUP_PRO_Storage_Types::S3:
                return ($this->s3_storage_folder == '' ? '/' : $this->s3_storage_folder);
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                return ($this->onedrive_storage_folder == '' ? '/' : $this->onedrive_storage_folder);
                break;
        }
    }

    public function copy_from_source_id($id)
    {
        /* @var $source_storage DUP_PRO_Storage_Entity */
        $source_storage = self::get_by_id($id);

        $this->dropbox_access_token        = $source_storage->dropbox_access_token;
        $this->dropbox_v2_access_token     = $source_storage->dropbox_v2_access_token;
        $this->dropbox_access_token_secret = $source_storage->dropbox_access_token_secret;
        $this->dropbox_authorization_state = $source_storage->dropbox_authorization_state;
        $this->dropbox_max_files           = $source_storage->dropbox_max_files;
        $this->dropbox_storage_folder      = $source_storage->dropbox_storage_folder;
        //$this->editable;
        $this->ftp_max_files               = $source_storage->ftp_max_files;
        $this->ftp_passive_mode            = $source_storage->ftp_passive_mode;
        $this->ftp_password                = $source_storage->ftp_password;
        $this->ftp_port                    = $source_storage->ftp_port;
        $this->ftp_server                  = $source_storage->ftp_server;
        $this->ftp_ssl                     = $source_storage->ftp_ssl;
        $this->ftp_storage_folder          = $source_storage->ftp_storage_folder;
        $this->ftp_timeout_in_secs         = $source_storage->ftp_timeout_in_secs;
        $this->ftp_username                = $source_storage->ftp_username;

        $this->sftp_password                = $source_storage->sftp_password;
        $this->sftp_port                    = $source_storage->sftp_port;
        $this->sftp_server                  = $source_storage->sftp_server;
        $this->sftp_storage_folder          = $source_storage->sftp_storage_folder;
        $this->sftp_timeout_in_secs         = $source_storage->sftp_timeout_in_secs;
        $this->sftp_username                = $source_storage->sftp_username;
        $this->sftp_private_key             = $source_storage->sftp_private_key;
        $this->sftp_private_key_password    = $source_storage->sftp_private_key_password;
        $this->sftp_max_files               = $source_storage->sftp_max_files;

        $this->local_storage_folder        = $source_storage->local_storage_folder;
        $this->local_max_files             = $source_storage->local_max_files;
        $this->name                        = sprintf(DUP_PRO_U::__('%1$s - Copy'), $source_storage->name);
        $this->notes                       = $source_storage->notes;
        $this->storage_type                = $source_storage->storage_type;

        $this->gdrive_access_token_set_json = $source_storage->gdrive_access_token_set_json;
        $this->gdrive_refresh_token         = $source_storage->gdrive_refresh_token;
        $this->gdrive_storage_folder        = $source_storage->gdrive_storage_folder;
        $this->gdrive_max_files             = $source_storage->gdrive_max_files;
        $this->gdrive_authorization_state   = $source_storage->gdrive_authorization_state;
        $this->quick_connect                = $source_storage->quick_connect;

        // S3 FIELDS
        $this->s3_storage_folder = $source_storage->s3_storage_folder;
        $this->s3_bucket         = $source_storage->s3_bucket;
        $this->s3_access_key     = $source_storage->s3_access_key;
        $this->s3_secret_key     = $source_storage->s3_secret_key;
        $this->s3_region         = $source_storage->s3_region;
        $this->s3_storage_class  = $source_storage->s3_storage_class;

        //ONEDRIVE FIELDS
        $this->onedrive_max_files           = $source_storage->onedrive_max_files;
        $this->onedrive_storage_folder      = $source_storage->onedrive_storage_folder;
        $this->onedrive_access_token        = $source_storage->onedrive_access_token;
        $this->onedrive_refresh_token       = $source_storage->onedrive_refresh_token;
        $this->onedrive_user_id             = $source_storage->onedrive_user_id;
        $this->onedrive_token_obtained      = $source_storage->onedrive_token_obtained;
        $this->onedrive_authorization_state = $source_storage->onedrive_authorization_state;
    }

    public static function get_all()
    {
        $default_local_storage = self::get_default_local_storage();

        $storages = self::get_by_type(get_class());

        array_unshift($storages, $default_local_storage);

        foreach ($storages as $storage) {
            /* @var $storage DUP_PRO_Storage_Entity */
            $storage->decrypt();
        }

        return $storages;
    }

    public function get_onedrive_client(){
        $scope = !$this->onedrive_is_business() ? DUP_PRO_OneDrive_Config::ONEDRIVE_ACCESS_SCOPE :
            DUP_PRO_OneDrive_Config::ONEDRIVE_BUSINESS_ACCESS_SCOPE;
		$state = (object)array(
            'redirect_uri' => null,
            'endpoint_url' => $this->onedrive_endpoint_url,
            'resource_id'  => $this->onedrive_resource_id,
            'token' => (object)array(
                'obtained' => $this->onedrive_token_obtained,
                'data' => (object)array(
                    'token_type' => 'bearer',
                    'expires_in' => 3600,
                    'scope' => $scope,
                    'access_token' => $this->onedrive_access_token,
                    'refresh_token' => $this->onedrive_refresh_token,
                    'user_id' => $this->onedrive_user_id
                )
            )
        );

        $onedrive = DUP_PRO_Onedrive_U::get_onedrive_client_from_state($state);

        if($onedrive->getAccessTokenStatus() < 0){
            $onedrive->renewAccessToken(DUP_PRO_OneDrive_Config::ONEDRIVE_CLIENT_SECRET);
        }

        return $onedrive;
    }

    public function onedrive_is_business(){
        return !empty( $this->onedrive_endpoint_url) && !empty($this->onedrive_resource_id);
    }

    public function get_onedrive_storage_folder(){
        $onedrive_folder_id = $this->onedrive_storage_folder_id;
        $onedrive = $this->get_onedrive_client();
        DUP_PRO_Log::traceObject("Client", $onedrive->getState());

        if(!$onedrive_folder_id){
            $onedrive_folder = $this->create_onedrive_folder();
            $this->onedrive_storage_folder_id = $onedrive_folder->getId();
            $this->save();
        }else{
            try{
                $onedrive_folder_candidate = $onedrive->fetchDriveItem($onedrive_folder_id);
                $onedrive_folder = $onedrive_folder_candidate;
            } catch (Exception $exception){
                if(strpos($exception->getMessage(),"Item does not exist") !== false){
                    $onedrive_folder = $this->create_onedrive_folder();
                    $this->onedrive_storage_folder_id = $onedrive_folder->getId();
                    $this->save();
                }else{
                    throw $exception;
                }

            }
        }

        return $onedrive_folder;
    }

    public function onedrive_folder_exists(){
        $onedrive_folder_id = $this->onedrive_storage_folder_id;
        $onedrive = $this->get_onedrive_client();

        if(!$onedrive_folder_id){
            return false;
        }else{
            $onedrive_folder_candidate = $onedrive->fetchDriveItem($onedrive_folder_id);
            return ($onedrive_folder_candidate) ? true : false;
        }
    }

    public function create_onedrive_folder(){
        $onedrive = $this->get_onedrive_client();
        $parent                 = null;
        $current_search_item    = $onedrive->fetchRoot();
        $create_folder          = true;
        $storage_folders_tree   = explode("/",$this->onedrive_storage_folder);

        foreach ($storage_folders_tree as $folder){
            $child_items = $current_search_item->fetchChildDriveItems();
            DUP_PRO_Log::traceObject("childs",$child_items);
            DUP_PRO_Log::trace("Checking $folder");
            if(!empty($folder)) {
                if(!empty($child_items)){
                    foreach ($child_items as $item) {
                        if ($item->isFolder()) {
                            $name = $item->getName();
                            DUP_PRO_Log::trace("$folder <===> $name");
                            if ($name == $folder) {
                                $current_search_item = $item;
                                $create_folder = false;
                                break;
                            }
                        }
                    }
                }
                if ($create_folder) {
                    $new_folder = $current_search_item->createFolder($folder);
                    $current_search_item = $new_folder;
                }
            }
            $create_folder = true;
        }
        $parent = $current_search_item;

        return $parent;
    }

	// Can't have namespaces in common files
    //public function purge_old_onedrive_packages(\Krizalys\Onedrive\Client $onedrive){
	public function purge_old_onedrive_packages($onedrive)
	{
        DUP_PRO_Log::trace("Starting purging of old packages in OneDrive");
        $storage_folder_id = $this->onedrive_storage_folder_id;
        $package_items = $onedrive->fetchDriveItems($storage_folder_id);

        $archives = array();
        $installers = array();

        foreach ($package_items as $item){
            $name = $item->getName();
          //  $extension = pathinfo($name)["extension"];
			$pathinfo = pathinfo($name);
			$extension = $pathinfo["extension"];

            if($extension == 'zip' || $extension == 'daf'){
                $archives[] = $item;
            }elseif($extension == 'php'){
                $installers[] = $item;
            }
        }

		$complete_packages = array();

        foreach ($archives as $archive){
            //$archive_name = pathinfo($archive->getName())["filename"];
			$pathinfo = pathinfo($archive->getName());
			$archive_name = $pathinfo["filename"];

            DUP_PRO_Log::trace($archive_name);
            $archive_name = str_replace('_archive','',$archive_name);
            foreach ($installers as $installer){

				//$installer_name = pathinfo($installer->getName())["filename"];
				$pathinfo = pathinfo($installer->getName());//["filename"];
				$installer_name = $pathinfo["filename"];

                DUP_PRO_Log::trace($installer_name);
                $installer_name = str_replace('_installer','',$installer_name);
                if($archive_name == $installer_name){
					
					$complete_packages[] = array(
                        "archive_id"    => $archive->getId(),
                        "installer_id"  => $installer->getId(),
                        "created_time"  => $archive->getCreatedTime(),
                    );

                    DUP_PRO_Log::trace(print_r($archive,true));
                }
            }
        }

		if($this->onedrive_max_files > 0) {
			$num_archives = count($complete_packages);
			$num_archives_to_delete = $num_archives - $this->onedrive_max_files;

			DUP_PRO_LOG::trace("Num archives files to delete={$num_archives_to_delete} since there are {$num_archives} on the drive and max files={$this->onedrive_max_files}");

			$retsort = usort($complete_packages, array('DUP_PRO_Storage_Entity', 'onedrive_compare_file_dates'));

			$index = 0;
			while ($index < $num_archives_to_delete) {


				$old_package = $complete_packages[$index];
				DUP_PRO_LOG::trace("Deleting old package created on " . $old_package['created_time']);
				$onedrive->deleteDriveItem($old_package["archive_id"]);
				$onedrive->deleteDriveItem($old_package["installer_id"]);

				$index++;
			}
		}
    }

    public function get_sanitized_storage_folder(){
        $storage_folders = explode("/",$this->get_storage_folder());

        foreach ($storage_folders as $i=>$folder){
            $storage_folders[$i] = rawurlencode ($folder);
        }
        if(end($storage_folders) != ""){
            $storage_folders[] = "";
        }

        return implode("/",$storage_folders);
    }

    public function get_dropbox_client($full_access = false)
    {
        /* @var $global DUP_PRO_Global_Entity */
        $global        = DUP_PRO_Global_Entity::get_instance();
        $configuration = self::get_dropbox_api_key_secret();
        if ($full_access) {
            $configuration['app_full_access'] = true;
        }
        // Note it's possible dropbox is in disabled mode but we are still constructing it.  Should have better error handling
        $use_curl     = ($global->dropbox_transfer_mode == DUP_PRO_Dropbox_Transfer_Mode::cURL);
        $dropbox      = new DUP_PRO_DropboxV2Client($configuration, 'en', $use_curl);
        $access_token = $this->get_dropbox_combined_access_token($global->dropbox_transfer_mode === DUP_PRO_Dropbox_Transfer_Mode::cURL);
        $dropbox->SetAccessToken($access_token);
        return $dropbox;
    }

    public static function get_dropbox_api_key_secret()
    {
        $dk = self::get_dk1();
        $dk = self::get_dk2().$dk;

        $akey = DUP_PRO_Crypt_Blowfish::decrypt('EQNJ53++6/40fuF5ke+IaQ==', $dk);
        $asec = DUP_PRO_Crypt_Blowfish::decrypt('ui25chqoBexPt6QDi9qmGg==', $dk);

        $akey = trim($akey);
        $asec = trim($asec);

        if (($akey != $asec) || ($akey != "fdda100")) {
            $akey = self::get_ak1().self::get_ak2();
            $asec = self::get_as1().self::get_as2();
        }


        $configuration = array('app_key' => $asec, 'app_secret' => $akey);
        return $configuration;
    }

    public static function get_raw_dropbox_client($full_access = false)
    {
        /* @var $global DUP_PRO_Global_Entity */
        $global = DUP_PRO_Global_Entity::get_instance();

        // $dk = self::get_dk1();
        // $dk = self::get_dk2() . $dk;
        // $akey = DUP_PRO_Crypt_Blowfish::decrypt('EQNJ53++6/40fuF5ke+IaQ==', $dk);
        // $asec = DUP_PRO_Crypt_Blowfish::decrypt('ui25chqoBexPt6QDi9qmGg==', $dk);
        // $akey = trim($akey);
        // $asec = trim($asec);
        // if (($akey != $asec) || ($akey != "fdda100"))
        // {
        //     $akey = self::get_ak1() . self::get_ak2();
        //     $asec = self::get_as1() . self::get_as2();
        // }
        // $configuration = array('app_key' => $asec, 'app_secret' => $akey);
        $configuration = self::get_dropbox_api_key_secret();
        // ob_start();
        // print_r($configuration);
        // $data=ob_get_clean();
        // file_put_contents(dirname(__FILE__) . '/configuration.log',$data,FILE_APPEND);
        if ($full_access) {
            $configuration['app_full_access'] = true;
        }

        // Note it's possible dropbox is in disabled mode but we are still constructing it.  Should have better error handling
        $use_curl = ($global->dropbox_transfer_mode == DUP_PRO_Dropbox_Transfer_Mode::cURL);

        $dropbox = new DUP_PRO_DropboxV2Client($configuration, 'en', $use_curl);

        return $dropbox;
    }

    // Retrieves the google client based on storage and auto updates the access token if necessary
    public function get_full_google_client()
    {
        $google_client = null;

        if (!empty($this->gdrive_access_token_set_json)) {
            $google_client = DUP_PRO_GDrive_U::get_raw_google_client();

            $google_client->setAccessToken($this->gdrive_access_token_set_json);

            // Reference on access/refresh token http://stackoverflow.com/questions/9241213/how-to-refresh-token-with-google-api-client
            if ($google_client->isAccessTokenExpired()) {
                DUP_PRO_LOG::traceObject("Access token is expired so checking token ", $this->gdrive_refresh_token);
                $google_client->refreshToken($this->gdrive_refresh_token);

                // getAccessToken return json encoded value of access token and other stuff
                $gdrive_access_token_set_json = $google_client->getAccessToken();

                if ($gdrive_access_token_set_json != null) {
                    $this->gdrive_access_token_set_json = $gdrive_access_token_set_json;

                    DUP_PRO_LOG::trace("Retrieved acess token set from google: {$this->gdrive_access_token_set_json}");

                    $this->save();
                } else {
                    DUP_PRO_LOG::trace("Can't retrieve access token!");
                    $google_client = null;
                }
            } else {
                DUP_PRO_LOG::trace("Access token ISNT expired");
            }
        } else {
            DUP_PRO_LOG::trace("Access token not set!");
        }

        return $google_client;
    }

    public function get_full_s3_client()
    {
        return DUP_PRO_S3_U::get_s3_client($this->s3_region, $this->s3_access_key, $this->s3_secret_key);
    }

    public static function delete_by_id($storage_id)
    {
        $packages = DUP_PRO_Package::get_all();

        foreach ($packages as $package) {
            /* @var $package DUP_PRO_Package */
            foreach ($package->upload_infos as $key => $upload_info) {
                /* @var $upload_info DUP_PRO_Package_Upload_Info */
                if ($upload_info->storage_id == $storage_id) {
                    DUP_PRO_LOG::traceObject("deleting uploadinfo from package $package->ID", $upload_info);
                    unset($package->upload_infos[$key]);
                    $package->save();
                    break;
                }
            }
        }


        parent::delete_by_id_base($storage_id);
    }

    public static function get_by_id($id, $decrypt = true)
    {
        if ($id == DUP_PRO_Virtual_Storage_IDs::Default_Local) {
            return self::get_default_local_storage();
        }

        $storage = self::get_by_id_and_type($id, get_class());

        if ($storage != null) {
            /* @var $storage DUP_PRO_Storage_Entity */
            if ($decrypt) {
                $storage->decrypt();
            }
        }

        return $storage;
    }

    public function get_dropbox_combined_access_token($use_curl = true)
    {
        $access_token = array();

        $access_token['t'] = $this->dropbox_access_token;
        $access_token['s'] = $this->dropbox_access_token_secret;

        /* if dropbox_access_token and dropbox_access_token_secret is not empty, but dropbox_v2_access_token is empty, that means it's auth1, then we try to get v2_access_token from it */
        if (!empty($this->dropbox_access_token) && !empty($this->dropbox_access_token_secret) && empty($this->dropbox_v2_access_token)) {
            require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/lib/DropPHP/DropboxClient.php');
            $configuration = self::get_dropbox_api_key_secret();
            $dropbox_v1    = new DUP_PRO_DropboxClient($configuration, 'en', $use_curl);
            $dropbox_v1->SetAccessToken($access_token);
            $response      = $dropbox_v1->token_from_oauth1();
            /*
              https://www.dropbox.com/developers-v1/core/docs#oa2-from-oa1
              return sample
              {"access_token": "ABCDEFG", token_type: "bearer"}
             */
            if (isset($response->access_token)) {
                $this->dropbox_v2_access_token = $response->access_token;
            }
            $this->save();
        }
        $access_token['v2_access_token'] = $this->dropbox_v2_access_token;

        return $access_token;
    }

    private static function get_dk1()
    {
        return 'y8!!';
    }

    private static function get_dk2()
    {
        return '32897';
    }

    public function process_package($package, $upload_info)
    {
        /* @var $package DUP_PRO_Package */
        $package->active_storage_id = $this->id;
      
        DUP_PRO_LOG::trace("Storage $this->name process");

        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Dropbox:
                // ob_start();
                // print_r($package);
                // print_r($upload_info);
                // $data=ob_get_clean();
                // file_put_contents(dirname(__FILE__) . '/package_upload_info.log',$data,FILE_APPEND);
                $this->copy_to_dropbox($package, $upload_info);
                break;

            case DUP_PRO_Storage_Types::GDrive:
                $this->copy_to_gdrive($package, $upload_info);
                break;

            case DUP_PRO_Storage_Types::FTP:
                $this->copy_to_ftp($package, $upload_info);
                break;

            case DUP_PRO_Storage_Types::SFTP:
                $this->copy_to_sftp($package, $upload_info);
                break;

            case DUP_PRO_Storage_Types::Local:
                $this->copy_to_local($package, $upload_info);
                break;

            case DUP_PRO_Storage_Types::S3:
                $this->copy_to_s3($package, $upload_info);
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                $this->copy_to_onedrive($package, $upload_info);
                break;

            default:
                DUP_PRO_LOG::trace("invalid storage type");
        }
    }

    public function encrypt()
    {
        /* @var $storage DUP_PRO_Storage_Entity */
        if (!empty($this->dropbox_access_token)) {
            $this->dropbox_access_token = DUP_PRO_Crypt_Blowfish::encrypt($this->dropbox_access_token);
        }

        if (!empty($this->dropbox_v2_access_token)) {
            $this->dropbox_v2_access_token = DUP_PRO_Crypt_Blowfish::encrypt($this->dropbox_v2_access_token);
        }

        if (!empty($this->dropbox_access_token_secret)) {
            $this->dropbox_access_token_secret = DUP_PRO_Crypt_Blowfish::encrypt($this->dropbox_access_token_secret);
        }

        if (!empty($this->gdrive_access_token_set_json)) {
            $this->gdrive_access_token_set_json = DUP_PRO_Crypt_Blowfish::encrypt($this->gdrive_access_token_set_json);
        }

        if (!empty($this->gdrive_refresh_token)) {
            $this->gdrive_refresh_token = DUP_PRO_Crypt_Blowfish::encrypt($this->gdrive_refresh_token);
        }

        if (!empty($this->s3_access_key)) {
            $this->s3_access_key = DUP_PRO_Crypt_Blowfish::encrypt($this->s3_access_key);
        }

        if (!empty($this->s3_secret_key)) {
            $this->s3_secret_key = DUP_PRO_Crypt_Blowfish::encrypt($this->s3_secret_key);
        }

//        if (!empty($this->onedrive_access_token)) {
//            $this->onedrive_access_token = DUP_PRO_Crypt_Blowfish::encrypt($this->onedrive_access_token);
//        }
//
//        if (!empty($this->onedrive_refresh_token)) {
//            $this->onedrive_refresh_token = DUP_PRO_Crypt_Blowfish::encrypt($this->onedrive_refresh_token);
//        }
    }

    public function decrypt()
    {
        /* @var $storage DUP_PRO_Storage_Entity */
        if (!empty($this->dropbox_access_token)) {
            $this->dropbox_access_token = DUP_PRO_Crypt_Blowfish::decrypt($this->dropbox_access_token);
        }

        if (!empty($this->dropbox_v2_access_token)) {
            $this->dropbox_v2_access_token = DUP_PRO_Crypt_Blowfish::decrypt($this->dropbox_v2_access_token);
        }

        if (!empty($this->dropbox_access_token_secret)) {
            $this->dropbox_access_token_secret = DUP_PRO_Crypt_Blowfish::decrypt($this->dropbox_access_token_secret);
        }

        if (!empty($this->gdrive_access_token_set_json)) {
            if (!DUP_PRO_STR::contains($this->gdrive_access_token_set_json, 'access_token')) {
                $this->gdrive_access_token_set_json = DUP_PRO_Crypt_Blowfish::decrypt($this->gdrive_access_token_set_json);
            }
        }

        if (!empty($this->gdrive_refresh_token)) {
            $this->gdrive_refresh_token = DUP_PRO_Crypt_Blowfish::decrypt($this->gdrive_refresh_token);
        }

        if (!empty($this->s3_access_key)) {
            $this->s3_access_key = DUP_PRO_Crypt_Blowfish::decrypt($this->s3_access_key);
        }

        if (!empty($this->s3_secret_key)) {
            $this->s3_secret_key = DUP_PRO_Crypt_Blowfish::decrypt($this->s3_secret_key);
        }

//        if (!empty($this->onedrive_access_token)) {
//            $this->onedrive_access_token = DUP_PRO_Crypt_Blowfish::decrypt($this->onedrive_access_token);
//        }
//
//        if (!empty($this->onedrive_refresh_token)) {
//            $this->onedrive_refresh_token = DUP_PRO_Crypt_Blowfish::decrypt($this->onedrive_refresh_token);
//        }
    }

    public function is_valid()
    {
        $is_valid = true;

        if ($this->storage_type == DUP_PRO_Storage_Types::Local) {
            $is_valid = is_writable($this->local_storage_folder);
        }

        return $is_valid;
    }

    public function get_type_text()
    {
        $type_text = DUP_PRO_U::__('Unknown');

        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Local:
                $type_text = DUP_PRO_U::__('Local');
                break;

            case DUP_PRO_Storage_Types::Dropbox:
                $type_text = DUP_PRO_U::__('Dropbox');
                break;

            case DUP_PRO_Storage_Types::FTP:
                $type_text = DUP_PRO_U::__('FTP');
                break;

            case DUP_PRO_Storage_Types::GDrive:
                $type_text = DUP_PRO_U::__('Google Drive');
                break;

            case DUP_PRO_Storage_Types::S3:
                $type_text = DUP_PRO_U::__('Amazon S3');
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                $type_text = !$this->onedrive_is_business() ? DUP_PRO_U::__('OneDrive') : DUP_PRO_U::__('OneDrive (B)');
                break;
        }

        return $type_text;
    }

    public function get_action_text()
    {
        $text = '';

        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Local:
                $text = sprintf(DUP_PRO_U::__('Copying to directory %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::Dropbox:
                $text = sprintf(DUP_PRO_U::__('Transferring to Dropbox folder %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::FTP:
                $text = sprintf(DUP_PRO_U::__('Transferring to FTP server %1$s in folder %2$s'), $this->ftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::SFTP:
                $text = sprintf(DUP_PRO_U::__('Transferring to SFTP server %1$s in folder %2$s'), $this->sftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::GDrive:
                $text = sprintf(DUP_PRO_U::__('Transferring to Google Drive folder %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::S3:
                $text = sprintf(DUP_PRO_U::__('Transferring to Amazon S3 folder %1$s'), $this->get_storage_folder());
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                $text = sprintf(DUP_PRO_U::__('Transferring to OneDrive folder %1$s'), $this->get_storage_folder());
                break;
        }

        return $text;
    }

    public function get_pending_text()
    {
        $text = '';

        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Local:
                $text = sprintf(DUP_PRO_U::__('Copy to directory %1$s is pending'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::Dropbox:
                $text = sprintf(DUP_PRO_U::__('Transfer to Dropbox folder %1$s is pending'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::FTP:
                $text = sprintf(DUP_PRO_U::__('Transfer to FTP server %1$s in folder %2$s is pending'), $this->ftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::SFTP:
                $text = sprintf(DUP_PRO_U::__('Transfer to SFTP server %1$s in folder %2$s is pending'), $this->sftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::GDrive:
                $text = sprintf(DUP_PRO_U::__('Transfer to Google Drive folder %1$s is pending'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::S3:
                $text = sprintf(DUP_PRO_U::__('Transfer to Amazon S3 folder %1$s is pending'), $this->get_storage_folder());
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                $text = sprintf(DUP_PRO_U::__('Transfer to OneDrive folder %1$s is pending'), $this->get_storage_folder());
                break;
        }

        return $text;
    }

    public function get_failed_text()
    {
        $text = '';

        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Local:
                $text = sprintf(DUP_PRO_U::__('Failed to copy to directory %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::Dropbox:
                $text = sprintf(DUP_PRO_U::__('Failed to transfer to Dropbox folder %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::FTP:
                $text = sprintf(DUP_PRO_U::__('Failed to transfer to FTP server %1$s in folder %2$s'), $this->ftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::SFTP:
                $text = sprintf(DUP_PRO_U::__('Failed to transfer to SFTP server %1$s in folder %2$s'), $this->sftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::GDrive:
                $text = sprintf(DUP_PRO_U::__('Failed to transfer to Google Drive folder %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::S3:
                $text = sprintf(DUP_PRO_U::__('Failed to transfer to Amazon S3 folder %1$s'), $this->get_storage_folder());
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                $text = sprintf(DUP_PRO_U::__('Failed to transfer to OneDrive folder %1$s'), $this->get_storage_folder());
                break;
        }

        return $text;
    }

    public function get_cancelled_text()
    {
        $text = '';

        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Local:
                $text = sprintf(DUP_PRO_U::__('Cancelled before could copy to directory %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::Dropbox:
                $text = sprintf(DUP_PRO_U::__('Cancelled before could transfer to Dropbox folder %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::FTP:
                $text = sprintf(DUP_PRO_U::__('Cancelled before could transfer to FTP server:<br/>%1$s in folder %2$s'), $this->ftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::SFTP:
                $text = sprintf(DUP_PRO_U::__('Cancelled before could transfer to SFTP server:<br/>%1$s in folder %2$s'), $this->sftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::GDrive:
                $text = sprintf(DUP_PRO_U::__('Cancelled before could transfer to Google Drive folder %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::S3:
                $text = sprintf(DUP_PRO_U::__('Cancelled before could transfer to Amazon S3 folder %1$s'), $this->get_storage_folder());
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                $text = sprintf(DUP_PRO_U::__('Cancelled before could transfer to OneDrive folder %1$s'), $this->get_storage_folder());
                break;
        }

        return $text;
    }

    public function get_succeeded_text()
    {
        $text = '';

        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Local:
                $text = sprintf(DUP_PRO_U::__('Copied the package to directory %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::Dropbox:
                $text = sprintf(DUP_PRO_U::__('Transferred the package to Dropbox folder %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::FTP:
                $text = sprintf(DUP_PRO_U::__('Transferred the package to FTP server:<br/>%1$s in folder %2$s'), $this->ftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::SFTP:
                $text = sprintf(DUP_PRO_U::__('Transferred the package to SFTP server:<br/>%1$s in folder %2$s'), $this->sftp_server, $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::GDrive:
                $text = sprintf(DUP_PRO_U::__('Transferred the package to Google Drive folder %1$s'), $this->get_storage_folder());
                break;

            case DUP_PRO_Storage_Types::S3:
                $text = sprintf(DUP_PRO_U::__('Transferred the package to Amazon S3 folder %1$s'), $this->get_storage_folder());
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                $text = sprintf(DUP_PRO_U::__('Transferred the package to OneDrive folder %1$s'), $this->get_storage_folder());
                break;
        }

        return $text;
    }

    private function copy_to_local($package, $upload_info)
    {
        /* @var $upload_info DUP_PRO_Package_Upload_Info */
        DUP_PRO_LOG::trace("copying to local");

        /* @var $package DUP_PRO_Package */
        if ($this->id == DUP_PRO_Virtual_Storage_IDs::Default_Local) {
            DUP_PRO_Log::info("Successfully copied to local default location at ".DUPLICATOR_PRO_SSDIR_PATH);
            // It's the default local storage location so do nothing - it's already there
            $upload_info->copied_archive   = true;
            $upload_info->copied_installer = true;
        } else {
            $package->active_storage_id = $this->id;
            $source_archive_filepath    = $package->Archive->getSafeFilePath();
            $source_installer_filepath  = $package->Installer->get_safe_filepath();
            $source_database_filepath   = $package->Database->getSafeFilePath();
            $source_log_filepath        = $package->get_safe_log_filepath();
            $source_scan_filepath       = $package->get_safe_scan_filepath();

            if (file_exists($source_archive_filepath)) {
                if (file_exists($source_installer_filepath)) {
                    if (DUP_PRO_IO::copyFile($source_archive_filepath, $this->local_storage_folder)) {
                        $upload_info->copied_archive = true;
                        $upload_info->progress       = 50;
                        $package->update();

                        if (DUP_PRO_IO::copyFile($source_installer_filepath, $this->local_storage_folder, true)) {
                            DUP_PRO_LOG::trace("Succesfully copied to $this->local_storage_folder");
                            DUP_PRO_Log::info("Successfully copied to local location $this->local_storage_folder");
                            $upload_info->progress         = 100;
                            $upload_info->copied_installer = true;

                            if (DUP_PRO_IO::copyFile($source_database_filepath, $this->local_storage_folder, true) == false) {
                                // Annoying but non-fatal
                                DUP_PRO_LOG::trace("Couldn't copy database file from $source_database_filepath to $this->local_storage_folder");
                            }

                            if (DUP_PRO_IO::copyFile($source_log_filepath, $this->local_storage_folder, true) == false) {
                                // Annoying but non-fatal
                                DUP_PRO_LOG::trace("Couldn't copy log file from $source_log_filepath to $this->local_storage_folder");
                            }

                            if (DUP_PRO_IO::copyFile($source_scan_filepath, $this->local_storage_folder, true) == false) {
                                // Annoying but non-fatal
                                DUP_PRO_LOG::trace("Couldn't copy scan file from $source_scan_filepath to $this->local_storage_folder");
                            }

                            if ($this->local_max_files > 0) {
                                DUP_PRO_LOG::trace('trying to purge local');
                                $this->purge_old_local_packages();
                            }
                        } else {
                            DUP_PRO_LOG::traceError("Problems copying from $source_installer_filepath to $this->local_storage_folder");
                            $upload_info->increase_failure_count();
                        }
                    } else {
                        DUP_PRO_LOG::traceError("Problems copying from $source_archive_filepath to $this->local_storage_folder");
                        $upload_info->increase_failure_count();
                    }
                } else {
                    DUP_PRO_LOG::traceError("Installer doesn't exist for $package->Name!? - $source_installer_filepath");
                    $upload_info->failed = true;
                }
            } else {
                DUP_PRO_LOG::traceError("Archive doesn't exist for $package->Name!? - $source_archive_filepath");
                $upload_info->failed = true;
            }
        }

        if ($upload_info->failed) {
            DUP_PRO_Log::info("Problem copying to $this->local_storage_folder");
        }

        $package->update();
    }

    private function copy_to_dropbox($package, $upload_info)
    {
        /* @var $upload_info DUP_PRO_Package_Upload_Info */

        /* @var $package DUP_PRO_Package */
        DUP_PRO_LOG::trace("copying to drop box");

        $source_archive_filepath = $package->Archive->getSafeFilePath();

        $source_installer_filepath = $package->Installer->get_safe_filepath();

        if (file_exists($source_archive_filepath)) {
            if (file_exists($source_installer_filepath)) {
                // $dropbox = DUP_PRO_Storage_Entity::get_raw_dropbox_client(false);
                $dropbox = $this->get_dropbox_client(false);
                //test only
                // $access_token=$this->get_dropbox_combined_access_token();
                // ob_start();
                // print_r($access_token);
                // $data=ob_get_clean();
                // file_put_contents(dirname(__FILE__) . '/access_token.log',$data,FILE_APPEND);
                // $dropbox->SetAccessToken($this->get_dropbox_combined_access_token());

                $dropbox_archive_path = basename($source_archive_filepath);
                $dropbox_archive_path = $this->dropbox_storage_folder."/$dropbox_archive_path";

                $dropbox_installer_path = basename($source_installer_filepath);
                $dropbox_installer_path = $this->dropbox_storage_folder."/$dropbox_installer_path";

                try {
                    if (!$upload_info->copied_installer) {
                        DUP_PRO_LOG::trace("attempting Dropbox upload of $source_installer_filepath to $dropbox_installer_path");

                        $dropbox->UploadFile($source_installer_filepath, $dropbox_installer_path);

                        $upload_info->copied_installer = true;
                        $upload_info->progress         = 5;

                        // The package update will automatically capture the upload_info since its part of the package
                        $package->update();
                    } else {
                        DUP_PRO_LOG::trace("Already copied installer on previous execution of Dropbox $this->name so skipping");
                    }

                    if (!$upload_info->copied_archive) {
                        /* Delete the archive if we are just starting it (in the event they are pushing another copy */
                        if ($upload_info->archive_offset == 0) {
                            DUP_PRO_LOG::trace("Archive offset is 0 so deleting $dropbox_archive_path");
                            try {
                                $dropbox->Delete($dropbox_archive_path);
                            } catch (Exception $ex) {
                                // Burying exceptions
                            }
                        }

                        /* @var $global DUP_PRO_Global_Entity */
                        $global = DUP_PRO_Global_Entity::get_instance();

                        /* @var $dropbox_upload_info DUP_PRO_DropboxClient_UploadInfo */
                        $server_load_delay = 0;
                        if ($global->server_load_reduction != DUP_PRO_Server_Load_Reduction::None) {
                            $server_load_delay = DUP_PRO_Server_Load_Reduction::microseconds_from_reduction($global->server_load_reduction);
                        }

                        $dropbox_upload_info = $dropbox->upload_file_chunk($source_archive_filepath, $dropbox_archive_path, $global->dropbox_upload_chunksize_in_kb * 1024,
                            $global->php_max_worker_time_in_sec, $upload_info->archive_offset, $upload_info->upload_id, $server_load_delay);

                        if ($dropbox_upload_info->error_details == null) {
                            // Clear the failure count - we are just looking for consecutive errors
                            $upload_info->failure_count = 0;

                            $upload_info->archive_offset = isset($dropbox_upload_info->next_offset) ? $dropbox_upload_info->next_offset : 0;
                            $upload_info->upload_id      = $dropbox_upload_info->upload_id;

                            $file_size             = filesize($source_archive_filepath);
                            //  $upload_info->progress = max(5, 100 * (bcdiv($upload_info->archive_offset, $file_size, 2)));
                            $upload_info->progress = max(5, DUP_PRO_U::percentage($upload_info->archive_offset, $file_size, 0));
                            DUP_PRO_LOG::trace("progress from $upload_info->archive_offset and total file size $file_size = $upload_info->progress");

                            if ($dropbox_upload_info->file_meta != null) {
                                DUP_PRO_LOG::trace("Successfully uploaded archive to dropbox");
                                $upload_info->copied_archive = true;

                                if ($this->dropbox_max_files > 0) {
                                    $this->purge_old_dropbox_packages($dropbox);
                                }
                            }
                        } else {
                            DUP_PRO_LOG::traceError("Problem uploading archive for package $package->Name: $dropbox_upload_info->error_details");

                            // Could have partially uploaded so retain that offset.
                            $upload_info->archive_offset = isset($dropbox_upload_info->next_offset) ? $dropbox_upload_info->next_offset : 0;
                            $upload_info->increase_failure_count();
                        }
                    } else {
                        DUP_PRO_LOG::trace("Already copied archive on previous execution of Dropbox $this->name so skipping");
                    }
                } catch (Exception $e) {
                    DUP_PRO_LOG::traceError("Problems copying package $package->Name to $this->dropbox_storage_folder. " . $e->getMessage());
                    $upload_info->increase_failure_count();
                }
            } else {
                DUP_PRO_LOG::traceError("Installer doesn't exist for $package->Name!? - $source_installer_filepath");
                $upload_info->failed = true;
            }
        } else {
            DUP_PRO_LOG::traceError("Archive doesn't exist for $package->Name!? - $source_archive_filepath");
            $upload_info->failed = true;
        }

        if ($upload_info->failed) {
            DUP_PRO_Log::info("Problem copying to Dropbox");
        }

        // The package update will automatically capture the upload_info since its part of the package
        $package->update();
    }

    private function copy_to_onedrive($package, $upload_info)
    {
        /* @var $upload_info DUP_PRO_Package_Upload_Info */

        /* @var $package DUP_PRO_Package */
        DUP_PRO_LOG::trace("copying to OneDrive");

        $source_archive_filepath = $package->Archive->getSafeFilePath();

        $source_installer_filepath = $package->Installer->get_safe_filepath();

        if (file_exists($source_archive_filepath)) {
            if (file_exists($source_installer_filepath)) {
                $onedrive = $this->get_onedrive_client();

                $onedrive_archive_path = basename($source_archive_filepath);
                $onedrive_archive_path = $this->get_sanitized_storage_folder().$onedrive_archive_path;

                $onedrive_installer_name = basename($source_installer_filepath);
                $onedrive_installer_path = $this->get_sanitized_storage_folder().$onedrive_installer_name;

                try {
                    $folder_id = $this->onedrive_storage_folder_id;
                    if(!$folder_id){
                        $this->get_onedrive_storage_folder();
                    }
                    if (!$upload_info->copied_installer) {
                        DUP_PRO_LOG::trace("attempting Onedrive upload of $source_installer_filepath to $onedrive_installer_path");

                        $onedrive->uploadFileChunk($source_installer_filepath,$onedrive_installer_path);

                        $upload_info->copied_installer = true;
                        $upload_info->progress         = 5;

                        // The package update will automatically capture the upload_info since its part of the package
                        $package->update();
                    } else {
                        DUP_PRO_LOG::trace("Already copied installer on previous execution of Onedrive $this->name so skipping");
                    }
                    if (!$upload_info->copied_archive) {
                        /* Delete the archive if we are just starting it (in the event they are pushing another copy */
                        if ($upload_info->archive_offset == 0) {
                            DUP_PRO_LOG::trace("Archive offset is 0 so deleting $onedrive_archive_path");
                            try {
                                $onedrive_archive = $onedrive->fetchDriveItemByPath($onedrive_archive_path);
                                $onedrive->deleteDriveItem($onedrive_archive->getId());
                            } catch (Exception $ex) {
                                // Burying exceptions
                            }
                        }

                        /* @var $global DUP_PRO_Global_Entity */
                        $global = DUP_PRO_Global_Entity::get_instance();

                        $server_load_delay = 0;
                        if ($global->server_load_reduction != DUP_PRO_Server_Load_Reduction::None) {
                            $server_load_delay = DUP_PRO_Server_Load_Reduction::microseconds_from_reduction($global->server_load_reduction);
                        }

                        if($upload_info->data != '' && $upload_info->data2 != ''){
                            $resumable = (object)array(
                                "uploadUrl" => $upload_info->data,
                                "expirationTime" => $upload_info->data2
                            );
                            $onedrive->uploadFileChunk($source_archive_filepath,null,$resumable, $global->php_max_worker_time_in_sec,$server_load_delay,$upload_info->archive_offset);
                        }else{
                            $onedrive->uploadFileChunk($source_archive_filepath,$onedrive_archive_path,null, $global->php_max_worker_time_in_sec, $server_load_delay);
                        }

                        /* @var $onedrive_upload_info \Krizalys\Onedrive\ResumableUploader */
                        $onedrive_upload_info = $onedrive->RUploader;

                        $upload_info->data = $onedrive_upload_info->getUploadUrl();
                        $upload_info->data2 = $onedrive_upload_info->getExpirationTime();

                        if ($onedrive_upload_info->getError() == null) {

                            // Clear the failure count - we are just looking for consecutive errors
                            $upload_info->failure_count = 0;

                            $upload_info->archive_offset = $onedrive_upload_info->getUploadOffset();
                            $file_size             = filesize($source_archive_filepath);

                            $upload_info->progress = max(5, DUP_PRO_U::percentage($upload_info->archive_offset, $file_size, 0));
                            DUP_PRO_LOG::trace("progress from $upload_info->archive_offset and total file size $file_size = $upload_info->progress");

                            if ($onedrive_upload_info->completed()) {
                                DUP_PRO_LOG::trace("Successfully uploaded archive to OneDrive");
                                $upload_info->archive_offset = $file_size;
                                $upload_info->copied_archive = true;

                                $this->purge_old_onedrive_packages($onedrive);
                            }
                        } else {
                            DUP_PRO_LOG::traceError("Problem uploading archive for package $package->Name: ".$onedrive_upload_info->getError());

                            // Could have partially uploaded so retain that offset.
                            $upload_info->archive_offset = $onedrive_upload_info->getUploadOffset();
                            $upload_info->increase_failure_count();
                        }
                    } else {
                        DUP_PRO_LOG::trace("Already copied archive on previous execution of Onedrive $this->name so skipping");
                    }
                } catch (Exception $e) {
                    DUP_PRO_LOG::traceError("Problems copying package $package->Name to $this->onedrive_storage_folder. " . $e->getMessage());
                    $upload_info->increase_failure_count();
                }
            } else {
                DUP_PRO_LOG::traceError("Installer doesn't exist for $package->Name!? - $source_installer_filepath");
                $upload_info->failed = true;
            }
        } else {
            DUP_PRO_LOG::traceError("Archive doesn't exist for $package->Name!? - $source_archive_filepath");
            $upload_info->failed = true;
        }

        if ($upload_info->failed) {
            DUP_PRO_Log::info("Problem copying to Onedrive");
        }

        // The package update will automatically capture the upload_info since its part of the package
        $package->update();
    }

   private function copy_to_gdrive($package, $upload_info)
    {
        /* @var $upload_info DUP_PRO_Package_Upload_Info */

        /* @var $package DUP_PRO_Package */
        DUP_PRO_LOG::trace("Copying to Google Drive");

        $source_archive_filepath   = $package->Archive->getSafeFilePath();
        $source_installer_filepath = $package->Installer->get_safe_filepath();

        if (file_exists($source_archive_filepath)) {
            if (file_exists($source_installer_filepath)) {
                try {
                    /* @var $google_client Google_Client */
                    $google_client = $this->get_full_google_client();

                    if ($google_client == null) {
                        throw new Exception("Google client is null!");
                    }

                    if (empty($upload_info->data)) {
                        $google_service_drive = new Google_Service_Drive($google_client);

                        $upload_info->data = DUP_PRO_GDrive_U::get_directory_id($google_service_drive, $this->gdrive_storage_folder);

                        if ($upload_info->data == null) {
                            $upload_info->failed = true;
                            DUP_PRO_LOG::trace("Error getting/creating directory");
                            $package->update();
                            return;
                        }
                    }

                    $tried_copying_installer = false;

                    if (!$upload_info->copied_installer) {
                        $tried_copying_installer = true;
                        DUP_PRO_LOG::trace("Attempting Google Drive upload of $source_installer_filepath to $this->gdrive_storage_folder");

                        $google_service_drive = new Google_Service_Drive($google_client);

                        //$upload_info->data is the parent file id
                        $source_installer_filename = basename($source_installer_filepath);
                        $existing_file_id          = DUP_PRO_GDrive_U::get_file($google_service_drive, $source_installer_filename, $upload_info->data);

                        if ($existing_file_id != null) {
                            DUP_PRO_LOG::trace("Installer already exists so deleting $source_installer_filename before uploading again. Existing file id = $existing_file_id");
                            DUP_PRO_GDrive_U::delete_file($google_service_drive, $existing_file_id);
                        } else {
                            DUP_PRO_LOG::trace("Installer doesn't exist already so no need to delete $source_installer_filename");
                        }

                        if (DUP_PRO_GDrive_U::upload_file($google_client, $source_installer_filepath, $upload_info->data)) {
                            $upload_info->copied_installer = true;
                            $upload_info->progress         = 5;
                        } else {
                            $upload_info->failed = true;
                            DUP_PRO_LOG::trace("Error uploading file");
                        }

                        // The package update will automatically capture the upload_info since its part of the package
                        $package->update();
                    } else {
                        DUP_PRO_LOG::trace("Already copied installer on previous execution of Google Drive $this->name so skipping");
                    }

                    if ((!$upload_info->copied_archive) && (!$tried_copying_installer)) {
                        /* @var $global DUP_PRO_Global_Entity */
                        $global = DUP_PRO_Global_Entity::get_instance();

                        /* @var $dropbox_upload_info DUP_PRO_DropboxClient_UploadInfo */
                        $server_load_delay = 0;
                        if ($global->server_load_reduction != DUP_PRO_Server_Load_Reduction::None) {
                            $server_load_delay = DUP_PRO_Server_Load_Reduction::microseconds_from_reduction($global->server_load_reduction);
                        }

                        // Warning: Google client is set to defer mode within this functin
                        // The upload_id for google drive is just the resume uri
                        //

                        if ($upload_info->archive_offset == 0) {
                            // If just starting on this go ahead and delete existing file

                            $google_service_drive = new Google_Service_Drive($google_client);

                            //$upload_info->data is the parent file id
                            $source_archive_filename = basename($source_archive_filepath);
                            $existing_file_id        = DUP_PRO_GDrive_U::get_file($google_service_drive, $source_archive_filename, $upload_info->data);

                            if ($existing_file_id != null) {
                                DUP_PRO_LOG::trace("Archive already exists so deleting $source_archive_filename before uploading again");
                                DUP_PRO_GDrive_U::delete_file($google_service_drive, $existing_file_id);
                            } else {
                                DUP_PRO_LOG::trace("Archive doesn't exist so no need to delete $source_archive_filename");
                            }
                        }

                        // Google Drive worker time capped at 10 seconds
                        $gdrive_upload_info = DUP_PRO_GDrive_U::upload_file_chunk($google_client, $source_archive_filepath, $upload_info->data, $global->gdrive_upload_chunksize_in_kb * 1024,
                                10, $upload_info->archive_offset, $upload_info->upload_id, $server_load_delay);

                        if ($gdrive_upload_info->error_details == null) {
                            // Clear the failure count - we are just looking for consecutive errors
                            $upload_info->failure_count  = 0;
                            $upload_info->archive_offset = isset($gdrive_upload_info->next_offset) ? $gdrive_upload_info->next_offset : 0;

                            // We are considering the whole Resume URI as the Upload ID
                            $upload_info->upload_id = $gdrive_upload_info->resume_uri;

                            $file_size = filesize($source_archive_filepath);

                            $upload_info->progress = max(5, DUP_PRO_U::percentage($upload_info->archive_offset, $file_size, 0));

                            DUP_PRO_LOG::trace("progress from $upload_info->archive_offset and total file size $file_size = $upload_info->progress");

                            if ($gdrive_upload_info->is_complete) {
                                DUP_PRO_LOG::trace("Successfully uploaded archive to Google Drive");

                                $upload_info->copied_archive = true;

                                if ($this->gdrive_max_files > 0) {
                                    $this->purge_old_gdrive_packages($google_client, $upload_info);
                                }
                            }
                        } else {
                            DUP_PRO_LOG::traceError("Problem uploading archive for package $package->Name: $gdrive_upload_info->error_details");

                            // Could have partially uploaded so retain that offset.
                            $upload_info->archive_offset = isset($gdrive_upload_info->next_offset) ? $gdrive_upload_info->next_offset : 0;
                            $upload_info->increase_failure_count();
                        }
                    } else {
                        DUP_PRO_LOG::trace("Already copied archive on previous execution of Google Drive $this->name so skipping");
                    }
                } catch (Exception $e) {
                    DUP_PRO_LOG::traceError("Problems copying package $package->Name to $this->gdrive_storage_folder. " + $e->getMessage());
                    $upload_info->increase_failure_count();
                }
            } else {
                DUP_PRO_LOG::traceError("Installer doesn't exist for $package->Name!? - $source_installer_filepath");
                $upload_info->failed = true;
            }
        } else {
            DUP_PRO_LOG::traceError("Archive doesn't exist for $package->Name!? - $source_archive_filepath");
            $upload_info->failed = true;
        }

        if ($upload_info->failed) {
            DUP_PRO_Log::info("Problem copying to Google Drive");
        }

        // The package update will automatically capture the upload_info since its part of the package
        $package->update();
    }
    
    private function copy_to_s3($package, $upload_info)
    {
        /* @var $upload_info DUP_PRO_Package_Upload_Info */

        /* @var $package DUP_PRO_Package */
        DUP_PRO_LOG::trace("Copying to S3");

        $source_archive_filepath   = $package->Archive->getSafeFilePath();
        $source_installer_filepath = $package->Installer->get_safe_filepath();

        if (file_exists($source_archive_filepath)) {
            if (file_exists($source_installer_filepath)) {
                /* @var $s3_client Aws\S3\S3Client */
                $s3_client = $this->get_full_s3_client();

                try {
                    $tried_copying_installer = false;

                    if (!$upload_info->copied_installer) {
                        $tried_copying_installer = true;
                        DUP_PRO_LOG::trace("Attempting S3 upload of $source_installer_filepath to $this->s3_storage_folder");

                        $source_installer_filename = basename($source_installer_filepath);

                        if (DUP_PRO_S3_U::upload_file($s3_client, $this->s3_bucket, $source_installer_filepath, $this->s3_storage_folder, $this->s3_storage_class)) {
                            $upload_info->copied_installer = true;
                            $upload_info->progress         = 5;
                        } else {
                            $upload_info->failed = true;
                            DUP_PRO_LOG::trace("Error uploading file to S3");
                        }

                        // The package update will automatically capture the upload_info since its part of the package
                        $package->update();
                        return;
                    } else {
                        DUP_PRO_LOG::trace("Already copied installer on previous execution of S3 $this->name so skipping");
                    }

                    if ((!$upload_info->copied_archive) && (!$tried_copying_installer)) {
                        /* @var $global DUP_PRO_Global_Entity */
                        $global = DUP_PRO_Global_Entity::get_instance();

                        $server_load_delay = 0;
                        if ($global->server_load_reduction != DUP_PRO_Server_Load_Reduction::None) {
                            $server_load_delay = DUP_PRO_Server_Load_Reduction::microseconds_from_reduction($global->server_load_reduction);
                        }

                        // Data

                        /* @var $s3_upload_info DUP_PRO_S3_Client_UploadInfo */
                        $s3_upload_info = new DUP_PRO_S3_Client_UploadInfo();

                        $s3_upload_info->bucket         = $this->s3_bucket;
                        $s3_upload_info->upload_id      = $upload_info->upload_id;
                        $s3_upload_info->dest_directory = $this->s3_storage_folder;
                        $s3_upload_info->src_filepath   = $source_archive_filepath;
                        $s3_upload_info->next_offset    = $upload_info->archive_offset;
                        $s3_upload_info->storage_class  = $this->s3_storage_class;

                        // Storing array of [part] and [parts] in an array within data
                        if ($upload_info->data == '') {
                            $upload_info->data  = 1; // part number
                            $upload_info->data2 = array(); // parts array
                        }

                        $s3_upload_info->part_number      = $upload_info->data;
                        $s3_upload_info->parts            = $upload_info->data2;
                        $s3_upload_info->upload_part_size = $global->s3_upload_part_size_in_kb * 1024;

                        $s3_upload_info = DUP_PRO_S3_U::upload_file_chunk($s3_client, $s3_upload_info, $global->php_max_worker_time_in_sec, $server_load_delay);

                        if ($s3_upload_info->error_details == null) {
                            // Clear the failure count - we are just looking for consecutive errors
                            $upload_info->failure_count  = 0;
                            $upload_info->archive_offset = isset($s3_upload_info->next_offset) ? $s3_upload_info->next_offset : 0;

                            $upload_info->upload_id = $s3_upload_info->upload_id;
                            $upload_info->data      = $s3_upload_info->part_number;
                            $upload_info->data2     = $s3_upload_info->parts;

                            $file_size = filesize($source_archive_filepath);

                            $upload_info->progress = max(5, DUP_PRO_U::percentage($upload_info->archive_offset, $file_size, 0));

                            DUP_PRO_LOG::trace("progress from $upload_info->archive_offset and total file size $file_size = $upload_info->progress");

                            if ($s3_upload_info->is_complete) {
                                DUP_PRO_LOG::trace("Successfully uploaded archive to S3");

                                $upload_info->copied_archive = true;

                                if ($this->s3_max_files > 0) {
                                    $this->purge_old_s3_packages($s3_client);
                                }
                            }
                        } else {
                            DUP_PRO_LOG::traceError("Problem uploading archive for package $package->Name: $s3_upload_info->error_details");

                            // Could have partially uploaded so retain that offset.
                            $upload_info->archive_offset = isset($s3_upload_info->next_offset) ? $s3_upload_info->next_offset : 0;
                            $upload_info->increase_failure_count();
                        }
                    } else {
                        if ($upload_info->copied_archive) {
                            DUP_PRO_LOG::trace("Already copied archive on previous execution of S3 $this->name so skipping");
                        }
                    }
                } catch (Exception $e) {
                    DUP_PRO_LOG::traceError("Problems copying package $package->Name to $this->s3_storage_folder. " + $e->getMessage());
                    $upload_info->increase_failure_count();
                }
            } else {
                DUP_PRO_LOG::traceError("Installer doesn't exist for $package->Name!? - $source_installer_filepath");
                $upload_info->failed = true;
            }
        } else {
            DUP_PRO_LOG::traceError("Archive doesn't exist for $package->Name!? - $source_archive_filepath");
            $upload_info->failed = true;
        }

        if ($upload_info->failed) {
            DUP_PRO_Log::info("Problem copying to S3");
        }

        // The package update will automatically capture the upload_info since its part of the package

        $package->update();
    }

    public function get_storage_location_string()
    {
        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Dropbox:
                return "https://dropbox.com/home/Apps/Duplicator%20Pro/$this->dropbox_storage_folder";
                break;

            case DUP_PRO_Storage_Types::FTP:
                return "ftp://$this->ftp_server:$this->ftp_port/$this->ftp_storage_folder";
                break;

            case DUP_PRO_Storage_Types::SFTP:
                return $this->sftp_server.":".$this->sftp_port;
                break;

            case DUP_PRO_Storage_Types::GDrive:
                return "google://$this->gdrive_storage_folder";
                break;

            case DUP_PRO_Storage_Types::Local:
                return $this->local_storage_folder;
                break;

            case DUP_PRO_Storage_Types::S3:
                $region = str_replace(' ', '%20', $this->s3_region);
                $bucket = str_replace(' ', '%20', $this->s3_bucket);
                $prefix = str_replace(' ', '%20', $this->s3_storage_folder);

                //return "<a target=\"_blank\" href=\"https://console.aws.amazon.com/s3/home?region=$region&bucket=$bucket&prefix=$prefix\">s3://$bucket/$prefix</a>";
                //return "s3://$bucket/{$this->s3_storage_folder}";
                return "<a target=\"_blank\" href=\"https://console.aws.amazon.com/s3/home?region=$region&bucket=$bucket&prefix=$prefix\">s3://$this->s3_bucket/$this->s3_storage_folder</a>";
                break;
            case DUP_PRO_Storage_Types::OneDrive:
                $url = $this->get_onedrive_storage_folder()->getWebURL();

                return $url;
        }

        return DUP_PRO_U::__('Unknown');
    }

    private function copy_to_ftp($package, $upload_info)
    {
        /* @var $upload_info DUP_PRO_Package_Upload_Info */

        /* @var $package DUP_PRO_Package */
        DUP_PRO_LOG::trace("copying to ftp");

        $source_archive_filepath = $package->Archive->getSafeFilePath();
        // $source_archive_filepath = DUP_PRO_U::$PLUGIN_DIRECTORY . '/lib/DropPHP/Poedit-1.6.4.2601-setup.bin';

        $source_installer_filepath = $package->Installer->get_safe_filepath();

        if (file_exists($source_archive_filepath)) {
            if (file_exists($source_installer_filepath)) {
                /* @var $ftp_client DUP_PRO_FTP_Chunker */
                $ftp_client = new DUP_PRO_FTP_Chunker($this->ftp_server, $this->ftp_port, $this->ftp_username, $this->ftp_password, $this->ftp_timeout_in_secs, $this->ftp_ssl,
                    $this->ftp_passive_mode);

                if ($ftp_client->open()) {
                    if ($ftp_client->create_directory($this->ftp_storage_folder) == false) {
                        DUP_PRO_LOG::trace("Couldn't create $this->ftp_storage_folder on $this->ftp_server");
                    }

                    try {
                        if ($upload_info->copied_installer == false) {
                            DUP_PRO_LOG::trace("attempting FTP upload of $source_installer_filepath to $this->ftp_storage_folder");

                            if ($ftp_client->upload_file($source_installer_filepath, $this->ftp_storage_folder) == false) {
                                $upload_info->failed = true;

                                DUP_PRO_LOG::trace(sprintf(__('Error uploading %1$s to %2$s'), $source_installer_filepath, $this->ftp_storage_folder));
                            } else {
                                DUP_PRO_LOG::trace("FTP upload of $source_installer_filepath to $this->ftp_storage_folder succeeded");
                                $upload_info->copied_installer = true;
                                $upload_info->progress         = 5;
                            }

                            // The package update will automatically capture the upload_info since its part of the package
                            $package->update();
                        } else {
                            DUP_PRO_LOG::trace("Already copied installer on previous execution of FTP $this->name so skipping");
                        }

                        if ($upload_info->copied_archive == false) {
                            /* @var $global DUP_PRO_Global_Entity */
                            $global = DUP_PRO_Global_Entity::get_instance();

                            $server_load_delay = 0;
                            if ($global->server_load_reduction != DUP_PRO_Server_Load_Reduction::None) {
                                $server_load_delay = DUP_PRO_Server_Load_Reduction::microseconds_from_reduction($global->server_load_reduction);
                            }

                            /* @var $ftp_upload_info DUP_PRO_FTP_UploadInfo */
                            DUP_PRO_LOG::trace("archive calling upload chunk with timeout");
                            $ftp_upload_info = $ftp_client->upload_chunk($source_archive_filepath, $this->ftp_storage_folder, $global->php_max_worker_time_in_sec,
                                $upload_info->archive_offset, $server_load_delay);

                            DUP_PRO_LOG::trace("after upload chunk archive");
                            if ($ftp_upload_info->error_details == null) {
                                // Since there was a successful chunk reset the failure count
                                $upload_info->failure_count  = 0;
                                $upload_info->archive_offset = isset($ftp_upload_info->next_offset) ? $ftp_upload_info->next_offset : 0;


                                $file_size             = filesize($source_archive_filepath);
                                //  $upload_info->progress = max(5, 100 * (bcdiv($upload_info->archive_offset, $file_size, 2)));
                                $upload_info->progress = max(5, DUP_PRO_U::percentage($upload_info->archive_offset, $file_size, 0));

                                DUP_PRO_LOG::trace("progress from $upload_info->archive_offset and total file size $file_size = $upload_info->progress");

                                if ($ftp_upload_info->success) {
                                    DUP_PRO_LOG::trace("Successfully ftp uploaded archive to $this->ftp_server");
                                    $upload_info->copied_archive = true;

                                    if ($this->ftp_max_files > 0) {
                                        $this->purge_old_ftp_packages($ftp_client);
                                    }

                                    $package->update();
                                } else {
                                    // Need to quit all together b/c ftp connection stays open
                                    DUP_PRO_LOG::trace("Exiting process since ftp partial");

                                    // A real hack since the ftp_close doesn't work on the async put
                                    $package->update();

                                    // Kick the worker again
                                    // DUP_PRO_Package_Runner::kick_off_worker();
                                    DUP_PRO_Package_Runner::$delayed_exit_and_kickoff = true;

                                    //exit();
                                    return;
                                }
                            } else {
                                DUP_PRO_LOG::traceError("Problem uploading archive for package $package->Name: $ftp_upload_info->error_details");

                                if ($ftp_upload_info->fatal_error) {
                                    $installer_filename     = basename($source_installer_filepath);
                                    $installer_ftp_filepath = "{$this->ftp_storage_folder}/$installer_filename";

                                    DUP_PRO_LOG::trace("Failed archive transfer so deleting $installer_ftp_filepath");
                                    $ftp_client->delete($installer_ftp_filepath);

                                    $upload_info->failed = true;
                                } else {
                                    $upload_info->archive_offset = isset($ftp_upload_info->next_offset) ? $ftp_upload_info->next_offset : 0;
                                    $upload_info->increase_failure_count();
                                }
                            }
                        } else {
                            DUP_PRO_LOG::trace("Already copied archive on previous execution of FTP $this->name so skipping");
                        }
                    } catch (Exception $e) {
                        $upload_info->increase_failure_count();
                        DUP_PRO_LOG::traceError("Problems copying package $package->Name to $this->ftp_storage_folder. " + $e->getMessage());
                    }

                    $ftp_client->close();
                } else {
                    $upload_info->increase_failure_count();
                    DUP_PRO_LOG::traceError("Couldn't open ftp connection ".$ftp_client->get_info());
                }
            } else {
                DUP_PRO_LOG::traceError("Installer doesn't exist for $package->Name!? - $source_installer_filepath");
                $upload_info->failed = true;
            }
        } else {
            DUP_PRO_LOG::traceError("Archive doesn't exist for $package->Name!? - $source_archive_filepath");
            $upload_info->failed = true;
        }

        // The package update will automatically capture the upload_info since its part of the package
        $package->update();
    }

    private function copy_to_sftp($package, $upload_info)
    {
        DUP_PRO_LOG::trace("copying to sftp");
        $source_archive_filepath = $package->Archive->getSafeFilePath();
        $source_installer_filepath = $package->Installer->get_safe_filepath();

        if (file_exists($source_archive_filepath)) {
            if (file_exists($source_installer_filepath)) {
                try {
                    $storage_folder         = $this->sftp_storage_folder;
                    $server                 = $this->sftp_server;
                    $port                   = $this->sftp_port;
                    $username               = $this->sftp_username;
                    $password               = $this->sftp_password;
                    $private_key            = $this->sftp_private_key;
                    $private_key_password   = $this->sftp_private_key_password;

                    if (DUP_PRO_STR::startsWith($storage_folder, '/') == false) {
                        $storage_folder = '/'.$storage_folder;
                    }

                    if (DUP_PRO_STR::endsWith($storage_folder, '/') == false) {
                        $storage_folder = $storage_folder.'/';
                    }

                    $dup_phpseclib = new DUP_PRO_PHPSECLIB();
                    $sftp = $dup_phpseclib->connect_sftp_server($server, $port, $username, $password, $private_key, $private_key_password);

                    if($sftp) {
                        if(!$sftp->file_exists($storage_folder)) {
                            $dup_phpseclib->mkdir_recursive($storage_folder,$sftp);
                        }

                        if ($upload_info->copied_installer == false) {
                            $source_filepath = $source_installer_filepath;
                            $basename = basename($source_filepath);
                            if($sftp->put($storage_folder.$basename, $source_filepath, $dup_phpseclib->source_local_files|$dup_phpseclib->sftp_resume)){
                                $upload_info->progress    = 5;
                                $upload_info->copied_installer = true;
                            }else{
                                $upload_info->failed = true;
                                DUP_PRO_LOG::trace(sprintf(__('Error uploading %1$s to %2$s'), $source_installer_filepath, $this->sftp_storage_folder));
                            }
                            // The package update will automatically capture the upload_info since its part of the package
                            $package->update();
                        }else{
                            DUP_PRO_LOG::trace("Already copied installer on previous execution of SFTP $this->name so skipping");
                        }

                        if ($upload_info->copied_archive == false) {

                            /* @var $global DUP_PRO_Global_Entity */
                            $global = DUP_PRO_Global_Entity::get_instance();

                            //Make sure time threshold not exceed the server maximum execution time
                            $time_threshold = $global->php_max_worker_time_in_sec;
                            if( $this->sftp_timeout_in_secs < $time_threshold ) {
                                $time_threshold = $this->sftp_timeout_in_secs;
                            }

                            $source_filepath = $source_archive_filepath;
                            $basename = basename($source_filepath);
                            if($sftp->put($storage_folder.$basename, $source_filepath, $dup_phpseclib->source_local_files|$dup_phpseclib->sftp_resume,$time_threshold)){
                                $file_size  = filesize($source_filepath);
                                $sftp_data_sent = $sftp->filesize($storage_folder.$basename);
                                $upload_info->progress = max(5, DUP_PRO_U::percentage($sftp_data_sent, $file_size, 0));
                                if($upload_info->progress >= 100) {
                                    $upload_info->copied_archive = true;
                                    if ($this->sftp_max_files > 0) {
                                        $this->purge_old_sftp_packages();
                                    }
                                }
                            }else{
                                $upload_info->failed = true;
                                DUP_PRO_LOG::traceError("Problem uploading archive for package $package->Name");
                            }
                            // The package update will automatically capture the upload_info since its part of the package
                            $package->update();
                        }else{
                            DUP_PRO_LOG::trace("Already copied archive on previous execution of SFTP $this->name so skipping");
                        }

                        if( $upload_info->failed ) {
                            $source_filepath = $source_archive_filepath;
                            $basename = basename($source_filepath);
                            $sftp->delete($storage_folder.$basename);

                            $source_filepath = $source_installer_filepath;
                            $basename = basename($source_filepath);
                            $sftp->delete($storage_folder.$basename);
                        }
                    }
                } catch (Exception $e) {
                    $upload_info->failed = true;
                    $upload_info->increase_failure_count();
                    DUP_PRO_LOG::traceError("Problems copying package $package->Name to $this->sftp_storage_folder. " + $e->getMessage());
                }
            }else{
                DUP_PRO_LOG::traceError("Installer doesn't exist for $package->Name!? - $source_installer_filepath");
                $upload_info->failed = true;
            }
        }else{
            DUP_PRO_LOG::traceError("Archive doesn't exist for $package->Name!? - $source_archive_filepath");
            $upload_info->failed = true;
        }

        // The package update will automatically capture the upload_info since its part of the package
        $package->update();
    }

    public function dropbox_compare_file_dates($a, $b)
    {
        $a_ts = strtotime($a->modified);
        $b_ts = strtotime($b->modified);

        if ($a_ts == $b_ts) {
            return 0;
        }

        return ($a_ts < $b_ts) ? -1 : 1;
    }

    public static function s3_compare_file_dates($array_a, $array_b)
    {
        $a_ts = strtotime($array_a['LastModified']);
        $b_ts = strtotime($array_b['LastModified']);

        if ($a_ts == $b_ts) {
            return 0;
        }

        return ($a_ts < $b_ts) ? -1 : 1;
    }

	public static function onedrive_compare_file_dates($a, $b)
	{
		$act = (int)$a['created_time'];
		$bct = (int)$b['created_time'];

		if($act == $bct){
			return 0;
		}

		return ($act < $bct ? -1 : 1);
	}


    function newest_local_file($a, $b)
    {
        return filemtime($a) - filemtime($b);
    }

    public function purge_old_local_packages()
    {
        try {
			
			function DUP_PRO_Storage_Entity_purge_old_local_packages_custom_sort($a, $b) {
				return filemtime($a)>filemtime($b);
			}
			
            $global = DUP_PRO_Global_Entity::get_instance();

            if ($this->local_max_files > 0) {

				$file_list = glob("{$this->local_storage_folder}/*"); // put all files in an array
                usort($file_list, 'DUP_PRO_Storage_Entity_purge_old_local_packages_custom_sort');

                $archive_filenames = array();

                foreach ($file_list as $file_path) {
                    if (DUP_PRO_STR::endsWith($file_path, '_archive.zip') || DUP_PRO_STR::endsWith($file_path, '_archive.daf')) {
                        DUP_PRO_LOG::trace("pushing $file_path");
                        array_push($archive_filenames, $file_path);
                    }
                }

                $index = 0;

                $num_archives     = count($archive_filenames);
                $num_archives_to_delete = $num_archives - $this->local_max_files;

                DUP_PRO_LOG::trace("Num zip files to delete=$num_archives_to_delete");

                while ($index < $num_archives_to_delete) {
                    $archive_filepath = $archive_filenames[$index];

                    if(DUP_PRO_STR::endsWith($archive_filepath, '_archive.zip')) {
                        $suffix = '_archive.zip';
                    } else {
                        $suffix = '_archive.daf';
                    }
                    
                    // Matching installer has to be present for us to delete
                    $installer_filepath = str_replace($suffix, "_{$global->installer_base_name}", $archive_filepath);

                    DUP_PRO_LOG::trace("$installer_filepath in array so deleting installer and archive");
                                   
                    $sql_filepath  = str_replace($suffix, '_database.sql', $archive_filepath);
                    $json_filepath = str_replace($suffix, '_scan.json', $archive_filepath);
                    $log_filepath  = str_replace($suffix, '.log', $archive_filepath);
                    
                    @unlink($installer_filepath);
                    @unlink($archive_filepath);
                    @unlink($sql_filepath);
                    @unlink($json_filepath);
                    @unlink($log_filepath);
                    $index++;
                }
            }
        } catch (Exception $e) {
            DUP_PRO_LOG::traceError("Error purging local packages ".$e->getMessage());
        }
    }

    public function purge_old_dropbox_packages($dropbox)
    {
        try {
            $global    = DUP_PRO_Global_Entity::get_instance();
            $file_list = $dropbox->GetFiles($this->dropbox_storage_folder);

            usort($file_list, array('DUP_PRO_Storage_Entity', 'dropbox_compare_file_dates'));

            $php_filenames = array();
            $archive_filenames = array();

            foreach ($file_list as $file_metadata) {

                if (DUP_PRO_STR::endsWith($file_metadata->file_path, "_{$global->installer_base_name}")) {
                    array_push($php_filenames, $file_metadata);
                }
                if (DUP_PRO_STR::endsWith($file_metadata->file_path, '_archive.zip') || DUP_PRO_STR::endsWith($file_metadata->file_path, '_archive.daf')) {
                    array_push($archive_filenames, $file_metadata);
                }
            }

            if ($this->dropbox_max_files > 0) {
                $num_php_files     = count($php_filenames);
                $num_php_to_delete = $num_php_files - $this->dropbox_max_files;
                $index             = 0;

                DUP_PRO_LOG::trace("Num php files to delete=$num_php_to_delete");

                while ($index < $num_php_to_delete) {
                    $dropbox->Delete($php_filenames[$index]->file_path);
                    $index++;
                }

                $index = 0;

                $num_archives     = count($archive_filenames);
                $num_archives_to_delete = $num_archives - $this->dropbox_max_files;

                DUP_PRO_LOG::trace("Num archives to delete=$num_archives_to_delete");

                while ($index < $num_archives_to_delete) {
                    $dropbox->Delete($archive_filenames[$index]->file_path);
                    $index++;
                }
            }
        } catch (Exception $e) {
            DUP_PRO_LOG::traceError("Error purging Dropbox packages ".$e->getMessage());
        }
    }

    public function purge_old_gdrive_packages($google_client, $upload_info)
    {
        if ($this->gdrive_max_files > 0) {
            $global               = DUP_PRO_Global_Entity::get_instance();
            $directory_id         = $upload_info->data;
            $google_service_drive = new Google_Service_Drive($google_client);

            $file_list = DUP_PRO_GDrive_U::get_files_in_directory($google_service_drive, $directory_id);

            if ($file_list != null) {
                $php_files = array();
                $archive_filenames = array();

                foreach ($file_list as $drive_file) {
                    $file_title = $drive_file->getName();

                    $parts = pathinfo($file_title);

                    $extension = $parts['extension'];

                    if (DUP_PRO_STR::endsWith($file_title, "_{$global->installer_base_name}")) {
                        array_push($php_files, $drive_file);
                    }

                    if (DUP_PRO_STR::endsWith($file_title, '_archive.zip') || DUP_PRO_STR::endsWith($file_title, '_archive.daf')) {
                        array_push($archive_filenames, $drive_file);
                    }
                }

                $index = 0;

                $num_archives     = count($archive_filenames);
                $num_archives_to_delete = $num_archives - $this->gdrive_max_files;

                DUP_PRO_LOG::trace("Num zip files to delete=$num_archives_to_delete since there are $num_archives on the drive and max files={$this->gdrive_max_files}");

                while ($index < $num_archives_to_delete) {
                    $archive_file = $archive_filenames[$index];

                    $archive_title   = $archive_file->getName();
                    // Matching installer has to be present for us to delete
                    if(DUP_PRO_STR::endsWith($archive_title, '_archive.zip')) {
                        $installer_title = str_replace('_archive.zip', "_{$global->installer_base_name}", $archive_title);
                    } else {
                        $installer_title = str_replace('_archive.daf', "_{$global->installer_base_name}", $archive_title);
                    }

                    // Now get equivalent installer
                    foreach ($php_files as $installer_file) {
                        /* @var $installer_file Google_Service_Drive_DriveFile */

                        if ($installer_title == $installer_file->getName()) {
                            DUP_PRO_LOG::trace("Attempting to delete $installer_title from Google Drive");

                            if (DUP_PRO_GDrive_U::delete_file($google_service_drive, $installer_file->getid()) == false) {
                                DUP_PRO_LOG::trace("Error purging old Google Drive file $installer_title");
                            }

                            DUP_PRO_LOG::trace("Attempting to delete $archive_title from Google Drive");
                            if (DUP_PRO_GDrive_U::delete_file($google_service_drive, $archive_file->getid()) == false) {
                                DUP_PRO_LOG::trace("Error purging old Google Drive file $archive_title");
                            }
                            break;
                        }
                    }

                    $index++;
                }
            } else {
                $message = "ERROR: Couldn't retrieve file list from Google Drive so can purge old packages";

                DUP_PRO_LOG::trace($message);
                $upload_info->failed = true;
            }
        }
    }

    public function purge_old_s3_packages($s3_client)
    {
        /* @var $s3_client Aws\S3\S3Client */
        try {
            $global = DUP_PRO_Global_Entity::get_instance();

            $return_value = $s3_client->listObjects(array(
                'Bucket' => $this->s3_bucket,
                'Delimiter' => '/',
                'Prefix' => trim($this->s3_storage_folder, '/').'/'
            ));



            $s3_objects = $return_value['Contents'];

            usort($s3_objects, array('DUP_PRO_Storage_Entity', 's3_compare_file_dates'));

            $php_files = array();
            $archive_filenames = array();

            foreach ($s3_objects as $s3_object) {
                $filename = basename($s3_object['Key']);

                if (DUP_PRO_STR::endsWith($filename, "_{$global->installer_base_name}")) {
                    array_push($php_files, $s3_object['Key']);
                }
                if (DUP_PRO_STR::endsWith($filename, '_archive.zip') || DUP_PRO_STR::endsWith($filename, '_archive.daf')) {
                    array_push($archive_filenames, $s3_object['Key']);
                }
            }

            DUP_PRO_LOG::traceObject("php files", $php_files);

            DUP_PRO_LOG::traceObject("archives", $archive_filenames);

            if ($this->s3_max_files > 0) {
                $num_php_files     = count($php_files);
                $num_php_to_delete = $num_php_files - $this->s3_max_files;
                $index             = 0;

                DUP_PRO_LOG::trace("Num php files to delete=$num_php_to_delete");

                while ($index < $num_php_to_delete) {
                    DUP_PRO_LOG::trace("Deleting {$php_files[$index]}");
                    $s3_client->deleteObject(array(
                        'Bucket' => $this->s3_bucket,
                        'Key' => $php_files[$index]
                    ));

                    DUP_PRO_LOG::trace("Deleted {$php_files[$index]}");

                    $index++;
                }

                $index = 0;

                $num_archives     = count($archive_filenames);
                $num_archives_to_delete = $num_archives - $this->s3_max_files;

                DUP_PRO_LOG::trace("Num archives to delete=$num_archives_to_delete");

                while ($index < $num_archives_to_delete) {
                    DUP_PRO_LOG::trace("Deleting {$archive_filenames[$index]}");

                    $s3_client->deleteObject(array(
                        'Bucket' => $this->s3_bucket,
                        'Key' => $archive_filenames[$index]
                    ));
                    DUP_PRO_LOG::trace("Deleting {$archive_filenames[$index]}");
                    $index++;
                }
            }
        } catch (Exception $e) {
            DUP_PRO_LOG::traceError("Error purging S3 packages ".$e->getMessage());
        }
    }

    private static function get_timestamp_from_filename($filename)
    {
        $retval = false;
        $global = DUP_PRO_Global_Entity::get_instance();

        if ((DUP_PRO_STR::endsWith($filename, "_{$global->installer_base_name}")) || (DUP_PRO_STR::endsWith($filename, '_archive.zip')) || (DUP_PRO_STR::endsWith($filename, '_archive.daf'))) {
            $pieces      = explode('_', $filename);
            $piece_count = count($pieces);
            if ($piece_count >= 4) {
                $numeric_index = count($pieces) - 2; // Right before the _installer or _archive
                if (is_numeric($pieces[$numeric_index])) {
                    $retval = (float)$pieces[$numeric_index];
                } else {
                    DUP_PRO_LOG::trace("Problem parsing file $filename when doing a comparison for ftp purge. Non-numeric timestamp");
                    $retval = false;
                }
            } else {
                DUP_PRO_LOG::trace("Problem parsing file $filename when doing a comparison for ftp purge");
                $retval = false;
            }
        } else {
            $retval = false;
        }

        return $retval;
    }

    public static function compare_package_filenames_by_date($filename_a, $filename_b)
    {
        $ret_val = 0;

        // Should be in the format uniqueid_2digityear
        $a_timestamp = self::get_timestamp_from_filename($filename_a);
        $b_timestamp = self::get_timestamp_from_filename($filename_b);

        DUP_PRO_LOG::trace("comparing a:$a_timestamp to b:$b_timestamp");
        if ($a_timestamp !== false) {
            if ($b_timestamp === false) {
                self::$sort_error = true;

                // b isn't valid timestamp wile a is valid so make b larger
                $ret_val = -1;
            } else {
                if ($a_timestamp > $b_timestamp) {
                    $ret_val = 1;
                } else if ($a_timestamp < $b_timestamp) {
                    $ret_val = -1;
                } else {
                    $ret_val = 0;
                }
            }
        } else {
            self::$sort_error = true;

            if ($b_timestamp === false) {
                // Both invalid so say equal
                $ret_val = 0;
            } else {
                // a isn't valid timestamp wile b is valid so make a larger
                $ret_val = 1;
            }
        }

        return $ret_val;
    }

    public function purge_old_sftp_packages()
    {

        try {
            $server                 = $this->sftp_server;
            $port                   = $this->sftp_port;
            $username               = $this->sftp_username;
            $password               = $this->sftp_password;
            $private_key            = $this->sftp_private_key;
            $private_key_password   = $this->sftp_private_key_password;

            if (DUP_PRO_STR::startsWith($storage_folder, '/') == false) {
                $storage_folder = '/'.$storage_folder;
            }

            if (DUP_PRO_STR::endsWith($storage_folder, '/') == false) {
                $storage_folder = $storage_folder.'/';
            }

            $dup_phpseclib = new DUP_PRO_PHPSECLIB();
            $sftp = $dup_phpseclib->connect_sftp_server($server, $port, $username, $password, $private_key, $private_key_password);
        } catch (Exception $e) {
            DUP_PRO_LOG::traceError("Problems making SFTP connection " + $e->getMessage());
        }

        if($sftp) {
            $storage_folder         = $this->sftp_storage_folder;
            if (DUP_PRO_STR::startsWith($storage_folder, '/') == false) {
                $storage_folder = '/'.$storage_folder;
            }
            if (DUP_PRO_STR::endsWith($storage_folder, '/') == false) {
                $storage_folder = $storage_folder.'/';
            }
            $global    = DUP_PRO_Global_Entity::get_instance();
            $file_list = $sftp->nlist($storage_folder);
            $file_list = array_diff($file_list, array(".", ".."));

            if(empty($file_list)) {
                DUP_PRO_LOG::traceError("Error retrieving file list for ".$this->sftp_server.":".$this->sftp_port." Storage Dir: ".$this->sftp_storage_folder );
            } else {

                self::$sort_error = false;

                $valid_file_list = array();

                foreach ($file_list as $file_name)
                {
                    DUP_PRO_LOG::trace("considering filename {$file_name}");
                    if(self::get_timestamp_from_filename($file_name) !== false)
                    {
                        $valid_file_list[] = $file_name;
                    }
                }

                DUP_PRO_LOG::traceObject('valid file list', $valid_file_list);

                // Sort list by the timestamp associated with it
                usort($valid_file_list, array('DUP_PRO_Storage_Entity', 'compare_package_filenames_by_date'));

                if(self::$sort_error === false)
                {
                    $php_files = array();
                    $archive_filepaths = array();

                    foreach ($valid_file_list as $file_name) {
                        $parts     = pathinfo($file_name);
                        $extension = $parts['extension'];
                        $file_path = "$this->sftp_storage_folder/$file_name";

                        // just look for the archives and delete only if has matching _installer
                        if (DUP_PRO_STR::endsWith($file_path, "_{$global->installer_base_name}")) {
                            array_push($php_files, $file_path);
                        }

                        if (DUP_PRO_STR::endsWith($file_path, '_archive.zip') || DUP_PRO_STR::endsWith($file_path, '_archive.daf')) {
                            array_push($archive_filepaths, $file_path);
                        }
                    }

                    if ($this->sftp_max_files > 0) {
                        $index             = 0;
                        $num_archives     = count($archive_filepaths);
                        $num_archives_to_delete = $num_archives - $this->sftp_max_files;

                        DUP_PRO_LOG::trace("Num archives to delete=$num_archives_to_delete");

                        while ($index < $num_archives_to_delete) {
                            $archive_filepath = $archive_filepaths[$index];

                            // Matching installer has to be present for us to delete
                            if(DUP_PRO_STR::endsWith($archive_filepath, '_archive.zip')) {
                                $installer_filepath = str_replace('_archive.zip', "_{$global->installer_base_name}", $archive_filepath);
                            } else {
                                $installer_filepath = str_replace('_archive.daf', "_{$global->installer_base_name}", $archive_filepath);
                            }

                            if (in_array($installer_filepath, $php_files)) {
                                DUP_PRO_LOG::trace("$installer_filepath in array so deleting installer and archive");
                                $sftp->delete($installer_filepath);
                                $sftp->delete($archive_filepath);
                            } else {
                                DUP_PRO_LOG::trace("$installer_filepath not in array so NOT deleting");
                            }

                            $index++;
                        }
                    }
                }
                else {
                    DUP_PRO_LOG::trace("Sort error when attempting to purge old FTP files");
                }
            }
        }else{
            DUP_PRO_LOG::traceError("Problems making SFTP connection, Purging old packages not possible.");
        }
    }

    public function purge_old_ftp_packages($ftp_client)
    {
        $global    = DUP_PRO_Global_Entity::get_instance();
        $file_list = $ftp_client->get_filelist($this->ftp_storage_folder);

        if ($file_list == false) {
            DUP_PRO_LOG::traceError("Error retrieving file list for ".$ftp_client->get_info());
        } else {
            self::$sort_error = false;

            $valid_file_list = array();

            foreach ($file_list as $file_name)
            {
                DUP_PRO_LOG::trace("considering filename {$file_name}");
                if(self::get_timestamp_from_filename($file_name) !== false)
                {                    
                    $valid_file_list[] = $file_name;
                }
            }

            DUP_PRO_LOG::traceObject('valid file list', $valid_file_list);
            
            // Sort list by the timestamp associated with it
            usort($valid_file_list, array('DUP_PRO_Storage_Entity', 'compare_package_filenames_by_date'));

            if(self::$sort_error === false)
            {
                $php_files = array();
                $archive_filepaths = array();

                foreach ($valid_file_list as $file_name) {
                    $parts     = pathinfo($file_name);
                    $extension = $parts['extension'];
                    $file_path = "$this->ftp_storage_folder/$file_name";

                    // just look for the archives and delete only if has matching _installer
                    if (DUP_PRO_STR::endsWith($file_path, "_{$global->installer_base_name}")) {
                        array_push($php_files, $file_path);
                    }

                    if (DUP_PRO_STR::endsWith($file_path, '_archive.zip') || DUP_PRO_STR::endsWith($file_path, '_archive.daf')) {
                        array_push($archive_filepaths, $file_path);
                    }
                }

                if ($this->ftp_max_files > 0) {
                    $index             = 0;
                    $num_archives     = count($archive_filepaths);
                    $num_archives_to_delete = $num_archives - $this->ftp_max_files;

                    DUP_PRO_LOG::trace("Num archives to delete=$num_archives_to_delete");

                    while ($index < $num_archives_to_delete) {
                        $archive_filepath = $archive_filepaths[$index];

                        // Matching installer has to be present for us to delete
                        if(DUP_PRO_STR::endsWith($archive_filepath, '_archive.zip')) {
                            $installer_filepath = str_replace('_archive.zip', "_{$global->installer_base_name}", $archive_filepath);
                        } else {
                            $installer_filepath = str_replace('_archive.daf', "_{$global->installer_base_name}", $archive_filepath);
                        }

                        if (in_array($installer_filepath, $php_files)) {
                            DUP_PRO_LOG::trace("$installer_filepath in array so deleting installer and archive");
                            $ftp_client->delete($installer_filepath);
                            $ftp_client->delete($archive_filepath);
                        } else {
                            DUP_PRO_LOG::trace("$installer_filepath not in array so NOT deleting");
                        }

                        $index++;
                    }
                }
            }
            else {
                DUP_PRO_LOG::trace("Sort error when attempting to purge old FTP files");
            }
        }
    }

    private static function get_ak1()
    {
        return strrev('i6gh72iv');
    }

    private static function get_ak2()
    {
        return strrev('1xgkhw2');
    }

    private static function get_as1()
    {
        return strrev('z7fl2twoo');
    }

    private static function get_as2()
    {
        return strrev('2z2bfm');
    }

    public function get_storage_type_string()
    {
        switch ($this->storage_type) {
            case DUP_PRO_Storage_Types::Dropbox:
                return DUP_PRO_U::__('Dropbox');

            case DUP_PRO_Storage_Types::FTP:
                return DUP_PRO_U::__('FTP');

            case DUP_PRO_Storage_Types::SFTP:
                return DUP_PRO_U::__('SFTP');

            case DUP_PRO_Storage_Types::GDrive:
                return DUP_PRO_U::__('Google Drive');

            case DUP_PRO_Storage_Types::Local:
                return DUP_PRO_U::__('Local');

            case DUP_PRO_Storage_Types::S3:
                return DUP_PRO_U::__('Amazon S3');
            case DUP_PRO_Storage_Types::OneDrive:
                return !$this->onedrive_is_business() ? DUP_PRO_U::__('OneDrive') : DUP_PRO_U::__('OneDrive (B)');

            default:
                return DUP_PRO_U::__('Unknown');
        }
    }

    public function save()
    {
        if (DUP_PRO_STR::startsWith($this->ftp_storage_folder, '/') == false) {
            $this->ftp_storage_folder = '/'.$this->ftp_storage_folder;
        }
        
        if (DUP_PRO_STR::startsWith($this->sftp_storage_folder, '/') == false) {
            $this->sftp_storage_folder = '/'.$this->sftp_storage_folder;
        }

        $this->encrypt();

        parent::save();

        $this->decrypt();   // Whenever its in memory its unencrypted
    }

    // Get a list of the permanent entries
    public static function get_default_local_storage()
    {
        /* @var $global DUP_PRO_Global_Entity */
        $global = DUP_PRO_Global_Entity::get_instance();

        $default_local_storage = new DUP_PRO_Storage_Entity();

        $default_local_storage->name                 = DUP_PRO_U::__('Default');
        $default_local_storage->notes                = DUP_PRO_U::__('The default location for storage on this server.');
        $default_local_storage->id                   = DUP_PRO_Virtual_Storage_IDs::Default_Local;
        $default_local_storage->storage_type         = DUP_PRO_Storage_Types::Local;
        $default_local_storage->local_storage_folder = DUPLICATOR_PRO_SSDIR_PATH;
        $default_local_storage->local_max_files      = $global->max_default_store_files;
        $default_local_storage->editable             = false;

        return $default_local_storage;
    }
}
