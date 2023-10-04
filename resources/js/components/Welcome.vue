<template>
    <nav>
    <h1><router-link to="/" class="nav">Product</router-link></h1>
    </nav>
</template>
<script>
  import { useRouter } from "vue-router";

</script>
<style scoped>
   nav{
    padding: 10px;
    width: 100%;
    background-color: black;
    color: white;
    position:absolute;
    top: 0;
    right: 0;
    left: 0;
   }
   .nav{
       all: unset;
   }
   
</style>