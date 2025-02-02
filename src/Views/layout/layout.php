  <!-- 全体のコンテナ -->
  <main class='bg-gray-100 flex  justify-center items-center'> <!-- start of content -->
    <div class="flex flex-row min-h-screen w-full">
      
      <!-- サイドバー -->
      <?php include_once __DIR__ .'/../component/sidebar.php'?>
      <?php include_once __DIR__ .'/../component/post.php'?>

      <!-- メインコンテンツ  メインコンテンツの終了タグ忘れず-->
      <div class="flex-1 bg-white p-6">
          <!-- 以下のいずれかを表示 --> 
          <!-- 
              ツイート一覧 
              プロフィール
              通知一覧
              フォロワー一覧 
          -->
          
          <!-- layout.phpの呼び出し元で定義された＄contentを読み込む--> 
          <?php 
              if(isset($content)) {
                echo $content;
              }else {
                error_log('no content');
              }
          ?>

      </div>


      <!-- サイドウィジェット -->
      <?php include_once __DIR__ .'/../component/sideWidget.php'?>

    </div>
</main> <!-- end of content -->