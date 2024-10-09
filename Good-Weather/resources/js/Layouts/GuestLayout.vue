<script setup>
 
import {  FaHome } from 'vue3-icons/fa';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center py-32 px-4 sm:pt-0 bg-[#23262E] ">
        <div>
            <Link href="/">
                 
                <FaHome class="w-20 h-20 fill-current text-[#EBE9EB]" />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-[#EBE9EB] shadow-md overflow-hidden rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
