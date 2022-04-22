<li class="border-t border-gray-200 pl-3 pr-4 py-3 flex items-center justify-between text-sm leading-5">
    <div class="w-0 flex-1 flex items-center">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
        </svg>
        <span class="ml-2 flex-1 w-0 truncate">
            <?=$_POST['name']?>
        </span>
    </div>
    <div class="ml-4 flex-shrink-0">
        <a onclick='editNewJobFile(this)' class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
            Edit
        </a>
    </div>
</li>