<template>
  <h1>Twelve Day weather</h1>


  <div class="block  w-full p-6 bg-[#080A32] mt-2    rounded-lg  ">

    <div v-for="forecast in sixteenDayForecast.data" :key="forecast">
      <p>Date:{{ forecast.datetime }}</p>
      <p>Maximum temperature:{{ forecast.max_temp }}</p>
      <p>Minimum temperature:{{ forecast.min_temp }}</p>
      <p>Wind Speed:{{ forecast.wind_spd }}</p>
      <p>Weather description:{{ forecast.weather.description }}</p>
      <p>Precipitation: {{ forecast.precip }}</p>
      <p>UV index :{{ forecast.uv }}</p>
      <p>Wind direction :{{ forecast.wind_cdir_full }}</p>
      <p>speed :{{ forecast.wind_spd }}</p>
    </div>

  </div>





</template>


<script setup>
defineProps({ sixteenDayForecast: Object });
</script>