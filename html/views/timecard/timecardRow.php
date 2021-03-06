<li>
    <a href="/daily/view/<?= $hours->getId() ?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                    <?= $hours->getJob()->getNumber() ?> - <?= $hours->getJob()->getName() ?>
                </div>
                <div class="ml-2 flex-shrink-0 flex">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        <?= $hours->getHours() ?> Hours
                    </span>
                </div>
            </div>
            <div class="mt-2 sm:flex sm:justify-between">
                <!--<div class="sm:flex">
                    <div class="mr-6 flex items-center text-sm leading-5 text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                            <?= NULL/*$hours->getWorkType()*/ ?>
                    </div>
                </div>-->
                <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span>
                    <time><?= $date->format('F d, Y')?>
                    </time>
                    </span>
                </div>
            </div>
        </div>
    </a>
</li>