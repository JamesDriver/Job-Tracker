<?php 
if (!$daily) { die; }
if (!canReadDaily($daily)) {
    log::error(errors::$dailyPermissionReadFail);
    die;
}
$workTypesTmp = getWorkTypes();
$workTypes = array();
foreach($workTypesTmp as $workType) {
    $workTypes[$workType->getId()] = $workType;
}
$date = new Datetime($daily->getDate());
?>
<style>
    .left-0 {
        left: 0;
        right: auto;
    }
    @media (min-width: 1024px) {
        .lg\:right-0 {
            right: 0;
            left: auto;
        }
    }
</style>

<main class=" h-full bg-white relative z-0 overflow-y-auto focus:outline-none" tabindex="0" x-init="$el.focus()">
    



<div class="h-full">
    <div class="mx-auto">
        <div class="bg-white overflow-hidden">
            
            <div class="p-8 bg-gray-100">
                <div class="max-w-7xl mx-auto">
                    <div class="lg:flex lg:items-center lg:justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex text-sm leading-5 text-gray-500 sm:mr-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Daily Report
                                </h3>
                            </div>
                            <div class="flex text-sm leading-5 text-gray-500 sm:mr-6">
                                <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                                    Job <?= $daily->getJob()->getNumber(); ?>
                            </p>
                            </div>
                            <div class="flex text-sm leading-5 text-gray-500 sm:mr-6">
                                <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                                    <?= $daily->getJob()->getName(); ?>
                                </p>
                            </div>

                            <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap">
                                <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?=date('F d, Y', strtotime($daily->getDate()));?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 flex lg:mt-0 lg:ml-4">
                            <?php include '/var/www/html/views/dailys/components/options.php'; ?>
                        </div>
                    </div>
                </div>
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
                                            <td> <?= ($a = $workTypes[($worker->getType())]) ? $a->getName() : '(other)' ?> </td>
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
                                        <img loading="lazy" class="dailyPicture" width="100" height="100" src="/image/<?=$file->getId()?>">
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



</main>