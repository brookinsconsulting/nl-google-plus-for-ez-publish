<?php
/*    */
class NlGooglePlus
{
    public function __contruct() {}

    public function operatorList()
    {
        return array( 'nlgoogleplus', 'nlgoogleplusfeed', 'nlgooglepluscomments' );
    }

    public function namedParameterPerOperator()
    {
        return true;
    }

    public function namedParameterList()
    {
        return array(	'nlgoogleplus' => array( 'userid' => array( 'type' => 'string', 'required' => true ) ),
       					'nlgoogleplusfeed' => array( 'userid' => array( 'type' => 'string', 'required' => true ) ),
       					'nlgooglepluscomments' => array( 'activityid' => array( 'type' => 'string', 'required' => true ), 
       													 'title' => array( 'type' => 'string', 'required' => false, 'default' => '' )),
        );
    }

    public function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
        	//G+ feed
        	//keep "nlgoogleplus" for compatibility
            case 'nlgoogleplus':
            case 'nlgoogleplusfeed':
            {
                $operatorValue = $this->getGooglePlusFeed($tpl,$namedParameters);
            } break;
            
            case 'nlgooglepluscomments':
            {
                $operatorValue = $this->getGooglePlusComments($tpl,$namedParameters);
            } break;
        }
    }
    
    /**
     * Generate G+ activity feed
     * @param eZTemplate $tpl
     * @param array $namedParameters
     * @return string
     */
    private function getGooglePlusFeed($tpl,$namedParameters) {
    	//get config
    	$nlGooglePlusIni = eZINI::instance('nlgoogleplus.ini');
    	 
    	//initilize G+ Service
    	$plus = $this->initializeGooglePlusService();
    	 
    	if( isset($namedParameters['userid']) ) {
    		$userId = $namedParameters['userid'];
    	}
    	else {
    		$userId = $nlGooglePlusIni->variable('Feed', 'UserId');
    	}
    	 
    	$optParams = array('maxResults' => $nlGooglePlusIni->variable('Feed', 'MaxResults'));
    	try {
    		$activities = $plus->activities->listActivities($userId, 'public', $optParams);
    	}
    	catch(Exception $e) {
    		eZLog::write('Google Plus get activities problem : '.$e->getMessage(),'error.log');
    	}
    	$items = $activities['items'];
    	
    	//use template
    	$tpl->setVariable('activities',$activities);
    	$tpl->setVariable('items',$items);
    	return $tpl->fetch( 'design:nlgoogleplus/googleplusbox.tpl' );
    	
    }
    
    /**
    * Generate G+ comments feed for an activity
    * @param eZTemplate $tpl
    * @param array $namedParameters
    * @return string
    */
    private function getGooglePlusComments($tpl,$namedParameters) {
    	//get config
    	$nlGooglePlusIni = eZINI::instance('nlgoogleplus.ini');
    
    	//initilize G+ Service
    	$plus = $this->initializeGooglePlusService();
    
    	if( !isset($namedParameters['activityid']) ) {
    		eZLog::write('Activity id is required','error.log');
    		return false;
    	}
    	
    	//parameters needed
    	$activityId = $namedParameters['activityid'];
    	$title = $namedParameters['title'];
        
    	//find all related comments
    	$optParams = array('maxResults' => $nlGooglePlusIni->variable('Feed', 'MaxResults'));
    	try {
    		$comments = $plus->comments->listComments($activityId, $optParams);
    	}
    	catch(Exception $e) {
    		eZLog::write('Google Plus get comments problem : '.$e->getMessage(),'error.log');
    	}

    	//use template
    	$tpl->setVariable('comments',$comments);
    	$tpl->setVariable('title',$title);
    	return $tpl->fetch( 'design:nlgoogleplus/googleplusboxcomments.tpl' );
    	 
    }
    
    /**
     * Initialize G+ service
     * @return apiPlusService
     */
    private function initializeGooglePlusService() {
    	//get config
    	$nlGooglePlusIni = eZINI::instance('nlgoogleplus.ini');
    	
    	//initialize Google+ API
    	$client = new apiClient();
    	$client->setApplicationName("NL Google Plus for eZ Publish");
    	// Visit https://code.google.com/apis/console to generate your
    	// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
    	$client->setClientId($nlGooglePlusIni->variable('GooglePlus', 'ClientId'));
    	$client->setClientSecret($nlGooglePlusIni->variable('GooglePlus', 'ClientSecret'));
    	$client->setDeveloperKey($nlGooglePlusIni->variable('GooglePlus', 'DeveloperKey'));
    	$client->setScopes(array($nlGooglePlusIni->variable('GooglePlus', 'Scope')));
    	//$client->setUseObjects(true);
    	return new apiPlusService($client);
    }
}

?>