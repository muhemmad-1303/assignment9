<template>
    <button @click="open">filter</button>
    <div v-if="show">
    <label v-for="(name, index) in uniqueNames" :key="index">
      <input type="checkbox" v-model="selectedNames" :value="name">
      {{ name }}
    </label>
 
    

    </div>
</template>
<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { request } from "../helper";
export default{
    setup(){
    let show=ref(false);
    let uniqueNames=ref();
    let selectedNames=ref([]);
    const open=async()=>{
        show.value=!show.value;
        const req = await request("post", "/api/product")
        uniqueNames.value = [...new Set(req.data.data.map(item => item.name))];
    }
    return{
        open,
        show,
        uniqueNames,
        selectedNames,
        
    }
}
}
</script>