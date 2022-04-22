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
</style>
<main id='scrollSaver' class="flex-1 relative z-0 overflow-y-auto focus:outline-none" tabindex="0" x-init="$el.focus()">
    <div>
        <div class="flex flex-col">
            <div class="">
                <div id='element5' class="align-middle inline-block min-w-full shadow sm:rounded-lg border-b border-gray-100">
                    
                    <?php include 'components/table.php' ?>
                    
                </div>
            </div>
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
<script>
$(function() {
    $(window).on("unload", function(e) {
      var scrollPosition = $("#scrollSaver").scrollTop();
      localStorage.setItem("scrollPosition", scrollPosition);
      console.log(scrollPosition);
   });
   if(localStorage.scrollPosition) {
       console.log('here');
       console.log(localStorage.getItem("scrollPosition"))
      $("#scrollSaver").scrollTop(localStorage.getItem("scrollPosition"));
   }
});
function goToDaily(daily, event) {
    if (event.target.classList.contains('options')) {
        return false;
    }
    location=daily;
}

var dailyTable;
function constructDailyTable() {
    dailyTable = new DailyTable(document.getElementById('dailyTableDiv'));
}
</script>






<script src='/starJob.js'></script>