<template>
  <form @submit.prevent="submitForm">
    <div class="relative">
      <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg class="w-4 h-4 text-[#EBE9EB]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
        </svg>
      </div>
      <input v-model="form.city" type="text"
        class="block w-full p-2 ps-10 text-sm text-[#F5F5F5] border border-[#31343C] rounded-lg bg-[#23262E]"
        placeholder="Search for any city " required />
      <button type="submit"
        class="text-[#23262E] absolute end-1 bottom-1 bg-[#EBE9EB] focus:ring-1 focus:outline-none focus:ring-[#31343C] font-medium rounded-lg text-sm px-4 py-1">search</button>
    </div>

    <p v-if="inputError" class="error-message">Please enter a city name.</p>
    <p v-if="formSubmitted && backendError" class="mt-2 error-message">{{ backendError }}</p>
  </form>
</template>

<script setup>
import { ref } from "vue";
import { startCase } from "lodash";
import { useForm, usePage } from "@inertiajs/vue3";

defineProps({ backendError: String });

const form = useForm({
  city: "",
});
const inputError = ref(false);
const formSubmitted = ref(false);
const emit = defineEmits(["submitCity"]);

function submitForm() {
  formSubmitted.value = true;
  if (!form.city.trim()) {
    inputError.value = true;
    return;
  }

  inputError.value = false;
  emit("submitCity", startCase(form.city)); // Emit the city to the parent component
  form.city = ""; // Clear the input field after submission
}
</script>

<style scoped>
.error-message {
  color: red;
  font-weight: 300;
  font-size: 14px;
  text-align: center;
}
</style>
