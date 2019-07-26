
export CONNECTIONSTRINGBLOB=DefaultEndpointsProtocol=https;AccountName=bscolsubsidiotest;AccountKey=1INLeStZNrhAR2zFSMUlk7Q1H5oGzsR+fSNSQY27AfVtWFlfzM2YKCUyaHkysYLHmhb57AkWknlOf30LU79sEA==
export CONTAINERNAME=colsubsidiodigrecreacion
export STOREPATH=/uploads/store
export URLBLOB=https://bscolsubsidiotest.blob.core.windows.net/colsubsidiodigrecreacion/
export MYSQL_USER=colsadmin@cols-db-digprod-hoteles
export MYSQL_PASSWORD=123456789!aA
export MYSQL_CONECTION_PHP=mysql:host=cols-db-digprod-hoteles.mysql.database.azure.com;port=3306;dbname=yii-colsubsidio 

echo $CONNECTIONSTRINGBLOB
echo $CONTAINERNAME
echo $STOREPATH
echo $URLBLOB
echo $MYSQL_USER
echo $MYSQL_PASSWORD
echo $MYSQL_CONECTION_PHP

# docker build ./ -t colsazurecontainerregistrydigital.azurecr.io/recreacionyturismo/hoteles/content:test-17

docker run -it -p 80:80  \
colsazurecontainerregistrydigital.azurecr.io/recreacionyturismo/hoteles/content:test-17 \

# -e CONTAINERNAME=colsubsidiodigrecreacion \

-e MYSQL_USER=colsadmin@cols-db-digprod-hoteles \
# -e MYSQL_PASSWORD=123456789\!aA \
# -e STOREPATH=/uploads/store \

# -e URLBLOB=https://bscolsubsidiotest.blob.core.windows.net/colsubsidiodigrecreacion/ \
# -e MYSQL_CONECTION_PHP=mysql:host=cols-db-digprod-hoteles.mysql.database.azure.com;port=3306;dbname=yii-colsubsidio 
# -e CONNECTIONSTRINGBLOB=DefaultEndpointsProtocol=https;AccountName=bscolsubsidiotest;AccountKey=1INLeStZNrhAR2zFSMUlk7Q1H5oGzsR+fSNSQY27AfVtWFlfzM2YKCUyaHkysYLHmhb57AkWknlOf30LU79sEA== \
                           
                                                                                                                                                                                            
