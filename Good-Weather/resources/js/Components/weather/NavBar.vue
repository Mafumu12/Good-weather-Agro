<template>
    <nav class="">


        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">



            <button @click="toggleMobileNav" type="button"
                class="inline-flex items-center justify-center p-2 w-10 h-10 text-sm text-[#EBE9EB] rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                aria-controls="navbar-hamburger" :aria-expanded="isMobileNavOpen.toString()">

                <svg v-if="!isMobileNavOpen" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>


            </button>


            <div :class="{ 'translate-x-0': isMobileNavOpen, '-translate-x-full': !isMobileNavOpen }"
                class="fixed top-0 left-0 w-full h-full bg-gray-50  transform transition-transform duration-300 ease-in-out z-50"
                id="navbar-hamburger">


                <div class="flex justify-between">

                    <p class="Menu p-2">Menu</p>
                    <button @click="toggleMobileNav" type="button"
                        class=" p-2 w-10 h-10 text-sm text-[#262439]  hover:bg-[#262439]"
                        aria-controls="navbar-hamburger" :aria-expanded="isMobileNavOpen.toString()">

                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>

                    </button>


                </div>


                <ul class="Menu-list flex flex-col p-2 font-medium mt-0   ">

                    <li v-if="isAuthenticated" class="flex items-center">
                        <FaRegUser />
                        <a href="/dashboard" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Profile</a>
                    </li>

                    <li v-else class="flex items-center">
                        <FaSignInAlt />
                        <a href="/login" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">SignIn</a>
                        <span>/</span>
                        <a href="/register" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">SignUp</a>
                    </li>
                    <hr>
                    <li>
                        <div class="flex items-center">

                            <FaHistory />
                            <span href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Recent
                                Locations
                            </span>
                        </div>
                        <hr>

                        <div v-for="(location, index) in history" :key="index">
                            <span class="history ml-6 cursor-pointer" @click="fetchWeather(location)"> {{ location
                                }}</span>

                            <hr>
                        </div>
                    </li>






                </ul>
            </div>

        </div>






    </nav>




</template>


<script setup>

import { ref } from 'vue';
import { FaHistory, FaRegUser, FaSignInAlt } from 'vue3-icons/fa';
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    history: Array,
    isAuthenticated: Boolean,
});

const form = useForm({
    city: '', // Initialize the city field
});
const isMobileNavOpen = ref(false);
const toggleMobileNav = () => {
    isMobileNavOpen.value = !isMobileNavOpen.value;
};

const fetchWeather = (city) => {
    form.city = city; // Set the city in the form
    form.post('/city', { city });
    toggleMobileNav(); // Use the post method from useForm
        
};



</script>

<style scoped>
.history {

    font-size: 14px;
    font-weight: 400;

}
</style>