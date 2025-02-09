<?php

?>

<div class="space-y-4 mb-1  w-2/3">
    <div class="bg-gray-200 py-10">
        <div class="relative  bg-cover bg-center" style="background-image: url('https://via.placeholder.com/800x300');">
        </div>

        <div class="max-w-3xl mx-auto px-4">
            <div class="relative">
                <!-- プロフィール画像 -->
                <img class="relative left-4 w-20 h-20 rounded-full border-4 border-white" src="https://via.placeholder.com/100" alt="Profile">
            </div>
            
            <div class="pt-12 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">ユーザー名</h1>
                    <p class="text-gray-600">@username</p>
                </div>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md">プロフィールの編集</button>
            </div>

            <div class="flex space-x-4 mt-2 text-gray-600">
                <span><strong>100</strong> フォロー中</span>
                <span><strong>250</strong> フォロワー</span>
            </div>
        </div>
    </div>
</div>

<?php 
include_once __DIR__ .'/../component/timeline.php';
?>