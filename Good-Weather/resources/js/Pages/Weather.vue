<template>
  <a href="/dashboard">profile</a>
  <InputForm :errors="errors" />

  <CurrentWeather :currentWeather="currentWeather" />
  <TwelveDayWeather :sixteenDayForecast="sixteenDayForecast" />
  <FavouriteCities v-if="isAuthenticated" />
</template>


<script setup>
import CurrentWeather from "@/Components/weather/CurrentWeather.vue";
import TwelveDayWeather from "@/Components/weather/TwelveDayWeather.vue";
import FavouriteCities from "@/Components/weather/FavouriteCities.vue";
import InputForm from "@/Components/weather/InputForm.vue";
import { usePage } from "@inertiajs/vue3";
const props = defineProps({
  currentWeather: Object,
  sixteenDayForecast: Object,
  errors: Object,
});

const { props: pageProps } = usePage();
const isAuthenticated = pageProps.auth.user !== null;

console.log("current weather: ", props.currentWeather);
console.log("16 day weather: ", props.sixteenDayForecast);
</script>