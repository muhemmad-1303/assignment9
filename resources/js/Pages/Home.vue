<template>
  <div class="nav">
    <div class="logout" @click="handleLogout">LogOut</div>
    <div class="welcome">Hello {{ user && user.name }}</div>
  </div>
  <UploadModal
    @modal="hidePagination"
    @dissappear="showPagination"
    v-show="modalshow"
    @handleproduct="handleProducts"
  />
  <div class="search">
    <div class="home" @click="handleProducts">🏠</div>
    <div class="updateButton" @click="modalshow=true;hidePagination()">
      upload
    </div>
    <form @submit.prevent="handleSearch" method="post">
      <input
        type="text"
        v-model="search"
        placeholder="search for the product"
      />
      <button type="submit">&#128269;</button>
    </form>
  </div>
  <div class="mainProduct">
    <div class="productList">
      <div class="productHeading " v-if="productCheck">
        <div class="item">Name <span @click="sort('name', 'DESC')">&#8593;</span><span @click="sort('name', 'ASC')">&#x2193;</span></div>
        <div class="item">SKU <span @click="sort('sku', 'DESC')">&#8593;</span><span @click="sort('sku', 'ASC')">&#x2193;</span></div>
        <div class="item">Price<span @click="sort('price', 'DESC')">&#8593;</span><span @click="sort('price', 'ASC')">&#x2193;</span></div>
        <div class="item">Description</div>
      </div>
      <div v-else>
           <h1 style="color: black;">No product data available</h1>
      </div>
      <div v-if="product">
        <div class="productItems" v-for="(val, idx) in product.data" :key="idx">
          <div class="item">{{ val.name }}</div>
          <div class="item">{{ val.sku }}</div>
          <div class="item">{{ val.price }}</div>
          <div class="item">{{ val.description }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="pagination" v-show="PaginationShow">
    <nav aria-label="Page navigation example">
      <ul class="pagination" v-if="ProductLink && product.data.length">
        <li class="page-item" v-for="(link, index) in ProductLink" :key="index">
          <a
            class="page-link"
            v-if="index != 0 && index != ProductLink.length - 1"
            v-html="link.label"
            @click.prevent="changePage(link)"
          ></a>
        </li>
      </ul>
      <ul class="pagination" v-if="searchLink && product.data.length">
        <li class="page-item" v-for="(link, index) in searchLink" :key="index">
          <a
            class="page-link"
            v-if="index != 0 && index != searchLink.length - 1"
            v-html="link.label"
            @click.prevent="changePage(link)"
          ></a>
        </li>
      </ul>
      <ul class="pagination" v-if="filterLink">
        <li class="page-item" v-for="(link, index) in filterLink" :key="index">
          <a
            class="page-link"
            v-if="index != 0 && index != filterLink.length - 1"
            v-html="link.label"
            @click.prevent="changePage(link)"
          ></a>
        </li>
      </ul>
    </nav>
  </div>
</template>
<script>
import { ref, onMounted,watch } from "vue";
import { useRouter } from "vue-router";
import { request } from "../helper";
import UploadModal from "../components/UploadModal.vue";


export default {
  components: { UploadModal},

  setup() {
    let user = ref();
    let productCheck=ref(true);
    let router = useRouter();
    let product = ref();
    let ProductLink = ref();
    let searchLink = ref();
    let search = ref("");
    let PaginationShow = ref(true);
    let filterLink = ref("");
    let modalshow = ref();
    let whatTo = ref("");
    let method = ref("");
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
        search.value="";
        const req = await request("get", "/api/product");
        product.value = req.data.data;
        if(!req.data.data.data.length){
          productCheck.value=false;
        }
        else{
          productCheck.value=true;
        }
        ProductLink.value = product.value.links;
      } catch (e) {
        await router.push("/");
      }
    };
   
     
    const handleSearch = async () => {
      if (search.value === "") {
        searchLink.value = "";
        productCheck.value=true;
        handleProducts();
        return;
      } else {
        const req = await request("post", "/api/product", {
          search: search.value,
        });
        product.value = "";
        ProductLink.value = "";
        filterLink.value = "";
        product.value = req.data.data;
        searchLink.value = product.value.links;
        if(!req.data.data.data.length){
          productCheck.value=false;
          searchLink.value="";
        }
        else{
          productCheck.value=true;
        }
        
        
      }
    };
    const changePage = async (link) => {
      if (!searchLink.value && !filterLink.value) {
        const req = await request("get", link.url);
        product.value = req.data.data;
      } else if (searchLink.value) {
        const req = await request("post", link.url, {
          search: search.value,
        });
        product.value = req.data.data;
      } else if (filterLink.value) {
        const req = await request("post", link.url, {
          name: whatTo.value,
          sort: method.value,
        });
        product.value = req.data.data;
      }
    };
    const handleLogout = () => {
      localStorage.removeItem("APP_DEMO_USER_TOKEN");
      router.push("/");
    };

    function hidePagination() {
      PaginationShow.value = false;
    }
    function showPagination() {
      modalshow.value = false;
      PaginationShow.value = true;
      productCheck.value=true;
      
    }

    const sort = async (what, methodsort) => {
      whatTo.value = what;
      method.value = methodsort;
      const req = await request("post", "/api/product/sort", {
        name: what,
        sort: methodsort,
      });
      product.value = "";
      ProductLink.value = "";
      searchLink.value = "";
      product.value = req.data.data;
      console.log(product.value);
      filterLink.value = product.value.links;
    };
    return {
      handleLogout,
      sort,
      product,
      ProductLink,
      user,
      changePage,
      search,
      handleSearch,
      handleProducts,
      searchLink,
      PaginationShow,
      hidePagination,
      showPagination,
      modalshow,
      filterLink,
      whatTo,
      method,
      productCheck,
    };
  },
};
</script>

<style scoped>
.mainProduct {
  margin: 40px;
  color: aliceblue;
  box-shadow: 3px 3px 3px white;
  font-family: Arial, Helvetica, sans-serif;
}
.productList {
  display: flex;
  flex-direction: column;
}
.productHeading {
  display: flex;
  background-color: #451952;
}
.productHeading .item {
  width: 25%;
  text-align: center;
  padding: 20px;
}



.productItems {
  display: flex;
  background-color: #f0f0f0;
  color: #445069;
}
.productItems:nth-child(odd) .item {
  background-color: white;
}
.productItems .item {
  width: 25%;
  text-align: center;

  padding: 20px;
}
.nav {
  background-color: black;
  padding: 30px;
  box-shadow: 1px 1px 1px 1px black;
  position: relative;
}
.logout {
  position: absolute;
  background: grey;
  padding: 10px;
  color: white;
  border-radius: 10px;
  right: 15px;
  top: 12px;
  font-family: Arial, Helvetica, sans-serif;
}
.welcome {
  background: grey;
  padding: 10px;
  color: white;
  position: absolute;
  border-radius: 10px;
  top: 10px;
}
.pagination {
  display: flex;
  justify-content: center;
}
.search {
  display: flex;
  justify-content: space-evenly;
  margin-top: 20px;
}
.search form {
  display: flex;
  gap: 10px;
}
.search input[type="text"] {
  outline: 0;
  border: 0;
  border-radius: 10px;
  box-shadow: 1px 1px 2px black;
  padding: 10px;
  background-color: rgb(255, 255, 255);
  width: 500px;
}
.search button[type="submit"] {
  border: 0;
  outline: 0;
  border-radius: 10px;
  box-shadow: 1px 1px 2px black;
  width: 50px;
  padding: 10px;
  text-align: center;
  background: white;
}
.updateButton{
  border-radius: 10px;
  padding: 10px;
  box-shadow: 1px 1px 2px white;
  background-color: #579e57;

}
.home{
  border-radius: 10px;
  text-align: center;
  padding: 10px;
  width: 50px;
  box-shadow: 1px 1px 2px black;
  font-size: 20px

}

</style>