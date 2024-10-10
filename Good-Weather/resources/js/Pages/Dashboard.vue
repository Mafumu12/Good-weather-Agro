<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template>
            
        </template>
 
        <div class="py-12 p-4 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-[#23262E] overflow-hidden shadow-sm rounded-lg">
                    <div class="forecast-heading p-6 ">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.forecast-heading
{
    font-weight: 500;
  color: #F5F5F5;
  font-size: 16px;
}

</style>
