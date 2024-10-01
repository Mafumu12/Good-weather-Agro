<template>

    <div v-if="history.length">
        <h3>Search History Component</h3>
        <ul>
            <li v-for="(item, index) in history" :key="index" @click="selectCity(item)">
                {{ item }}
            </li>
        </ul>
    </div>

</template>


<script setup>
import { ref, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

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
</script>