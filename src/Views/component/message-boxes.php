<?php

    $success = \src\Response\FlashData::getFlashData('success');
    $error = \src\Response\FlashData::getFlashData('error');
?>

<div class="container mt-5 mb-5">
    <?php if($success): ?>
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

</div>