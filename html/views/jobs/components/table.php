<?php 
$jobCols = getJobColumns();
global $fields;
if (!isset($jobs)) {
    if (isset($jobSort)) {
        $jobs = $jobSort->run(getJobs());
    } else {
        $jobSort = new Sort();
        global $userSortValue;
        if (isset($userSortValue)) {
            $jobSort = $userSortValue;
        } elseif (isset($_GET['all'])) {
            $jobSort = new Sort();
        } else {
            $statuses = getStatusesByFunc(4);
            foreach($statuses as $status) {
                $jobSort->removeStatus($status->getId());
            }
        }
        $jobs = $jobSort->run(getJobs());
    }
}
if (!isset($page)) { $page = 0;}
?>
<div id='jobTableDiv'> 
    

    <table class="jobTable min-w-full" itemCount='<?= count($jobs) ?>' page='<?=$page?>'>
        <thead>
            <tr>
                <th class="pl-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    <svg @click="jobSortOpen = true;console.log('here');" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M 22.875 0 L 1.125 0 C 0.128906 0 -0.378906 1.210938 0.332031 1.921875 L 9 10.589844 L 9 20.25 C 9 20.617188 9.179688 20.960938 9.480469 21.171875 L 13.230469 23.796875 C 13.96875 24.3125 15 23.789062 15 22.875 L 15 10.589844 L 23.667969 1.921875 C 24.375 1.214844 23.875 0 22.875 0 Z M 22.875 0" clip-rule="evenodd"></path>
                    </svg>
                </th>
                <th class="py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <div class="text-xs leading-5 font-medium text-gray-900">Number</div>
                            <div class="text-xs leading-5 text-gray-500">Name</div>
                        </div>
                    </div>
                </th>
                <?php if ($jobCols->status):?>
                    <th class="px-3 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                <?php endif; ?>
                <?php if ($jobCols->client && $fields->jobClient):?>
                    <th class="px-3 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider md:table-cell hidden">
                        Client
                    </th>
                <?php endif; ?>
                <?php if ($jobCols->type):?>
                    <th class="px-3 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider lg:table-cell hidden">
                        Type
                    </th>
                <?php endif; ?>
                <?php if ($jobCols->location && $fields->jobLocation):?>
                    <th class="px-3 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider lg:table-cell hidden">
                        Location
                    </th>
                <?php endif; ?>
                <?php if ($jobCols->fieldworker && $fields->jobWorkers):?>
                    <th class="px-3 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider xl:table-cell hidden">
                        Field Worker(s)
                    </th>
                <?php endif; ?>
                <th class="px-3 py-3 border-b border-gray-200 bg-gray-100 xl:table-cell hidden"></th>

                <th class="px-3 py-3 border-b border-gray-200 bg-gray-100 xl:table-cell hidden"></th>
            </tr>
        </thead>
        
        <tbody class="bg-white">

            <?php
            $pageLength = 100;
            $i = 0;
            ?>
            <?php foreach($jobs as $job): ?>
                <?php $i++; ?>
                <?php if ($i < $page * $pageLength) continue; ?>
                <?php if ($i > $page * $pageLength + $pageLength) break; ?>

                <tr onclick='goToJob("/job/edit/<?=$job->getId()?>", event);' class='cursor-pointer hover:bg-gray-100'>
                    <td class="pl-4 border-b border-gray-200">
                        <?php if ($job->isStarred()): ?>
                            <div onclick='starJob(<?=$job->getId()?>);' class="flex-shrink-0 h-10 w-10 star" x-data="{ nonStar: false, star: true }" >
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full cursor-pointer star">
                                    <span x-show='nonStar' @click='nonStar=false, star=true' class="text-xl text-gray-800 hover:text-yellow-300 star" style="display: none;">☆</span>
                                    <span x-show='star' @click='nonStar=true, star=false'class="text-xl text-yellow-300 hover:text-gray-800 star">★</span>
                                </span>
                            </div>
                        <?php else: ?>
                            <div onclick='starJob(<?=$job->getId()?>);' class="flex-shrink-0 h-10 w-10 star" x-data="{ nonStar: true, star: false }" >
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full cursor-pointer star">
                                    <span x-show='nonStar' @click='nonStar=false, star=true' class="text-xl text-gray-800 hover:text-yellow-300 star">☆</span>
                                    <span x-show='star' @click='nonStar=true, star=false'class="text-xl text-yellow-300 hover:text-gray-800 star" style="display: none;">★</span>
                                </span>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <div class="text-sm leading-5 font-medium text-gray-900"><?=$job->getNumber();?></div>
                                <div class="text-sm leading-5 text-gray-500"><?=$job->getName();?></div>
                                <div class="text-sm leading-5 text-gray-500"><?=date('F d', strtotime($job->getCreateDate()));?></div>
                            </div>
                        </div>
                    </td>
                    <?php if ($jobCols->status):?>
                        <td class="px-3 py-4 border-b border-gray-200">
                            <?php $status = $job->getStatus(); ?>
                            <div class='flex items-center'>
                                <div>
                                    <span class=" block h-2 w-2 rounded-full text-white shadow-solid" style='background-color:<?=$status->getColor()?>'></span>
                                </div>
                                <div class="ml-2 text-sm leading-5 text-gray-500">
                                    <?=$status->getName()?>
                                </div>
                            </div>
                        </td>
                    <?php endif; ?>
                    <td class="px-3 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500 overflow-visible md:table-cell hidden">
                        <?php if ($job->getClient()): ?>
                            <?php $client = $job->getClient(); ?>
                            <div x-data="{ open: false, stayOpen: false }"  @mouseleave="open = false" @keydown.escape="open = false" @click.away="open = false" class=' relative inline-block'>
                                <span @mouseenter="open = !open" class="inline-flex items-center justify-center">
                                    <span class=" leading-none text-gray-500"><?= $client->getName() ?>
                                    </span>
                                </span>
                                <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" 
                                class="overflow-visible right-0 absolute pt-2 w-56 rounded-md shadow-lg origin-top-right z-10" style="display: none;">
                                    <div class="rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                        <div class="px-4 py-3">
                                            <p class="text-left text-sm leading-5 font-medium text-gray-900 truncate">
                                                <?= $client->getName() ?>
                                            </p>
                                        </div>
                                        <div class="border-t border-gray-100"></div>
                                        <div class="py-1">
                                            <?php if ($client->getPhone()): ?>
                                                <a href="tel:<?=$client->getPhone()?>" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd" d="M13.04 14.69l1.07-2.14a1 1 0 0 1 1.2-.5l6 2A1 1 0 0 1 22 15v5a2 2 0 0 1-2 2h-2A16 16 0 0 1 2 6V4c0-1.1.9-2 2-2h5a1 1 0 0 1 .95.68l2 6a1 1 0 0 1-.5 1.21L9.3 10.96a10.05 10.05 0 0 0 3.73 3.73zM8.28 4H4v2a14 14 0 0 0 14 14h2v-4.28l-4.5-1.5-1.12 2.26a1 1 0 0 1-1.3.46 12.04 12.04 0 0 1-6.02-6.01 1 1 0 0 1 .46-1.3l2.26-1.14L8.28 4zm7.43 5.7a1 1 0 1 1-1.42-1.4L18.6 4H16a1 1 0 0 1 0-2h5a1 1 0 0 1 1 1v5a1 1 0 0 1-2 0V5.41l-4.3 4.3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Call
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($client->getEmail()): ?>
                                                <a href="mailto:<?=$client->getEmail()?>" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd" d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2zm16 3.38V6H4v1.38l8 4 8-4zm0 2.24l-7.55 3.77a1 1 0 0 1-.9 0L4 9.62V18h16V9.62z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Email
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($client->getAddress()): ?>
                                                <a href="https://www.google.com/maps/dir/?api=1&destination=<?=urlencode($client->getAddress(true))?>" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd" d="M14 5.62l-4 2v10.76l4-2V5.62zm2 0v10.76l4 2V7.62l-4-2zm-8 2l-4-2v10.76l4 2V7.62zm7 10.5L9.45 20.9a1 1 0 0 1-.9 0l-6-3A1 1 0 0 1 2 17V4a1 1 0 0 1 1.45-.9L9 5.89l5.55-2.77a1 1 0 0 1 .9 0l6 3A1 1 0 0 1 22 7v13a1 1 0 0 1-1.45.89L15 18.12z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Directions
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                    </td>
                    <?php $type = $job->getType(); ?>
                    <td class="px-3 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500 lg:table-cell hidden">
                        <div class='flex items-center'>
                            <div>
                                <span class="block h-2 w-2 rounded-full text-white shadow-solid" style='background-color:<?=$type->getColor()?>'></span>
                            </div>
                            <div class="ml-2 text-sm leading-5 text-gray-500">
                                <?=$type->getName()?>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500 overflow-visible lg:table-cell hidden">
                        <?php if ($job->getLocation()): ?>

                            <div x-data="{ open: false, stayOpen: false }"  @mouseleave="open = false" @keydown.escape="open = false" @click.away="open = false" class=' relative inline-block'>
                                <span @mouseenter="open = !open" class="inline-flex items-center justify-center">
                                    <span class=" leading-none text-gray-500"><?= $job->getLocation() ?>
                                    </span>
                                </span>
                                <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" 
                                class="overflow-visible right-0 absolute pt-2 w-56 rounded-md shadow-lg origin-top-right z-10" style="display: none;">
                                    <div class="rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                        <div class="px-4 py-3">
                                            <p class="text-left text-sm leading-5 font-medium text-gray-900 truncate">
                                                <?= $job->getLocation() ?>
                                            </p>
                                        </div>
                                        <div class="border-t border-gray-100"></div>
                                        <div class="py-1">
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?=urlencode($job->getLocation(true))?>" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M14 5.62l-4 2v10.76l4-2V5.62zm2 0v10.76l4 2V7.62l-4-2zm-8 2l-4-2v10.76l4 2V7.62zm7 10.5L9.45 20.9a1 1 0 0 1-.9 0l-6-3A1 1 0 0 1 2 17V4a1 1 0 0 1 1.45-.9L9 5.89l5.55-2.77a1 1 0 0 1 .9 0l6 3A1 1 0 0 1 22 7v13a1 1 0 0 1-1.45.89L15 18.12z" clip-rule="evenodd"></path>
                                                </svg>
                                                Directions
                                            </a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>




                    </td>
                    <td class="px-3 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500 overflow-visible xl:table-cell hidden">
                        <div class="ml-4">
                            <div class="flex overflow-visible">
                                <?php $workerCt = 0; ?>
                                <?php foreach($job->getWorkers() as $fieldWorker): ?>
                                    <?php if ($workerCt < 3): ?>
                                        <div x-data="{ open: false, stayOpen: false }"  @mouseleave="open = false" @keydown.escape="open = false" @click.away="open = false" class='userIcon relative inline-block text-right'>
                                            <span @mouseenter="open = !open" class="<?= ($workerCt > 0) ? '-ml-2' : NULL ?> userIcon inline-flex h-8 w-8 rounded-full text-white shadow-solid items-center justify-center rounded-full bg-gray-500">
                                                <span class="userIcon text-sm font-medium leading-none text-white"><?= $fieldWorker->getInitials() ?>
                                                </span>
                                            </span>
                                            <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" 
                                            class="overflow-visible right-0 absolute pt-2 w-56 rounded-md shadow-lg origin-top-right z-10" style="display: none;">
                                                <div class="rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                    <div class="px-4 py-3">
                                                        <p class="text-left text-sm leading-5 font-medium text-gray-900 truncate">
                                                        <?= $fieldWorker->getName() ?>
                                                        </p>
                                                    </div>
                                                    <div class="border-t border-gray-100"></div>
                                                    <div class="py-1">
                                                        <a href="/user/view/<?=$fieldWorker->getId()?>" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            View
                                                        </a>
                                                        <a href="tel:<?=$fieldWorker->getPhone()?>" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M13.04 14.69l1.07-2.14a1 1 0 0 1 1.2-.5l6 2A1 1 0 0 1 22 15v5a2 2 0 0 1-2 2h-2A16 16 0 0 1 2 6V4c0-1.1.9-2 2-2h5a1 1 0 0 1 .95.68l2 6a1 1 0 0 1-.5 1.21L9.3 10.96a10.05 10.05 0 0 0 3.73 3.73zM8.28 4H4v2a14 14 0 0 0 14 14h2v-4.28l-4.5-1.5-1.12 2.26a1 1 0 0 1-1.3.46 12.04 12.04 0 0 1-6.02-6.01 1 1 0 0 1 .46-1.3l2.26-1.14L8.28 4zm7.43 5.7a1 1 0 1 1-1.42-1.4L18.6 4H16a1 1 0 0 1 0-2h5a1 1 0 0 1 1 1v5a1 1 0 0 1-2 0V5.41l-4.3 4.3z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Call
                                                        </a>
                                                        <a href="mailto:<?=$fieldWorker->getPhone()?>" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2zm16 3.38V6H4v1.38l8 4 8-4zm0 2.24l-7.55 3.77a1 1 0 0 1-.9 0L4 9.62V18h16V9.62z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Email
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php $workerCt++ ?>
                                <?php endforeach; ?>
                                <?php if ($workerCt > 2): ?>
                                    <div class="ml-2 inline-flex items-center justify-center text-sm leading-5 text-gray-500 whitespace-no-wrap">
                                    + <?= $workerCt - 3 ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-4 border-b border-gray-200 text-sm leading-5 text-gray-500 overflow-visible">
                        <?php include 'options.php'; ?>
                    </td>
                    <td class="px-3 py-4 text-right border-b border-gray-200 text-sm leading-5 font-medium xl:table-cell hidden">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </td>
                </tr>
                <?php //endforeach; ?>
            <?php endforeach; ?>
            

        </tbody>
        
    </table>



    <div class="pagination bg-white px-4 py-3 flex items-center justify-between border-gray-200 sm:px-6 w-full">
        <div class="w-full sm:hidden">
            <a class="prevPageSmall relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                Previous
            </a>
            <a class="nextPageSmall ml-3 float-right relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                Next
            </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm leading-5 text-gray-700">
                    Showing
                    <span class="startNum font-medium"></span>
                    to
                    <span class="endNum font-medium"></span>
                    of
                    <span class="totalNum font-medium"></span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex shadow-sm">
                    <a class="prevPage relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Previous">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a class="page1 -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    
                    </a>
                    <a  class="page2 -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    
                    </a>
                    <a class="page3 hidden md:inline-flex -ml-px relative items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    
                    </a>
                    <a class="page4 hidden md:inline-flex -ml-px relative items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    
                    </a>
                    <a class="page5 hidden md:inline-flex -ml-px relative items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    
                    </a>
                    <a class="page6 -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    
                    </a>
                    <a class="page7 -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    
                    </a>
                    <a class="nextPage -ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Next">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</div>