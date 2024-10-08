<template>

  <div class="city-country   w-full   bg-[#080A32] mt-2 rounded-lg">


    <div class="flex justify-center items-center gap-4 p-4">
      <button type="button"
        class=" flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        @click="prevSlide">
        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30  ">
          <FaLessThan />
        </span>
      </button>
      <h6>16 Day Forecast</h6>
      <button type="button"
        class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        @click="nextSlide">
        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30  ">
          <FaGreaterThan />
        </span>
      </button>

    </div>
    <hr>

    <div class=" w-full overflow-hidden">
      
      

      <div v-for="(chunk, index) in chunkedForecasts" :key="index" v-show="currentSlide === index"
        class="grid grid-cols-4 gap-4">
        <div v-for="forecast in chunk" :key="forecast.datetime" class="forecast-item">
          <div class="day">{{ formatDate(forecast.datetime) }}</div>

          <div>
            <img :src="`https://www.weatherbit.io/static/img/icons/${forecast.weather.icon}.png`"
            :alt="forecast.weather.description" class="weather-icon" />

          </div>
          <div>
          
            <span class="descrip">{{ forecast.weather.description }}</span>
          </div>
          <div class="max-temp">{{ Math.round(forecast.max_temp) }}°C</div>
          <div class="min-temp">{{ Math.round(forecast.min_temp) }}°C</div>
          <div class="wind">{{ Math.round(forecast.wind_spd) }}m/s</div>
        </div>
      </div>


    </div>




  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { FaGreaterThan, FaLessThan } from 'vue3-icons/fa';

const props = defineProps({ sixteenDayForecast: Object });
const currentSlide = ref(0);

// Divide forecasts into chunks of 4
const chunkedForecasts = computed(() => {
  // Check if sixteenDayForecast is available and has a `data` property
  const forecasts = props.sixteenDayForecast?.data || [];
  const chunkSize = 4;
  const chunks = [];
  for (let i = 0; i < forecasts.length; i += chunkSize) {
    chunks.push(forecasts.slice(i, i + chunkSize));
  }
  return chunks;
});

// Functions to navigate the carousel
const nextSlide = () => {
  if (currentSlide.value < chunkedForecasts.value.length - 1) {
    currentSlide.value++;
  }
};

const prevSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

// Format date as 'Wed 8'
function formatDate(datetime) {
  const date = new Date(datetime);
  const day = date.getDate();
  const weekDay = date.toLocaleDateString('en-US', { weekday: 'short' });
  return `${weekDay} ${day}`;
}
</script>

<style scoped>
.city-country {
  font-weight: 700;
  color: #F5F5F5;
  font-size: 12px;
}

.weather-icon {
  width: 40px;
  height: 40px;
}

.forecast-item {
  text-align: center;
}

.day {
  font-weight: 400;
  font-size: 14px;
  color: #F5F5F5;

}

.descrip {
  font-weight: 700;
  color: #F5F5F5;
  font-size: 12px;

}

.max-temp {
  font-weight: 700;
  color: #F5F5F5;
  font-size: 12px;
}

.min-temp {

  font-weight: 600;
  color: #F5F5F5;
  font-size: 12px;

}

.wind {

  font-weight: 400;
  color: #F5F5F5;
  font-size: 12px;

}
</style>
