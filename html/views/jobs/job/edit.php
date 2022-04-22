<?php 
//$job = getJobById(32910);
$clients   = getClients();
$users     = getUsers();
$statuses  = getStatuses();
$types     = getTypes();
$userTypes = getUserTypes();
$jobCols   = getJobColumns();
global $permissions2;
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
    .select2-selection__choice {
        margin-bottom:5px;
    }

    .loader {
    border-top-color: #3498db;
    -webkit-animation: spinner 1.5s linear infinite;
    animation: spinner 1.5s linear infinite;
    }

    @-webkit-keyframes spinner {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }

    body {
        background-color: #ffffff;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='192' height='192' viewBox='0 0 192 192'%3E%3Cpath fill='%23000000' fill-opacity='0.22' d='M192 15v2a11 11 0 0 0-11 11c0 1.94 1.16 4.75 2.53 6.11l2.36 2.36a6.93 6.93 0 0 1 1.22 7.56l-.43.84a8.08 8.08 0 0 1-6.66 4.13H145v35.02a6.1 6.1 0 0 0 3.03 4.87l.84.43c1.58.79 4 .4 5.24-.85l2.36-2.36a12.04 12.04 0 0 1 7.51-3.11 13 13 0 1 1 .02 26 12 12 0 0 1-7.53-3.11l-2.36-2.36a4.93 4.93 0 0 0-5.24-.85l-.84.43a6.1 6.1 0 0 0-3.03 4.87V143h35.02a8.08 8.08 0 0 1 6.66 4.13l.43.84a6.91 6.91 0 0 1-1.22 7.56l-2.36 2.36A10.06 10.06 0 0 0 181 164a11 11 0 0 0 11 11v2a13 13 0 0 1-13-13 12 12 0 0 1 3.11-7.53l2.36-2.36a4.93 4.93 0 0 0 .85-5.24l-.43-.84a6.1 6.1 0 0 0-4.87-3.03H145v35.02a8.08 8.08 0 0 1-4.13 6.66l-.84.43a6.91 6.91 0 0 1-7.56-1.22l-2.36-2.36A10.06 10.06 0 0 0 124 181a11 11 0 0 0-11 11h-2a13 13 0 0 1 13-13c2.47 0 5.79 1.37 7.53 3.11l2.36 2.36a4.94 4.94 0 0 0 5.24.85l.84-.43a6.1 6.1 0 0 0 3.03-4.87V145h-35.02a8.08 8.08 0 0 1-6.66-4.13l-.43-.84a6.91 6.91 0 0 1 1.22-7.56l2.36-2.36A10.06 10.06 0 0 0 107 124a11 11 0 0 0-22 0c0 1.94 1.16 4.75 2.53 6.11l2.36 2.36a6.93 6.93 0 0 1 1.22 7.56l-.43.84a8.08 8.08 0 0 1-6.66 4.13H49v35.02a6.1 6.1 0 0 0 3.03 4.87l.84.43c1.58.79 4 .4 5.24-.85l2.36-2.36a12.04 12.04 0 0 1 7.51-3.11A13 13 0 0 1 81 192h-2a11 11 0 0 0-11-11c-1.94 0-4.75 1.16-6.11 2.53l-2.36 2.36a6.93 6.93 0 0 1-7.56 1.22l-.84-.43a8.08 8.08 0 0 1-4.13-6.66V145H11.98a6.1 6.1 0 0 0-4.87 3.03l-.43.84c-.79 1.58-.4 4 .85 5.24l2.36 2.36a12.04 12.04 0 0 1 3.11 7.51A13 13 0 0 1 0 177v-2a11 11 0 0 0 11-11c0-1.94-1.16-4.75-2.53-6.11l-2.36-2.36a6.93 6.93 0 0 1-1.22-7.56l.43-.84a8.08 8.08 0 0 1 6.66-4.13H47v-35.02a6.1 6.1 0 0 0-3.03-4.87l-.84-.43c-1.59-.8-4-.4-5.24.85l-2.36 2.36A12 12 0 0 1 28 109a13 13 0 1 1 0-26c2.47 0 5.79 1.37 7.53 3.11l2.36 2.36a4.94 4.94 0 0 0 5.24.85l.84-.43A6.1 6.1 0 0 0 47 84.02V49H11.98a8.08 8.08 0 0 1-6.66-4.13l-.43-.84a6.91 6.91 0 0 1 1.22-7.56l2.36-2.36A10.06 10.06 0 0 0 11 28 11 11 0 0 0 0 17v-2a13 13 0 0 1 13 13c0 2.47-1.37 5.79-3.11 7.53l-2.36 2.36a4.94 4.94 0 0 0-.85 5.24l.43.84A6.1 6.1 0 0 0 11.98 47H47V11.98a8.08 8.08 0 0 1 4.13-6.66l.84-.43a6.91 6.91 0 0 1 7.56 1.22l2.36 2.36A10.06 10.06 0 0 0 68 11 11 11 0 0 0 79 0h2a13 13 0 0 1-13 13 12 12 0 0 1-7.53-3.11l-2.36-2.36a4.93 4.93 0 0 0-5.24-.85l-.84.43A6.1 6.1 0 0 0 49 11.98V47h35.02a8.08 8.08 0 0 1 6.66 4.13l.43.84a6.91 6.91 0 0 1-1.22 7.56l-2.36 2.36A10.06 10.06 0 0 0 85 68a11 11 0 0 0 22 0c0-1.94-1.16-4.75-2.53-6.11l-2.36-2.36a6.93 6.93 0 0 1-1.22-7.56l.43-.84a8.08 8.08 0 0 1 6.66-4.13H143V11.98a6.1 6.1 0 0 0-3.03-4.87l-.84-.43c-1.59-.8-4-.4-5.24.85l-2.36 2.36A12 12 0 0 1 124 13a13 13 0 0 1-13-13h2a11 11 0 0 0 11 11c1.94 0 4.75-1.16 6.11-2.53l2.36-2.36a6.93 6.93 0 0 1 7.56-1.22l.84.43a8.08 8.08 0 0 1 4.13 6.66V47h35.02a6.1 6.1 0 0 0 4.87-3.03l.43-.84c.8-1.59.4-4-.85-5.24l-2.36-2.36A12 12 0 0 1 179 28a13 13 0 0 1 13-13zM84.02 143a6.1 6.1 0 0 0 4.87-3.03l.43-.84c.8-1.59.4-4-.85-5.24l-2.36-2.36A12 12 0 0 1 83 124a13 13 0 1 1 26 0c0 2.47-1.37 5.79-3.11 7.53l-2.36 2.36a4.94 4.94 0 0 0-.85 5.24l.43.84a6.1 6.1 0 0 0 4.87 3.03H143v-35.02a8.08 8.08 0 0 1 4.13-6.66l.84-.43a6.91 6.91 0 0 1 7.56 1.22l2.36 2.36A10.06 10.06 0 0 0 164 107a11 11 0 0 0 0-22c-1.94 0-4.75 1.16-6.11 2.53l-2.36 2.36a6.93 6.93 0 0 1-7.56 1.22l-.84-.43a8.08 8.08 0 0 1-4.13-6.66V49h-35.02a6.1 6.1 0 0 0-4.87 3.03l-.43.84c-.79 1.58-.4 4 .85 5.24l2.36 2.36a12.04 12.04 0 0 1 3.11 7.51A13 13 0 1 1 83 68a12 12 0 0 1 3.11-7.53l2.36-2.36a4.93 4.93 0 0 0 .85-5.24l-.43-.84A6.1 6.1 0 0 0 84.02 49H49v35.02a8.08 8.08 0 0 1-4.13 6.66l-.84.43a6.91 6.91 0 0 1-7.56-1.22l-2.36-2.36A10.06 10.06 0 0 0 28 85a11 11 0 0 0 0 22c1.94 0 4.75-1.16 6.11-2.53l2.36-2.36a6.93 6.93 0 0 1 7.56-1.22l.84.43a8.08 8.08 0 0 1 4.13 6.66V143h35.02z'%3E%3C/path%3E%3C/svg%3E");
    }
</style>
<div class="sm:hidden">
    <select 
        @change="
        if ($event.target.value == 1) { job=true,daily=false,material=false,permits=false }
        if ($event.target.value == 2) { job=false,daily=true,material=false,permits=false }
        if ($event.target.value == 3) { job=false,daily=false,material=true,permits=false }
        if ($event.target.value == 4) { job=false,daily=false,material=false,permits=true }"
        aria-label="Selected tab" class="form-select block w-full">
        <option selected
                x-bind:value='1'>
            Job
        </option>
        <option
                x-bind:value='2'>
            Daily Reports
        </option>
        <option
                x-bind:value='3'>
            Materials
        </option>
        <option
                x-bind:value='4'>
            Permits & Inspections
        </option>
    </select>
</div>
<div class="hidden sm:block">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex">
            <a x-show='!job'
                @click="job = true, daily=false,material=false,permits=false" 
                :aria-expanded="job ? 'true' : 'false'" 
                :class="{ 'active': job }"
                href="#" class="w-1/4 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                Job
            </a>
            <a x-show='job'
                @click="job = true, daily=false,material=false,permits=false" 
                :aria-expanded="job ? 'true' : 'false'" 
                :class="{ 'active': job }"
                href="#" class="w-1/4 py-4 px-1 text-center border-b-2 border-indigo-500 font-medium text-sm leading-5 text-indigo-600 focus:outline-none focus:text-indigo-800 focus:border-indigo-700">
                Job
            </a>
            <a x-show='!daily'
                @click="daily = true, job=false,material=false,permits=false" 
                :aria-expanded="daily ? 'true' : 'false'" 
                :class="{ 'active': daily }"
                href="#" class="w-1/4 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                Daily Reports
            </a>
            <a x-show='daily'
                @click="daily = true, job=false,material=false,permits=false" 
                :aria-expanded="daily ? 'true' : 'false'" 
                :class="{ 'active': daily }"
                href="#" class="w-1/4 py-4 px-1 text-center border-b-2 border-indigo-500 font-medium text-sm leading-5 text-indigo-600 focus:outline-none focus:text-indigo-800 focus:border-indigo-700">
                Daily Reports
            </a>
            <a x-show='!material'
                @click="material = true, daily=false,job=false,permits=false" 
                :aria-expanded="material ? 'true' : 'false'" 
                :class="{ 'active': material }"
                href="#" class="w-1/4 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                Materials
            </a>
            <a x-show='material'
                @click="material = true, daily=false,job=false,permits=false" 
                :aria-expanded="material ? 'true' : 'false'" 
                :class="{ 'active': material }"
                href="#" class="w-1/4 py-4 px-1 text-center border-b-2 border-indigo-500 font-medium text-sm leading-5 text-indigo-600 focus:outline-none focus:text-indigo-800 focus:border-indigo-700">
                Materials
            </a>
            <a x-show='!permits'
                @click="permits = true, daily=false,material=false,job=false" 
                :aria-expanded="permits ? 'true' : 'false'" 
                :class="{ 'active': permits }"
                href="#" class="w-1/4 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                Permits & Inspections
            </a>
            <a x-show='permits'
                @click="permits = true, daily=false,material=false,job=false" 
                :aria-expanded="permits ? 'true' : 'false'" 
                :class="{ 'active': permits }"
                href="#" class="w-1/4 py-4 px-1 text-center border-b-2 border-indigo-500 font-medium text-sm leading-5 text-indigo-600 focus:outline-none focus:text-indigo-800 focus:border-indigo-700">
                Permits & Inspections
            </a>
        </nav>
    </div>
    <div id='dispatchStatusSuccess' class="rounded-md bg-green-50 p-4 bottom-0 hidden"> 
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
                    <button onclick='document.getElementById("dispatchStatusSuccess").style.display="none";' class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id='dispatchStatusFail' class="rounded-md bg-red-50 p-4 bottom-0 hidden">
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
                    <button onclick='document.getElementById("dispatchStatusFail").style.display="none";' class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:bg-red-100 transition ease-in-out duration-150">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id='dispatchStatus' class="rounded-md bg-blue-50 p-4 bottom-0 hidden"> 
        <div class="flex">
            <div class="flex-shrink-0">
                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-6 w-6"></div>
            </div>
            <div class="ml-3">
                <p class="text-sm leading-5 font-medium text-blue-800">
                    Sending...
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button onclick='document.getElementById("dispatchStatus").style.display="none";' class="inline-flex rounded-md p-1.5 text-blue-500 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 transition ease-in-out duration-150">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>




<main class="flex-1 relative z-0 overflow-y-auto focus:outline-none" tabindex="0" x-init="$el.focus()">
    <div x-show='job'>
        <?php if ($job->isStarred()): ?>
            <div class="p-8 bg-white" x-data="{ nonStar: false, star: true }">
        <?php else: ?>
            <div class="p-8 bg-white" x-data="{ nonStar: true, star: false }">
        <?php endif; ?>
            <div class="max-w-7xl mx-auto">
                <div class="lg:flex lg:items-center lg:justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex text-sm leading-5 text-gray-500 sm:mr-6">
                            <?php if ($job->isStarred()): ?>
                                <div id='starJob' onclick='starJob(<?=$job->getId()?>);' class="inline-blockstar pr-2 pt-2" x-data="{ nonStar: false, star: true }" >
                            <?php else: ?>
                                <div id='starJob' onclick='starJob(<?=$job->getId()?>);' class="inline-blockstar pr-2 pt-2" x-data="{ nonStar: true, star: false }" >
                            <?php endif; ?>
                                    <span class="inline-flex items-center justify-center rounded-full cursor-pointer star">
                                        <span id='star' onclick='hideElement("add");showElement("remove");' x-show='nonStar' @click='nonStar=false, star=true' class="text-2xl text-gray-500 hover:text-yellow-300 star">☆</span>
                                        <span id='nonStar' onclick='hideElement("remove");showElement("add");' x-show='star' @click='nonStar=true, star=false'class="text-2xl text-yellow-300 hover:text-gray-800 star">★</span>
                                    </span>
                                </div>
                            <h2 class="inline-block text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                                <?=$job->getNumber()?>
                            </h2>
                        </div>
                        <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap">
                            <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Created on <?=date('F d, Y', strtotime($job->getCreateDate()));?>
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
                    </div>
                    
                    <div class="mt-5 flex lg:mt-0 lg:ml-4">
                        <!--<span class="hidden sm:block shadow-sm rounded-md">
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:text-gray-800 active:bg-gray-50 transition duration-150 ease-in-out">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Edit
                            </button>
                        </span>-->
                        

                        
                        <!-- Dropdown -->
                        

                        <?php include '/var/www/html/views/jobs/components/options.php'; ?>
                        


                        
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <form autocomplete="off" method='post'>
                <div class="">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5">
                        <label for="name" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Name
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg flex rounded-md shadow-sm">
                                <input name='name' id="name" class="flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value='<?=$job->getName()?>'/>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="status" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Job Status
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg rounded-md shadow-sm">
                                <select name='status' id="status" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
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
                                <select name='type' id="type" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
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
                                <select name='managers[]' id="managers" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
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
                                <select name='client' id="client" class="select-2 block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
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
                                <select name='workers[]' id="workers" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
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
                                <input name='location' id="location" class="flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" value='<?=$job->getLocation()?>'/>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="bid" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Bid
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg flex rounded-md shadow-sm">
                                <input name='bid' id="bid" class="flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"  value='<?=$job->getBid()?>' />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="poNumber" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Po Number
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg flex rounded-md shadow-sm">
                                <input name='poNumber' id="poNumber" class="flex-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"  value='<?=$job->getPoNumber()?>'/>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="description" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Description
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg flex rounded-md shadow-sm">
                                <textarea name='description' id="description" rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"><?=$job->getDescription()?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="notes" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Notes
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg flex rounded-md shadow-sm">
                                <textarea name='notes' id="notes" rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"><?=$job->getNotes()?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="cover_photo" class="block text-sm leading-5 font-medium text-gray-700 sm:mt-px sm:pt-2">
                        Files
                        </label>
                        <div class="mt-2 sm:mt-0 sm:col-span-2">
                            <div id='dropContainer' class="max-w-lg flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <input id='jobFiles' name='files' type="file" class="file" style="display: none;" multiple/>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <button type="button" onclick='document.getElementById("jobFiles").click();' class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                                            Upload a file
                                        </button>
                                        <!--or drag and drop-->
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                            Attachments
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900">
                            <ul id='jobFileList' class="border border-gray-200 rounded-md">
                                <?php if ($job->getFiles()): ?>
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
                                            <?php $numItems = count($userTypes); ?>
                                            <li class="border-t border-gray-200 pl-3 pr-4 py-3 flex items-center justify-between text-sm leading-5">
                                            <input class='json' type='hidden' name='file[]' value='
                                            {
                                                "id": "<?= $file->getId() ?>",
                                                "name": "<?= $file->getName() ?>",
                                                "permissions": [
                                                <?php foreach($userTypes as $userType): ?>
                                                    {
                                                        "id": "<?= $userType->getId() ?>",
                                                        "value": "<?= (in_array($userType->getId(), $allowed)) ? 'true' : 'false'; ?>"
                                                    }
                                                    <?= ($userType===end($userTypes)) ? NULL: ','; ?>
                                                <?php endforeach; ?>
                                                ]
                                            }'>      
                                                <div class="w-0 flex-1 flex items-center">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="fileName ml-2 flex-1 w-0 truncate">
                                                    <?=$file->getName();?>
                                                    </span>
                                                </div>



                                        
                                                <?php foreach($userTypes as $userType): ?>
                                                    <?php 
                                                    $displayType = true;
                                                        if (!in_array($userType->getId(), $allowed) && ($userType != 0)) {
                                                            $displayType = false;
                                                        }
                                                    ?>
                                                    <div x-data="{ open: false, stayOpen: false }"  @mouseleave="open = false" @keydown.escape="open = false" @click.away="open = false" class='userIcon<?=$userType->getId()?> userIcon relative inline-block text-right <?= ($displayType) ? NULL : 'hidden'; ?>'>
                                                        <span @mouseenter="open = !open" class="-ml-2 userIcon inline-flex h-8 w-8 rounded-full text-white shadow-solid items-center justify-center rounded-full bg-gray-500">
                                                            <span class="userIcon text-sm font-medium leading-none text-white"><?= $userType->getName()[0] ?>
                                                            </span>
                                                        </span>
                                                        <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" 
                                                        class="overflow-visible right-0 absolute pt-2 w-56 rounded-md shadow-lg origin-top-right z-10" style="display: none;">
                                                            <div class="rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                                <div class="px-4 py-3">
                                                                    <p class="text-left text-sm leading-5 font-medium text-gray-900 truncate">
                                                                    <?= $userType->getName() ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>






                                                <div class="ml-4 flex-shrink-0 md:block hidden">
                                                    <a href="/file/download/<?=$file->getId()?>" class="cursor-pointer font-medium text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
                                                        Download&nbsp;&nbsp;&nbsp;
                                                    </a>
                                                    <a @click="editFile = true;" class="editFile cursor-pointer font-medium text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
                                                        Edit&nbsp;&nbsp;&nbsp;
                                                    </a>
                                                    <a @click="deleteFile = true;" class="deleteFile cursor-pointer font-medium text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
                                                        Delete
                                                    </a>
                                                </div>
                                                    



                                                <div x-data="{ open: false }" @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left block ml-2 md:hidden">
                                                    <div>
                                                        <span class="rounded-md shadow-sm">
                                                            <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150 " id="options-menu" aria-haspopup="true" aria-expanded="true" x-bind:aria-expanded="open">
                                                                Options
                                                                <svg class="-mr-1 ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                        </span>
                                                    </div>

                                                    <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg ">
                                                        <div class="rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                            <div class="py-1">
                                                                <a href="/file/download/<?=$file->getId()?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Download</a>
                                                                <a @click="editFile = true;" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Edit</a>
                                                                <a @click="deleteFile = true;" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </dd>
                    </div>


                </div>
                <div class="mt-8 border-t border-gray-200 pt-5">
                    <div class="flex justify-end">
                        <span class="inline-flex rounded-md shadow-sm">
                        <!--<button onclick='cancel()' class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                            Cancel
                        </button>-->
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
        

        <div id='dispatchModal' class="fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center" style='display:none;' >
            <div onclick='document.getElementById("dispatchModal").style.display="none" 'x-description="Background overlay, show/hide based on modal state." x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div x-description="Modal panel, show/hide based on modal state." x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div>
                    <div class=" text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                            Dispatch Job
                        </h3>
                        <div class="mt-2">
                            <div class="mt-1 sm:mt-0 sm:col-span-2">

                                <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="last_name" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                                        Users to Dispatch:
                                    </label>
                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                        <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                                            <select name='dispatchWorkers[]' id="dispatchWorkers" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                                                <?php foreach($users as $user): ?>
                                                    <?php $selected = (in_array($user, $job->getWorkers())) ? 'selected' : NULL; ?>
                                                    <option <?=$selected?> value='<?=$user->getId()?>'><?=$user->getName()?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <span class="flex w-full rounded-md shadow-sm sm:col-start-2">
                        <button onclick='dispatchJob(<?=$job->getId()?>)' type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Dispatch
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:col-start-1">
                        <button onclick='document.getElementById("dispatchModal").style.display="none"' type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancel
                        </button>
                    </span>
                </div>
            </div>
        </div>




    <div x-show="editFile" id='jobFileModal' x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center" style='display:none;'>
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>


        <div x-description="Modal panel, show/hide based on modal state." class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-xl sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button @click="editFile = false;" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div>
                <div class="mt-3 text-center sm:mt-5">
                    <div class=" py-4 px-2 bg-white">

                        <input type='hidden' class='fileId'>
                        <div class=''>
                            <div class='text-left'>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    File Name
                                </h3>
                            </div>
                        </div>
                        <div class='mt-4 sm:border-t sm:border-gray-200'>

                            <div class="mt-4 max-w-lg flex rounded-md shadow-sm">
                                <input class="fileName flex-1 form-input block w-full min-w-0 rounded-none rounded-l-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                <span class="fileExt inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    
                                </span>
                            </div>

                        </div>

                        <div class='text-left border-t mt-4'>
                            <h3 class="mt-4 text-lg leading-6 font-medium text-gray-900">
                                User Access
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                                Only selected user types will be able to access this file.
                            </p>
                        </div>
                        <div class="mt-2 sm:border-t sm:border-gray-200">
                            <?php foreach($userTypes as $userType): ?>
                                <div class="permissionItems mt-2 flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type='hidden' class='id' value='<?=$userType->getId()?>'>
                                        <input id="<?=$a = randomString(10)?>" type="checkbox" class="userType<?=$userType->getId()?> userType form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" value='<?= $userType->getId()?>'/>
                                        <div class="ml-3 text-sm leading-5">
                                            <label for="<?=$a?>" class="font-medium text-gray-700"><?=$userType->getName()?></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-6">
                <span class="flex w-full rounded-md shadow-sm">
                    <button type="button" @click="editFile = false;"  class="fileSave inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    Save
                    </button>
                </span>
            </div>
        </div>
    </div>
            



    <div x-show="deleteFile" class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center" style='display:none;'>
        <div x-show="deleteFile" x-description="Background overlay, show/hide based on modal state." x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75">
            </div>
        </div>

        <div x-show="deleteFile" x-description="Modal panel, show/hide based on modal state." x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                        Delete file
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm leading-5 text-gray-500">
                            Are you sure you want to delete this file? It will be permanently removed from our servers forever. This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button @click="deleteFile = false;" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        Delete
                    </button>
                </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <button @click="deleteFile = false;" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        Cancel
                    </button>
                </span>
            </div>
        </div>
    </div>



    <div x-show='daily'>daily</div>
    <div x-show='material'>material</div>
    <div x-show='permits'>permit</div>
</main>
<script src='/jobFiles.js'></script>
<script>
    let jobFileInput = document.getElementById('jobFiles');
    let jobFileList  = document.getElementById('jobFileList');
    let fileModal    = new FileEditModal(document.getElementById('jobFileModal'));
    var handler      = new JobFileHandler(jobFileInput, jobFileList, fileModal);
</script>
<script src='/jobEdit.js'></script>
<script src='/starJob.js'></script>