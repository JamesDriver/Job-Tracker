<?php 
$users = getUsers();
$messages = getMessages();
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
            <?php foreach($messages->getSideMessages() as $message): ?>
                <?php include 'side.php'; ?>
            <?php endforeach; ?>
        </div>
        <div class="flex-1 flex flex-col w-0">
            <div class="p-3 flex-1 overflow-y-auto">
                <?php if ($id): ?>
                    <?php foreach($messages->getMessagesBy($id) as $message): ?>
                        <?php include 'message.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if (isset($id)): ?>
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
            <?php endif; ?>
        </div>
    </div>
</main>