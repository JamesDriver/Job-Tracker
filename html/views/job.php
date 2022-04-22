       
       
       
       <div class="sm:hidden">
            <select 
                @change="$event.target.value = !$event.target.value"
                aria-label="Selected tab" class="form-select block w-full">
                <option selected
                        x-bind:value='job'>
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
        </div>
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none" tabindex="0" x-init="$el.focus()">
            <div x-show='job'>
                <?php include '/var/www/views/templates/job.php'; ?>
            </div>
            <div x-show='daily'>daily</div>
            <div x-show='material'>material</div>
            <div x-show='permits'>permit</div>
        </main>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#managers').select2();
        $('#client').select2();
        $('#workers').select2();
    });
</script>









</body>