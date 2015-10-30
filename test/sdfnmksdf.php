<?php
die("CAPUM");
require_once( __DIR__ . '/../../src/bootstrap.php' );

file_put_contents(__DIR__.'/log', "Came in");

if ( $_GET['m'] == 'login' ) {
    if ( $_POST['username'] == 'invalid' ) {
        $sOut = json_encode( [ "error_description" => "authentication failure", "error" => "invalid_grant" ] );
    } else if ( $_POST['username'] == 'invalid_token' ) {
        $sOut = json_encode( [ 'id' => 'https://test.salesforce.com/id/00D250000004kfZEAQ/00524000000zy6hAAA'
                , 'issued_at' => '1436431666925'
                , 'token_type' => 'Bearer'
                , 'instance_url' => trim( Configuration::GetValue( 'webrooturl' ), '/' )
                , 'signature' => 'VlvOBuvtawUkI8FFUOrp1/Z/5xU8RcTWtEfFvi3FjZY='
                , 'access_token' => 'invalid_token'
            ]
        );
    } else {
        $sOut = json_encode( [ 'id' => 'https://test.salesforce.com/id/00D250000004kfZEAQ/00524000000zy6hAAA'
                , 'issued_at' => '1436431666925'
                , 'token_type' => 'Bearer'
                , 'instance_url' => trim( Configuration::GetValue( 'webrooturl' ), '/' )
                , 'signature' => 'VlvOBuvtawUkI8FFUOrp1/Z/5xU8RcTWtEfFvi3FjZY='
                , 'access_token' => 'valid_token'
            ]
        );
    }
}

if ( $_GET['m'] == 'getDocument' ) {
    if ( $_SERVER['HTTP_Authorization'] != 'Bearer valid_token' && $_SERVER['HTTP_AUTHORIZATION'] != 'Bearer valid_token' ) {
        $sOut = json_encode( [ [ "errorCode" => "INVALID_SESSION_ID", "message" => 'Session expired or invalid' ] ] );
    } else if ( $_GET['id'] == 'validId' ) {
        $sOut = file_get_contents( __DIR__ . '/../../tests/docs/testCs.docx' );
    } else if ( $_GET['id'] == 'test_validId' ) {
        $sOut = 'valid document content';
    } else {
        $sOut = json_encode( [ [ "errorCode" => "NOT_FOUND", "message" => "The requested resource does not exist" ] ] );
    }
}

if ( $_GET['m'] == 'sobjects' ) {
    if ( $_SERVER['HTTP_Authorization'] != 'Bearer valid_token' && $_SERVER['HTTP_AUTHORIZATION'] != 'Bearer valid_token' ) {
        $sOut = json_encode( [ [ "errorCode" => "INVALID_SESSION_ID", "message" => 'Session expired or invalid' ] ] );
    }  else {
        $sOut = json_encode( [ 'sobjects' => [ 'obj' => 'value' ] ] );
    }
}

if ( $_GET['m'] == 'upsertCandidate' ) {
    $aRawPostedData = json_decode( file_get_contents( 'php://input' ), true );

    $aMandatoryParams = array( 'sourceSystem', 'firstName', 'lastName', 'emailAddress', 'brandCode' );
    $aOptionalParams  = array( 'mobileNumber', 'jobTitle', 'jobId', 'title', 'phoneNumber', 'postCode', 'country', 'dpRemoved', 'cvFileExtension', 'cvSource', 'coverNote' );

    $aPostedData = $aRawPostedData['request'];

    $sStatusCode    = 'ok';
    $sStatusMessage = '';
    $sCvFileName    = empty( $aPostedData['cvFileExtension'] ) ? '' : 'Mock new CV Filename';

    foreach( $aMandatoryParams as $sParamKey ) {
        if( empty( $aPostedData[$sParamKey] ) ) {
            $sStatusCode    = 'error';
            $sStatusMessage = "Mock Missing Mandatory Parameter: $sParamKey";
        }
    }

    if( $aPostedData['cvSource'] == 'AllKeysSet' ) {
        $aAllValidParams = array_merge( $aMandatoryParams, $aOptionalParams );
        foreach( $aAllValidParams as $sParamKey ) {
            if( empty( $aPostedData[$sParamKey] ) ) {
                $sStatusCode    = 'error';
                $sStatusMessage = "Missing Parameter [$sParamKey] when all keys expected to be set";
                $sCvFileName    = null;
            }
        }
    }

    if( $aPostedData['sourceSystem'] == 'invalidAuth' ) {
        $sStatusCode    = 'error';
        $sStatusMessage = 'Mock Invalid Authentication';
    }

    if( $aPostedData['sourceSystem'] == 'timeout' ) {
        sleep(5);
    }

    $sOut = json_encode( array( 'statusCode'    => $sStatusCode
    , 'statusMessage' => $sStatusMessage
    , 'cvFileName'    => $sCvFileName ) );

}

if ( $_GET['m'] == 'insertApplication' ) {
    $aRawPostedData = json_decode( file_get_contents( 'php://input' ), true );

    $aMandatoryParams = array( 'candidateId, ','sourceSystem', 'jobId', 'cvSource', 'brandCode' );
	$aOptionalParams  = array( );
	
	$aPostedData = $aRawPostedData['request'];

	$sStatusCode    = 'ok';
	$sStatusMessage = '';

	foreach( $aMandatoryParams as $sParamKey ) {
        if( empty( $aPostedData[$sParamKey] ) ) {
            $sStatusCode    = 'error';
            $sStatusMessage = "Mock Missing Mandatory Parameter: $sParamKey";
        }
    }
	
/*	if( $aPostedData['cvSource'] == 'AllKeysSet' ) {
		$aAllValidParams = array_merge( $aMandatoryParams, $aOptionalParams );
		foreach( $aAllValidParams as $sParamKey ) {
			if( empty( $aPostedData[$sParamKey] ) ) {
				$sStatusCode    = 'error';
				$sStatusMessage = "Missing Parameter [$sParamKey] when all keys expected to be set";
				$sCvFileName    = null;	
			}
		}
	}*/

	if( $aPostedData['sourceSystem'] == 'invalidAuth' ) {
        $sStatusCode    = 'error';
        $sStatusMessage = 'Mock Invalid Authentication';
    }
	
	if( $aPostedData['sourceSystem'] == 'timeout' ) {
        sleep(5);
    }

	$sOut = json_encode( array( 'statusCode'    => $sStatusCode
    , 'statusMessage' => $sStatusMessage
    , 'cvFileName'    => $sCvFileName ) );

}


echo $sOut;