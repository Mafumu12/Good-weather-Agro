<template>

  <div class="block  w-full p-6 bg-[#080A32] mt-2    rounded-lg  ">

    <h5 class="hero">Discover the weather any where in the world</h5>

    <form @submit.prevent="searchCity">

      <div class="relative">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
          </svg>
        </div>
        <input v-model="form.city" type="text" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500   
    " placeholder="Search for any city " required />
        <button type="submit"
          class="text-white absolute end-2.5 bottom-2.5 bg-[#106D15] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2  ">search</button>


      </div>


      <p v-if="inputError" class="error-message">Please enter a city name.</p>
      <p v-if="errors.city" class="error-message">{{ errors.city }}</p>
       

    </form>



  </div>

</template>


<script setup>
import { ref, onMounted } from "vue";
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


onMounted(() => {
  if (props.auth.user) {
    // Fetch search history from the database
    axios
      .get("/search-history")
      .then((response) => {
        history.value = response.data;
      })
      .catch((error) => {
        console.error("Error fetching search history:", error);
      });
  } else {
    // Fetch search history from local storage
    if (localStorage.getItem("searchHistory")) {
      history.value = JSON.parse(localStorage.getItem("searchHistory"));
    }
  }
});

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
    // Authenticated user: update history in the database
    axios
      .post("/search-history", { city })
      .then(() => {
        history.value.unshift(city);
        if (history.value.length > 5) {
          history.value.pop();
        }
      })
      .catch((error) => {
        console.error("Error updating search history:", error);
      });
  } else {
    // Unauthenticated user: update history in local storage
    let searchHistory = history.value;
    searchHistory.push(city);
    if (searchHistory.length > 5) {
      searchHistory.shift();
    }
    localStorage.setItem("searchHistory", JSON.stringify(searchHistory));
    history.value = searchHistory;
  }
}

function selectCity(city) {
  form.city = city;
  searchCity();
}
</script>

<style scoped>
.hero {
  font-family: Prompt;
  font-weight: 400;
  color: #F5F5F5;
  font-size: 24px;
}
</style>