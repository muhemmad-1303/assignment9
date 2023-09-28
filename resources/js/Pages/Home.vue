<template>
  <h1>upload file</h1>
  <form @submit.prevent="fileSumbit" method="post">
    <input  ref="selectedFile" type="file"  />
    <button type="submit">upload</button>
  </form>
</template>
<script>
import { reactive, ref } from "vue";
export default {
  setup() {
    const selectedFile=ref();
    let fileSumbit = (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append("file", selectedFile.value.files[0]);
        fetch('api/auth/upload', {
        method: "POST",
        body:formData,
      })
        .then((response) => {
          if (response.ok) {
            console.log("File uploaded successfully!");
          } else {
            console.error("File upload failed.");
          }
        })
        .catch((error) => {
          console.error("Error uploading file:", error);
        }); 
    };

    return {
      selectedFile,
      fileSumbit,
    };
  }
};
</script>