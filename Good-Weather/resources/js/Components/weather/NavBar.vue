<template>
    <nav class="  bg-[#080A32] rounded-lg ">


        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">



            <button @click="toggleMobileNav" type="button"
                class="inline-flex items-center justify-center p-2 w-10 h-10 text-sm text-[#106D15] rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
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


                <ul class="Menu-list flex flex-col p-2 font-medium mt-0 rounded-lg bg-gray-50 ">

                    <li v-if="isAuthenticated" class="flex items-center">
                        <FaRegUser />
                        <a href="/dashboard" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">profile</a>
                    </li>

                    <li v-else class="flex items-center">
                        <FaSignInAlt />
                        <a href="/login" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">SignIn</a>
                        <span>/</span>
                        <a href="/register" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">SignUp</a>
                    </li>
                    <hr>
                    <ol>
                        <div class="flex items-center">

                            <FaHistory />
                            <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Recent Locations
                            </a>
                        </div>
                        <hr>

                        <li v-for="(location, index) in history" :key="index">
                            <span class="history ml-6"> {{ location }}</span>

                            <hr>
                        </li>
                    </ol>
                    <hr>





                </ul>
            </div>

        </div>






    </nav>




</template>


<script setup>

import { ref, onMounted } from 'vue';
import { FaHistory, FaRegUser, FaSignInAlt } from 'vue3-icons/fa';

const props = defineProps({
    history: Array,
    isAuthenticated: Boolean,
});
const isMobileNavOpen = ref(false);
const toggleMobileNav = () => {
    isMobileNavOpen.value = !isMobileNavOpen.value;
};






</script>

<style scoped>
.Menu {
    font-family: Prompt;
    font-weight: 500;
}

.Menu-list {
    font-family: Prompt;
    font-weight: 400;
}

.history {
    font-family: Prompt;
    font-weight: 300;
}
</style>