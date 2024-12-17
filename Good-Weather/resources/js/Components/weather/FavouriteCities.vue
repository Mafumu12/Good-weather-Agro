<template>
  <div v-if="favorites.length > 0">
    <div class="flex items-center justify-between px-4 mt-4 md:mt-8 md:justify-center lg:mt-12">
      <button type="button"
        class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        @click="prevSlide" :disabled="currentSlide === 0">
        <span class=" arrows inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30">
          <FaLessThan />
        </span>
      </button>

      <p class="city-heading">Your Favorite Cities</p>

      <!-- Next Button -->
      <button type="button"
        class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        @click="nextSlide" :disabled="currentSlide === chunkedCities.length - 1">
        <span class="arrows inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30">
          <FaGreaterThan />
        </span>
      </button>



    </div>

    <div :class="chunkedCities[currentSlide].length === 1 ? 'grid-cols-1' : 'grid-cols-2'"
      class="grid gap-4 mt-4 md:mt-8 lg:mt-12 ">



      <div v-for="favorite in chunkedCities[currentSlide]" :key="favorite.id" :class="{
        'justify-between mx-auto w-1/2 lg:w-1/5': chunkedCities[currentSlide].length === 1,
        'justify-between w-full lg:w-full lg:mx-auto': chunkedCities[currentSlide].length > 1
      }" class="flex items-center  text-white bg-[#23262E] cursor-pointer  font-medium rounded-lg text-sm px-5 py-2.5">
        <span @click="fetchWeather(favorite.city)">{{ favorite.city }}</span>
        <FaTrash @click="deleteCity(favorite.id)" />
      </div>
    </div>







  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { FaEllipsisV, FaLessThan, FaGreaterThan, FaTrash } from 'vue3-icons/fa';
import axios from 'axios';
const favorites = ref([]);
const { props } = usePage();
const currentSlide = ref(0);
const emit = defineEmits(["submitCity"]);
const form = useForm({
  city: '', // Initialize the city field
});

const fetchWeather = (city) => {
  form.city = city; // Set the city in the form
  emit("submitCity", form.city);


};

// Check if the user is authenticated
if (props.auth.user) {
  onMounted(() => {
    axios
      .get("/favorites")
      .then((response) => {
        favorites.value = response.data;
        console.log("favourite cities:", (favorites.value = response.data));
      })
      .catch((error) => {
        console.error("Error fetching favorite cities:", error);
      });
  });
}


const chunkedCities = computed(() => {
  // Check if sixteenDayForecast is available and has a `data` property

  const chunkSize = 2;
  const chunks = [];
  for (let i = 0; i < favorites.value.length; i += chunkSize) {
    chunks.push(favorites.value.slice(i, i + chunkSize));
  }
  return chunks;
});


const nextSlide = () => {
  if (currentSlide.value < chunkedCities.value.length - 1) {
    currentSlide.value++;
  }
};

const prevSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

const deleteCity = (cityId) => {
  if (confirm("Are you sure you want to delete this city?")) {
    axios
      .delete(`/favorites/${cityId}`)
      .then(() => {
        // Update the local favorites list after deletion
        favorites.value = favorites.value.filter(favorite => favorite.id !== cityId);
      })
      .catch((error) => {
        console.error("Error deleting city:", error);
      });
  }
};
</script>

<style scoped>
.city-heading {
  font-weight: 500;
  color: #F5F5F5;
  font-size: 16px;
  text-align: center;
}

.arrows {
  color: #EBE9EB;
  font-size: 12px;
}
</style>