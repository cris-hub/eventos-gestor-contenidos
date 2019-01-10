<?php 
error_reporting(E_ALL); 
require_once '../vendor/autoload.php';
//header('Content-type: image/jpeg'); 
require_once '../vendor/Microsoft/WindowsAzure/Storage/Blob.php'; 
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

// Connect to Windows Azure cloud storage 
$client = new Microsoft_WindowsAzure_Storage_Blob( 
                    "blob.core.windows.net", 
                    "colsubsidioportalsalud", 
                    "1INLeStZNrhAR2zFSMUlk7Q1H5oGzsR+fSNSQY27AfVtWFlfzM2YKCUyaHkysYLHmhb57AkWknlOf30LU79sEA=="
                ); 

// Read file Data.xml from container called dataset 
//$localpath = getcwd() . 'Capturaerror.PNG'; 
//echo 'Hola existe el blob ' . $client->blobExists('colsubsidioportalsalud', 'Capturaerror.PNG'); 

//$client->getBlob('colsubsidioportalsalud', '/colsubsidioportalsalud/Capturaerror.PNG', $localpath); 

$local_server_file_path = getcwd() . '\upload';
// Write file example.txt to container called dataset 
$localpath = getcwd() . '\101430248.jpg'; 
echo $localpath;
$client->createContainer('azuretest');
$client->putBlob('azuretest', './101430248.jpg', './101430248.jpg');
$client->listBlobs('azuretest', '/', '/');
echo ' ------------- ';
echo $client->listBlobs('colsubsidioportalsalud');
//echo $client->containerExists('bscolsubsidiotest');
echo ' ------------- ';
//$result = $client->putBlob('bscolsubsidiotest', '101430248.jpg', $localpath); 

//$file_url = "https://bscolsubsidiotest.blob.core.windows.net/colsubsidioportalsalud/Capturaerror.PNG"; 
//file_put_contents($local_server_file_path, 
//file_get_contents($file_url)); 
//$zip->addFile( $local_server_file_path, $prop_doc['image_name'] );
?>