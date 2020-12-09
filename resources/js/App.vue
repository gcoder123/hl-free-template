<template>
    <div class="app">
        <div class="app__content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: "App",
    }
</script>

<style lang="scss">
    .app {
        &__content {
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overflow-y: auto;
        }
    }
</style>
