<template>
  <h1>upload file</h1>
  <form @submit.prevent="fileSubmit" method="post">
    <input ref="selectedFile" type="file" />
    <button type="submit">upload</button>
  </form>
  <div class="mainProduct">
    <div class="productList">
        <div class="productHeading">
            <div class="item">Name</div>
            <div class="item">SKU</div>
            <div class="item">Price</div>
            <div class="item">Description</div>
        </div>
        <div v-for="(val, idx) in product" :key="idx">
            <div class="item">{{ val.name }}</div>
            <div class="item">{{ val.sku }}</div>
            <div class="item">{{ val.price }}</div>
            <div class="item">{{ val.description }}</div>
        </div>
    </div>
  </div>
</template>
<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { request } from "../helper";
export default {
  setup() {
    const selectedFile = ref();
    let user = ref();
    let router = useRouter();
    let product=ref();
    onMounted(() => {
      authentication();
      handleProducts();
    });
    const authentication = async () => {
      try {
        const req = await request("get", "/api/user");
        user.value = req.data;
      } catch (e) {
        await router.push("/");
      }
    };
    const handleProducts = async () => {
            try {
                const req = await request('get', '/api/product')
                product.value = req.data.data;
            } catch (e) {
                await router.push('/')
            }
        }
    const fileSubmit = async () => {
    try {
        const formData = new FormData();
        formData.append("file", selectedFile.value.files[0]);
        const uploadUrl = "/api/product";
        const response = await request('post', uploadUrl, formData);
        if (response && response.data) {
            handleProducts();
            console.log("File uploaded successfully!", response.data);
        } else {
            console.error("File upload failed.");
        }
    } catch (error) {
        console.error("Error uploading file:", error);
    }
};

    return {
      selectedFile,
      fileSubmit,
      product,
    };
  },
};
</script>