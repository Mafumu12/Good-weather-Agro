<template>





  <form @submit.prevent="searchCity">

    <div class="relative">
      <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg class="w-4 h-4 text-[#EBE9EB]  " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
        </svg>
      </div>
      <input v-model="form.city" type="text" class="block w-full p-2 ps-10 text-sm text-[#F5F5F5] border border-[#31343C]  rounded-lg bg-[#23262E]     
    " placeholder="Search for any city " required />
      <button type="submit"
        class="text-[#23262E] absolute end-1 bottom-1 bg-[#EBE9EB]  focus:ring-1 focus:outline-none focus:ring-[#31343C] font-medium rounded-lg text-sm px-4 py-1  ">search</button>


    </div>


    <p v-if="inputError" class="   error-message">Please enter a city name.</p>
    <p v-if="errors.city" class="mt-2 error-message">{{ errors.city }}</p>


  </form>





</template>


<script setup>
import { ref } from "vue";
import axios from "axios";
import { useForm, usePage } from "@inertiajs/vue3";

defineProps({ currentWeather: Object, errors: Object });
const city = ref("");
const inputError = ref(false);
const form = useForm({
  city: "",
});
const history = ref([]);
const { props } = usePage();
const emit = defineEmits(["update-history"]);




function searchCity() {
  if (!form.city.trim()) {
    inputError.value = true;
    return;
  }

  inputError.value = false;
  form.post("/city", {
    onSuccess: () => {
      // Add the new city to the search history
      updateSearchHistory(form.city);
      form.reset();
    },
    onError: () => {
      form.reset();
    },
  });
}


function updateSearchHistory(city) {
  if (props.auth.user) {
    // For authenticated users, send the search history to the backend.
    axios.post("/search-history", { city }).then(() => {
      history.value.unshift(city);
      if (history.value.length > 5) {
        history.value.pop(); // Keep only the 5 most recent searches
      }
      emit("update-history", history.value); // Emit updated history
    });
  } else {
    // For unauthenticated users, use localStorage
    let searchHistory = JSON.parse(localStorage.getItem("searchHistory")) || []; // Retrieve existing history or start with an empty array

    // Add the new city to the front of the array
    if (!searchHistory.includes(city)) {
      searchHistory.unshift(city);
    }

    // Ensure we only keep the 5 most recent searches
    if (searchHistory.length > 5) {
      searchHistory.pop();
    }

    // Update localStorage
    localStorage.setItem("searchHistory", JSON.stringify(searchHistory));

    // Update the component's history state
    history.value = searchHistory;
    emit("update-history", history.value); // Emit updated history to parent component
  }
}

function selectCity(city) {
  form.city = city;
  searchCity();
}
</script>

<style scoped>
.hero {

  font-weight: 400;
  color: #F5F5F5;
  font-size: 24px;
}

.back-up {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

}

.error-message {
  color: red;
  font-weight: 300;
  font-size: 14px;
  text-align: center;
}
</style>