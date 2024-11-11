<template>


  <div v-if="!loading && currentWeather && currentWeather.data && currentWeather.data.length"
    class="block  w-full p-4 bg-[#399EF8] mt-4 shadow    rounded-lg md:mt-8   md:p-6 ">


    <div v-for="current in currentWeather.data" :key="current">
      <div>
        <p class="city-country">{{ current.city_name }}, <span>{{ current.country_code }}</span></p>
        <div class=" flex items-center justify-between md:my-2">

          <Link @click="addToFavorites(current.city_name)" class="flex items-center gap-1 md: gap-2">
          <span class="gold-icon">
            <FaStar />
          </span>

          <p class="favourites">Add To Favourite Cities</p>

          </Link>

          <span class="date-time">{{ formatDate(current.ob_time) }}</span>
        </div>


      </div>
      <div>
        <span class="current-weather-header  ">Current weather</span>


      </div>


      <div class=" flex justify-start items-center gap-4 my-2 md:justify-between md:gap-2">
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
          <p class="values md:my-1">{{ current.wind_spd }} km/h</p>
        </div>

        <div>

          <p class="attributes-heading">Humidity</p>
          <p class="values md:my-1">{{ current.rh }}%</p>
        </div>



        <div>
          <p class="attributes-heading">Air Quality</p>
          <p class="values md:my-1">{{ current.aqi }}</p>

        </div>



      </div>








    </div>


  </div>
  <div v-else-if="!loading" class="block  w-full p-4 bg-[#399EF8] mt-8 shadow    rounded-lg md:w-full md:mx-auto  ">

    <p class="welcome">Welcome to The Good Weather App! Stay ahead of the skies with instant weather updates for any
      city, anywhere in
      the world!</p>
  </div>




</template>


<script setup>
import { usePage, router, Link } from "@inertiajs/vue3";
import { FaStar } from 'vue3-icons/fa';
import axios from "axios";
import dayjs from 'dayjs';

defineProps({
  currentWeather: Object,
  loading: Boolean
});




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
    .post('/favorites', { city: cityName })
    .then((response) => {

      if (response.data.success) {
        console.log("City added to favorites:", response);
      }



    })
    .catch((error) => {
      console.error("Error adding city to favorites:", error);
      // Optionally show an error message
      alert(`Failed to add ${cityName} to favorites.`);
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
  font-size: 12px;
}

.favourites {

  font-weight: 300;
  color: #F5F5F5;
  font-size: 10px;

}

.current-weather-header {
  font-weight: 500;
  color: #F5F5F5;
  font-size: 12px;
}

.weather-icon {
  width: 80px;
  height: 80px;
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


.date-time {
  font-weight: 600;
  color: #F5F5F5;
  font-size: 12px;
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



.welcome {

  font-weight: 500;
  color: #F5F5F5;
  font-size: 24px;

}

@media (min-width: 768px) {

  .city-country {

    font-weight: 700;
    color: #F5F5F5;
    font-size: 28px;
  }

  .gold-icon {
    color: gold;
    font-size: 20px;
  }

  .favourites {

    font-weight: 300;
    color: #F5F5F5;
    font-size: 16px;

  }

  .current-weather-header {
    font-weight: 500;
    color: #F5F5F5;
    font-size: 18px;
  }


  .weather-icon {
    width: 100px;
    height: 100px;
  }


  .current-temperature {
    font-weight: 400;
    color: #F5F5F5;
    font-size: 88px;
  }

  .super-script {
    font-weight: 300;
    color: #F5F5F5;
    font-size: 56px;
  }

  .weather-description {
    font-weight: 500;
    color: #F5F5F5;
    font-size: 22px;

  }

  .feels-like {
    font-weight: 400;
    color: #F5F5F5;
    font-size: 18px;
  }

  .date-time {
    font-weight: 600;
    color: #F5F5F5;
    font-size: 16px;
  }


  .attributes-heading {
    font-weight: 400;
    color: #F5F5F5;
    font-size: 20px;
  }

  .values {
    font-weight: 500;
    color: #F5F5F5;
    font-size: 18px;
  }



}
</style>