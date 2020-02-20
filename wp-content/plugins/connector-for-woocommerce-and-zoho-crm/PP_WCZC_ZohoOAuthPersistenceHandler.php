<?php
// Copied from vendor/zohocrm/php-sdk/src/oauth/persistence/ZohoOAuthPersistenceHandler.php and modified

use zcrmsdk\crm\utility\Logger;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\oauth\exception\ZohoOAuthException;
//use zcrmsdk\oauth\utility\ZohoOAuthConstants;
use zcrmsdk\oauth\utility\ZohoOAuthTokens;

class PP_WCZC_ZohoOAuthPersistenceHandler implements zcrmsdk\oauth\persistence\ZohoOAuthPersistenceInterface
{
    
    public function saveOAuthData($zohoOAuthTokens)
    {
        try {
			update_option( 'pp_wczc_zoho_api2_token', array( $zohoOAuthTokens->getUserEmailId(), $zohoOAuthTokens->getAccessToken(), $zohoOAuthTokens->getRefreshToken(), $zohoOAuthTokens->getExpiryTime() ) );
        } catch (Exception $ex) {
            Logger::severe("Exception occured while inserting OAuthTokens into DB(file::ZohoOAuthPersistenceHandler)({$ex->getMessage()})\n{$ex}");
        }
    }
    
    public function getOAuthTokens($userEmailId)
    {
        $oAuthTokens = new ZohoOAuthTokens();
        try {
			$row = get_option('pp_wczc_zoho_api2_token', false);
            if (!$row) {
                Logger::severe("Getting result set failed");
                throw new ZohoOAuthException("No Tokens exist for the given user-identifier,Please generate and try again.");
            } else {
                $oAuthTokens->setExpiryTime($row[3]);
				$oAuthTokens->setRefreshToken($row[2]);
				$oAuthTokens->setAccessToken($row[1]);
				$oAuthTokens->setUserEmailId($row[0]);
            }
			
        } catch (Exception $ex) {
            Logger::severe("Exception occured while getting OAuthTokens from DB(file::ZohoOAuthPersistenceHandler)({$ex->getMessage()})\n{$ex}");
        }
        return $oAuthTokens;
    }
    
    public function deleteOAuthTokens($userEmailId)
    {
        try {
          delete_option('pp_wczc_zoho_api2_token');
        } catch (Exception $ex) {
            Logger::severe("Exception occured while Deleting OAuthTokens from DB(file::ZohoOAuthPersistenceHandler)({$ex->getMessage()})\n{$ex}");
        }
    }
}