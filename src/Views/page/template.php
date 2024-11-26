<?php  
 $layoutFile = __DIR__ .'/../layout/layout.php';

 try{
    /*
        contentFile: layout.phpのcontentに当てはめるファイルを呼び出し元で定義する 
    */
    if(!file_exists($contentFile)){
      throw new Exception("Rquired file {$contentFile} is missing");
    }

    ob_start();
    require_once $contentFile;
    $content = ob_get_clean();

    if(!file_exists($layoutFile)){
      throw new Exception("Rquired file {$layoutFile} is missing");
    }

    require_once $layoutFile; 
 }catch(Exception $e){
    error_log("Error: " . $e->getMessage());
 };
?>
