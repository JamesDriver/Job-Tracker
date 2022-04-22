<!DOCTYPE html>
<head>
    <title>Creating Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tailwindcss/ui@latest/dist/tailwind-ui.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.3.5/dist/alpine.min.js" defer></script>

</head>
<body translate='no' class='bg-gray-100'>
<div class="p-8 bg-gray-800">
    <div class="max-w-7xl mx-auto">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:leading-9 sm:truncate">
                    Configuration:
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-gray-100">
    <form action='/companyCreate/submit' method="POST">
        <div>
            <div>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Company Information
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                    </p>
                </div>
                <input type='hidden' name='price' value='<?=$price?>'>
                <div class="mt-6 sm:mt-5">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="company_name" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Company Name
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='company_name' id="company_name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="company_email" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Email address
                        <p class='text-gray-500 font-normal'>This email will be used for company communications</p>
                        </label>
                        
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg rounded-md shadow-sm">
                                <input required name='company_email' id="company_email" type="email" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="company_phone" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Phone Number
                        <p class='text-gray-500 font-normal'>This number will be used for company communications</p>
                        </label>
                        
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='company_phone' id="company_phone" type="phone" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>


                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="company_country" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Country / Region
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <select required name='company_country' id="company_country" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    <option value='United States'>United States</option>
                                    <option value='Canada'>Canada</option>
                                    <option value='Mexico'>Mexico</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="company_address" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Street address
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg rounded-md shadow-sm">
                                <input required name='company_address' id="company_address" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="company_city" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        City
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='company_city' id="company_city" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="company_state" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        State / Province
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='company_state' id="company_state" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="company_zip" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        ZIP / Postal
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='company_zip' id="company_zip" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8 sm:mt-5 sm:pt-10">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Administrator Account
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                        Account details
                    </p>
                </div>
                <div class="mt-6 sm:mt-5">

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="first_name" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        First Name
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='first_name' id="first_name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="last_name" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Last Name
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='last_name' id="last_name" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="username" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Username
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='username' id="username" minlength="8" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="phone" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Phone
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='phone' id="phone" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="email" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Email
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input required name='email' id="email" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="password" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                        Password
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-xs rounded-md shadow-sm">
                                <input minlength="8" name='password' id="password" type='password' class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" required >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    Next
                </button>
                </span>
            </div>
        </div>
    </form>
</div>


</body>