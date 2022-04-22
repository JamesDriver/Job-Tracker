<?php 
require_once '/var/www/classes/messages.php';
?>
        <main class="flex-1 flex relative z-0 overflow-y-auto focus:outline-none" tabindex="0" x-init="$el.focus()">
            <div class="relative flex flex-col w-full max-w-xs flex-grow border-l border-r bg-gray-200">
                <div class="flex-shrink-0 px-4 py-2 border-b flex items-center justify-between">
                    <button class="flex items-center text-xs font-semibold text-gray-600">
                        Sorted by Date
                        <span class="leading-loose h-6 w-6 stroke-current text-gray-500">
                            <i class="fas fa-chevron-down ml-1"></i>
                        </span>
                    </button>
                    <button>
                        <span class=" h-6 w-6 stroke-current text-gray-500">
                        <i class="fas fa-sort-amount-up"></i>
                        </span>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <a href="#" class="block px-6 pt-3 pb-4 bg-white">
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-gray-900">Masturah Adam</span>
                            <span class="text-sm text-gray-500">2 days ago</span>
                        </div>
                        <p class="text-sm text-gray-900">Refund</p>
                        <p class="text-sm text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc tempus element...</p>
                    </a>
                    <a href="#" class="block px-6 pt-3 pb-4 bg-white border-t">
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-gray-900">Masturah Adam</span>
                            <span class="text-sm text-gray-500">2 days ago</span>
                        </div>
                        <p class="text-sm text-gray-900">Refund</p>
                        <p class="text-sm text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc tempus element...</p>
                    </a>
                    <a href="#" class="block px-6 pt-3 pb-4 bg-gray-100 border-t">
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-gray-900">Masturah Adam</span>
                            <span class="text-sm text-gray-500">2 days ago</span>
                        </div>
                        <p class="text-sm text-gray-900">Refund</p>
                        <p class="text-sm text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc tempus element...</p>
                    </a>
                </div>
            </div>
            <div class="flex-1 flex flex-col w-0">
                <div class="p-3 flex-1 overflow-y-auto">
                    <article class="px-10 pt-6 pb-8 bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold">
                                <span class="text-gray-900">Masturah Adam</span>
                                <span class="text-gray-600">wrote</span>
                            </p>
                            <div class="flex items-center">
                                <span class="text-xs text-gray-600">Yesterday at 7:24 AM</span>
                                <img 
                                class="ml-5 h-8 w-8 rounded-full object-cover" 
                                src="https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3.5&w=144&q=60">
                            </div>
                        </div>
                        <div class="mt-6 text-gray-800 text-sm">
                            <p>Thanks so much!! Can't wait to try it out :)</p>
                        </div>
                    </article>
                    <article class="mt-3 px-10 pt-6 pb-8 bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold">
                                <span class="text-gray-900">Akanbi Lawal</span>
                                <span class="text-gray-600">wrote</span>
                            </p>
                            <div class="flex items-center">
                                <span class="text-xs text-gray-600">Yesterday at 7:24 AM</span>
                                <img class="ml-5 h-8 w-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=144&q=80">
                            </div>
                        </div>
                        <div class="mt-6 text-gray-800 text-sm">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            </p> 
                            <p class="mt-4">
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                                occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                            <p class="mt-4 font-semibold text-gray-900">Akanbi Lawal</p>
                            <p>Customer Service</p>
                        </div>
                    </article>
                    <article class="mt-3 px-10 pt-6 pb-8 bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold">
                                <span class="text-gray-900">Masturah Adam</span>
                                <span class="text-gray-600">wrote</span>
                            </p>
                            <div class="flex items-center">
                                <span class="text-xs text-gray-600">Yesterday at 7:24 AM</span>
                                <img 
                                class="ml-5 h-8 w-8 rounded-full object-cover" 
                                src="https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3.5&w=144&q=60">
                            </div>
                        </div>
                        <div class="mt-6 text-gray-800 text-sm">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                            <p class="mt-4">
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                                occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                            <p class="mt-4 font-semibold text-gray-900">Masturah Abiola</p>
                            <p>Customer Service</p>
                        </div>
                    </article>
                    <article class="mt-3 px-10 pt-6 pb-8 bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold">
                                <span class="text-gray-900">Masturah Adam</span>
                                <span class="text-gray-600">wrote</span>
                            </p>
                            <div class="flex items-center">
                                <span class="text-xs text-gray-600">Yesterday at 7:24 AM</span>
                                <img 
                                class="ml-5 h-8 w-8 rounded-full object-cover" 
                                src="https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3.5&w=144&q=60">
                            </div>
                        </div>
                        <div class="mt-6 text-gray-800 text-sm">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                            <p class="mt-4">
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                                occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                            <p class="mt-4 font-semibold text-gray-900">Masturah Abiola</p>
                            <p>Customer Service</p>
                        </div>
                    </article>
                    <p class='text-sm text-gray-500'>read</p>
                </div>
                

                <div class="flex-shrink-0 px-4 py-2 border-b flex items-center justify-between bg-gray-200">
                    <input type='text' class='form-input block w-full sm:text-sm sm:leading-5 rounded-full mr-2'>
                    <button class='rounded-full'>
                        <span class="inline-flex h-8 w-8 rounded-full text-white items-center justify-center rounded-full bg-blue-600">
                            <svg class="text-sm h-6 w-6 font-medium leading-none text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13 5.41V21a1 1 0 0 1-2 0V5.41l-5.3 5.3a1 1 0 1 1-1.4-1.42l7-7a1 1 0 0 1 1.4 0l7 7a1 1 0 1 1-1.4 1.42L13 5.4z"></path>
                            </svg>
                        </span>
                    </button>
                </div>

            </div>
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






