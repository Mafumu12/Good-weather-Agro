<template>


  <div class="block  w-full p-6 bg-[#080A32] mt-2    rounded-lg  ">


    <div v-for="current in currentWeather.data" :key="current">
      <div class="city-country">
        {{ current.city_name }}, <span>{{ current.country_code }}</span>
      </div>
      <div class=" city-country flex justify-between ">
        <span>Current weather</span>
        <span>{{ current.ob_time }}</span>
      </div>


      <div class="city-country flex justify-between items-center">
        <span> {{ current.temp }}</span>
        <div class="city-country">
          <p>{{ current.weather.description }}</p>
          <p>Feels like Temperature {{ current.app_temp }}</p>
        </div>
      </div>

      <div class="city-country flex justify-between">


        <div>
          <p>wind</p>
          <p>{{ current.wind_spd }}</p>
        </div>

        <div>

          <p>Humidity</p>
          <p>{{ current.rh }}</p>
        </div>



        <div>
          <p>Air Quality</p>
          <p>{{ current.aqi }}</p>

        </div>



      </div>






      <a @click="addToFavorites(current.city_name)">*Add To Favorites*</a>

    </div>


  </div>




</template>


<script setup>
import { usePage, router } from "@inertiajs/vue3";
import axios from "axios";
defineProps({ currentWeather: Object });

const { props: pageProps } = usePage();

function addToFavorites(cityName) {
  // Check if user is authenticated
  if (!pageProps.auth || !pageProps.auth.user) {
    // Redirect to login if not authenticated
    router.visit("/welcome");
    return;
  }

  // If authenticated, proceed with adding to favorites
  axios
    .post("/favorites", { city: cityName })
    .then((response) => {
      console.log("City added to favorites:", response.data);
    })
    .catch((error) => {
      console.error("Error adding city to favorites:", error);
    });
}
</script>

<style scoped>
.city-country {
  font-family: Prompt;
  font-weight: 300;
  color: #F5F5F5;
  font-size: 12px;
}
</style>