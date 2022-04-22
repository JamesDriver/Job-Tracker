
<?php
$user = getUserById($id);

$thisWeek = new Datetime();
$thisWeek->setISODate($year, $week);
$thisWeek->setTime ( 0, 0, 0 );

$lastWeek = new Datetime();
$lastWeek->setISODate($year, $week-1);
$lastWeek->setTime ( 0, 0, 0 );

$nextWeek = new Datetime();
$nextWeek->setISODate($year, $week+1);
$nextWeek->setTime ( 0, 0, 0 );

$thisMonth = new Datetime();
$thisMonth->modify('first day of this month');
$thisMonth->setTime ( 0, 0, 0 );

$lastMonth = new Datetime();
$lastMonth->modify('first day of last month');
$lastMonth->setTime ( 0, 0, 0 );

$sixMonthsAgo = new Datetime($lastWeek->format(format::$time));
$sixMonthsAgo->modify('-26 week');
$sixMonthsAgo->setTime ( 0, 0, 0 );

$thisHours = $user->getWeekHours($thisWeek);
$lastHours = $user->getWeekHours($lastWeek);
$thisMonthHours  = $user->getMonthHours($thisMonth);
$lastMonthHours  = $user->getMonthHours($lastMonth);
$this6MonthHours = $user->getDailies($sixMonthsAgo->format(format::$time), $thisWeek->format(format::$time));

$totalHoursThisWeek    = 0;
$totalHoursLastWeek    = 0;
$totalHoursThisMonth   = 0;
$totalHoursLastMonth   = 0;
$totalHoursLast6Months = 0;

foreach($thisHours as $hour) {
    $totalHoursThisWeek += $hour->getHours();
}
foreach($lastHours as $hour) {
    $totalHoursLastWeek += $hour->getHours();
}
foreach($thisMonthHours as $hour) {
    $totalHoursThisMonth += $hour->getHours();
}
foreach($lastMonthHours as $hour) {
    $totalHoursLastMonth += $hour->getHours();
}
$weeks = array();
foreach($this6MonthHours as $hour) {
    $created = new Datetime();
    $created->modify($hour->getDate());
    $weeks[$created->format('Y-W')] = 1;
    $totalHoursLast6Months += $hour->getHours();
}
$averageHours = $totalHoursLast6Months/count($weeks);
$percentMonth = ($totalHoursLastMonth != 0) 
                    ? intval(($totalHoursThisMonth - $totalHoursLastMonth)/$totalHoursLastMonth * 100)
                    : 0;
$percentWeek = ($totalHoursLastWeek != 0) 
                    ? intval(($totalHoursThisWeek  - $totalHoursLastWeek) /$totalHoursLastWeek  * 100)
                    : 0;
$percentAve  = ($averageHours != 0) 
                    ? intval(($totalHoursThisWeek  - $averageHours) /$averageHours  * 100)
                    : 0;

?>
<div class="p-8 bg-gray-800">
    <div class="max-w-7xl mx-auto">
        <div class="lg:flex lg:items-center lg:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:leading-9 sm:truncate">
                    Timecard
                </h2>
                <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap">
                    <div class="mt-2 flex items-center text-sm leading-5 text-gray-300">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <?=$thisWeek->modify('-6 days')->format('F d, Y')?> - <?=$thisWeek->modify('+6 days')->format('F d, Y')?>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex lg:mt-0 lg:ml-4">
                <span class="sm:block shadow-sm rounded-md">
                    <a href='/timecard/<?=$id?>/<?= $lastWeek->format('Y/W')?>'>
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
                            <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Previous
                        </button>
                    </a>
                </span>

                <span class="sm:block ml-3 shadow-sm rounded-md">
                    <a href='/timecard/<?=$id?>/<?= $nextWeek->format('Y/W')?>'>
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:shadow-outline-gray focus:border-gray-700 active:bg-gray-700 transition duration-150 ease-in-out">
                            Next
                            <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </a>
                </span>
                <!-- Dropdown -->
            </div>
        </div>
    </div>
</div>


<main class=" relative z-0 overflow-y-auto focus:outline-none" tabindex="0" x-init="$el.focus()">
    <div class='px-4 py-5 sm:p-6 w-full flex items-center justify-center'>
        <div class="mt-5 grid grid-cols-1 rounded-lg bg-white overflow-hidden shadow md:grid-cols-3">
            <div>
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-base leading-6 font-normal text-gray-900">
                            Total Hours (week)
                        </dt>
                        <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                            <div class="flex items-baseline text-2xl leading-8 font-semibold text-indigo-600">
                                <?= $totalHoursThisWeek ?>
                                <span class="ml-2 text-sm leading-5 font-medium text-gray-500">
                                previous <?= $totalHoursLastWeek ?>
                                </span>
                            </div>
                            <?php if ($percentWeek > 0): ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="sr-only">
                                    Increased by
                                    </span>
                                    <?=abs($percentWeek)?>%
                                </div>
                            <?php elseif ($percentWeek < 0): ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="sr-only">
                                    Decreased by
                                    </span>
                                    <?=abs($percentWeek)?>%
                                </div>
                            <?php else: ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-gray-100 text-gray-800 md:mt-2 lg:mt-0">
                                    <span class="sr-only">
                                    Changed by
                                    </span>
                                    0%
                                </div>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="border-t border-gray-200 md:border-0 md:border-l">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-base leading-6 font-normal text-gray-900">
                            Total Hours (Month)
                        </dt>
                        <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                            <div class="flex items-baseline text-2xl leading-8 font-semibold text-indigo-600">
                                <?= $totalHoursThisMonth ?>
                                <span class="ml-2 text-sm leading-5 font-medium text-gray-500">
                                previous <?= $totalHoursLastMonth ?>
                                </span>
                            </div>
                            <?php if ($percentMonth > 0): ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="sr-only">
                                    Increased by
                                    </span>
                                    <?=abs($percentMonth)?>%
                                </div>
                            <?php elseif ($percentMonth < 0): ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="sr-only">
                                    Decreased by
                                    </span>
                                    <?=abs($percentMonth)?>%
                                </div>
                            <?php else: ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-gray-100 text-gray-800 md:mt-2 lg:mt-0">
                                    <span class="sr-only">
                                    Changed by
                                    </span>
                                    0%
                                </div>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="border-t border-gray-200 md:border-0 md:border-l">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-base leading-6 font-normal text-gray-900">
                            Avg. Weekly Hours (last 6 Mo.)
                        </dt>
                        <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                            <div class="flex items-baseline text-2xl leading-8 font-semibold text-indigo-600">
                                <?= number_format((float)$averageHours, 1, '.', ''); ?>
                                <span class="ml-2 text-sm leading-5 font-medium text-gray-500">
                                current <?= $totalHoursThisWeek ?>
                                </span>
                            </div>
                            <?php if ($percentAve > 0): ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="sr-only">
                                    Increased by
                                    </span>
                                    <?=abs($percentAve)?>%
                                </div>
                            <?php elseif ($percentAve < 0): ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="sr-only">
                                    Decreased by
                                    </span>
                                    <?=abs($percentAve)?>%
                                </div>
                            <?php else: ?>
                                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium leading-5 bg-gray-100 text-gray-800 md:mt-2 lg:mt-0">
                                    <span class="sr-only">
                                    Changed by
                                    </span>
                                    0%
                                </div>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-5 sm:p-6 w-full flex items-center justify-center" >
        <div class="w-full  mt-5 rounded-lg bg-white shadow ">
            <div class="bg-white overflow-hidden sm:rounded-lg sm:shadow">
                <div class="flex bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 inline-flex">
                        Timecard
                    </h3>
                    <h4 class="ml-auto text-md leading-6 font-medium text-gray-500 inline-flex">
                        <?= $totalHoursThisWeek ?> Hours
                    </h4>
                </div>   
                
                <ul>
                    <?php $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'); ?>
                    <?php foreach($days as $day): ?>
                        <li class='border-t border-gray-300'>
                            <a class="bg-gray-100 block hover:bg-gray-200 focus:outline-none focus:bg-gray-200 transition duration-150 ease-in-out">
                                <div class="px-4 py-4 sm:px-6">
                                    <?= $day ?>
                                </div>
                            </a>
                        </li>
                        <?php foreach($thisHours as $hours): ?>
                            <?php $date = new Datetime($hours->getDate()); ?>
                            <?php if ($date->format('l') == $day): ?>
                                <?php include 'timecardRow.php'; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</main>