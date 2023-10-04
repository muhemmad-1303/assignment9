<template>
  <div class="backdrop">
    <div class="modalcard">
      <form @submit.prevent="fileSubmit" method="post">
        <input ref="selectedFile" type="file" />
        <div class="modalbutton">
        <button @click="modalchange">cancel</button>
        <button type="submit">upload</button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { request } from "../helper";
import { computed } from "vue";
export default {
  emits: ['modal','dissappear','handleproduct'],
  setup(props, ctx) {
    const selectedFile = ref();
    ctx.emit('modal')
    const modalchange=()=>{
        ctx.emit('dissappear')
    }
    const fileSubmit = async (e) => {
            try {
                e.preventDefault();
                const formData = new FormData();
                if(selectedFile.value.files[0]){
                formData.append("file", selectedFile.value.files[0])};
                const uploadUrl = "/api/product";
                const response = await request("post", uploadUrl, formData);
                if (response && response.data) {
                    console.log("File uploaded successfully!", response.data);
                    ctx.emit('dissappear');
                    ctx.emit('handleproduct')
                }
                else {
                    console.error("File upload failed.");
                }
            }
            catch (error) {
                console.error("Error uploading file:", error);
            }
        };
  return {
    fileSubmit,
    selectedFile,
    modalchange,
  }
}
   
}
</script>
<style scoped>
.backdrop {
    position: absolute;
    background-color: rgba(0,0,0,0.5);
    width: 100vw;
    height: 100vh;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}
.modalcard{
    background-color: white;
    box-shadow: 1px 2px 3px black;
    width: 300px;
    height: 250px;
    box-shadow: 2px 2px 3px white;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-content: center;
    padding: 30px;
    box-sizing: border-box;
}
form{
    display: flex;
    flex-direction: column;
    gap: 20px;
    align-items: center;
}
input[type="file"]{
    font-size: 12px;
    position: absolute;
    background-color: #1877f2;
    padding: 10px;
    border-radius: 10px;
}
.modalbutton{
    margin: auto auto;
    display: flex;
    gap: 10px;
}
button{
    border: 0;
    outline: 0;
    background: white;
    border-radius:10px;
    padding:10px;

}
</style>