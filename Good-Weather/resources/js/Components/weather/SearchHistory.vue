<template>

    <div v-if="isAuthenticated">

        <div class="md:flex  md:justify-center  md:items-center">

            <button type="button"
                class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                @click="prevSlide" :disabled="currentSlide === 0">
                <span class=" arrows inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30">
                    <FaLessThan />
                </span>
            </button>

            <div class="md:flex md:justify-center gap-2">
                <div v-for="item in chunkedCities[currentSlide]" :key="item.id"
                    class="flex md:items-center md:justify-between  md:w-[150px] text-white bg-[#23262E] font-medium rounded-lg text-sm px-5 py-2.5">
                    <span @click="fetchWeather(item.city)">{{ item.city }}</span>
                    <FaTrash @click="deleteHistory(item.id)" />
                </div>

            </div>

            <button type="button"
                class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                @click="nextSlide" :disabled="currentSlide === chunkedCities.length - 1">
                <span class="arrows inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30">
                    <FaGreaterThan />
                </span>
            </button>


        </div>

    </div>

    <div v-else>

        <div class="md:flex  md:justify-center  md:items-center">
            <button type="button"
                class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                @click="prevSlide" :disabled="currentSlide === 0">
                <span class=" arrows inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30">
                    <FaLessThan />
                </span>
            </button>
            <div class="md:flex md:justify-center gap-2">

                <div v-for="(item, index) in chunkedCities[currentSlide]" :key="index"
                    class="flex md:items-center md:justify-between  md:w-[150px] text-white bg-[#23262E] font-medium rounded-lg text-sm px-5 py-2.5">
                    <span @click="fetchWeather(item)">{{ item }}</span>
                    <FaTrash @click="deleteHistory(item)" />
                </div>


            </div>


            <!-- Next Button -->
            <button type="button"
                class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                @click="nextSlide" :disabled="currentSlide === chunkedCities.length - 1">
                <span class="arrows inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30">
                    <FaGreaterThan />
                </span>
            </button>



        </div>







    </div>


</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { FaEllipsisV, FaLessThan, FaGreaterThan, FaTrash } from 'vue3-icons/fa';


const currentSlide = ref(0);


const props = defineProps({
    history: Array,
    isAuthenticated: Boolean,
});
const emit = defineEmits(["submitCity", "historyDeleted"]);
const form = useForm({
    city: '', // Initialize the city field
});





const chunkedCities = computed(() => {
    const cities = props.history || []; // Provide a default empty array if history is undefined
    const chunkSize = 2;
    const chunks = [];
    for (let i = 0; i < cities.length; i += chunkSize) {
        chunks.push(cities.slice(i, i + chunkSize));
    }
    return chunks;
});



const nextSlide = () => {
    if (currentSlide.value < chunkedCities.value.length - 1) {
        currentSlide.value++;
    }
};

const prevSlide = () => {
    if (currentSlide.value > 0) {
        currentSlide.value--;
    }
};

const fetchWeather = (city) => {
    form.city = city; // Set the city in the form
    emit("submitCity", form.city);
    toggleMobileNav(); // Use the post method from useForm

};

const deleteHistory = (itemId) => {
    if (props.isAuthenticated) {
        axios.delete(`/historyItem/${itemId}`).then(() => {


            emit("historyDeleted", itemId);

            console.log('successful');

        })
    }
    else {

        let localHistory = JSON.parse(localStorage.getItem("searchHistory")) || [];
        if (localHistory.includes(itemId)) {
            let index = localHistory.indexOf(itemId);
            localHistory.splice(index, 1);

            localStorage.setItem("searchHistory", JSON.stringify(localHistory));

            emit("historyDeleted", itemId);

        }

    }
} 
</script>

<style scoped>
.city-heading {
    font-weight: 500;
    color: #F5F5F5;
    font-size: 16px;
    text-align: center;
}

.arrows {
    color: #EBE9EB;
    font-size: 12px;
}
</style>