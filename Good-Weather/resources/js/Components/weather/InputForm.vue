<template>
  <form @submit.prevent="searchCity">
    <input v-model="form.city" type="text" placeholder="city name" />
    <button type="submit">search</button>
    <p v-if="inputError" class="error-message">Please enter a city name.</p>
    <p v-if="errors.city" class="error-message">{{ errors.city }}</p>
    <div v-if="history.length">
      <h3>Search History:</h3>
      <ul>
        <li
          v-for="(item, index) in history"
          :key="index"
          @click="selectCity(item)"
        >
          {{ item }}
        </li>
      </ul>
    </div>
  </form>
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

// Load history from local storage when the component is mounted
/*
onMounted(() => {
  if (localStorage.getItem("searchHistory")) {
    history.value = JSON.parse(localStorage.getItem("searchHistory"));
  }
});
*/
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
/*
function updateSearchHistory(city) {
  let searchHistory = history.value;

  searchHistory.push(city);
  if (searchHistory.length > 5) {
    searchHistory.shift();
  }

  localStorage.setItem("searchHistory", JSON.stringify(searchHistory));
  history.value = searchHistory;
}*/

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