<template>


  <div class="block  w-full p-4 bg-[#399EF8] mt-4 shadow    rounded-lg  ">


    <div v-for="current in currentWeather.data" :key="current">
      <div>
        <p class="city-country">{{ current.city_name }}, <span>{{ current.country_code }}</span></p>
        <div class=" flex items-center justify-between">

          <Link @click="addToFavorites(current.city_name)" class="flex items-center gap-1">
            <span class="gold-icon">
              <FaStar />
            </span>
            
            <p class="favourites" >Add To Favourite Cities</p>

          </Link>

          <span class="date-time">{{ formatDate(current.ob_time) }}</span>
        </div>


      </div>
      <div>
        <span class="current-weather-header  ">Current weather</span>


      </div>


      <div class=" flex justify-start items-center gap-4 my-2">
        <img :src="`https://www.weatherbit.io/static/img/icons/${current.weather.icon}.png`"
          :alt="current.weather.description" class="weather-icon" />
        <span class="current-temperature"> {{ Math.round(current.temp) }}<sup class="super-script">°C</sup></span>
        <div class="">

          <p class="weather-description">{{ current.weather.description }}</p>
          <p class="feels-like">Feels Like {{ Math.round(current.app_temp) }} <sup>°</sup></p>
        </div>
      </div>

      <div class="  flex justify-between items-center">


        <div>
          <p class="attributes-heading">wind</p>
          <p class="values">{{ current.wind_spd }} km/h</p>
        </div>

        <div>

          <p class="attributes-heading">Humidity</p>
          <p class="values">{{ current.rh }}%</p>
        </div>



        <div>
          <p class="attributes-heading">Air Quality</p>
          <p class="values">{{ current.aqi }}</p>

        </div>



      </div>








    </div>


  </div>




</template>


<script setup>
import { usePage, router, Link } from "@inertiajs/vue3";
import { FaHistory, FaRegUser, FaSignInAlt, FaStar } from 'vue3-icons/fa';
import axios from "axios";
import dayjs from 'dayjs';

defineProps({ currentWeather: Object });

const { props: pageProps } = usePage();

function formatDate(dateTime) {
  const date = dayjs(dateTime); // Parse the string into a dayjs object
  const daySuffix = getDaySuffix(date.date());
  return `${date.format('D')}${daySuffix} ${date.format('MMMM YYYY, hh:mm A')} `;
}
function getDaySuffix(day) {
  // Add suffix (st, nd, rd, th) to day of the month
  if (day > 3 && day < 21) return 'th'; // General case for 11th-19th
  switch (day % 10) {
    case 1: return 'st';
    case 2: return 'nd';
    case 3: return 'rd';
    default: return 'th';
  }

}

function addToFavorites(cityName) {
  // Check if user is authenticated
  if (!pageProps.auth || !pageProps.auth.user) {
    // Redirect to login if not authenticated
    router.visit("/login");
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

  font-weight: 700;
  color: #F5F5F5;
  font-size: 16px;
}

.gold-icon {
  color: gold;
  font-size:12px;
}

.date-time {
  font-weight: 600;
  color: #F5F5F5;
  font-size: 12px;
}

.current-weather-header {
  font-weight: 500;
  color: #F5F5F5;
  font-size: 12px;
}

.current-temperature {
  font-weight: 400;
  color: #F5F5F5;
  font-size: 68px;
}

.super-script {
  font-weight: 300;
  color: #F5F5F5;
  font-size: 40px;
}

.weather-description {
  font-weight: 500;
  color: #F5F5F5;
  font-size: 16px;

}

.feels-like {
  font-weight: 400;
  color: #F5F5F5;
  font-size: 14px;
}

.weather-icon {
  width: 80px;
  height: 80px;
}

.attributes-heading {
  font-weight: 400;
  color: #F5F5F5;
  font-size: 16px;
}

.values {
  font-weight: 500;
  color: #F5F5F5;
  font-size: 14px;
}

.favourites {

  font-weight: 300;
  color: #F5F5F5;
  font-size: 10px;

}
</style>