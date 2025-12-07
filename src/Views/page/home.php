<?php  



   $templateFile = __DIR__ .'/template.php';
   $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
   if($path === '/profile'){
      $contentFile = __DIR__ .'/../component/profile.php';
   }else{
      $contentFile = __DIR__ .'/../component/timeline.php';
   };

 try{
    if(!file_exists($templateFile)){
      throw new Exception("Rquired file {$templateFile} is missing");
    }

    require_once $templateFile; 
 }catch(Exception $e){
    error_log("Error: " . $e->getMessage());
 };
?>
