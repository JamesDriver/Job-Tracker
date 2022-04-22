<?php
$other = $message->getOtherPerson();
$unread = ($message->isRead());
error_log()
?>
<a href="/team/<?=$other->getId()?>" class="block px-6 pt-3 pb-4 <?=$unread ? 'bg-gray-100' : 'bg-white' ?> border-t">
    <div class="flex justify-between">
        <span class="text-sm font-semibold text-gray-900"><?=$other->getName()?></span>
        <span class="text-sm text-gray-500"><?= $message->getSideDate() ?></span>
    </div>
    <p class="text-sm text-gray-600"><?=$message->getContent()?></p>
</a>