<?php

namespace nemmo\attachments\controllers;
//require_once '../../../../vendor/autoload.php';

use nemmo\attachments\models\File;
use nemmo\attachments\models\UploadForm;
use nemmo\attachments\ModuleTrait;
use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

class FileController extends Controller
{
    use ModuleTrait;

    public function getSubDirs($fileHash, $depth = 3)
    {
        $depth = min($depth, 9);
        $path = '';

        for ($i = 0; $i < $depth; $i++) {
            $folder = substr($fileHash, $i * 3, 2);
            $path .= $folder;
            if ($i != $depth - 1) $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }

    public function actionUpload()
    {        

        $model = new UploadForm();
        $model->file = UploadedFile::getInstances($model, 'file');

        if ($model->rules()[0]['maxFiles'] == 1 && sizeof($model->file) == 1) {
            $model->file = $model->file[0];
        }

        if ($model->file && $model->validate()) {
            $result['uploadedFiles'] = [];
            if (is_array($model->file)) {
                foreach ($model->file as $file) {
                    $path = $this->getModule()->getUserDirPath() . DIRECTORY_SEPARATOR . $file->name;
                    $file->saveAs($path);
                    $result['uploadedFiles'][] = $file->name;                
                }
            } else {
                $path = $this->getModule()->getUserDirPath() . DIRECTORY_SEPARATOR . $model->file->name;
                $model->file->saveAs($path);
                $result['uploadedFiles'][] = $model->file->name;
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'error' => $model->getErrors('file')
            ];
        }
    }

    public function actionDownload($id)
    {
        $connectionString = "DefaultEndpointsProtocol=https;AccountName=bscolsubsidiotest;AccountKey=1INLeStZNrhAR2zFSMUlk7Q1H5oGzsR+fSNSQY27AfVtWFlfzM2YKCUyaHkysYLHmhb57AkWknlOf30LU79sEA==";
        $blobClient = BlobRestProxy::createBlobService($connectionString);

        getcwd();
        chdir('../');
        $pathLocal = getcwd() . "/";

        $createContainerOptions = new CreateContainerOptions();
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
        $createContainerOptions->addMetaData("key1", "value1");
        $createContainerOptions->addMetaData("key2", "value2");
        $containerName = "colsubsidioportalsalud";

        $storePath = "/uploads/store";
        $fileResponse = "";
        $file = File::findOne(['id' => $id]);
        
        $filePath = $this->getModule()->getFilesDirPath($file->hash) . DIRECTORY_SEPARATOR . $file->hash . '.' . $file->type;
       
        $filePathBlob = $storePath . DIRECTORY_SEPARATOR . $this->getSubDirs($file->hash) . DIRECTORY_SEPARATOR . $file->hash . '.' . $file->type;
        $filePathBlob = str_replace('\\', '/', $filePathBlob);

        $listBlobsOptions = new ListBlobsOptions();
        
        $newFilePath = explode($pathLocal, $filePath);
        $newFilePath = $newFilePath[1];        

        $listBlobsOptions->setPrefix("");
        $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
        
        $opts = array(
            'http'=>array(
              'method'=>"GET",
              'header'=>"Accept-language: en\r\n" .
                        "Cookie: foo=bar\r\n"
            )
        );
        
        foreach ($result->getBlobs() as $blob){
            $urlBlobAux = "/" . $blob->getName();
            if($urlBlobAux == $filePathBlob){
                $context = stream_context_create($opts);                                 
                $fileResponse = file_get_contents($blob->getUrl(), false, $context);                         
                //$fileResponse = file_get_contents($blob->getName() . " -- " . $filePathBlob, false, $context); 
            }
        }     
            
        //$context = stream_context_create($opts);                 
        //$filePathBlob = str_replace('\\', '/', $filePathBlob);
        //$fileResponse = file_get_contents("https://bscolsubsidiotest.blob.core.windows.net/colsubsidioportalsalud/" . $storePath . DIRECTORY_SEPARATOR. $filePathBlob, false, $context); 
           
        //$context = stream_context_create($opts);
        //$fileResponse = file_get_contents($path . DIRECTORY_SEPARATOR . $filePathBlob, false, $context);
                
        return $fileResponse;
        //return Yii::$app->response->sendFile2($path . DIRECTORY_SEPARATOR . $filePathBlob);
        //foreach ($result->getBlobs() as $blob)
        //{    
            //echo " -- " . $blob->getName() . " -- " . $newFilePath;        
            //return Yii::$app->response->sendFile($blob->getUrl());
            //if($blob->getName() == ($newFilePath)){             
                //return Yii::$app->response->sendFile($filePath);
                //return Yii::$app->response->sendFile($pathLocal . DIRECTORY_SEPARATOR . $blob->getName(), "$file->name.$file->type");
            //}
           // return Yii::$app->response->sendFile($filePath);           
        //}

        //return Yii::$app->response->sendFile($newFilePath);
        //return Yii::$app->response->redirect($filePath);

        //return Yii::$app()->getimg->readImage($filePath);
    }

    public function actionDelete($id)
    {
        if ($this->getModule()->detachFile($id)) {
            return true;
        } else {
            return false;
        }
    }

    public function actionDownloadTemp($filename)
    {
        $filePath = $this->getModule()->getUserDirPath() . DIRECTORY_SEPARATOR . $filename;

        return Yii::$app->response->sendFile($filePath, $filename);
    }

    public function actionDeleteTemp($filename)
    {
        $userTempDir = $this->getModule()->getUserDirPath();
        $filePath = $userTempDir . DIRECTORY_SEPARATOR . $filename;
        unlink($filePath);
        if (!sizeof(FileHelper::findFiles($userTempDir))) {
            rmdir($userTempDir);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [];
    }
}
