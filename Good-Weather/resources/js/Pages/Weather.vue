<template>
  <div class="bg-[#31343C]  min-h-screen h-full">
    <NavBar class="md:hidden" :history="searchHistory" :isAuthenticated="isAuthenticated" @submitCity="fetchWeatherData"
      @historyDeleted="deleteHistoryItem" />

    <nav class="md:bg-white md:border-gray-200   ">
      <div v-if="isAuthenticated">

        <div class="md:flex md:items-center md:justify-center hidden ">
          <FaRegUser />
          <a href="/dashboard" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Profile</a>

        </div>


      </div>


      <div v-else>

        <div class="md:flex md:items-center md:justify-center hidden ">

          <FaSignInAlt />
          <a href="/login" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">SignIn</a>
          <span>/</span>
          <a href="/register" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">SignUp</a>
        </div>



      </div>





    </nav>

    <div class="p-4 md:mx-auto md:w-10/12 md:p-4 md:my-8  lg:my-12">


      <div class="md:flex md:items-center md:justify-between md:mx-auto ">

        <InputForm :backendError="backendError" @submitCity="fetchWeatherData" />
        <div class="hidden md:block">
          <SearchHistory :history="searchHistory" :isAuthenticated="isAuthenticated" @submitCity="fetchWeatherData"
            @historyDeleted="deleteHistoryItem" />

        </div>


      </div>



      <FavouriteCities v-if="isAuthenticated" @submitCity="fetchWeatherData" />
      <CurrentWeather :currentWeather="currentWeather" :loading="loading" />
      <TwelveDayWeather :sixteenDayForecast="sixteenDayForecast" :loading="loading" />
    </div>
  </div>
</template>

<script setup>
import { FaHistory, FaRegUser, FaSignInAlt } from 'vue3-icons/fa';
import CurrentWeather from "@/Components/weather/CurrentWeather.vue";
import TwelveDayWeather from "@/Components/weather/TwelveDayWeather.vue";
import FavouriteCities from "@/Components/weather/FavouriteCities.vue";
import InputForm from "@/Components/weather/InputForm.vue";
import SearchHistory from "@/Components/weather/SearchHistory.vue";
import NavBar from "@/Components/weather/NavBar.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { startCase } from "lodash";

const currentWeather = ref(null);
const sixteenDayForecast = ref(null);
const loading = ref(false);
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


const deleteHistoryItem = (itemId) => {
  searchHistory.value = searchHistory.value.filter(item => item.id !== itemId);
  if (!isAuthenticated) {
    searchHistory.value = JSON.parse(localStorage.getItem("searchHistory")) || [];
  }
};
// Fetch weather data and update search history only if successful
const fetchWeatherData = async (city) => {

  loading.value = true;
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
  finally {
    loading.value = false; // Set loading to false after the request completes
  }
};


function getSearchHistory() {
  axios.get("/search-history").then((response) => {


    searchHistory.value = response.data;
    console.log('history', response.data)
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
