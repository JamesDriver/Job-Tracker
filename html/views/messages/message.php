<article class="mt-3 px-10 pt-6 pb-8 bg-white rounded-lg shadow">
    <div class="flex items-center justify-between">
        <p class="text-lg font-semibold">
            <span class="text-gray-900"><?=$message->getSender()->getName()?></span>
            <span class="text-gray-600">wrote</span>
        </p>
        <div class="flex items-center">
            <?php
            $date = date('F d', strtotime($message->getSendDate()));
            if (strtotime($message->getSendDate()) > strtotime('-1 day')) {
                $date = 'Yesterday at ' . date("H:i A",strtotime($time));
            } elseif (strtotime($message->getSendDate()) > strtotime('-7 day')) {
                $date = date("L",strtotime($time)) . ' at ' . date("H:i A",strtotime($time));
            }
            ?>
            <span class="text-xs text-gray-600"><?= $date ?></span>
            <span @mouseenter="open = !open" class="<?= ($workerCt > 0) ? '-ml-2' : NULL ?> userIcon inline-flex h-8 w-8 rounded-full text-white shadow-solid items-center justify-center rounded-full bg-gray-500">
                <span class="userIcon text-sm font-medium leading-none text-white"><?= $fieldWorker->getInitials() ?>
                </span>
            </span>
        </div>
    </div>
    <div class="mt-6 text-gray-800 text-sm">
        <p>
            <?= $message->getContent() ?>
        </p>
        <p class="mt-4 font-semibold text-gray-900">Masturah Abiola</p>
        <p>Customer Service</p>
    </div>
</article>
<?php if ($message->isRead()): ?>
    <p class='text-sm text-gray-500'>read at <?=$message->getReadDate()?></p>
<?php endif; ?>
