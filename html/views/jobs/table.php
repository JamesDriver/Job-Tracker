<style>
    th {
        position: sticky !important;
        top: 0;
        z-index: 1;
        text-align: left;
        vertical-align: middle !important;
    }
    td { 
        overflow: hidden; 
        text-overflow: ellipsis; 
        word-wrap: break-word;
    }
    table { width: 100%; }
    .select2 {
        width:100% !important;
    }
</style>

<main id='scrollSaver' x-data="{ jobSortOpen: false }" class="flex-1 relative z-0 overflow-y-auto focus:outline-none" tabindex="0" x-init="$el.focus()">
    <div>
        <div class="flex flex-col">
            <?php $isTable = true; ?>
            <div id='element5' class="align-middle inline-block min-w-full shadow sm:rounded-lg border-b border-gray-200">
                <?php include 'components/table.php'; ?>
            </div>
        </div>
    </div>     
    <div id='jobSort'  class="flex-1 fixed overflow-hidden inset-0 z-20 mt-15" x-show="jobSortOpen" style="display: none;">
        <div class="absolute inset-0 overflow-hidden">
            <section @click.away="jobSortOpen = false;" class="absolute inset-y-0 right-0 pl-10 max-w-full flex">
                <div class="w-screen max-w-md" x-description="Slide-over panel, show/hide based on slide-over state." x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
                    <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                        <header class="space-y-1 py-6 px-4 bg-indigo-700 sm:px-6">
                            <div class="flex items-center justify-between space-x-3">
                                <h2 class="text-lg leading-7 font-medium text-white">
                                    Job Sort
                                </h2>
                                <div class="h-7 flex items-center">
                                    <button @click="jobSortOpen = false;" aria-label="Close panel" class="text-indigo-200 hover:text-white transition ease-in-out duration-150">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm leading-5 text-indigo-300">
                                    Apply filters to the currently shown jobs
                                </p>
                            </div>
                        </header>
                        <div class="relative flex-1 py-6 px-4 sm:px-6">
                            <!-- Replace with your content -->
                            <div class="absolute inset-0 py-6 px-4 sm:px-6">
                                
                                <?php 
                                    $selectedStatuses = array();
                                    $selectedTypes = array();
                                    $selectedWorkers = array();
                                    if (isset($jobSort)) {
                                        $selectedStatuses = $jobSort->getStatuses();
                                        $selectedTypes = $jobSort->getTypes();
                                        $selectedWorkers = $jobSort->getWorkers();
                                    }
                                    $users = getUsers(); 
                                    $statuses = getStatuses();
                                    $types = getTypes();
                                ?>
                                <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="statusSort" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                                    Job Statuses
                                    </label>
                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                        <div class="max-w-lg rounded-md shadow-sm">
                                            <select id="statusSort" class="statusSort block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                                                <?php foreach($statuses as $status): ?>
                                                    <?php $selected = (in_array($status->getId(), $selectedStatuses)) ? 'selected' : NULL ?>
                                                    <option <?=$selected?> value='<?=$status->getId()?>'><?=$status->getName()?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="typeSort" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                                    Job Types
                                    </label>
                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                        <div class="w-full rounded-md shadow-sm">
                                            <select id="typeSort" class="typeSort block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                                                <?php foreach($types as $type): ?>
                                                    <?php $selected = (in_array($status->getId(), $selectedStatuses)) ? 'selected' : NULL ?>
                                                    <option <?=$selected?> value='<?=$type->getId()?>'><?=$type->getName()?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="workerSort" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                                    Job Workers
                                    </label>
                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                        <div class="max-w-lg rounded-md shadow-sm">
                                            <select id="workerSort" class="workerSort block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                                                <?php foreach($users as $user): ?>
                                                    <?php $selected = (in_array($status->getId(), $selectedStatuses)) ? 'selected' : NULL ?>
                                                    <option <?=$selected?> value='<?=$user->getId()?>'><?=$user->getName()?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 border-t border-gray-200 pt-5">
                                    <div class="flex justify-end">
                                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                                            <button id='jobSortSubmit' class="jobSortSubmit inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                            Sort
                                            </button>
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <!-- /End replace -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>                 
</main>
<div id='success' class="rounded-md bg-green-50 p-4 bottom-0 hidden"> 
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm leading-5 font-medium text-green-800">
                Success
            </p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button onclick='hideSuccess()' class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
<div id='fail' class="rounded-md bg-red-50 p-4 bottom-0 hidden">
    <div class="flex" >
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm leading-5 font-medium text-red-800">
                Failed
            </p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button onclick='hideFail()' class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:bg-red-100 transition ease-in-out duration-150">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
<script src='/table.js'></script>
<script>
$(document).ready(function() {
    $('#statusSort').select2();
    $('#typeSort').select2();
    $('#workerSort').select2();
});
$(function() {
    $(window).on("unload", function(e) {
      var scrollPosition = $("#scrollSaver").scrollTop();
      localStorage.setItem("scrollPosition", scrollPosition);
   });
   if(localStorage.scrollPosition) {
      $("#scrollSaver").scrollTop(localStorage.getItem("scrollPosition"));
   }
});
function goToJob(job, event) {
    if (event.target.classList.contains('star')) {
        return false;
    }
    if (event.target.classList.contains('userIcon')) {
        return false;
    }
    if (event.target.classList.contains('options')) {
        return false;
    }
    //console.log(event.target.classList);
    location=job;
}
var jobTable;
var jobSort = new JobSort(document, document.getElementById('jobSort'));
function constructJobTable() {
    var jobTable = new JobTable(document.getElementById('jobTableDiv'));
    jobTable.setSort(jobSort);
}
constructJobTable();
</script>






<script src='/starJob.js'></script>