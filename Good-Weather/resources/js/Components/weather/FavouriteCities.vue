<template>
  <div v-if="favorites.length > 0">
    <div class="flex items-center justify-between ">
      <button type="button"
        class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        @click="prevSlide" :disabled="currentSlide === 0">
        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30">
          <FaLessThan />
        </span>
      </button>

      <p class="city-heading">Your Favorite Cities</p>

      <!-- Next Button -->
      <button type="button"
        class="  flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        @click="nextSlide" :disabled="currentSlide === chunkedCities.length - 1">
        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/30">
          <FaGreaterThan />
        </span>
      </button>

    </div>

    <div class="grid grid-cols-2 gap-4">



      <div v-for="favorite in chunkedCities[currentSlide]" :key="favorite.id"
        class="flex items-center justify-between w-full text-white bg-[#23262E] font-medium rounded-lg text-sm px-5 py-2.5">
        {{ favorite.city }}
        <FaEllipsisV />
      </div>
    </div>

    <!-- Previous Button -->






  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { FaEllipsisV, FaLessThan, FaGreaterThan } from 'vue3-icons/fa';

const favorites = ref([]);
const { props } = usePage();
const currentSlide = ref(0);

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
</script>

<style scoped>
.city-heading {
  font-weight: 500;
  color: #F5F5F5;
  font-size: 20px;
  text-align: center;
}
</style>