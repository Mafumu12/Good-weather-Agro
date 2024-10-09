<template>


  <div class="bg-[#31343C] h-screen ">

    <NavBar :history="searchHistory" :isAuthenticated="isAuthenticated" />

    
    <div class="p-4 " >
      <InputForm :errors="errors" @update-history="setSearchHistory" />
      <FavouriteCities v-if="isAuthenticated" />
      <CurrentWeather :currentWeather="currentWeather" />
      <TwelveDayWeather :sixteenDayForecast="sixteenDayForecast" />
      
    </div>

   


  </div>


</template>


<script setup>
import CurrentWeather from "@/Components/weather/CurrentWeather.vue";
import TwelveDayWeather from "@/Components/weather/TwelveDayWeather.vue";
import FavouriteCities from "@/Components/weather/FavouriteCities.vue";
import InputForm from "@/Components/weather/InputForm.vue";
import NavBar from "@/Components/weather/NavBar.vue";
import { ref, onMounted } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
const props = defineProps({
  currentWeather: Object,
  sixteenDayForecast: Object,
  errors: Object,
});

const { props: pageProps } = usePage();
const isAuthenticated = pageProps.auth.user !== null;
const searchHistory = ref([]);

onMounted(() => {
  if (isAuthenticated) {
    axios.get("/search-history").then((response) => {
      searchHistory.value = response.data;
    });
  } else {
    // Unauthenticated user, use local storage
    if (localStorage.getItem("searchHistory")) {
      searchHistory.value = JSON.parse(localStorage.getItem("searchHistory"));
    }
  }
});

// Update search history when a new city is searched
const setSearchHistory = (history) => {
  searchHistory.value = history;
};

console.log("current weather: ", props.currentWeather);
console.log("16 day weather: ", props.sixteenDayForecast);
</script>