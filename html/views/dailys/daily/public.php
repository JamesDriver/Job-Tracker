<?php 
if (!$daily) { die; }
$workTypesTmp = getWorkTypes();
$workTypes = array();
foreach($workTypesTmp as $workType) {
    $workTypes[$workType->getId()] = $workType;
}
$date = new Datetime($daily->getDate());
?>

<div class="h-full">
    <div class="mx-auto">
        <div class="bg-white overflow-hidden">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-100">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                Daily Report
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                Job <?= $daily->getJob()->getNumber(); ?> - <?= $daily->getJob()->getName(); ?>
                </p>
                <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                <?= $date->format('F d, Y'); ?>
                </p>
            </div>
            <div class="px-4 py-5 sm:p-0">
                <dl>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                        Creator
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= $daily->getCreator()->getName() ?>
                        </dd>
                    </div>
                    <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                        Mileage
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <?= $daily->getMileage() ?>
                        </dd>
                    </div>
                    <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                        Hours
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <table class='w-full text-left'>
                                <thead class='w-full'>
                                    <tr class='text-sm leading-5 text-gray-900'>
                                        <th>Worker</th>
                                        <th>Work Type</th>
                                        <th>Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($daily->getWorkers() as $worker): ?>
                                        <tr class='text-sm leading-5 text-gray-900'>
                                            <td> <?= $worker->getWorker()->getName(); ?>            </td>
                                            <td> <?= $workTypes[($worker->getType())]->getName() ?> </td>
                                            <td> <?= $worker->getHours() ?>                         </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </dd>
                    </div>

                    <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                        Completed Today
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <?= $daily->getCompleted(); ?>
                        </dd>
                    </div>
                    <?php if ($daily->getGoals()): ?>
                        <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                            Tomorrow's goals
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= $daily->getGoals() ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($daily->getIssues()): ?>
                        <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                            Issues on Site
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= $daily->getIssues() ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($daily->getSowChanges()): ?>
                        <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                            Scope of Work Changes
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= $daily->getSowChanges() ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($daily->getMaterial()): ?>
                        <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                            Material Needed
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= $daily->getMaterial() ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($daily->getEquipment()): ?>
                        <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                            Equipment on Site
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= $daily->getEquipment() ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($daily->getNotes()): ?>
                        <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                            Additional Notes
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= $daily->getNotes() ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                    <div class="mt-8 sm:mt-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-t sm:border-gray-200 sm:px-6 sm:py-5">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                            Photos
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex flex-wrap" id="imagerow">
                                <?php foreach($daily->getFiles() as $file): ?>
                                    <a href="/image/<?=$file->getId()?>/daily/<?=$daily->getId()?>">
                                        <img class="dailyPicture" width="100" height="100" src="data:image/png;base64, <?=base64_encode(file_get_contents($file->getUrl()))?>">
                                        <input name="images[]" type="hidden" value="23845">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>