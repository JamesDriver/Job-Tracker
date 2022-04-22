<?php $new = ($job->getId()) ? false : true; ?>
<?php if (!$new): ?>
    <div class="p-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                        Job 12948
                    </h2>
                    <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap">
                        <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            Created on January 9, 2020
                        </div>
                    </div>
                </div>
                <?php if ($job->getCreator()): ?>
                    <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mr-6">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        <?= $job->getCreator()->getName() ?>
                    </div>
                <?php endif; ?>
                <div class="mt-5 flex lg:mt-0 lg:ml-4">
                    <!--<span class="hidden sm:block shadow-sm rounded-md">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:text-gray-800 active:bg-gray-50 transition duration-150 ease-in-out">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                            Edit
                        </button>
                    </span>-->
                    <?php if ($permissions2->jobReport): ?>
                    <span class="hidden sm:block ml-3 shadow-sm rounded-md">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:text-gray-800 active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                            </svg>
                            Generate Report
                        </button>
                    </span>
                    <?php endif; ?>
                    <?php if ($permissions2->jobDispatch): ?>
                    <span class="sm:ml-3 shadow-sm rounded-md">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Dispatch
                        </button>
                    </span>
                    <?php endif; ?>
                    <!-- Dropdown -->
                    <span x-data="{ open: false }" class="ml-3 relative shadow-sm rounded-md sm:hidden">
                        <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:shadow-outline focus:border-blue-300 transition duration-150 ease-in-out" id="mobile-menu" aria-haspopup="true" x-bind:aria-expanded="open">
                            More
                            <svg class="-mr-1 ml-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 -mr-1 w-48 rounded-md shadow-lg" aria-labelledby="mobile-menu" role="menu" style="display: none;">
                            <div class="py-1 rounded-md bg-white shadow-xs">
                                <!--<a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out" role="menuitem">Edit</a>
                                <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out" role="menuitem">View</a>-->
                            </div>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
    <form autocomplete="off">
        <div class="">
            <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5">
                <label for="name" class="block tedxt-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Name
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg flex rounded-md shadow-sm">
                        <input id="name" class="flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value='<?=$job->getName()?>'/>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="status" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Job Status
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <select id="status" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <?php foreach($statuses as $status): ?>
                                <?php $selected = ($status == $job->getStatus()) ? 'selected' : NULL ?>
                                <option <?=$selected?> value='<?=$status->getId()?>'><?=$status->getName()?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="type" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Job Type
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <select id="type" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <?php foreach($types as $type): ?>
                                <?php $selected = ($type == $job->getType()) ? 'selected' : NULL ?>
                                <option <?=$selected?> value='<?=$type->getId()?>'><?=$type->getName()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="managers" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Job Managers
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <select id="managers" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                            <?php foreach($users as $user): ?>
                                <?php $selected = (in_array($user, $job->getManagers())) ? 'selected' : NULL; ?>
                                <option <?=$selected?> value='<?=$user->getId()?>'><?=$user->getName()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="client" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Client
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <select id="client" class="select-2 block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option></option>
                            <?php foreach($clients as $client): ?>
                                <?php $selected = ($client == $job->getClient()) ? 'selected' : NULL; ?>
                                <option <?=$selected?> value='<?=$client->getId()?>'><?=$client->getName()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="workers" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Field Worker(s)
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <select id="workers" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                            <?php foreach($users as $user): ?>
                                <?php $selected = (in_array($user, $job->getWorkers())) ? 'selected' : NULL; ?>
                                <option <?=$selected?> value='<?=$user->getId()?>'><?=$user->getName()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="location" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Location
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg flex rounded-md shadow-sm">
                        <input id="location" class="flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value='<?=$job->getLocation()?>'/>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="bid" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Bid
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg flex rounded-md shadow-sm">
                        <input id="bid" class="flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"  value='<?=$job->getBid()?>' />
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="poNumber" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Po Number
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg flex rounded-md shadow-sm">
                        <input id="poNumber" class="flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"  value='<?=$job->getPoNumber()?>'/>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="description" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Description
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg flex rounded-md shadow-sm">
                        <textarea id="description" rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"><?=$job->getDescription()?></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="notes" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                Notes
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg flex rounded-md shadow-sm">
                        <textarea id="notes" rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"><?=$job->getNotes()?></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label for="cover_photo" class="block text-sm leading-5 font-medium text-gray-700 sm:mt-px sm:pt-2">
                Files
                </label>
                <div class="mt-2 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-600">
                                <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                                Upload a file
                                </button>
                                or drag and drop
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($job->getFiles()): ?>
                <div class="sm:col-span-2">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Attachments
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900">
                        <ul class="border border-gray-200 rounded-md">
                            <?php foreach($job->getFiles() as $file): ?>
                                <?php 
                                    $displayFile = true;
                                    $levels = getJobFileLevels($file->getId());
                                    $allowed = array();
                                    foreach($levels as $level) {
                                        array_push($allowed, $level['allowed']);
                                    }
                                    if (!empty($levels)) {
                                        $currentType = ($currentUser->getType())->getId();
                                        if (!in_array($currentType, $allowed) && ($currentType != 0)) {
                                            $displayFile = false;
                                        }
                                    }
                                ?>
                                <?php if ($displayFile): ?>
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm leading-5">
                                        <div class="w-0 flex-1 flex items-center">
                                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="ml-2 flex-1 w-0 truncate">
                                            <?=$file->getName();?>
                                            </span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
                                            Download
                                            </a>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </dd>
                </div>
            <?php endif; ?>


        </div>
        <div class="mt-8 border-t border-gray-200 pt-5">
            <div class="flex justify-end">
                <span class="inline-flex rounded-md shadow-sm">
                <button type="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                Cancel
                </button>
                </span>
                <span class="ml-3 inline-flex rounded-md shadow-sm">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                Save
                </button>
                </span>
            </div>
        </div>
    </form>
</div>