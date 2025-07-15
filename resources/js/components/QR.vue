<!-- Quote.vue -->
<template>
    <!-- <img id="qrSelf" class="qrSelf" src="data:image/png;base64," /> -->
    <img :src="'data:image/png;base64,' + base64Image" />
    <!-- <img :src="'data:image/png;utf8,' + base64Image" /> -->
    <!-- <img class="qrSelf"  /> -->
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  
  const qr = ref('')
  const base64Image = ref('')
  const getQuote = async () => {
    console.log('hi')
    try {
      const res = await axios.get('api/qr')
      console.log(res)
      console.log(res.data)
    //   document.getElementById('qrSelf').setAttribute('src',res.data)
    base64Image.value = res.data
    // base64Image.value = res.data.path
    } catch (err) {
    //   quote.value = { quote: 'Failed to load quote.', author: '' }
    // alert('Couldnt load QR')
    console.log('Couldnt load')
    }

  }
  
  onMounted(getQuote)
  </script>