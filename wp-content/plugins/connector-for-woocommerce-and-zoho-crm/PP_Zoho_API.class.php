<?php
/**
 * Author: Potent Plugins
 * License: GNU General Public License version 3 or later
 */
 
// This file includes code from the Zoho CRM PHP SDK, copyright Zoho Corporation Pvt. Ltd., used by permission. Code taken from vendor/zohocrm/php-sdk/README.md and/or other files
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
class PP_Zoho_API {
	
	private static $apiUrl;
	
	function __construct($authToken=null) {
		include_once(__DIR__.'/vendor/autoload.php');
		
		$configuration = array(
			"client_id"=>get_option('pp_wczc_zoho_client_id'),
			"client_secret"=>get_option('pp_wczc_zoho_client_secret'),
			"redirect_uri"=>admin_url('admin.php?page=pp_wczc'),
			"currentUserEmail"=>get_option('pp_wczc_zoho_email'),
			'persistence_handler_class' => __DIR__.'/PP_WCZC_ZohoOAuthPersistenceHandler.php',
			'persistence_handler_class_name' => 'PP_WCZC_ZohoOAuthPersistenceHandler',
			'apiBaseUrl' => self::getRegionalUrl("www.zohoapis.com") //woocommerce-and-zoho-crm-connector-pro\vendor\zohocrm\php-sdk\src\crm\utility\ZCRMConfigUtil.php
		);
		
		try {
			ZCRMRestClient::initialize($configuration);
		} catch (Exception $ex){};
		
	}
	
	public static function getRegionalUrl($url) {
		$altDomains = array(
			'zoho.com.au',
			'zoho.eu',
			'zoho.in',
			'zoho.com.cn'
		);
		
		$host = get_option('pp_wczc_zoho_host', 'https://crm.zoho.com');
		
		foreach ($altDomains as $domain) {
			if ( strpos($host, $domain) ) {
				return str_replace('zoho.com', $domain, $url);
			}
		}
		
		return $url;
	}
	
	public static function getAuthUrl() {
		return esc_url(
				self::getRegionalUrl('https://accounts.zoho.com/oauth/v2/auth?scope=ZohoCRM.users.ALL,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL&client_id=')
				.get_option('pp_wczc_zoho_client_id')
				.'&response_type=code&access_type=offline&prompt=consent&redirect_uri='
				.urlencode(admin_url('admin.php?page=pp_wczc'))
		);
	}
	
	public static function getApiToken($grantToken) {
		$oAuthClient = zcrmsdk\oauth\ZohoOAuth::getClientInstance();
		$oAuthTokens = $oAuthClient->generateAccessToken($grantToken);
	}
	
	public function sendData($module, $data, $existingId=null, $updateExisting=0, $wfTrigger=false) {
		try {
			$allFields = pp_wczc_get_fields_zoho($module);
			
			//woocommerce-and-zoho-crm-connector-pro\vendor\zohocrm\php-sdk\README.md
			
			$record=zcrmsdk\crm\crud\ZCRMRecord::getInstance($module, $existingId);
			foreach ($data as $field => $value) {
				if ( !empty($allFields[$field]['id']) ) {
					$record->setFieldValue($allFields[$field]['id'],$value); 
				}
			}
			
			$zcrmModuleIns = zcrmsdk\crm\crud\ZCRMModule::getInstance($module);
			$records = array($record);
			$wfTrigger = $wfTrigger ? array('workflow') : null;
			
			if ( !empty($existingId) ) {
				$bulkAPIResponse = $zcrmModuleIns->updateRecords( $records, $wfTrigger );
			} else {
			
				switch ($updateExisting) {
					case 1:
						$bulkAPIResponse = $zcrmModuleIns->upsertRecords( $records, $wfTrigger );
						break;
					case 0:
						switch ($module) {
							case 'Contacts':
							case 'Leads':
								$searchColumn = 'Email';
								break;
						}
					
						// This may produce an exception!
						$existingResult = $this->search($module, $searchColumn, $record->getFieldValue($searchColumn), 1);
						
						if ($existingResult) {
							return $module == 'Contacts' ? array( $existingResult[0]->getEntityId(), false ) : $existingResult[0]->getEntityId();
						}
						
						// No break intentional
					case 2:
						$bulkAPIResponse = $zcrmModuleIns->createRecords( $records, $wfTrigger );
						break;
				}
				
			}
			
			$entityResponses = $bulkAPIResponse->getEntityResponses();
			
			if ( !empty($entityResponses) && "success" == $entityResponses[0]->getStatus() ) {
				if ( !empty($existingId) ) {
					return true;
				} else if ($module == 'Contacts') {
					return array(
						$entityResponses[0]->getData()->getEntityId(),
						strpos($entityResponses[0]->getMessage(), 'added successfully') !== false
					);
				} else {
					return $entityResponses[0]->getData()->getEntityId();
				}
				
			}
			
		} catch (Exception $ex){}
		
		return false;
	}
	
	// Returns [ contactId, isNew ]
	public function addContact($contactData, $updateExisting=false, $wfTrigger=false) {
		return $this->sendData('Contacts', $contactData, null, $updateExisting, $wfTrigger);
	}
	
	public function exists($module, $searchColumn, $searchValue, $maxReults=10) {
		// This may produce an exception!
		return !empty( $this->search($module, $searchColumn, $searchValue, 1) );
	}
	
	public function search($module, $searchColumn, $searchValue, $maxResults=10) {
		//woocommerce-and-zoho-crm-connector-pro\vendor\zohocrm\php-sdk\README.md
		$zcrmModuleIns = zcrmsdk\crm\crud\ZCRMModule::getInstance($module);
		
		try {
			//woocommerce-and-zoho-crm-connector-pro\vendor\zohocrm\php-sdk\src\crm\crud\ZCRMModule.php
			$response = $zcrmModuleIns->searchRecordsByCriteria(
				'('
					.$searchColumn
					.':equals:'
					.str_replace(
						array(',', '(', ')'),
						array('\\,', '\\(', '\\)'),
						$searchValue
					)
				.')',
				array(
					'per_page' => $maxResults
				)
			);
			
		//woocommerce-and-zoho-crm-connector-pro\vendor\zohocrm\php-sdk\src\crm\exception\ZCRMException.php
		} catch (zcrmsdk\crm\exception\ZCRMException $ex) {
		
			//woocommerce-and-zoho-crm-connector-pro\vendor\zohocrm\php-sdk\src\crm\exception\ZCRMException.php
			if ($ex->getExceptionCode() == 'NO CONTENT') {
				return array();
			} else {
				throw $ex;
			}
			
		}
		
		//woocommerce-and-zoho-crm-connector-pro\vendor\zohocrm\php-sdk\samplecodes\module.php
		return $response->getData();
	}
	
	public function addLead($leadData, $updateExisting=0, $wfTrigger=false) {
		return $this->sendData('Leads', $leadData, null, $updateExisting, $wfTrigger);
	}
	
	public function getModuleFields($module) {
		try {
			
			//woocommerce-and-zoho-crm-connector-pro\vendor\zohocrm\php-sdk\samplecodes\module.php
			$moduleIns = ZCRMRestClient::getInstance()->getModuleInstance($module); // To get module instance
			$response = $moduleIns->getAllFields(); // to get the field
			$fields = $response->getData(); // to get the array of ZCRMField instances
			
			if (empty($fields)) {
				return false;
			}
			
			$myFields = array();
			
			foreach ($fields as $field) { // each field
				$myFields[$field->getFieldLabel()] = array(
					'id' => $field->getApiName(),
					'required' => $field->isMandatory()
				);
			}
			
			return $myFields;
			
		} catch (Exception $ex) {}
        
		return false;
		
	}
	
}
?>