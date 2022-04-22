<?php if (!isset($page)) { $page = 0;} ?>
<div id='dailyTableDiv'>
<table class="dailyTable min-w-full" itemCount='<?=count($daily_reports)?>' page='<?=$page?>''>
    <thead>
        <tr>
            <th class="px-6 py-3 border-b border-gray-100 bg-gray-100 text-left text-xs leading-5 font-medium text-gray-900 uppercase tracking-wider">
                Job
            </th>
            <th class="px-6 py-3 border-b border-gray-100 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Hour - User
            </th>
            <th class="px-6 py-3 border-b border-gray-100 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Mileage
            </th>
            <th class="px-6 py-3 border-b border-gray-100 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Completed
            </th>
            <th class="px-6 py-3 border-b border-gray-100 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Options
            </th>
            <th class="px-6 py-3 border-b border-gray-100 bg-gray-100"></th>
        </tr>
    </thead>
    
    <tbody class="bg-white">
        <?php
        $pageLength = 100;
        $i = 0;
        ?>
        <?php foreach($daily_reports as $daily_report): ?>
            <?php if (canReadDaily($daily_report)): ?>
                <?php $i++; ?>
                <?php if ($i < $page * $pageLength) continue; ?>
                <?php if ($i > $page * $pageLength + $pageLength) break; ?>

                <?php $job = $daily_report->getJob(); ?>
                <tr onclick='goToDaily("/daily/view/<?=$daily_report->getId()?>", event);' class='cursor-pointer hover:bg-gray-100'>
                    <td class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <div class="text-sm leading-5 font-medium text-gray-900"><?=$job->getNumber();?></div>
                                <div class="text-sm leading-5 text-gray-500"><?=$job->getName();?></div>
                                <div class="text-sm leading-5 text-gray-500"><?=date('F d', strtotime($job->getCreateDate()));?></div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 border-b border-gray-200">
                        <?php //foreach($daily_report->getHours() as $hours): ?>
                            <div class="text-sm leading-5 font-medium text-gray-900"><?= 1//var_dump($hours) ?></div>
                        <?php //endforeach; ?>
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500">
                        <?= $daily_report->getMileage() ?>
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500">
                        <?php if (strlen($daily_report->getCompleted()) > 150): ?>
                            <?= substr($daily_report->getCompleted(), 0, 150) . '...'  ?>
                        <?php else: ?>
                            <?= $daily_report->getCompleted() ?>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500 overflow-visible">
                        <?php include 'components/options.php'; ?>
                    </td>
                    <td class="px-6 py-4 text-right border-b border-gray-200 text-sm leading-5 font-medium">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </td>
                </tr>
                <?php //endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>