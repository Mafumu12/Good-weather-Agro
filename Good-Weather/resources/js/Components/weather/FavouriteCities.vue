<template>
  <div v-if="favorites.length > 0" class="block  w-full p-4 bg-[#399EF8] mt-4  shadow   rounded-lg  ">
    <p class="city-heading">Your Favorite Cities</p>
     
        <div  class="flex    " v-for="favorite in favorites" :key="favorite.id">

          

          <div   class="w-1/3 text-white bg-[#6AD2FF] font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2    "> {{ favorite.city }}</div>
          
        </div>
      
     
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

const favorites = ref([]);
const { props } = usePage();

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
</script>

<style scoped>

.city-heading {
  font-weight: 500;
  color: #F5F5F5;
  font-size: 20px;
  text-align: center;
}


</style>