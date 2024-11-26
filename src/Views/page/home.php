<?php  



 $contentFile = __DIR__ .'/../component/timeline.php';
 $templateFile = __DIR__ .'/template.php';

 try{
    if(!file_exists($templateFile)){
      throw new Exception("Rquired file {$templateFile} is missing");
    }

    require_once $templateFile; 
 }catch(Exception $e){
    error_log("Error: " . $e->getMessage());
 };
?>
