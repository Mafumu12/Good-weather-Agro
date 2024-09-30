<template>
  <h1>Current Weather</h1>
  <div v-if="currentWeather && currentWeather.data">
    <div v-for="current in currentWeather.data" :key="current">
      <p>Temperature:{{ current.temp }}</p>
      <p>Feels like Temperature:{{ current.app_temp }}</p>
      <p>Weather description:{{ current.weather.description }}</p>
      <p>Wind Speed:{{ current.wind_spd }}</p>
      <p>Humidity:{{ current.rh }}</p>
      <p>Air Quality:{{ current.aqi }}</p>
      <p>Country Code:{{ current.country_code }}</p>
      <p>TimeStamp:{{ current.ob_time }}</p>
      <p>
        City:{{ current.city_name }}
        <a @click="addToFavorites(current.city_name)">*Add To Favorites*</a>
      </p>
    </div>
  </div>
  <p v-else>No weather data available.</p>
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