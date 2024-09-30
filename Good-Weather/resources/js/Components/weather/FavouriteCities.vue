<template>
  <div>
    <h2>Your Favorite Cities</h2>
    <div v-if="favorites.length > 0">
      <ul>
        <li v-for="favorite in favorites" :key="favorite.id">
          {{ favorite.city }}
        </li>
      </ul>
    </div>
    <p v-else>You don't have any favorite cities yet.</p>
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