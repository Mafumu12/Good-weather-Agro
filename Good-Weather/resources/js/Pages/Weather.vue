<template>
  <div class="bg-[#31343C] h-screen">
    <NavBar :history="searchHistory" :isAuthenticated="isAuthenticated" @submitCity="fetchWeatherData" />
    <div class="p-4">
      <InputForm :backendError="backendError" @submitCity="fetchWeatherData" />
      <FavouriteCities v-if="isAuthenticated" @submitCity="fetchWeatherData" />
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
import { usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { startCase } from "lodash";

const currentWeather = ref(null);
const sixteenDayForecast = ref(null);
const backendError = ref(null);
const { props: pageProps } = usePage();
const isAuthenticated = pageProps.auth.user !== null;
const searchHistory = ref([]);
console.log('Authenticated user:', pageProps.auth.user);
console.log('isAuthenticated value:', isAuthenticated);

// Fetch search history on mount
onMounted(() => {

  const lastSearchedCity = localStorage.getItem("lastSearchedCity");
  if (lastSearchedCity) {
    fetchWeatherData(lastSearchedCity);
  }


});



// Fetch weather data and update search history only if successful
const fetchWeatherData = async (city) => {
  try {


    const response = await axios.post("/city", { city });
    currentWeather.value = response.data.currentWeather;
    sixteenDayForecast.value = response.data.sixteenDayForecast;

    // Only update search history after a successful response
    updateSearchHistory(city);

    localStorage.setItem("lastSearchedCity", city);
    backendError.value = null;

  } catch (error) {
    console.error("Failed to fetch weather data:", error);
    if (error.response && error.response.data) {
      backendError.value = error.response.data.error;
    }
  }
};


function getSearchHistory() {
  axios.get("/search-history").then((response) => {
    searchHistory.value = response.data;
  });
}

// Update search history function
function updateSearchHistory(city) {

  console.log('search history city', city);
  if (isAuthenticated) {
    axios.post("/search-history", { city }).then(() => {

      getSearchHistory();

    });
  } else {
    try {
      console.log('Unauthenticated, handling local history');
      let localHistory = JSON.parse(localStorage.getItem("searchHistory")) || [];
      console.log('search history', localHistory);
      if (localHistory.includes(city)) {
        let index = localHistory.indexOf(city);
        localHistory.splice(index, 1);
        localHistory.unshift(startCase(city));
      } else {
        localHistory.unshift(startCase(city));
      }
      if (localHistory.length > 5) {
        localHistory.pop();
      }
      localStorage.setItem("searchHistory", JSON.stringify(localHistory));
      searchHistory.value = localHistory;

      console.log('search history', searchHistory.value);

    } catch (e) {
      console.error('Error accessing localStorage:', e);
    }


  }
}
</script>
