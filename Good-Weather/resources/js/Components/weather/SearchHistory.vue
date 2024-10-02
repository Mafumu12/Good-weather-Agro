<template>

    <div v-if="history.length">


        <div class="Menu-list" v-for="(item, index) in history" :key="index" @click="selectCity(item)">
            {{ item }}
            <hr>
        </div>
        


    </div>

</template>


<script setup>

 






</script>

<style scoped>
.Menu-list {
    font-family: Prompt;
    font-weight: 300;
    font-size: 14px;
}
</style>